<?php
namespace AppBundle\Utils\Metadata;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use AppBundle\Utils\Event\CatalogDataEvent;
use AppBundle\Utils\EventDispatcher;
use AppBundle\Utils\Exception\ArchiveFileNotFoundException;

use AppBundle\Utils\Validator\ValidatorManager;
use AppBundle\Utils\Validator\FieldValidatorManager;
use AppBundle\Utils\Validator\EntityFieldValidator;
use AppBundle\Utils\Validator\UrlFieldValidator;
use AppBundle\Utils\Validator\DateFieldValidator;
use AppBundle\Utils\Validator\MetadataHeaderValidator\MetadataHeaderValidator;

use AppBundle\Utils\MetadataEntry\MetadataPlainTextEntry;
use AppBundle\Utils\MetadataEntry\MetadataResourceEntry;
use AppBundle\Utils\MetadataEntry\MetadataChoiceEntry;
use AppBundle\Utils\MetadataEntry\MetadataEntityEntry;
use AppBundle\Utils\MetadataEntry\MetadataDateEntry;


abstract class Metadata extends EventDispatcher{

	const INPUT_FILE_TYPE = "Xlsx";
	const TEMPNAME_PREFIX = "Aaz";

	/**
	* le chemin du fichier zip à ouvrie
	*
	* @var string
	*/
	protected $path;
	/**
	* l'onglet par default à lire
	*
	* @var string
	*/
	protected $defaultSheetname;
	/**
	* le dossier temporaire
	*
	* @var string
	*/
	protected $tmpdname;
	/**
	* les entetes de la feuille
	*
	* @var [type]
	*/
	protected $sheetHeader;
	/**
	 * le fichier zip actuellement ouvert
	 *
	 * @var \ZipArchive
	 */
	protected $za;

	/**
	* permet de valider les entetes du fichier
	*
	* @var AppBundle\Utils\Validator\ValidatorManager
	*/
	protected $hvm;

	/**
	* permet de valider les cellules de la feuille
	*
	* @var AppBundle\Utils\Validator\FieldValidatorManager
	*/
	protected $dvm;

	/**
	* parametres de configuration du lecteur
	*
	* @var array
	*/
	protected $options = [
		"entity_manager"=>null,
	];

	/**
	* L'entête en cours de traitement
	*
	* @var string
	*/
	protected $currentField;

	/**
	* Initialize le lecteur de metadonnée catalogue
	* 
	* @param string $path represente le chemin du fichier zip
	*/
	public function __construct($path,array $headerValidators = array(),array $bodyValidators = array(),array $options = array()){
		parent::__construct();
		$this->tmpdname = __DIR__."/../../../web/tmp";
		$this->path = $path;
		$this->dvm = new FieldValidatorManager();
		$this->hvm = new ValidatorManager();

		foreach ($headerValidators as $key => $el) {
			$this->hvm->add($el);
		}

		foreach ($bodyValidators as $key => $el) {
			$this->dvm->add($el);
		}

		$this->setOptions($options);
	}

	/**
	* change l'onglet par default
	* 
	* @param string $sheetname le nom de l'onglet à definir
	*/
	public function setDefaultSheetname($sheetname){
		return $this->defaultSheetname = $sheetname;
	}
	/**
	 * retourne le nom de l'onglet par defaut à lire
	 * 
	 * @return [type] [description]
	 */
	public function getDefaultSheetname(){
		return $this->defaultSheetname;
	}

	public function getSheetHeader(){
		return $this->sheetHeader;
	}

	/**
	 * 
	 * @return null
	 */
	public function process(){

		$sheetname = $this->getDefaultSheetname();

		$za = new \ZipArchive();
        $za->open($this->path);
        $this->za = $za;

        for ( $i = 0; $i < $za->numFiles; $i++ ) {
            $stat = $za->statIndex($i);
            $name = $stat['name'];
            $ext = array_slice(explode(".", $name),-1)[0];

            if(in_array($ext, ['xls','xlsx'])){

                $tmpfname = tempnam(null, self::TEMPNAME_PREFIX);
                $data = $za->getFromName($name);
                file_put_contents($tmpfname, $data);
                $inputFileName = $tmpfname;


                $reader = IOFactory::createReader(self::INPUT_FILE_TYPE);
        		$reader->setReadDataOnly(TRUE);

                if($sheetname){
               		$reader->setLoadSheetsOnly($sheetname);
                }

                $sheetnames = $reader->listWorksheetNames($inputFileName);
                $worksheetData = $reader->listWorksheetInfo($inputFileName);

               	$this->emit("sheetnames",$sheetnames);
               	$this->emit("worksheetData",$worksheetData);

                $spreadsheet = $reader->load($inputFileName);
                $sheet = $spreadsheet->getActiveSheet();

            	try {
               		foreach ($sheet->getRowIterator() as $key=>$row) {
			            $cellIterator = $row->getCellIterator();
			            $cellIterator->setIterateOnlyExistingCells(FALSE); 
			            $data = [];
			            $rawData = [];
			            $curr_header;
			            foreach ($cellIterator as $pos => $cell) {
			            	$value = $cell->getValue();
			            	$entry = new MetadataPlainTextEntry();
				            $this->dvm->setCellToProcess($pos.$key);

				            $cbkValidated = function($evt)use(&$entry){
				            	$related = $evt->getRelatedTarget();

				            	if($related){
				            		if($related instanceof EntityFieldValidator){
				            			$entry = new MetadataEntityEntry();
				            			$entry->addChoice($evt->getValue());
				            		}
				            		else if($related instanceof UrlFieldValidator){
				            			if($related->getOption("multiple")){
				            				$entry = new MetadataChoiceEntry();
				            				$entry->addChoice($evt->getValue());
				            			}
				            		}
				            		else if($related instanceof DateFieldValidator){

				            			$entry = new MetadataDateEntry();
				            			$entry->setRange($evt->getValue());
				            		}
				            	}
				            };

				            $this->dvm->on("validated",$cbkValidated);

			                if($key != 1){
			                	$headers = $this->getSheetHeader();
			                	$posIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($pos) -1;

			                	$curr_header = $headers[$posIndex];

			            		$this->setCurrentField($curr_header);
				            	$this->dvm->setFieldToProcess($curr_header);

			                	if($curr_header[0] == "@" && $value){

			                		if($entry instanceof MetadataPlainTextEntry){
			                			$entry = new MetadataResourceEntry();
			                		}

			                		$multiples = explode(";", $value);

			                		// il s'agit d'un champs a valeur unique
			                		if(count($multiples) == 1){

			                			if(($rscrStat = $za->statName($value)) === false){
					                		throw new ArchiveFileNotFoundException($value);
					                	}
					                	else{
					          				$rawRsrc = $za->getFromName($value);
					                		$this->dvm->process(array($rawRsrc,$value));
					                		$entry->addResource($rawRsrc,$value);
					                	}
			                		}
			                		// il s'agit d'un champs a valeur multiple
			                		else{
			                			$args = [];
			                			foreach ($multiples as $e) {
			                				if(($ov = $za->statName($e)) === false){
						                		throw new ArchiveFileNotFoundException($value);
						                	}
						                	else{
						                		$rawRsrc = $za->getFromName($e);
						                		$this->dvm->process(array($rawRsrc,$e));
						                		$entry->addResource($rawRsrc,$e);
						                	}
			                			}
			                		}
				                }else{
				                	$this->dvm->process($value);
				                }
			                }

				            $f_value = $this->dvm->processFilters($value);
				            $entry->setFiltered($f_value);
				            $entry->setValue($value);
			               	$data[] = $entry;
			               	$rawData[] = $value;

			               	$this->dvm->off("validated",$cbkValidated);

			            }
			            if($key == 1){
			            	$this->sheetHeader = $rawData;
			            	$this->hvm->process($rawData);
			           		$this->emit("header",$data);
			            }
			            else{
			            	$this->emit(new CatalogDataEvent($curr_header,$data));
			            }
			        }

               	} catch (\Exception $e) {
               		throw $e;
               	}finally{
               		unlink($tmpfname);
               	}
            }
        }
        $za->close();
        $this->emit("end","bye bye!");
	}

	/**
	* @param string $field
	*/
	public function setCurrentField($field){
		$this->currentField = $field;
		return $this;
	}
	public function getCurrentField(){
		return $this->currentField;
	}



	public function getOption($option){
		return isset($this->options[$option]) ? $this->options[$option] : null;
	}

	public function setOption($key,$value){
		$this->options[$key] = $value;
		return $this;
	}

	public function setOptions(array $options){
		$this->options = array_merge($this->options,$options);
		return $this;
	}
}
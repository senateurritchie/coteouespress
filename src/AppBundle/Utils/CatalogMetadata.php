<?php
namespace AppBundle\Utils;

use \PhpOffice\PhpSpreadsheet\IOFactory;

class CatalogMetadata extends EventDispatcher{

	const TARGET_SHEETNAME = "Version numérique";
	const INPUT_FILE_TYPE = "Xlsx";
	const TEMPNAME_PREFIX = "Aaz";

	/**
	 * le chemin du fichier zip à ouvrie
	 * @var string
	 */
	protected $path;
	/**
	* le dossier temporaire
	* @var string
	*/
	protected $tmpdname;
	/**
	 * les entetes de la feuille
	 * @var [type]
	 */
	protected $sheetHeader;

	/**
	* Initialize le lecteur de metadonnée catalogue
	* @param string $path represente le chemin du fichier zip
	*/
	public function __construct($path,$tmpdname = __DIR__."/../../../web/tmp"){
		parent::__construct();
		$this->$tmpdname = $tmpdname;
		$this->path = $path;
	}

	public function getSheetHeader(){
		return $this->sheetHeader;
	}

	/**
	 * 
	 * @return null
	 */
	public function process($sheetname=null){
		$za = new \ZipArchive();
        $za->open($this->path);

        for ( $i = 0; $i < $za->numFiles; $i++ ) {
            $stat = $za->statIndex($i);
            $name = $stat['name'];
            $ext = array_slice(explode(".", $name),-1)[0];
            if(in_array($ext, ['xls','xlsx'])){

                $tmpfname = tempnam($this->tmpdname, self::TEMPNAME_PREFIX);
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

                $header = [];
                foreach ($sheet->getRowIterator() as $key=>$row) {
		            $cellIterator = $row->getCellIterator();
		            $cellIterator->setIterateOnlyExistingCells(FALSE); 
		            $data = [];
		            foreach ($cellIterator as $cell) {
		                $data[] = $cell->getValue();
		            }
		            if($key == 1){
		            	$this->sheetHeader = $data;
		           		$this->emit("header",$data);
		            }
		            else{
		            	$this->emit("data",$data);
		            }
		        }
                unlink($tmpfname);
            }
        }

        $za->close();
	}
}
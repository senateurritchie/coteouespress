<?php
namespace AppBundle\Utils;

use AppBundle\Utils\EventDispatcher;
use \PhpOffice\PhpSpreadsheet\IOFactory;

use AppBundle\Utils\Event\Event;
use AppBundle\Utils\Event\DataEvent;
use AppBundle\Utils\Event\CellDataEvent;

class ExcelReader extends EventDispatcher{

	protected $input;
	protected $hasErrorEvent;
	/**
	* les entetes de la feuille
	*
	* @var [type]
	*/
	protected $sheetHeader;
	/**
	* L'entête en cours de traitement
	*
	* @var string
	*/
	protected $currentField;

	/**
	*  @param string $input le lien du fichier à lire
	*/
	public function __construct($input){
		$this->input = $input;
		$this->hasErrorEvent = false;
	}

	public function getSheetHeader(){
		return $this->sheetHeader;
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
	
	/**
	* lance la lecture du fichier excel
	*/
	public function process(){

        $reader = IOFactory::createReader("Xlsx");
		$reader->setReadDataOnly(TRUE);

        $sheetnames = $reader->listWorksheetNames($this->input);
        $worksheetData = $reader->listWorksheetInfo($this->input);

       	$this->emit("sheetnames",$sheetnames);
       	$this->emit("worksheetData",$worksheetData);

        $spreadsheet = $reader->load($this->input);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheetnames as $sheetname) {

        	$reader->setLoadSheetsOnly($sheetname);

       		foreach ($sheet->getRowIterator() as $key=>$row) {

       			if($this->hasErrorEvent){
       				break;
       			}

	            $cellIterator = $row->getCellIterator();
	            $cellIterator->setIterateOnlyExistingCells(FALSE); 
	            $data = [];
	            $rawData = [];
	            $curr_header;
	            foreach ($cellIterator as $pos => $cell) {
	            	$isResourceMultiple = false;

	            	if($this->hasErrorEvent){
       					break;
       				}

	            	$value = $cell->getValue();
	            	$value = trim($value);

	            	if($cell->isFormula()){
	            		//$value = $cell->getCalculatedValue();
	            	}

	            	$cellToProcess = $pos.$key;

	                if($key != 1){
	                	$headers = $this->getSheetHeader();

	                	$posIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($pos) -1;

	                	$curr_header = $headers[$posIndex];
	            		$this->setCurrentField($curr_header);
	                }

	               	$rawData[] = $value;

	               	// on emet un evenement pour la cellule traitée
	               	$cellDataEvent = new CellDataEvent($this->getCurrentField(),$rawData);
	            	$this->emit($cellDataEvent);
	            }

	            if($key == 1){
	            	$this->sheetHeader = $rawData;
	            	$headerEvent = new Event("header-data",$rawData);
	           		$this->emit($headerEvent);
	            }
	            else{
	            	// on emet un evenement pour la ligne traitée
	            	$dataEvent = new DataEvent($this->getSheetHeader(),$rawData);
	            	$this->emit($dataEvent);
	            }
	        }
	        break;
        }
	}
}


<?php
$path = dirname(__FILE__).'/../gdata/library';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_Docs');
require_once(ROOT.'classes/database.php');
require_once(ROOT.'classes/lib.php');

class spreedsheet {
	private $pdo;
	private $columnsGD;
	private $columnsDB;
	private $columnCount=0;
	private $spreadSheetService;




	public function __construct(){
		$this->pdo=database::getInstance();
		$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient(USERNAME, PASSWORD, $service);
		$this->spreadSheetService = new Zend_Gdata_Spreadsheets($client);

		$this->columnsGD=$this->getRelationGdDb('1');
		$this->columnsDB=$this->getRelationDbGd('1');
		$this->columnCount=sizeof($this->columnsGD);
	}

	public function add_personne($aData=null){
		$aDataSend=array();
		foreach ($aData as $key=>$value) {
			if(array_key_exists($key, $this->columnsDB)){
				$aDataSend[$this->columnsDB[$key]["name_gd"]]=utf8_encode($value);
			}
		}
		$aDataSend["timestamp"]=date("d/m/Y H:i:s");
		$this->spreadSheetService->insertRow($aDataSend, KEY);
	}

	/*public function add_personne($aData=null){
		foreach ($this->columnsGD as $col) {
			$testData[$col] = "Dynamically added " . date("Y-m-d H:i:s") . " in column " . $col;
		}
		var_dump($testData);
		$ret = $this->spreadSheetService->insertRow($testData, KEY);
	}*/

	public function getColumn(){
		$columnsGD=array();
		$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
		$query->setSpreadsheetKey(KEY);
		$feed = $this->spreadSheetService->getWorksheetFeed($query);
		$entries = $feed->entries[0]->getContentsAsRows();
		$columnCount=(isset($entries[0]))?sizeof($entries[0]):0;
		$query = new Zend_Gdata_Spreadsheets_CellQuery();
		$query->setSpreadsheetKey(KEY);
		$feed = $this->spreadSheetService->getCellFeed($query);
		for ($i = 0; $i < $columnCount; $i++) {
			if(isset($feed->entries[$i])){
				$columnName = $feed->entries[$i]->getCell()->getText();
				$columnsGD[$i] = $this->filtreName($columnName);
			}    
		}
		//file_put_contents(ROOT."entete_fichier_google.txt",serialize($columnsGD));
		return $columnsGD;
	}


	public function filtreName($columnName){
		$value=array("'",":"," ");
		$replace=array("","","");
		return str_replace($value,$replace,strtolower($columnName));
	}



	public function getRelationGdDb($actif=""){
		$where=($actif=='1'||$actif=='0')?"WHERE actif='".$actif."'":"";
		$stmt = $this->pdo->prepare("SELECT * FROM `r_db_gd`  ".$where);
		$stmt->execute();
		$ret=array();
		while($relation=$stmt->fetch(PDO::FETCH_ASSOC)){
			$ret[$relation['name_gd']]=$relation;
		}
		return $ret;
	}
	public function getRelationDbGd($actif=""){
		$where=($actif=='1'||$actif=='0')?"WHERE actif='".$actif."'":"";
		$stmt = $this->pdo->prepare("SELECT * FROM `r_db_gd`  ".$where);
		$stmt->execute();
		$ret=array();
		while($relation=$stmt->fetch(PDO::FETCH_ASSOC)){
			$ret[$relation['name_db']]=$relation;
		}
		return $ret;
	}


	public function InsertRelationDbGd($aData){
		if(empty($aData["name_db"])){
			$aData["name_db"]=null;
			$aData["actif"]='0';
		}
		$stmt = $this->pdo->prepare("INSERT INTO  `r_db_gd` (
			`id_r_db_gd` ,`name_gd` ,`position_gd` ,`name_db`  ,`actif` 
			)
		VALUES (
			NULL ,  :name_gd,  :position_gd,  :name_db,  :actif )"
		);
		$stmt->execute($aData);
	}

	public function UpdateRelationDbGd($name_gd,$position_gd){		
		$stmt = $this->pdo->prepare("UPDATE `r_db_gd` SET `position_gd`=:position_gd WHERE `name_gd`=:name_gd ");
		$stmt->execute(array("name_gd"=>$name_gd,"position_gd"=>$position_gd));
	}

	public function DisabledRelationDbGd($aName){
		$stmt = $this->pdo->prepare("UPDATE `r_db_gd` SET `actif`='0' WHERE name_gd=:name_gd");
		$stmt->execute($aName);
	}


	public function majRelationDbGd(){
		$aRelation=$this->getRelationGdDb();
		$aColumns=$this->getColumn();
		 // echo "<pre>";
		 // var_dump($aRelation,$aColumns);
		 // echo "</pre>";
		//insertion nouveau champ en base
		foreach($aColumns as $key=>$value){
			if(!array_key_exists($value, $aRelation)){
				$this->InsertRelationDbGd(array("name_gd"=>$value,"position_gd"=>$key));
				lib::send_mail("nouveau champ GD : ".$value, EMAIL_ADMIN, "nouveau champ GD", EMAIL_FROM, EMAIL_MANIF);
			}
			else if($key!=$aRelation[$value]["position_gd"]){
				$this->UpdateRelationDbGd($value,$key);
			}
		}

		//desactivation ancien champ GD
		foreach($aRelation as $key=>$aValue){
			if(!in_array($key, $aColumns)){
				$this->DisabledRelationDbGd(array("name_gd"=>$key));
			}
		}
	}





}



?>

<?php
session_start(); 
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
require_once(ROOT.'lib/file.lib.php');

if(isset($_FILES['fichier_import'])){
	$fichier=ROOT."fichiers_importes/".$_FILES['fichier_import']['name'];
	//recuperation du fichier a analyser
	$select_req=mysql_query("SELECT id FROM releve where fichier='".$fichier."' ");
	$select=mysql_fetch_assoc($select_req);   
	if(mysql_num_rows($select_req)>0 ){
	  if(isset($_POST['remplacer']) && $_POST['remplacer']=='true' ){
	      mysql_query("DELETE FROM releve WHERE id=".$select['id']);
	  }
	  else{
	     echo "ce relevé a déjà été importé<br><br>Cliquez <a href='".$_SERVER['REQUEST_URI']."&remplacer=true'>ici</a>" ;
	     die();
	  }  
	}






	if (move_uploaded_file($_FILES['fichier_import']['tmp_name'], $fichier)) {
		$aContenuFichier=parse_csv_file($fichier);

	}
}


?>
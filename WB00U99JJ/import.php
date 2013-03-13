<?php
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
require_once(ROOT.'lib/file.lib.php');

$pdo=database::getInstance();
if(isset($_FILES['fichier_import'])){
	$fichier=ROOT."fichiers_importes/".$_FILES['fichier_import']['name'];
	//recuperation du fichier a analyser
	/*$stmt = $pdo->prepare("SELECT id FROM releve where fichier=:fichier");
	$stmt->execute(array("fichier"=>$fichier));

	$select=mysql_fetch_assoc($select_req);   
	if(mysql_num_rows($select_req)>0 ){
		if(isset($_POST['remplacer']) && $_POST['remplacer']=='true' ){
			mysql_query("DELETE FROM releve WHERE id=".$select['id']);
		}
		else{
			echo "ce relevé a déjà été importé<br><br>Cliquez <a href='".$_SERVER['REQUEST_URI']."&remplacer=true'>ici</a>" ;
			die();
		}  
	}*/






	if (move_uploaded_file($_FILES['fichier_import']['tmp_name'], $fichier)) {
		// on crée la requête SQL
		$stmt = $pdo->prepare('SELECT * FROM regex_replace where TYPE is not null order by ordre ');
		$stmt->execute();
		/*$aRegex_find=array();
		while($assoc_keywords2=$stmt->fetch(PDO::FETCH_ASSOC)){
			$aRegex_find[]=$assoc_keywords2;
		}*/
		$aRegex_find=$stmt->fetchAll(PDO::FETCH_ASSOC);
//todo cf si fetchall
		


		$aContenuFichier=parse_csv_file($fichier,0,4);

		$date_deb="";
		$date_fin="";
		$stmt = $pdo->prepare("INSERT INTO  `releve` (
			mois_releve,annee_releve,date_debut,date_fin,montant_debut,montant_fin,fichier
			)
		VALUES (:mois_releve,:annee_releve,:date_debut,:date_fin,:montant_debut,:montant_fin,:fichier)"
		);
		$stmt->execute(array(
			"mois_releve"=>'1',
			"annee_releve"=>'2013',
			"date_debut"=>'2013-01-01',
			"date_fin"=>'2013-01-01',
			"montant_debut"=>0,
			"montant_fin"=>0,
			"fichier"=>$fichier
			));
		$id_releve=$pdo->lastInsertId();




	//	$insert_releve="INSERT INTO releve(mois_releve,annee_releve,date_debut,date_fin,montant_debut,montant_fin,fichier) VALUES ('".substr($result['date_final'],3,2)."','".substr($result['date_final'],6,4)."','".$date_deb."','".$date_fin."','".str_replace(",",".",$result['solde_init'])."','".str_replace(",",".",$result['solde_final'])."','".str_replace("txt","pdf",$name)."')";

		$stmt = $pdo->prepare("INSERT INTO  `releve_detail` (
			id_operations,type,libelle,id_releve,montant,date
			)
		VALUES (:id_operations,:type,:libelle,:id_releve,:montant,:date)"
		);

		$stmt_erreur = $pdo->prepare("INSERT INTO  `releve_detail` (
			trouve,type,libelle,id_releve,montant,date
			)
		VALUES (:trouve,:type,:libelle,:id_releve,:montant,:date)"
		);

		foreach($aContenuFichier as $key=> $value){
			$j=0;
			$montant=number_format(floatval(str_replace(",",".",$value[3])) , 2,',','' );
			$date=preg_replace("#([0-9]{2})/([0-9]{2})/([0-9]{4})#","$3-$2-$1",$value[0]);							
			$type=($montant<0)?"DEBIT":"CREDIT";	
			while($j<sizeof($aRegex_find) && !preg_match($aRegex_find[$j]['regex'],$value[1])){
				$j++;
			}
			if($j>=sizeof($aRegex_find)){
				$val=array('id_releve'=>$id_releve,'trouve'=>'0','type'=>$type,"libelle"=>$value[1],"montant"=>$montant,'date'=>$date);
				$stmt_erreur->execute($val);
			}
			else{	    		
				$val=array('id_releve'=>$id_releve,'id_operations'=>$aRegex_find[$j]['id_operations'],'type'=>$type,"libelle"=>$value[1],"montant"=>$montant,'date'=>$date);  
				$stmt->execute($val);
			}
		}





header('Location: releve.php?id_releve='.$id_releve); 








	}
}


?>
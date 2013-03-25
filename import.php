<?php
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
require_once(ROOT.'lib/file.lib.php');

$pdo=database::getInstance();
$erreur=1;
if(isset($_FILES['fichier_import']) 
	&& !empty($_POST['mois_import'])
	&& !empty($_POST['annee_import'])
	&& isset($_POST['modifier'])
	){
	$fichier=ROOT."fichiers_importes/".$_FILES['fichier_import']['name'];
	$stmt = $pdo->prepare("SELECT id  FROM `releve`  WHERE mois_releve=:mois_releve AND annee_releve=:annee_releve limit 1");
	$stmt->execute(array("mois_releve"=>$_POST['mois_import'],"annee_releve"=>$_POST['annee_import'])) ;
	$ret=$stmt->fetch(PDO::FETCH_ASSOC);
	$id_releve = (empty($ret))?0:$ret['id'];
	$modifier=($_POST['modifier']==1)?1:0;


	if (move_uploaded_file($_FILES['fichier_import']['tmp_name'], $fichier)) {
		// on crée la requête SQL
		$stmt = $pdo->prepare('SELECT * FROM regex_replace where TYPE is not null order by ordre ');
		$stmt->execute();
		$aRegex_find=$stmt->fetchAll(PDO::FETCH_ASSOC);

		$aContenuFichier=parse_csv_file($fichier,0,4);
		$date_deb="";
		$date_fin="";

		if(!$modifier || $id_releve==0){
			if($id_releve>0){
				$stmt = $pdo->prepare("delete  FROM `releve`  WHERE id=:id");
				$stmt->execute(array("id"=>$id_releve)) ;
			}
			$stmt = $pdo->prepare("INSERT INTO  `releve` (
								mois_releve,annee_releve,date_debut,date_fin,montant_debut,montant_fin,fichier
								)
								VALUES (:mois_releve,:annee_releve,:date_debut,:date_fin,:montant_debut,:montant_fin,:fichier)"
			);
			$stmt->execute(array(
				"mois_releve"=>$_POST['mois_import'],
				"annee_releve"=>$_POST['annee_import'],
				"date_debut"=>'2013-01-01',
				"date_fin"=>'2013-01-01',
				"montant_debut"=>0,
				"montant_fin"=>0,
				"fichier"=>$fichier
				));
			$id_releve=$pdo->lastInsertId();
		}

		

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
		$erreur=0;
	}
	else{
		$erreur=2;
	}
}
if($erreur){

		header('Location: index.php?erreur='.$erreur);
}
else{
	header('Location: releve.php?id_releve='.$id_releve);  
}



?>
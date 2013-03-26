<?php
require_once('../conf.php'); 
require_once(ROOT.'classes/database.php');

$pdo=database::getInstance();
if(isset($_POST['update_categorie']) && isset($_POST['id']) && isset($_POST['id_categorie'])){
	$stmt = $pdo->prepare("UPDATE  `releve_detail` set id_cat=:id_cat where id=:id");
	$stmt->execute(array("id_cat"=>$_POST['id_categorie'],"id"=>$_POST['id']));
}

if(isset($_POST['update_operations']) && isset($_POST['id']) && isset($_POST['id_operations'])){
	$stmt = $pdo->prepare("UPDATE  `releve_detail` set id_operations=:id_operations, trouve='1' where id=:id");
	$stmt->execute(array("id_operations"=>$_POST['id_operations'],"id"=>$_POST['id']));
}

if(isset($_POST['update_pointage']) && isset($_POST['id']) && isset($_POST['pointe'])){
	$stmt = $pdo->prepare("UPDATE  `releve_detail` set pointe=:pointe, trouve='1' where id=:id");
	$stmt->execute(array("pointe"=>$_POST['pointe'],"id"=>$_POST['id']));
}

if(isset($_POST['supprimer_releve']) && isset($_POST['id_releve'])){
	$stmt = $pdo->prepare("DELETE  FROM `releve` where id=:id_releve");
	echo "DELETE  FROM `releve` where id=:id_releve";
	$stmt->execute(array("id_releve"=>$_POST['id_releve']));
}

if(isset($_POST['supprimer_ligne_releve']) && isset($_POST['id_ligne_releve'])){
	$stmt = $pdo->prepare("DELETE  FROM `releve_detail` where id=:id_ligne_releve");
	$stmt->execute(array("id_ligne_releve"=>$_POST['id_ligne_releve']));
}

if(isset($_POST['verif_insert_releve']) && isset($_POST['mois_releve'])  && isset($_POST['annee_releve'])){
	$stmt = $pdo->prepare("SELECT id  FROM `releve`  WHERE mois_releve=:mois_releve AND annee_releve=:annee_releve limit 1");
	$stmt->execute(array("mois_releve"=>$_POST['mois_releve'],"annee_releve"=>$_POST['annee_releve'])) ;
	$ret=$stmt->fetch(PDO::FETCH_ASSOC);
	echo (empty($ret))?0:$ret['id'];
}




if(isset($_POST['update_table']) && isset($_POST['update_champ']) && isset($_POST['id']) && isset($_POST['valeur'])){
	$ok=false;
	switch($_POST['update_table']){
		case 'categories':
				if($_POST['update_champ']=="libelle"){
					$stmt = $pdo->prepare("Update `liste_cat` SET libelle:valeur WHERE id_cat=:id");
					$ok=true;
				}
			break;

		case 'operations':
				if($_POST['update_champ']=="libelle"){
					$stmt = $pdo->prepare("Update `operations` SET nom_operations:valeur WHERE id_operations=:id");
					$ok=true;
				}
			break;

		case 'excel':
				if($_POST['update_champ']=="position"){
					$stmt = $pdo->prepare("Update `import_excel` SET position:valeur WHERE id_excel=:id");
					$ok=true;
				}
			break;

		case 'keywords':
				if($_POST['update_champ']=="value"){
					$stmt = $pdo->prepare("Update `keywords` SET value:valeur WHERE id_keywords=:id");
					$ok=true;
				}
				if($_POST['update_champ']=="id_cat"){
					$stmt = $pdo->prepare("Update `keywords` SET id_cat:valeur WHERE id_keywords=:id");
					$ok=true;
				}
			break;

		case 'regex':
				if($_POST['update_champ']=="regex"){
					$stmt = $pdo->prepare("Update `regex_replace` SET regex:valeur WHERE id_keywords=:id");
					$ok=true;
				}
				if($_POST['update_champ']=="replace"){
					$stmt = $pdo->prepare("Update `regex_replace` SET replace:valeur WHERE id_keywords=:id");
					$ok=true;
				}
				if($_POST['update_champ']=="ordre"){
					$stmt = $pdo->prepare("Update `regex_replace` SET ordre:valeur WHERE id_keywords=:id");
					$ok=true;
				}
				if($_POST['update_champ']=="id_operations"){
					$stmt = $pdo->prepare("Update `regex_replace` SET id_operations:valeur WHERE id_keywords=:id");
					$ok=true;
				}
			break;

	}
	if($ok){
		$stmt->execute(array("valeur"=>$_POST['valeur'],"id"=>$_POST['id'])) ;
	}
}










?>
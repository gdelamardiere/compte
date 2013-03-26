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


if(isset($_POST['fonction']) && $_POST['fonction']=="get_detail_releve_good" && isset($_POST['id_releve']) && isset($_POST['sens']) && isset($_POST['tri'])){
	if(in_array($_POST['tri'],array("rd.date",
									"rd.libelle",
									"operations",
									"rd.montant",
									"rd.type",
									"categorie",
									"pointe")
	) && in_array($_POST['sens'],array("ASC","DESC"))
	){
		$stmt = $pdo->prepare("SELECT rd.*,o.nom_operations as operations, lc.libelle as categorie
			from releve_detail rd
			inner join operations o on o.id_operations=rd.id_operations 
			left join liste_cat lc on rd.id_cat=lc.id_cat
			where rd.id_releve=:id_releve
			AND trouve='1'
			ORDER BY ".$_POST['tri']." ".$_POST['sens']." ");
		$debit=0;
		$credit=0;
		$stmt->execute(array("id_releve"=>$_POST['id_releve']));
		$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt = $pdo->prepare('SELECT * from liste_cat');	
		$stmt->execute();
		$select="";
		$aCat=array();
		while($assoc_cat=$stmt->fetch(PDO::FETCH_ASSOC)){
			$aCat[]= $assoc_cat;
			$select.="<option value='".$assoc_cat['id_cat']."'>".$assoc_cat['libelle']."</option>";
		}


		$aPointage=array("0"=>"","-1"=>"en erreur","1"=>"ok");
		require_once(ROOT."tpl_good_detail_releve.php");
	}
	
}


if(isset($_POST['fonction']) && $_POST['fonction']=="get_detail_releve_bad" && isset($_POST['id_releve']) && isset($_POST['sens']) && isset($_POST['tri'])){
	$_POST['tri']=str_replace("bad_","",$_POST['tri']);
	if(in_array($_POST['tri'],array("rd.date",
									"rd.libelle",
									"rd.montant",
									"rd.type",
									"pointe")
	) && in_array($_POST['sens'],array("ASC","DESC"))
	){
		$stmt = $pdo->prepare("SELECT rd.*
			from releve_detail rd
			where rd.id_releve=:id_releve		
			AND trouve='0'
			ORDER BY ".$_POST['tri']." ".$_POST['sens']." ");
		$debit=0;
		$credit=0;
		$stmt->execute(array("id_releve"=>$_POST['id_releve']));
		$aReleveErreur=$stmt->fetchAll(PDO::FETCH_ASSOC);

		$stmt = $pdo->prepare('SELECT * from liste_cat');	
		$stmt->execute();
		$select="";
		$aCat=array();
		while($assoc_cat=$stmt->fetch(PDO::FETCH_ASSOC)){
			$aCat[]= $assoc_cat;
			$select.="<option value='".$assoc_cat['id_cat']."'>".$assoc_cat['libelle']."</option>";
		}

		$stmt = $pdo->prepare('SELECT * from operations');	
		$stmt->execute();
		
		$aOperations=$stmt->fetchAll(PDO::FETCH_ASSOC);

		$aPointage=array("0"=>"","-1"=>"en erreur","1"=>"ok");
		require_once(ROOT."tpl_bad_detail_releve.php");
	}
	
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
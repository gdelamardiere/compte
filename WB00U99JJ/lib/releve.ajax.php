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
?>
<?php
require_once('conf.php'); 
require_once(ROOT.'classes/reports.class.php');
require_once PHPEXCEL.'PHPExcel/IOFactory.php';

$pdo=database::getInstance();
$reports=new reports();
$date=$_GET['date_releve'];
$id_selected=$_GET['id_releve'];
$liste_id=$id_selected;
if(isset($_GET['annuel'])){
	$date="annee_".$_GET['annuel'];
	$liste_id=$reports->getListeIdForYear($_GET['annuel']);
}
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Michel de la Mardière")
							 ->setLastModifiedBy("Michel de la Mardière")
							 ->setTitle("export_releve")
							 ->setSubject("export d'un relevé au format excel")
							 ->setDescription("export d'un relevé au format excel")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("export_releve");




$stmt = $pdo->prepare("SELECT rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,
									DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe,
									o.nom_operations as operations, lc.libelle as categorie
		from releve_detail rd
		inner join operations o on o.id_operations=rd.id_operations 
		left join liste_cat lc on rd.id_cat=lc.id_cat
		where rd.id_releve in(".$liste_id.")
		AND trouve='1'
		ORDER BY rd.date,rd.id_operations,rd.type");

$stmt->execute();
$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("SELECT rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,
								DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe
	from releve_detail rd
	where rd.id_releve=:id_releve		
	AND trouve='0'
	ORDER BY rd.date,rd.id_operations,rd.type");

$stmt->execute(array("id_releve"=>$id_selected));
$aReleveErreur=$stmt->fetchAll(PDO::FETCH_ASSOC);




// Add header
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'date transaction')
            ->setCellValue('B1', 'Libellé')
            ->setCellValue('C1', 'Montant')
            ->setCellValue('D1', 'Type')
            ->setCellValue('E1', 'Opérations')
            ->setCellValue('F1', 'Catégorie')
            ->setCellValue('G1', 'Pointage');

$i=2;
foreach($aReleve as $value){
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $value['date'])
            ->setCellValue('B'.$i, $value['libelle'])
            ->setCellValue('C'.$i, $value['montant'])
            ->setCellValue('D'.$i, $value['type'])
            ->setCellValue('E'.$i, $value['operations'])
            ->setCellValue('F'.$i, $value['categorie'])
            ->setCellValue('G'.$i, $value['pointe']);
            $i++;
}


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relevé du '.$date);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="export_releve_'.$date.'xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;






?>
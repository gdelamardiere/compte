<?php
require_once('conf.php'); 
require_once(ROOT.'classes/reports.class.php');
require_once PHPEXCEL.'PHPExcel/IOFactory.php';

$pdo=database::getInstance();
$reports=new reports();
$date=$_GET['date_releve'];
$id_selected=$_GET['id_releve'];
$liste_id=$id_selected;
$array_id=array($id_selected);
$annee=$date;
if(isset($_GET['annuel'])){
	$annee=$_GET['annuel'];
	$date="annee_".$_GET['annuel'];
	$liste_id=$reports->getListeIdForYear($_GET['annuel']);
	$array_id=explode(",",$liste_id);
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





//select_mois
$stmt = $pdo->prepare("SELECT r.mois_releve,rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,GROUP_CONCAT(re.nom  ORDER BY re.nom SEPARATOR ',') as regroupements,
									DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe,
									o.nom_operations as operations, lc.libelle as categorie
		from releve_detail rd
		left join releve r on r.id=rd.id_releve 
		inner join operations o on o.id_operations=rd.id_operations 
		left join liste_cat lc on rd.id_cat=lc.id_cat
		left join r_regroupement_cat rc on rd.id_cat=rc.id_cat
		left join regroupement re on rc.id_regroupement=re.id_regroupement
		where rd.id_releve =:id
		GROUP BY rd.id
		ORDER BY rd.date,rd.id_operations,rd.type");


$sheet=0;
foreach($array_id as $id){
	$stmt->execute(array("id"=>$id));
	$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);

$objPHPExcel->createSheet($sheet);

	// Add header
	$objPHPExcel->setActiveSheetIndex($sheet)
	            ->setCellValue('A1', 'date transaction')
	            ->setCellValue('B1', 'Libellé')
	            ->setCellValue('C1', 'Montant')
	            ->setCellValue('D1', 'Type')
	            ->setCellValue('E1', 'Opérations')
	            ->setCellValue('F1', 'Regroupement')
	            ->setCellValue('G1', 'Catégorie')
	            ->setCellValue('H1', 'Pointage')
	            ->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('B')->setWidth(80);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('G')->setWidth(20);



	$i=2;
	foreach($aReleve as $value){
		$objPHPExcel->setActiveSheetIndex($sheet)
				->setCellValue('A'.$i, $value['date'])
	            ->setCellValue('B'.$i, $value['libelle'])
	            ->setCellValue('C'.$i, $value['montant'])
	            ->setCellValue('D'.$i, $value['type'])
	            ->setCellValue('E'.$i, $value['operations'])
	            ->setCellValue('F'.$i, $value['regroupements'])
	            ->setCellValue('G'.$i, $value['categorie'])
	            ->setCellValue('H'.$i, $value['pointe']);
	            $i++;
	}
	$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($aReleve[0]['mois_releve'].'_'.$annee);
	$sheet++;
}




//select_total
if(sizeof($array_id)>1){
	$stmt = $pdo->prepare("SELECT rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,GROUP_CONCAT(re.nom  ORDER BY re.nom SEPARATOR ',') as regroupements,
										DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe,
										o.nom_operations as operations, lc.libelle as categorie
			from releve_detail rd
			inner join operations o on o.id_operations=rd.id_operations 
			left join liste_cat lc on rd.id_cat=lc.id_cat			
		left join r_regroupement_cat rc on rd.id_cat=rc.id_cat
		left join regroupement re on rc.id_regroupement=re.id_regroupement
			where rd.id_releve in(".$liste_id.")
			GROUP BY rd.id
			ORDER BY rd.date,rd.id_operations,rd.type");

	$stmt->execute();
	$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);



	// Add header
	$objPHPExcel->setActiveSheetIndex($sheet)
	            ->setCellValue('A1', 'date transaction')
	            ->setCellValue('B1', 'Libellé')
	            ->setCellValue('C1', 'Montant')
	            ->setCellValue('D1', 'Type')
	            ->setCellValue('E1', 'Opérations')
	            ->setCellValue('F1', 'Regroupement')
	            ->setCellValue('G1', 'Catégorie')
	            ->setCellValue('H1', 'Pointage')            
	            ->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('B')->setWidth(80);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex($sheet)->getColumnDimension('G')->setWidth(20);




	$i=2;
	foreach($aReleve as $value){
		$objPHPExcel->setActiveSheetIndex($sheet)
				->setCellValue('A'.$i, $value['date'])
	            ->setCellValue('B'.$i, $value['libelle'])
	            ->setCellValue('C'.$i, $value['montant'])
	            ->setCellValue('D'.$i, $value['type'])
	            ->setCellValue('E'.$i, $value['operations'])
	            ->setCellValue('F'.$i, $value['regroupements'])
	            ->setCellValue('G'.$i, $value['categorie'])
	            ->setCellValue('H'.$i, $value['pointe']);
	            $i++;
	}


	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('total '.$date);
	$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="export_releve_'.$date.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;






?>
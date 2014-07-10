<?php

require_once('conf.php');
require_once(ROOT . 'classes/database.php');
require_once(ROOT . 'lib/file.lib.php');
require_once(ROOT . 'classes/reports.class.php');
require_once PHPEXCEL . 'PHPExcel/IOFactory.php';

$pdo = database::getInstance();
$reports = new reports();
$liste_Excel = $reports->listeExcel();
$erreur = 1;
if (isset($_FILES['fichier_import']) && !empty($_POST['compte_import'])) {
    $extension = strtolower(pathinfo($_FILES['fichier_import']['name'], PATHINFO_EXTENSION));
    if ($extension != "xls" && $extension != "xlsx" && $extension != "csv") {
        $erreur = 3;
    } else {
        $fichier = "fichiers_importes/import_" . time() . "." . $extension;
        if (move_uploaded_file($_FILES['fichier_import']['tmp_name'], ROOT . $fichier)) {
            // on crée la requête SQL
            $stmt = $pdo->prepare('SELECT * FROM regex_replace where TYPE is not null order by ordre ');
            $stmt->execute();
            $aRegex_find = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("INSERT INTO  `releve` (fichier,compte) VALUES (:fichier,:compte_releve)");
            $stmt->execute(array(
                "compte_releve" => $_POST['compte_releve'],
                "fichier" => $fichier
            ));
            $id_releve = $pdo->lastInsertId();



            $stmt_good = $pdo->prepare("INSERT INTO  `releve_detail` (
				id_operations,type,libelle,id_releve,montant,date
				)
			VALUES (:id_operations,:type,:libelle,:id_releve,:montant,:date) ON DUPLICATE KEY UPDATE id=id"
            );

            $stmt_erreur = $pdo->prepare("INSERT INTO  `releve_detail` (
				trouve,type,libelle,id_releve,montant,date
				)
			VALUES (:trouve,:type,:libelle,:id_releve,:montant,:date) ON DUPLICATE KEY UPDATE id=id"
            );

            if ($extension == "xls" || $extension == "xlsx") {
                $objPHPExcel = PHPExcel_IOFactory::load($fichier);
                $aContenuFichier = $objPHPExcel->getActiveSheet()->toArray(null, true, false, false);
            } else {
                $aContenuFichier = parse_csv_file($fichier, 0, 4);
            }


            foreach ($liste_Excel as $id => $tab) {
                ${'pos_' . $tab['libelle']} = $tab['position'] - 1;
            }

            foreach ($aContenuFichier as $key => $value) {
                $j = 0;
                $montant = number_format(floatval(str_replace(",", ".", $value[$pos_montant])), 2, ',', '');
                if ($extension == "xls" || $extension == "xlsx") {
                    $date = PHPExcel_Style_NumberFormat::toFormattedString($value[$pos_date], "YYYY/MM/DD");
                } else {
                    $date = preg_replace("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", "$3-$2-$1", $value[$pos_date]);
                }

                $type = ($montant < 0) ? "DEBIT" : "CREDIT";
                while ($j < sizeof($aRegex_find) && !preg_match($aRegex_find[$j]['regex'], $value[$pos_libelle])) {
                    $j++;
                }
                if ($j >= sizeof($aRegex_find)) {
                    $val = array('id_releve' => $id_releve, 'trouve' => '0', 'type' => $type, "libelle" => $value[1], "montant" => $montant, 'date' => $date);
                    $stmt_erreur->execute($val);
                } else {
                    $val = array('id_releve' => $id_releve, 'id_operations' => $aRegex_find[$j]['id_operations'], 'type' => $type, "libelle" => $value[1], "montant" => $montant, 'date' => $date);
                    $stmt_good->execute($val);
                }
            }

            $erreur = 0;
        } else {
            $erreur = 2;
        }
    }
}
if ($erreur) {

    header('Location: index.php?erreur=' . $erreur);
} else {
    header('Location: releve.php?id_releve=' . $id_releve);
}
?>
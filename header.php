<?php
require_once('conf.php');
require_once(ROOT . 'classes/reports.class.php');
header('Content-Type: text/html; charset=utf-8');

$pdo = database::getInstance();
$reports = new reports();
$liste_cat = $reports->listeCategories();
$liste_keywords = $reports->listeKeywords();
$liste_regex = $reports->listeRegex();
$liste_operations = $reports->listeOperations();
$liste_regroupements = $reports->listeRegroupement();
$liste_Excel = $reports->listeExcel();
$liste_filtre = $reports->get_liste_filtre();
$liste_releve = $reports->listeReleveMensuel();
if (empty($liste_releve) && $page != "home") {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">
        <title>Dashboard - Base Admin</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet">

        <!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet"> -->
        <link href="css/font-awesome.css" rel="stylesheet">

        <link href="css/base-admin.css" rel="stylesheet">
        <link href="css/base-admin-responsive.css" rel="stylesheet">

        <link href="css/pages/dashboard.css" rel="stylesheet">
        <link href="css/pages/reports.css" rel="stylesheet">
        <link href="css/jquery-ui.css" rel="stylesheet">


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>

    <body>

        <div class="navbar navbar-fixed-top">

            <div class="navbar-inner">

                <div class="container">

                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <a class="brand" href="index.php">
                        Gestionaire de Comptes
                    </a>

                    <div class="nav-collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">

                                <a href="#" class="dropdown-toggle" onclick="window.print()"><i class="icon-print"></i>
                                    Imprimer
                                </a>
                            </li>
                            <li class="dropdown">

                                <a  href="#" class="dropdown-toggle" data-toggle="modal" data-target="#myModal">
                                    <i class="icon-cog"></i>
                                    Settings
                                    <b class="caret"></b>
                                </a>
                            </li>

                        </ul>

                    </div><!--/.nav-collapse -->

                </div> <!-- /container -->

            </div> <!-- /navbar-inner -->

        </div> <!-- /navbar -->





        <div class="subnavbar">

            <div class="subnavbar-inner">

                <div class="container">

                    <ul class="mainnav">

                        <li <?php if ($page == "home") echo 'class="active"'; ?>>
                            <a href="index.php">
                                <i class="icon-home"></i>
                                <span>Home</span>
                            </a>
                        </li>


                        <li <?php if ($page == "reports") echo 'class="active"'; ?>>
                            <a href="reports.php">
                                <i class="icon-bar-chart"></i>
                                <span>Graphiques Standard</span>
                            </a>
                        </li>
                        <li <?php if ($page == "liste_import") echo 'class="active"'; ?>>
                            <a href="liste_import.php">
                                <i class="icon-pushpin"></i>
                                <span>Liste des imports</span>
                            </a>
                        </li>

                        <li <?php if ($page == "liste_releve") echo 'class="active"'; ?>>
                            <a href="liste_releve.php">
                                <i class="icon-pushpin"></i>
                                <span>Relevé Mensuel</span>
                            </a>
                        </li>

                        <li <?php if ($page == "releve") echo 'class="active"'; ?>>
                            <a href="releve.php">
                                <i class="icon-bar-chart"></i>
                                <span>Détail Relevé</span>
                            </a>
                        </li>

                    </ul>

                </div> <!-- /container -->

            </div> <!-- /subnavbar-inner -->

        </div> <!-- /subnavbar -->
        <div class="main">
            <div class="main-inner">

                <div class="container">



                    <?php require_once('settings.php'); ?>



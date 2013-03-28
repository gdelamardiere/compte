<?php
if(defined('HOSTNAME_BASE')){

	$con=mysqli_connect(HOSTNAME_BASE,USERNAME_BASE,PASSWORD_BASE);
	
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	
	$sql=file_get_contents(ROOT."install_sql.sql");
	
	if (!mysqli_multi_query($con,$sql)) 
	{
		echo "Error creating database: " . mysqli_error();die();
	}
	else{

		$mysqli = new mysqli(HOSTNAME_BASE,USERNAME_BASE,PASSWORD_BASE,DATABASE_BASE);
		if (!$mysqli->query("DROP PROCEDURE IF EXISTS `update_releve_detail`") ||
			!$mysqli->query("CREATE PROCEDURE `update_releve_detail`() BEGIN
				update releve_detail rd  set rd.id_cat=
					(select k.id_cat
						from keywords k 
						where rd.libelle REGEXP k.value
						limit 1)
			where rd.id_cat is null ; 
			END; ")) {
			echo "Echec lors de la création de la procédure stockée :(" . $mysqli->errno . ") " . $mysqli->error;
	}

	 	

}?>
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
			
				<li <?php if($page=="home") echo 'class="active"';?>>
					<a href="index.php">
						<i class="icon-home"></i>
						<span>Home</span>
					</a>	    				
				</li>
				

				<li <?php if($page=="reports") echo 'class="active"';?>>
					<a href="reports.php">
						<i class="icon-bar-chart"></i>
						<span>Graphiques Standard</span>
					</a>    				
				</li>

				
				<li <?php if($page=="liste_releve") echo 'class="active"';?>>					
					<a href="liste_releve.php">
						<i class="icon-pushpin"></i>
						<span>Liste</span>
					</a>  									
				</li>

				<li <?php if($page=="releve") echo 'class="active"';?>>
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

la création de la base de données est effectué Vous pouvez recharger la page

	    		    </div> <!-- /container -->
	    
	</div> <!-- /main-inner -->
</div><!-- /main -->
<div class="footer">
	
	<div class="footer-inner">
		
		<div class="container">
			
			<div class="row">
				
    			<div class="span12">
    				&copy; 2012 <a href="http://bootstrapadmin.com/">Base Admin</a>.
    			</div> <!-- /span12 -->
    			
    		</div> <!-- /row -->
    		
		</div> <!-- /container -->
		
	</div> <!-- /footer-inner -->
	
</div> <!-- /footer -->









<?php

}

else{header('Location: index.php');}

?>

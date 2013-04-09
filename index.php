<?php
$page="home";
require_once ('header.php');

$liste_graphes_retenus=array();
$liste_releve=$reports->listeReleve();
if(!empty($liste_releve)){
	$id_selected=$liste_releve[0]['id_releve'];
	$liste_graphes_retenus[]="regroupement";
}
$erreur=0;
if(!empty($_GET['erreur'])){
	switch ($_GET['erreur']){
		case 1:$erreur="tous les champs sont obligatoires";
		break;
		case 2:$erreur="erreur lors de l'upload du fichier";
		break;
		case 3:$erreur="l'extension n'est pas valide";
		break;
	}
}

 
  ?>
    
	
	
	
	      <div class="row">
	      	
	      	<div class="span6">				
						
											
				<div class="widget">
						
					<div class="widget-header">
						<i class="icon-signal"></i>
						<?php if(!empty($liste_releve)){?>
						<h3>Découpage par regroupement (débit) pour le relevé du <?php echo $liste_releve[0]['date'];?></h3>
						<?php }?>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">	
						<?php if(!empty($liste_releve)){?>				
						<div id="regroupement_debit" style="height:400px;width:500px; "></div>
						<div style="margin-top: 10px; float: right;"><input type="button" id="zoom_regroupement_debit" value="reset du zoom"/></div>					
						<?php }else{?>	
						Aucun relevé en base !!
						<?php }?>	
					</div> <!-- /widget-content -->
				
				</div> <!-- /widget -->
	      		
		    </div> <!-- /span6 -->
	      	
	      	
	      	<div class="span6">	
	      		
	      		
	      		<div class="widget">
						
					<div class="widget-header">
						<i class="icon-bookmark"></i>
						<h3>Import nouveau fichier</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<?php if(!empty($erreur)){?>
						<div class="erreur_import">
							<?php echo $erreur;?>
						</div>
						<?php }?>
						<form id="import_fichier" action="import.php" enctype='multipart/form-data' method="POST">
							<div id="saisie_form">
								<label for="fichier_import">Fichier à importer (format xlsx, xls ou csv)</label>
								<input type="file" required  name="fichier_import" id="fichier_import"> 
								 <div class="control-group">
									<label class="control-label">Mois de l'import (ex: 01)</label>
									<div class="controls">
										<input type="text" required  name="mois_import" id="mois_import"
										data-validation-regex-regex="[0-1][0-9]"
										data-validation-regex-message="le mois doit être sur 2 chiffres (ex: 01)" />
										<p class="help-block"></p>
									</div>
								</div>  
								<div class="control-group">
									<label class="control-label">Année de l'import (ex: 2013)</label>
									<div class="controls">
										<input type="number" required  name="annee_import" id="annee_import"
										min="1960" max="2013" />
										<p class="help-block">L'année doit être sur 4 chiffres entre 1960 et 2013</p>
									</div>
								</div>
							</div>
							<input type="hidden" id="verif_submit" name="verif_submit" value="1"/>
							<input type="hidden" id="modifier" name="modifier" value="0"/>
							<input type="hidden" id="id_releve" name="id_releve" value="0"/>
							<div style="margin-top: 10px; float: right;">
								<input type="submit"  value="Envoyer"/> 
							</div>  

						</form>
					
					</div> <!-- /widget-content -->   		
				
				
				</div> <!-- /widget -->
									
		      </div> <!-- /span6 -->
	      	
	      </div> <!-- /row -->
	
<div id="confirmation" title="confirmation">
	Attention, vous avez déjà inséré un relevé pour cette date.<br>
	Voulez-vous 
</div>
    

    


<?php
  require_once ('footer.php');
  ?>
    
    

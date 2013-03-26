<?php
$page="reports";
require_once ('header.php');

$liste_graphes=array("type"=>"Découpage par type",
					"categorie"=>"Découpage par catégorie",
					"operations"=>"Découpage par opérations",
					"comparatif_sur_annee"=>"Comparatif sur un an");
$liste_graphes_perso=array("comparatif_2ans"=>"",
					"comparatif_perso"=>"Comparatif personalisés");

$liste_graphes_default=array("type","categorie","operations","comparatif_sur_annee");
$liste_graphes_retenus=(isset($_POST['liste_graphe']))?$_POST['liste_graphe']:$liste_graphes_default;

$liste_cat=$reports->listeCategories();
$liste_operations=$reports->listeOperations();
$liste_releve=$reports->listeReleve();
if(empty($liste_releve)){
	header('Location: reports_vide.php');
}
$liste_id_cat=array();
foreach($liste_cat as $id_cat=>$libelle){
	$liste_id_cat[]=$id_cat;
}
$liste_id_operations=array();
foreach($liste_operations as $id_operations=>$libelle){
	$liste_id_operations[]=$id_operations;
}
$select_releve="";
$Liste_id_selected=(isset($_POST['id_releve']))?$_POST['id_releve']:array($liste_releve[0]['id_releve']);
$liste_coche_categorie=(isset($_POST['coche_categorie']))?$_POST['coche_categorie']:$liste_id_cat;
$reports->setFiltersCategorie($liste_coche_categorie);
$liste_coche_operations=(isset($_POST['coche_operations']))?$_POST['coche_operations']:$liste_id_operations;
$reports->setFiltersOperations($liste_coche_operations);
foreach($liste_releve as $value){		
	$select_releve.="<option value='".$value['id_releve']."' ".((in_array($value['id_releve'],$Liste_id_selected))?'selected="selected"':'').">".$value['date']."</option>";
}
$id_selected=implode(",",$Liste_id_selected);

$liste_annee=$reports->listeAnnee();
$id_filtre_annee1=(isset($_POST['filtre_annee_1']))?$_POST['filtre_annee_1']:0;
$id_filtre_annee2=(isset($_POST['filtre_annee_2']))?$_POST['filtre_annee_2']:0;
$select_filtre_annee1="<option value=''></option>";
$select_filtre_annee2="<option value=''></option>";
$select_filtre_perso1="";
$select_filtre_perso2="";
$id_filtre_perso1=(isset($_POST['filtre_perso_1']))?$_POST['filtre_perso_1']:array();
$id_filtre_perso2=(isset($_POST['filtre_perso_2']))?$_POST['filtre_perso_2']:array();
foreach($liste_releve as $value){		
	$select_filtre_perso1.="<option value='".$value['id_releve']."' ".((in_array($value['id_releve'],$id_filtre_perso1))?'selected="selected"':'').">".$value['date']."</option>";
	$select_filtre_perso2.="<option value='".$value['id_releve']."' ".((in_array($value['id_releve'],$id_filtre_perso2))?'selected="selected"':'').">".$value['date']."</option>";
}

foreach($liste_annee as $value){		
	$select_filtre_annee1.="<option value='".$value['annee_releve']."' ".(($value['annee_releve']==$id_filtre_annee1)?'selected="selected"':'').">".$value['annee_releve']."</option>";
	$select_filtre_annee2.="<option value='".$value['annee_releve']."' ".(($value['annee_releve']==$id_filtre_annee2)?'selected="selected"':'').">".$value['annee_releve']."</option>";
}



	
?>
<div class="row" id="filtre">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Filtres</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_filtre">

				<form id="reports" action="reports.php" method="POST">
					<div>
						<h3 style="display:inline;">Relevé à prendre en compte :</h3>
						<SELECT name='id_releve[]' multiple size="5"><?php echo $select_releve;?></SELECT>
					</div>
					<div>
						<h3>Liste des catégories à afficher</h3>
						<p><?php $i=1; foreach($liste_cat as $id_cat=>$libelle){
							$libelle=($libelle=="")?"non défini":$libelle;
							?>
							<span class="coche_reports">
								<input class="checboxFiltre" type="checkbox" name="coche_categorie[]" value="<?php echo $id_cat; ?>" id="coche_cat_<?php echo $id_cat;?>" 
								<?php if(in_array($id_cat,$liste_coche_categorie)){echo 'checked="checked"';}?>/>
								<label for="coche_cat_<?php echo $id_cat;?>" <?php if(!in_array($id_cat,$liste_coche_categorie)){echo 'class="deselected"';}?> id="label_coche_cat_<?php echo $id_cat;?>">
									<?php echo $libelle;?>
								</label>
							</span>
						<?php 
						if($i==12){$i=0;echo"<br/>";}
							$i++;
						}
						?>
						</p>
					</div>

					<div>
						<h3>Liste des Opérations à afficher</h3>
						<p><?php $i=1; foreach($liste_operations as $id_operations=>$libelle){
							$libelle=($libelle=="")?"non défini":$libelle;
							?>
							<span class="coche_reports">
								<input class="checboxFiltre" type="checkbox" name="coche_operations[]" value="<?php echo $id_operations; ?>" id="coche_operations_<?php echo $id_operations;?>" 
								<?php if(in_array($id_operations,$liste_coche_operations)){echo 'checked="checked"';}?>/>
								<label for="coche_operations_<?php echo $id_operations;?>" <?php if(!in_array($id_operations,$liste_coche_operations)){echo 'class="deselected"';}?> id="label_coche_operations_<?php echo $id_operations;?>">
									<?php echo $libelle;?></label>
							</span>
						<?php 
						if($i==12){$i=0;echo"<br/>";}
							$i++;
						}
						?>
						</p>
					</div>

					<div>
						<h3>Liste des Graphes à afficher</h3>
						<p><?php foreach($liste_graphes as $key=>$libelle){?>
							<span class="coche_reports">
								<input class="checboxFiltre" type="checkbox" name="liste_graphe[]" value="<?php echo $key; ?>" id="coche_graphe_<?php echo $key;?>" 
								<?php if(in_array($key,$liste_graphes_retenus)){echo 'checked="checked"';}?>/>
								<label for="coche_graphe_<?php echo $key;?>" <?php if(!in_array($key,$liste_graphes_retenus)){echo 'class="deselected"';}?> id="label_coche_graphe_<?php echo $key;?>">
									<?php echo $libelle;?></label>
							</span>
							<?php }?>
						</p>
						<h4>Graphes personalisés:</h4>
						<div>
							<div style="float:left;width:150px;padding-top:5px;">Comparatif entre 2 années: </div>
							<div><SELECT name='filtre_annee_1' ><?php echo $select_filtre_annee1;?></SELECT> 
								vs <SELECT name='filtre_annee_2' ><?php echo $select_filtre_annee2;?></SELECT>
							</div>
						</div>	
						<div>
							<div style="float:left;width:150px;padding-top:20px;">Comparatif personalisés <br/>(sélection multiple): </div>
							<div><SELECT name='filtre_perso_1[]' multiple size="5"><?php echo $select_filtre_perso1;?></SELECT> 
								vs <SELECT name='filtre_perso_2[]' multiple size="5"><?php echo $select_filtre_perso2;?></SELECT>
							</div>
						</div>
					</div>
					<div onclick="$('#reports').submit();" class="widget-header" style="width:130px; cursor:pointer;float:right; margin-right:20px;">
						<i class="icon-refresh" ></i> <span style="padding-left:20px">Calculer </span>
					</div> 
					
				</form>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<?php if(in_array("type",$liste_graphes_retenus)){?>
<div class="row" id="graphe_type">

	<div class="span6" style="margin-left: 25%;">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Débit / Crédit</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_type">

				<div id="percentByType" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->
<?php }if(in_array("categorie",$liste_graphes_retenus)){?>
<div class="row" id="graphe_categorie">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage en catégorie</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_cat1">

				<div id="percentByCategorie" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Prix par catégorie</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_cat2">

				<div id="prixByCategorie" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="ByCategorie" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->

	
</div> <!-- /row -->
<?php } if(in_array("operations",$liste_graphes_retenus)){?>
<div class="row" id="graphe_operations">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage en opérations</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_op1">

				<div id="percentByOperations" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Prix par opérations</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="op2">

				<div id="prixByOperations" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="ByOperations" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px" >reset du zoom </span>
				</div>	

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->
	
		
</div> <!-- /row -->
<?php } if(in_array("comparatif_sur_annee",$liste_graphes_retenus)){?>
<div class="row" id="graphe_annee">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="plot2" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="annuel" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->


<?php } if($id_filtre_annee1!=0 && $id_filtre_annee2!=0){?>
<div class="row" id="graphe_2annees">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif entre <?php echo $id_filtre_annee1;?> et <?php echo $id_filtre_annee2;?></h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="comparatif_annees" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="reset_comparatif_annees" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<?php } ?>

<?php
if(!empty($id_filtre_perso1) && !empty($id_filtre_perso2)){?>
<div class="row" id="graphe_perso">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif personalisé</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="comparatif_perso" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="reset_comparatif_perso" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<?php } ?>










<?php
require_once ('footer.php');
?>
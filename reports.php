<?php
$page="reports";
require_once ('header.php');

if(isset($_POST['nom_filtre']) && $_POST['nom_filtre']!=""){
	$reports->save_filtre($_POST);
}
$get_filtre=0;
if(isset($_POST['get_filtre']) && $_POST['get_filtre']!=0){
	$_POST=$reports->get_filtre($_POST['get_filtre']);
	$get_filtre==$_POST['get_filtre'];
}




//liste des graphes a afficher
$liste_graphes=array("type"=>"Découpage par type",
					"regroupement"=>"Découpage par regroupement",
					"categorie"=>"Découpage par catégorie",
					"comparatif_sur_annee"=>"Comparatif sur un an");
$liste_graphes_perso=array("comparatif_2ans"=>"",
					"comparatif_perso"=>"Comparatif personalisés");

$liste_graphes_default=array("type","regroupement","comparatif_sur_annee");
$liste_graphes_retenus=(isset($_POST['liste_graphe']))?$_POST['liste_graphe']:$liste_graphes_default;

//selection des id a afficher
$liste_id_cat=array();
foreach($liste_cat as $id_cat=>$libelle){
	$liste_id_cat[]=$id_cat;
}
$select_releve="";
$Liste_id_selected=(isset($_POST['id_releve']))?$_POST['id_releve']:array($liste_releve[0]['id_releve']);
foreach($liste_releve as $value){		
	$select_releve.="<option value='".$value['id_releve']."' ".((in_array($value['id_releve'],$Liste_id_selected))?'selected="selected"':'').">".$value['date']."</option>";
}
$id_selected=implode(",",$Liste_id_selected);


//selection des regroupements a afficher
$liste_id_regroupements=array();
foreach($liste_regroupements as $id_regroupements=>$libelle){
	$liste_id_regroupements[]=$id_regroupements;
}
$liste_coche_regroupements=(isset($_POST['coche_regroupements']))?$_POST['coche_regroupements']:$liste_id_regroupements;
$reports->setFiltersRegroupements($liste_coche_regroupements);

//selection des regroupements detailles
$liste_details=(isset($_POST['coche_regroupements_detail']))?$_POST['coche_regroupements_detail']:array();


//selection des categories a afficher
$liste_coche_categorie=(isset($_POST['coche_categorie']))?$_POST['coche_categorie']:$liste_id_cat;
$reports->setFiltersCategorie($liste_coche_categorie);


//selection filtre anne1 vs anne2
$liste_annee=$reports->listeAnnee();
$id_filtre_annee1=(isset($_POST['filtre_annee_1']))?$_POST['filtre_annee_1']:0;
$id_filtre_annee2=(isset($_POST['filtre_annee_2']))?$_POST['filtre_annee_2']:0;
$select_filtre_annee1="<option value=''></option>";
$select_filtre_annee2="<option value=''></option>";

//selection filtre perso
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


//selection du filtre
$select_filtre="<option value=''></option>";
foreach($liste_filtre as $key=>$value){		
	$select_filtre.="<option value='".$key."' ".(($key==$get_filtre)?'selected="selected"':'').">".$value."</option>";
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
						<h3>Liste des regroupements à afficher</h3>
						<p><?php $i=1; foreach($liste_regroupements as $id_regroupements=>$libelle){
							$libelle=($libelle=="")?"non défini":$libelle;
							?>
							<span class="coche_reports">
								<input class="checboxFiltre" type="checkbox" name="coche_regroupements[]" value="<?php echo $id_regroupements; ?>" id="coche_regroupements_<?php echo $id_regroupements;?>" 
								<?php if(in_array($id_regroupements,$liste_coche_regroupements)){echo 'checked="checked"';}?>/>
								<label for="coche_regroupements_<?php echo $id_regroupements;?>" <?php if(!in_array($id_regroupements,$liste_coche_regroupements)){echo 'class="deselected"';}?> id="label_coche_regroupements_<?php echo $id_regroupements;?>">
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
						<h3>Afficher le détail des regroupements suivants</h3>
						<p><?php $i=1; foreach($liste_regroupements as $id_regroupements=>$libelle){
							$libelle=($libelle=="")?"non défini":$libelle;
							?>
							<span class="coche_reports">
								<input class="checboxFiltre" type="checkbox" name="coche_regroupements_detail[]" value="<?php echo $id_regroupements; ?>" id="coche_regroupements_detail_<?php echo $id_regroupements;?>" 
								<?php if(in_array($id_regroupements,$liste_details)){echo 'checked="checked"';}?>/>
								<label for="coche_regroupements_detail_<?php echo $id_regroupements;?>" <?php if(!in_array($id_regroupements,$liste_details)){echo 'class="deselected"';}?> id="label_coche_regroupements_detail_<?php echo $id_regroupements;?>">
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
					<div class="widget-header" style="height:60px;padding-top: 15px;" >
						<div onclick="$('#reports').submit();" class="widget-header" style="width:130px; cursor:pointer;float:right; margin-right:20px;">
							<i class="icon-refresh" ></i> <span style="padding-left:20px">Calculer </span>
						</div> 
						<div style="width:494px;float:left;margin-left:10px;">
								<span>Charger un filtre existant : <SELECT onchange="$('#reports').submit();" name='get_filtre' ><?php echo $select_filtre;?></SELECT></span>
						</div>	
					
						<span>Enregistrer ce filtre: <input type="text" name="nom_filtre" id="nom_filtre" value=""/></span>
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
<?php } if(in_array("regroupement",$liste_graphes_retenus)){?>
<div class="row" id="graphe_regroupement">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage par regroupement (crédit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_op1">

				<div id="regroupement_credit" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_regroupement_credit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px" >reset du zoom </span>
				</div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Découpage par regroupement (débit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="op2">

				<div id="regroupement_debit" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_regroupement_debit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px" >reset du zoom </span>
				</div>	

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->
	
		
</div> <!-- /row -->
<?php } if(in_array("categorie",$liste_graphes_retenus)){?>
<div class="row" id="graphe_categorie">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage par catégorie (crédit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_op1">

				<div id="categorie_credit" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_categorie_credit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px" >reset du zoom </span>
				</div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Découpage par catégorie (débit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="op2">

				<div id="categorie_debit" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_categorie_debit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px" >reset du zoom </span>
				</div>	

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->
	
		
</div> <!-- /row -->
<?php }
if(!empty($liste_details)){
	foreach($liste_regroupements as $id=>$nom){
		if(in_array($id,$liste_details)){?>
<div class="row" id="graphe_categorie_<?php echo $id;?>">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Détail de <?php echo $nom;?> (crédit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_detail_credit_<?php echo $id;?>">

				<div class="detail_regroupement_credit" id="detail_credit_<?php echo $id;?>" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_detail_credit_<?php echo $id;?>" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Détail de <?php echo $nom;?> (débit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content" id="content_detail_debit_<?php echo $id;?>">

				<div class="detail_regroupement_debit" id="detail_debit_<?php echo $id;?>" style="height:400px;width:500px; "></div>
				<div class="widget-header" id="zoom_detail_debit_<?php echo $id;?>" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->
	</div> <!-- /span6 -->

	
</div> <!-- /row -->
<?php }}} if(in_array("comparatif_sur_annee",$liste_graphes_retenus)){?>
<div class="row" id="graphe_annee_credit">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois par regroupement (crédit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="annuel_credit" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="zoom_annuel_credit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->
<div class="row" id="graphe_annee_debit">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois par regroupement (débit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="annuel_debit" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="zoom_annuel_debit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<?php } if(in_array("comparatif_sur_annee",$liste_graphes_retenus) && in_array("categorie",$liste_graphes_retenus)){?>
<div class="row" id="graphe_annee_cat_credit">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois par catégorie (crédit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="annuel_cat_credit" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="zoom_annuel_cat_credit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
					<i class="icon-zoom-out" ></i>
					<span style="padding-left:20px">reset du zoom </span>
				</div>	
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->
<div class="row" id="graphe_annee_cat_debit">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois par catégorie (débit)</h3>
				<span class="reduction">
					<i class="icon-resize-full"></i>
				</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="annuel_cat_debit" style="height:700px;width:1000px; "></div>
				<div class="widget-header" id="zoom_annuel_cat_debit" style="width:130px; cursor:pointer;float:right; margin-right:20px;margin-top: 10px;">
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
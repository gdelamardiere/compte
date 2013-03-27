<?php
$page="releve";
require_once ('header.php');

$liste_releve=$reports->listeReleve();
if(empty($liste_releve)){
	header('Location: reports_vide.php');
}
$id_selected=(isset($_GET['id_releve']))?$_GET['id_releve']:$liste_releve[0]['id_releve'];
if(isset($id_selected)){

	if(isset($_POST['new_cat']) && $_POST['new_cat']!=''){
		$stmt = $pdo->prepare("INSERT INTO liste_cat(libelle) VALUE(:libelle)");
		$stmt->execute(array("libelle"=>$_POST['new_cat']));
	}

	if(isset($_POST['new_keywords']) && $_POST['new_keywords']!='' ){
		$stmt = $pdo->prepare("INSERT INTO keywords(id_cat,value) VALUE(:id_cat,:value)");
		$stmt->execute(array("id_cat"=>$_POST['keywords_cat'],"value"=>$_POST['new_keywords']));
	}

	$stmt = $pdo->prepare("call update_releve_detail()");
	$stmt->execute();


	$stmt = $pdo->prepare("SELECT r.`id`,r.mois_releve,r.annee_releve,
							(SELECT SUM(montant) from releve_detail where id_releve=r.id AND type='DEBIT') as total_debit,
							(SELECT SUM(montant) from releve_detail where id_releve=r.id AND type='CREDIT') as total_credit,
							(SELECT count(*)  from releve_detail where id_releve=r.id ) as nb_operations,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND trouve='0') as nb_operations_erreur,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND (id_cat='1' OR id_cat is null)) as nb_operations_sans_categorie,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='0') as nb_operations_non_pointe,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='-1') as nb_operations_pointe_erreur,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='1') as nb_operations_pointe_ok
						FROM `releve` r 
						WHERE r.id=:id_releve
						"
						);

	$stmt->execute(array("id_releve"=>$id_selected));
	$aGlobalReleve=$stmt->fetch(PDO::FETCH_ASSOC);



	$stmt = $pdo->prepare("SELECT rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,
									DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe,
									o.nom_operations as operations, lc.libelle as categorie
		from releve_detail rd
		inner join operations o on o.id_operations=rd.id_operations 
		left join liste_cat lc on rd.id_cat=lc.id_cat
		where rd.id_releve=:id_releve
		AND trouve='1'
		ORDER BY rd.date,rd.id_operations,rd.type");

	$stmt->execute(array("id_releve"=>$id_selected));
	$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);


	$stmt = $pdo->prepare("SELECT rd.id,rd.libelle,rd.montant,rd.type,rd.id_operations,rd.id_cat,
									DATE_FORMAT(rd.date, '%e/%m/%Y') as date,rd.id_releve,rd.trouve,rd.pointe
		from releve_detail rd
		where rd.id_releve=:id_releve		
		AND trouve='0'
		ORDER BY rd.date,rd.id_operations,rd.type");

	$stmt->execute(array("id_releve"=>$id_selected));
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
	$debit=0;
	$credit=0;
	$total=$aGlobalReleve['total_debit']+$aGlobalReleve['total_credit'];
	
	?>
	<input type="hidden" id="id_releve" value="<?php echo $id_selected;?>"/>
	<div class="widget big-stats-container">
	      			
	      			<div class="widget-content">
	      				
			      		<div id="big_stats" class="cf">
							<div class="stat">								
								<h4>Nb total <br/>d'opérations</h4>
								<span class="value" id="nb_operations"><?php echo $aGlobalReleve['nb_operations'];?></span>								
							</div> <!-- .stat -->
							
							<div class="stat">								
								<h4>Total Débit</h4>
								<span class="value" id="total_debit"><?php echo $aGlobalReleve['total_debit'];?> &euro;</span>								
							</div> <!-- .stat -->
							
							<div class="stat">								
								<h4>Total Crédit</h4>
								<span class="value" id="total_credit"><?php echo $aGlobalReleve['total_credit'];?> &euro;</span>								
							</div> <!-- .stat -->

							<div class="stat">								
								<h4>Montant total</h4>
								<span class="value" id="total"><?php echo $total;?> &euro;</span>								
							</div> <!-- .stat -->

							<div class="stat">								
								<h4>Nb d'opérations <br/>sans catégorie</h4>
								<span class="value" id="nb_operations_sans_categorie"><?php echo $aGlobalReleve['nb_operations_sans_categorie'];?></span>								
							</div> <!-- .stat -->
							
							<div class="stat">								
								<h4>Nb d'opérations <br/>mal enregistrés</h4>
								<span class="value" id="nb_operations_erreur"><?php echo $aGlobalReleve['nb_operations_erreur'];?></span>								
							</div> <!-- .stat -->

							<div class="stat">								
								<h4>Nb d'opérations <br/>pointés</h4>
								<span class="value" id="nb_operations_pointe_ok"><?php echo $aGlobalReleve['nb_operations_pointe_ok'];?></span>								
							</div> <!-- .stat -->

							<div class="stat">								
								<h4>Nb d'opérations <br/>à pointer</h4>
								<span class="value" id="nb_operations_non_pointe"><?php echo $aGlobalReleve['nb_operations_non_pointe'];?></span>								
							</div> <!-- .stat -->

							<div class="stat">								
								<h4>Nb d'opérations <br/>pointés en erreur</h4>
								<span class="value" id="nb_operations_pointe_erreur"><?php echo $aGlobalReleve['nb_operations_pointe_erreur'];?></span>								
							</div> <!-- .stat -->
						</div>
					
					</div> <!-- /widget-content -->
					
				</div> <!-- /widget -->


	
		
		
	
	<div class="widget-header" id="clearFilter" style="cursor:pointer;float:right;width:100px;margin-bottom: 10px;padding-left:20px;margin-right:30px;">
						Vider le filtre 
	</div> 
	<div class="widget-header" style="float:right;width:140px;margin-bottom: 10px;padding-left:20px;margin-right:30px;">
			<span style="cursor:pointer;" onclick="edition();" class="lecture" >Passer en mode édition</span>
			<span style="cursor:pointer;" onclick="lecture();" class="edition" >Passer en mode lecture</span>   
	</div> 
	<div class="widget-header" style="width:140px;margin-bottom: 10px;padding-left:20px;">
		Relevé du mois 
						<?php echo $aGlobalReleve['mois_releve'];?> /<?php echo $aGlobalReleve['annee_releve'];?>
	</div> 
	<div class="detail_releve">
		<table id="detail_releve"> 
			<thead>
				<tr>
					<th width="80" class="tri_good" id="rd.date"><i class="icon-arrow-down"></i> Date Transaction </th>
					<th width="400" class="tri_good" id="rd.libelle"><i style="display:none"></i> Libellé</th>
					<th width="80" class="tri_good" id="rd.montant"><i style="display:none"></i> Montant</th>
					<th width="80" class="tri_good" id="rd.type" filter-type='ddl'><i style="display:none"></i> Type</th>
					<th width="150" class="tri_good" id="operations" filter-type='ddl'><i style="display:none"></i> Opérations</th>		
							
					<th width="200" class="tri_good" id="categorie"  filter-type='ddl'><i style="display:none"></i> Catégorie</th>
					<th width="0" style="display:none"  filter='false'></th>
					<th width="50" class="tri_good" id="pointe"  filter='false'><i style="display:none"></i> Pointage</th>
					<th width="0" style="display:none"  filter='false'></th>
					<th width="0" style="display:none"  filter='false'></th>
					<th width="0" style="display:none"  filter='false'></th>
					<th width="80" filter='false'>Suppression</th>
				</tr>
			</thead>
			<tbody id="detail_releve_good">
			<?php require_once("tpl_good_detail_releve.php");?>
			</tbody>

			<tfoot>
				<tr>
					<th>Total Débit : <?php echo $debit;?> &euro;</th>
					<td></td>
					<th>Total Crédit : <?php echo $credit;?> &euro;</th>
					<td colspan="2"></td>
					<th colspan="3">Total : <?php echo $debit+$credit;?> &euro;</th>
				</tr>
			</tfoot>
		</table>
	</div>
<?php if(sizeof($aReleveErreur>0) && !empty($aReleveErreur)){?>	
	<hr/>
	<h2>Lignes dont le type d'opérations n'a pas été trouvé</h2>
	<div class="detail_releve">
		<input type="button" onclick="actualiser();" value="Recharger la page">
		<table>
			<thead>
				<tr>
					<th width="80" class="tri_bad" id="bad_rd.date"><i class="icon-arrow-down"></i> Date Transaction </th>
					<th width="400" class="tri_bad" id="bad_rd.libelle"><i style="display:none"></i> Libellé</th>
					<th width="80">Opérations</th>
					<th width="80" class="tri_bad" id="bad_rd.montant"><i style="display:none"></i> Montant</th>
					<th width="150" class="tri_bad" id="bad_rd.type"><i style="display:none"></i> Type</th>
					<th width="200"> Catégorie</th>
					<th width="50">Pointage</th>
					<th width="80">Suppression</th>
				</tr>
			</thead>
			<tbody id="detail_releve_bad">
			<?php require_once("tpl_bad_detail_releve.php");?>	
			
			</tbody>
			<tfoot>
				<tr>
					<th>Total Débit : <?php echo $debit;?> &euro;</th>
					<td></td>
					<th>Total Crédit : <?php echo $credit;?> &euro;</th>
					<td colspan="2"></td>
					<th colspan="3">Total : <?php echo $debit+$credit;?> &euro;</th>
				</tr>
			</tfoot>
		</table>
	</div>
<?php
	}
	require_once ('footer.php');
}
?>
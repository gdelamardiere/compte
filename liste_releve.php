<?php

$page="liste_releve";	
require_once ('header.php');

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
						ORDER BY annee_releve DESC, mois_releve DESC"
						);

	$stmt->execute();
	$aListeReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);
if(empty($aListeReleve)){
	header('Location: reports_vide.php');
}

?>
	

	<div class="detail_releve">
		<table style="text-align:center;width:1000px;"  id="detail_releve">
			<thead>
				<tr>
					<th>Relevé<br/>du mois de</th>
					<th>Total Débit</th>
					<th>Total Crédit</th>
					<th>Total</th>
					<th>Nb opérations</th>
					<th>Nb opérations <br/>non trouvé</th>
					<th>Nb opérations <br/>sans catégorie</th>
					<th>Nb opérations <br/>pointé</th>
					<th>Nb opérations <br/>non pointé</th>
					<th>Nb opérations pointé<br/> en erreur</th>
					<th filter='false'>Edition</th>
					<th filter='false'>Export</th>
					<th filter='false'>Export annuel</th>
					<th filter='false'>Supprimer</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($aListeReleve as $value){
				$total=$value['total_debit']+$value['total_credit'];
				echo "<tr>
				<td>".$value['mois_releve']."/".$value['annee_releve']."</td>
				<td>".$value['total_debit']." &euro;</td>
				<td>".$value['total_credit']." &euro;</td>
				<td>".$total."  &euro;</td>
				<td >".$value['nb_operations']."</td>
				<td >".$value['nb_operations_erreur']."</td>
				<td >".$value['nb_operations_sans_categorie']."</td>
				<td>".$value['nb_operations_pointe_ok']."</td> 
				<td >".$value['nb_operations_non_pointe']."</td>
				<td >".$value['nb_operations_pointe_erreur']."</td>
				<td>
					<a href='releve.php?id_releve=".$value['id']."'>
						<img width='20' id='editer".$value['id']."' src='img/editer.jpg' alt ='éditer'/>
					</a>
				</td>
				<td>
					<a href='export.php?id_releve=".$value['id']."&date_releve=".$value['mois_releve']."_".$value['annee_releve']."'>
						<img width='20' src='img/excel.jpg' alt ='excel'/>
					</a>
					
				</td> 
				<td>
					<a href='export.php?annuel=".$value['annee_releve']."&id_releve=".$value['id']."&date_releve=".$value['mois_releve']."_".$value['annee_releve']."'>
						<img width='20' src='img/excel.jpg' alt ='excel'/>
					</a>
					
				</td>
				<td>
					<a href='#' onclick='supprimer_releve(".$value['id'].")'>
						<img width='20' id='delete_".$value['id']."' src='img/erreur.gif' alt ='supprimer'/>
					</a>
					
				</td> 				
				</tr>";
			}?>  
			</tbody>
			
		</table>
	</div>
<?php
	require_once ('footer.php');
?>
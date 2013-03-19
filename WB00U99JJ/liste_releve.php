<?php
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
header('Content-Type: text/html; charset=utf-8');

$pdo=database::getInstance();

$stmt = $pdo->prepare("SELECT r.`id`,r.mois_releve,r.annee_releve,
							(SELECT SUM(montant) from releve_detail where id_releve=r.id AND type='DEBIT') as total_debit,
							(SELECT SUM(montant) from releve_detail where id_releve=r.id AND type='CREDIT') as total_credit,
							(SELECT count(*)  from releve_detail where id_releve=r.id ) as nb_operations,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND trouve='0') as nb_operations_erreur,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND (id_cat='1' OR id_cat is null)) as nb_operations_sans_categorie,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='0') as nb_operations_non_pointe,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='-1') as nb_operations_pointe_erreur,
							(SELECT count(*)  from releve_detail where id_releve=r.id AND pointe='1') as nb_operations_pointe_ok
						FROM `releve` r "
						);

	$stmt->execute();
	$aListeReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);


	require_once ('header.html');?>
	

	<div class="detail_releve">
		<table>
			<thead>
				<tr>
					<th>Relevé</th>
					<th>Total Débit</th>
					<th>Total Créit</th>
					<th>Total</th>
					<th>Nb Opé</th>
					<th>Nb Opé non trouvé</th>
					<th>Nb Opé sans catégorie</th>
					<th>Nb opé pointé</th>
					<th>Nb opé non pointé</th>
					<th>Nb opé pointé en erreur</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($aListeReleve as $value){
				$total=$value['total_debit']+$value['total_credit'];
				echo "<tr>
				<td>n°".$value['id']." du ".$value['mois_releve']."/".$value['annee_releve']."</td>
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
						<img width='10' id='editer".$value['id']."' src='img/erreur.gif' alt ='éditer'/>
					</a>
					<img width='10' id='delete_".$value['id']."' src='img/erreur.gif' alt ='supprimer'/>
				</td> 				
				</tr>";
			}?>  
			</tbody>
			
		</table>
	</div>
<?php
	require_once ('footer.php');
?>
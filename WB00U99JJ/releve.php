<?php
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
header('Content-Type: text/html; charset=utf-8');

$pdo=database::getInstance();
if(isset($_GET['id_releve'])){

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



	$stmt = $pdo->prepare("SELECT rd.*,r.mois_releve,r.annee_releve,o.nom_operations as operations, lc.libelle as categorie
		from releve_detail rd
		inner join releve r on rd.id_releve=r.id
		inner join operations o on o.id_operations=rd.id_operations 
		left join liste_cat lc on rd.id_cat=lc.id_cat
		where rd.id_releve=:id_releve
		AND trouve='1'
		ORDER BY rd.date,rd.id_operations,rd.type");

	$stmt->execute(array("id_releve"=>$_GET['id_releve']));
	$aReleve=$stmt->fetchAll(PDO::FETCH_ASSOC);


	$stmt = $pdo->prepare("SELECT rd.*,r.mois_releve,r.annee_releve
		from releve_detail rd
		inner join releve r on rd.id_releve=r.id
		where rd.id_releve=:id_releve		
		AND trouve='0'
		ORDER BY rd.date,rd.id_operations,rd.type");

	$stmt->execute(array("id_releve"=>$_GET['id_releve']));
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
	$select="";
	$aOperations=$stmt->fetchAll(PDO::FETCH_ASSOC);


	$aPointage=array("0"=>"","-1"=>"en erreur","1"=>"ok");

	require_once ('header.html');
	?>

	<form method="post" action="#">
		<div class="bouton_edition">
			<input type="button" onclick="edition();" class="lecture" value="Passer en mode édition">
			<input type="button" onclick="lecture();" class="edition" value="Passer en mode lecture">
		</div>
		<div class="edition">
			Nouvelle catégorie:  <input type='text' name="new_cat"/>
			<input type="submit" name="button_cat" value="Ajouter">
			<br><br>
			Nouveau Keywords:  <input type='text' name="new_keywords"/> <SELECT name='keywords_cat' ><?php echo $select;?></SELECT>
			<input type="submit" name="button_keywords" value="Ajouter">
		</div>
	</form> 

	<div class="detail_releve">
		<table>
			<thead>
				<tr>
					<th>Relevé</th>
					<th>Date Transaction</th>
					<th>Libellé</th>
					<th>Opérations</th>
					<th>Montant</th>
					<th>Type</th>
					<th>Catégorie</th>
					<th>Pointage</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="row">Total</th>
					<td colspan="4">67 designs</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach($aReleve as $value){
				switch($value['pointe']){
					case '1':
						$img="<img width='10' id='pointage_".$value['id']."' src='img/valider.png' alt ='valide'/>";
						break;
					case '-1':
						$img="<img width='10' id='pointage_".$value['id']."' src='img/erreur.gif' alt ='erreur'/>";
						break;
					default :
						$img="<img width='10' id='pointage_".$value['id']."' src='' alt ='aucun' style='display:none'/>";
						break;

				}

				echo "<tr>
				<td>n°".$value['id_releve']." du ".$value['mois_releve']."/".$value['annee_releve']."</td>
				<td>".$value['date']."</td>
				<td>".$value['libelle']."</td>
				<td>".$value['operations']."</td>
				<td class='odd'>".$value['montant']."</td>
				<td>".$value['type']."</td> 

				<td class='lecture'>".$value['categorie']."</td> 
				<td class='edition'><SELECT onchange=\"update_categorie('".$value['id']."',this);\" >";
				foreach($aCat as $categorie){
					echo "<option value='".$categorie['id_cat']."' ".(($categorie['id_cat']==$value['id_cat'])?'selected="selected"':'').">"
					.$categorie['libelle']."</option>";
				}
				echo "</SELECT></td>

				<td class='lecture'>".$img."</td> 
				<td class='edition'><SELECT onchange=\"update_pointage('".$value['id']."',this);\" >";
				foreach($aPointage as $key=>$pointage){
					echo "<option value='".$key."' ".(($key==$value['pointe'])?'selected="selected"':'').">".$pointage."</option>";
				}
				echo "</SELECT></td>
				</tr>";
			}?>  
			</tbody>
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
					<th>Relevé</th>
					<th>Date Transaction</th>
					<th>Libellé</th>
					<th>Opérations</th>
					<th>Montant</th>
					<th>Type</th>
					<th>Catégorie</th>
					<th>Pointage</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="row">Total</th>
					<td colspan="4">67 designs</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach($aReleveErreur as $value){
				echo "<tr>
				<td>n°".$value['id_releve']." du ".$value['mois_releve']."/".$value['annee_releve']."</td>
				<td>".$value['date']."</td>
				<td>".$value['libelle']."</td>
				<td><SELECT id='".$value['id']."' onchange=\"update_operations('".$value['id']."',this);\" >";
				echo "<option value=''></option>";
				foreach($aOperations as $operations){
					echo "<option value='".$operations['id_operations']."'>".$operations['nom_operations']."</option>";
				}
				echo "</SELECT></td>
				<td class='odd'>".$value['montant']."</td>
				<td>".$value['type']."</td> 
				<td><SELECT id='".$value['id']."' onchange=\"update_categorie('".$value['id']."',this);\" >";
				foreach($aCat as $categorie){
					echo "<option value='".$categorie['id_cat']."' ".(($categorie['id_cat']==$value['id_cat'])?'selected="selected"':'').">"
					.$categorie['libelle']."</option>";
				}
				echo "</SELECT></td>
				
				<td class='lecture'>".$img."</td> 
				<td class='edition'><SELECT onchange=\"update_pointage('".$value['id']."',this);\" >";
				foreach($aPointage as $key=>$pointage){
					echo "<option value='".$key."' ".(($key==$value['pointe'])?'selected="selected"':'').">".$pointage."</option>";
				}
				echo "</SELECT></td>
				</tr>";
			}?>  
			</tbody>
		</table>
	</div>
<?php
	}
	require_once ('footer.html');
}
?>
<?php
  // on se connecte à MySQL
$db = mysql_connect('localhost', 'root', '');

// on sélectionne la base
mysql_select_db('perso',$db);

if(isset($_POST['new_cat2']) && $_POST['new_cat2']!=''){
    $sql="INSERT INTO liste_cat2(libelle) VALUE('".$_POST['new_cat2']."')";
    mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
}

if(isset($_POST['new_keywords']) && $_POST['new_keywords']!='' ){
    $sql="INSERT INTO keywords(id_cat2,value) VALUE('".$_POST['keywords_cat2']."','".$_POST['new_keywords']."')";
    mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 	
}


mysql_query("call update_releve_detail_id_cat2()") or die('Erreur SQL !<br>call update_releve_detail_id_cat2()<br>'.mysql_error()); 










$sql_releve='SELECT rd.*,r.mois_releve,r.annee_releve,l.name as cat1 from releve_detail rd, releve r, liste_cat1 l where id_cat2 is null AND rd.id_releve=r.id AND l.id_cat1=rd.id_cat1';

if(isset($_GET['id_releve'])&&preg_match("#^[0-9]+$#",$_GET['id_releve'])){
   $sql_releve.=' AND rd.id_releve='.$_GET['id_releve'];
}

$sql_releve.=" ORDER BY rd.id_releve,rd.id_cat1,rd.type";

  $req_releve = mysql_query($sql_releve) or die('Erreur SQL !<br>'.$sql_releve.'<br>'.mysql_error());
$aReleve=array();
// on fait une boucle qui va faire un tour pour chaque enregistrement
while($assoc_releve = mysql_fetch_assoc($req_releve)) {
     $aReleve[]= $assoc_releve;
}



  $sql_cat2='SELECT * from liste_cat2';
  $req_cat2 = mysql_query($sql_cat2) or die('Erreur SQL !<br>'.$sql_cat2.'<br>'.mysql_error());
$aCat2=array();
$select='';
// on fait une boucle qui va faire un tour pour chaque enregistrement
while($assoc_cat2 = mysql_fetch_assoc($req_cat2)) {
     $aCat2[]= $assoc_cat2;
     $select.="<option value='".$assoc_cat2['id_cat2']."'>".$assoc_cat2['libelle']."</option>";
}
     

?>

<form method="post" action="#">
Nouvelle catégorie:  <input type='text' name="new_cat2"/>
<input type="submit" name="button_cat2" value="Ajouter">
<br><br>
Nouveau Keywords:  <input type='text' name="new_keywords"/> <SELECT name='keywords_cat2' ><?php echo $select;?></SELECT>
<input type="submit" name="button_keywords" value="Ajouter">
</form>    
    

<table><tr><td>Relevé</td><td>date transaction</td><td>libellé</td><td>catégorie</td><td>montant</td><td>type</td></tr>
<?php foreach($aReleve as $value){
     echo "<tr><td>n°".$value['id_releve']." du ".$value['mois_releve']."/".$value['annee_releve']."</td>
     <td>".$value['date']."</td>
     <td>".$value['libelle']."</td>
     <td>".$value['cat1']."</td>
     <td>".$value['montant']."</td>
     <td>".$value['type']."</td>"  ;


}




















?>  </table>
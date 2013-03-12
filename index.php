<?php
  // on se connecte à MySQL
$db = mysql_connect('localhost', 'root', '');

// on sélectionne la base
mysql_select_db('perso',$db);

// on crée la requête SQL
$sql_keywords = 'SELECT * FROM regex_replace order by ordre';

// on envoie la requête
$req_keywords = mysql_query($sql_keywords) or die('Erreur SQL !<br>'.$sql_keywords.'<br>'.mysql_error());
$aRegex_replace=array();
// on fait une boucle qui va faire un tour pour chaque enregistrement
while($assoc_keywords = mysql_fetch_assoc($req_keywords)) {
     $aRegex_replace[]= $assoc_keywords;
}

// on crée la requête SQL
$sql_keywords2 = 'SELECT * FROM regex_replace where TYPE is not null order by ordre ';

// on envoie la requête
$req_keywords2 = mysql_query($sql_keywords2) or die('Erreur SQL !<br>'.$sql_keywords2.'<br>'.mysql_error());
$aRegex_find=array();
// on fait une boucle qui va faire un tour pour chaque enregistrement
while($assoc_keywords2 = mysql_fetch_assoc($req_keywords2)) {
    $aRegex_find[]= $assoc_keywords2;
}



//recuperation du fichier a analyser
$name=$_GET['file'] ;
$select_req=mysql_query("SELECT id FROM releve where fichier='".str_replace("txt","pdf",$name)."' ");
$select=mysql_fetch_assoc($select_req);   
if(mysql_num_rows($select_req)>0 ){
  if(isset($_GET['remplacer']) && $_GET['remplacer']=='true' ){
      mysql_query("DELETE FROM releve WHERE id=".$select['id']);
  }
  else{
     echo "ce relevé a déjà été importé<br><br>Cliquez <a href='".$_SERVER['REQUEST_URI']."&remplacer=true'>ici</a>" ;
     die();
  }  
}

/*$name='RCHQ_101_300040085000000762828_20120921_2226.txt';
//$name='RCHQ_101_300040085000000762828_20120921_2226.txt';
//$name='RCHQ_101_300040085000000762828_20120721_2305.txt';
//$name='RCHQ_101_300040085000000762828_20110627_2236.txt';
//$name='RCHQ_101_300040085000000762828_20120721_2305.txt';   */
$fichier='rch/'.$name;
$contenu_fichier=file_get_contents ($fichier);
foreach($aRegex_replace as $regex){
    $contenu_fichier = preg_replace ($regex['regex'],$regex['replace'],$contenu_fichier);
}

$result=array();
//date et solde
preg_match("#Credit SOLDE (CREDITEUR)?(DEBITEUR)? AU ([0-9]{2}.[0-9]{2}.[0-9]{4}) ([^ ]+)#",$contenu_fichier,$temp);
$result['solde_init']=($temp[1]=="")?"DEBITEUR":"CREDITEUR";
$result['date_init']=$temp[3];
$result['solde_init']=str_replace(".","",$temp[4]);

$contenu_fichier=preg_replace("#^.+Credit SOLDE (CREDITEUR)?(DEBITEUR)? AU ".$result['date_init']." ([^ ]+)#","",$contenu_fichier);


//liste des dates:
preg_match("#^ (([0-9]{2}.[0-9]{2} )+)[^0-9]#",$contenu_fichier,$temp);
$dates=explode(" ",$temp[1]);
if($dates[sizeof($dates)-1]==""){unset($dates[sizeof($dates)-1]);}
$nb_dates=sizeof($dates);
$contenu_fichier=preg_replace("#^ (([0-9]{2}.[0-9]{2} )+)([^0-9])#","$3",$contenu_fichier);
$contenu_fichier=preg_replace("#^/explode/(.+)$#","$1",$contenu_fichier);
$libelles=explode("/explode/",$contenu_fichier);

preg_match("#(.+) (([0-9]{2}.[0-9]{2} ){".$nb_dates."})(([0-9\.]{1,},[0-9]{2} ){".$nb_dates."})(.+)#",$libelles[$nb_dates-1],$temp);
$libelles[$nb_dates-1]=$temp[1];
$liste_date=$temp[2];
$liste_montant=explode(" ",$temp[4]);
if($liste_montant[sizeof($liste_montant)-1]==""){unset($liste_montant[sizeof($liste_montant)-1]);}
$date2=$temp[6];
$libelle_final=array();
$date_final=array();
$libelle_debit=array();
$libelle_credit=array();
$date_debit=array();
$date_credit=array();
for($i=0;$i<$nb_dates;$i++){
    $j=0;
    while(!preg_match($aRegex_find[$j]['regex'],$libelles[$i])&&$j<sizeof($aRegex_find)){
        $j++;
    }
    if($aRegex_find[$j]['type']=='CREDIT'){
        $libelle_credit[]=array('id_cat1'=>$aRegex_find[$j]['id_cat1'],'type'=>"CREDIT","libelle"=>$libelles[$i]);
        $date_credit[]=$dates[$i];
    }
    else{
        $libelle_debit[]=array('id_cat1'=>$aRegex_find[$j]['id_cat1'],'type'=>"DEBIT","libelle"=>$libelles[$i]);
        $date_debit[]=$dates[$i];

    }
}
$libelle_final=array_merge($libelle_debit,$libelle_credit);
$date_final=array_merge($date_debit,$date_credit);



while(preg_match("#^.+Credit (([0-9]{2}.[0-9]{2} )+)#",$date2)){
    $date2=preg_replace("#^.+Credit (([0-9]{2}.[0-9]{2} )+)#","$1",$date2);
    $dates2=explode(" ",$date2);
    if($dates2[sizeof($dates2)-1]==""){unset($dates2[sizeof($dates2)-1]);}
    $dates=array_merge($dates,$dates2);
    $nb_dates=sizeof($dates);
    $nb_dates2=sizeof($dates2);
    if(preg_match("#SOLDE (CREDITEUR)?(DEBITEUR)? AU ([0-9]{2}.[0-9]{2}.[0-9]{4}) ([^ ]+)#",$libelles[$nb_dates-1],$temp)){
        $result['solde_final']=($temp[1]=="")?"DEBITEUR":"CREDITEUR";
        $result['date_final']=$temp[3];
        $result['solde_final']=str_replace(".","",$temp[4]);
    }
    preg_match("#(.+) (([0-9]{2}.[0-9]{2} ){".$nb_dates2."})(([0-9\.]{1,},[0-9]{2} ){".$nb_dates2."})(.+)#",$libelles[$nb_dates-1],$temp);
 $libelles[$nb_dates-1]=$temp[1];
    $liste_date=$temp[2];
   $liste_montant=array_merge($liste_montant,explode(" ",$temp[4]));
    if($liste_montant[sizeof($liste_montant)-1]==""){unset($liste_montant[sizeof($liste_montant)-1]);}
    $date2=$temp[6];

    $libelle_debit=array();
    $libelle_credit=array();
    $date_debit=array();
    $date_credit=array();
    for($i=$nb_dates-$nb_dates2;$i<$nb_dates;$i++){
        $j=0;
        while(!preg_match($aRegex_find[$j]['regex'],$libelles[$i])&&$j<sizeof($aRegex_find)){
            $j++;
        }
        if($aRegex_find[$j]['type']=='CREDIT'){
            $libelle_credit[]=array('id_cat1'=>$aRegex_find[$j]['id_cat1'],'type'=>"CREDIT","libelle"=>$libelles[$i]);
            $date_credit[]=$dates[$i];
        }
        else{
            $libelle_debit[]=array('id_cat1'=>$aRegex_find[$j]['id_cat1'],'type'=>"DEBIT","libelle"=>$libelles[$i]);
            $date_debit[]=$dates[$i];
        }
    }
    $libelle_final=array_merge($libelle_final,$libelle_debit,$libelle_credit);
    $date_final=array_merge($date_final,$date_debit,$date_credit);
}

$date_deb=substr($result['date_init'],6,4)."-".substr($result['date_init'],3,2)."-".substr($result['date_init'],0,2);
$date_fin=substr($result['date_final'],6,4)."-".substr($result['date_final'],3,2)."-".substr($result['date_final'],0,2);
$insert_releve="INSERT INTO releve(mois_releve,annee_releve,date_debut,date_fin,montant_debut,montant_fin,fichier) VALUES ('".substr($result['date_final'],3,2)."','".substr($result['date_final'],6,4)."','".$date_deb."','".$date_fin."','".str_replace(",",".",$result['solde_init'])."','".str_replace(",",".",$result['solde_final'])."','".str_replace("txt","pdf",$name)."')";
mysql_query($insert_releve) or die(mysql_error());
$id_releve=mysql_insert_id();

$resultat=array();
for($i=0;$i<$nb_dates;$i++){
    $resultat[]=array_merge($libelle_final[$i],array("id_releve"=>$id_releve,"montant"=>$liste_montant[$i],"date"=>str_replace(".","/",$date_final[$i])."/2012"));
}
$query="";
foreach($resultat as $val){
    $query.="(";
    foreach($val as $key=>$sval){
        if($key=="date"){
            $sval=substr($sval,6,4)."-".substr($sval,3,2)."-".substr($sval,0,2);
        }
        if($key=="montant"){
            $sval=str_replace(",",".",$sval);
        }
        $query.="'".$sval."',";
    }
    $query.="),";
}
$query=str_replace(",)",")",$query);
$query=substr($query,0,strlen($query)-1);

$insert_detail="INSERT INTO releve_detail(id_cat1,type,libelle,id_releve,montant,date) VALUES ".$query;


mysql_query($insert_detail) or die(mysql_error());
mysql_query("call update_loyer()")or die(mysql_error());

header('Location: update_releve.php?id_releve='.$id_releve); 


?>
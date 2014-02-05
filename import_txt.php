<?php

$db=true;
	// on crée la requête SQL
$stmt = $pdo->prepare('SELECT * FROM regex_replace order by ordre ');
$stmt->execute();
$aRegex_replace=$stmt->fetchAll(PDO::FETCH_ASSOC);

$contenu_fichier=file_get_contents ($fichier);
if($db){
	echo "<pre>";
	print_r($contenu_fichier);
	echo "</pre>";
}
foreach($aRegex_replace as $regex){
	$contenu_fichier = preg_replace ($regex['regex'],$regex['replace'],$contenu_fichier);
}
	$contenu_fichier=str_replace("/explode//explode/","/explode/",$contenu_fichier);
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
if($db){
	echo "<pre>";
	print_r($contenu_fichier);
	echo "</pre>";
}
$libelles=explode("/explode/",$contenu_fichier);
if($db){
	echo "<pre>";
	print_r($libelles);
	echo "</pre>";
}
preg_match("#(.+) (([0-9]{2}.[0-9]{2} ){".$nb_dates."})(([0-9\.]{1,},[0-9]{2} ){".$nb_dates."})(.+)#",$libelles[$nb_dates-1],$temp);
$libelles[$nb_dates-1]=$temp[1];
$liste_date=$temp[2];
$liste_montant=explode(" ",str_replace(".","",$temp[4]));
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
		$libelle_credit[]=array('id_operations'=>$aRegex_find[$j]['id_operations'],'type'=>"CREDIT","libelle"=>$libelles[$i]);
		$date_credit[]=$dates[$i];
	}
	else{
		$libelle_debit[]=array('id_operations'=>$aRegex_find[$j]['id_operations'],'type'=>"DEBIT","libelle"=>$libelles[$i]);
		$date_debit[]=$dates[$i];

	}
}
$libelle_final=array_merge($libelle_debit,$libelle_credit);
$date_final=array_merge($date_debit,$date_credit);

if($db){
	echo "<pre>";
	print_r($libelles);
	echo "</pre>";
}



while(preg_match("#^.+Credit (([0-9]{2}.[0-9]{2} )+)#",$date2)){
	$date2=preg_replace("#^.+Credit (([0-9]{2}.[0-9]{2} )+)#","$1",$date2);
	$dates2=explode(" ",$date2);
	if($dates2[sizeof($dates2)-1]==""){unset($dates2[sizeof($dates2)-1]);}
	if($db){
		echo "<pre>";
		print_r($dates2);
		echo "</pre>";
	}
	$dates=array_merge($dates,$dates2);
	if($db){
		echo "<pre>";
		print_r($dates);
		echo "</pre>";
	};
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
	$liste_montant=array_merge($liste_montant,explode(" ",str_replace(".","",$temp[4])));
	if($liste_montant[sizeof($liste_montant)-1]==""){unset($liste_montant[sizeof($liste_montant)-1]);}
	$date2=$temp[6];
	
	$libelle_debit=array();
	$libelle_credit=array();
	$date_debit=array();
	$date_credit=array();
	for($i=$nb_dates-$nb_dates2;$i<$nb_dates;$i++){
		$j=0;
		while($j<sizeof($aRegex_find)&&!preg_match($aRegex_find[$j]['regex'],$libelles[$i])){
			$j++;
		}
		if($j==sizeof($aRegex_find)){
			echo $libelles[$i]."erreur";die();
		}
		if($aRegex_find[$j]['type']=='CREDIT'){
			$libelle_credit[]=array('id_operations'=>$aRegex_find[$j]['id_operations'],'type'=>"CREDIT","libelle"=>$libelles[$i]);
			$date_credit[]=$dates[$i];
		}
		else{
			$libelle_debit[]=array('id_operations'=>$aRegex_find[$j]['id_operations'],'type'=>"DEBIT","libelle"=>$libelles[$i]);
			$date_debit[]=$dates[$i];
		}
	}
	$libelle_final=array_merge($libelle_final,$libelle_debit,$libelle_credit);
	$date_final=array_merge($date_final,$date_debit,$date_credit);
}

	

$resultat=array();
for($i=0;$i<$nb_dates;$i++){
	$resultat[]=array_merge($libelle_final[$i],array("id_releve"=>$id_releve,"montant"=>$liste_montant[$i],"date"=>str_replace(".","/",$date_final[$i])."/2012"));
}

foreach($resultat as $val){
	$date=substr($val['date'],6,4)."-".substr($val['date'],3,2)."-".substr($val['date'],0,2);
	$val=array('id_releve'=>$id_releve,'id_operations'=>$val['id_operations'],'type'=>$val['type'],"libelle"=>$val['libelle'],"montant"=>str_replace(",",".",$val['montant']),'date'=>$date);  
	$stmt_good->execute($val);
}

   		
						





?>
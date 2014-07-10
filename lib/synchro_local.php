<?php

require_once(__DIR__."/../conf.php");
function push_synchro(){
	rename(REP_SYNCHRO.SYNCHRO_SQL,REP_SYNCHRO.SYNCHRO_SQL_TEMP);
	$file = REP_SYNCHRO.SYNCHRO_SQL_TEMP;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, SITE_DISTANT."lib/synchro_distant.php");
    curl_setopt($ch, CURLOPT_POST, true);
    $post = array(
    	'My_File' => '@'.$file
    );
 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $response = curl_exec($ch);
 	curl_close($curl);
    return $response;	
}

function pull_synchro(){
	$url = SITE_DISTANT."synchro/".SYNCHRO_SQL;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	curl_close($curl);	
	var_dump($data);
}


function synchro(){
	pull_synchro();
	push_synchro();
}
synchro();
	
?>
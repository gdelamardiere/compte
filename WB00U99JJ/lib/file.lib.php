<?php
/**
 * TODO: Fonction à définir
 * @param $sFilepath
 * @param int $nLineStartOffset
 * @param int $nMaxColonne
 * @return array
 */
function parse_csv_file($sFilepath, $nLineStartOffset = 0, $nMaxColonne = 1) {
	# Ouverture du fichier
	$sFileContent = file_get_contents($sFilepath);

	# Découpage en ligne
	$aLines = explode("\n", $sFileContent);

	# Retrait des lignes de titre
	for ($i = 0; $i < $nLineStartOffset; $i++) {
		array_shift($aLines);
	}

	# Retrait de la dernière ligne issue de l'explode
	if (empty($aLines[count($aLines) - 1])) {
		array_pop($aLines);
	}

	# Création d'un tableau de lignes avec les colonnes comme index d'un tableau imbriqué
	$aFileLines = array();
	foreach ($aLines as $nLineOffset => $sLineContent) {
		# Nettoyage des caractères indésirables
		$sLineContent = str_replace("\r", "", $sLineContent);
		$sLineContent = str_replace("\n", "", $sLineContent);

		# Découpage de la ligne
		if (strpos($sLineContent, ';') === false) {
			$aLineElements = array_fill(0, $nMaxColonne, '');
		}
		else {
			$aLineElements = explode(";", $sLineContent);
			if ($nMaxColonne < count($aLineElements)) {
				$aLineElements = array_slice($aLineElements, 0, $nMaxColonne);
			}
			$aLineElements = array_map('trim', $aLineElements);
		}
		$aFileLines[($nLineOffset + $nLineStartOffset + 1)] = $aLineElements;
	}
	return $aFileLines;
}

/**
 * Récupère le contenu d'un dossier
 * @param string $sCurrentDirectory
 * @param string $sFilter regex
 * @param string $funcTri function de tri du tableau
 * @return array
 */
function getFolderContent($sCurrentDirectory = '',$sFilter='',$funcTri='') {

	$handle = opendir($sCurrentDirectory);

	$aFolderContent = array();

	while ($sFilename = readdir($handle)) {
		// Forbidden files & folders
		if ($sFilename == "." OR $sFilename == ".gitignore") {
			continue;
		}
		if($sFilter!='' && !preg_match("#".$sFilter."#i", $sFilename)){
			continue;
		}
		// Folders
		if (is_dir($sCurrentDirectory . "/" . $sFilename)) {
			$aFolderContent['a' . $sFilename] = array(
				'is_dir' => true,
				'name' => $sFilename,
			);
		}
		else {
			$aFolderContent['b' . $sFilename] = array(
				'is_dir' => false,
				'name' => $sFilename,
			);
		}
	}
	if($funcTri!="" && function_exists($funcTri)){
		uksort($aFolderContent,$funcTri);
	}
	else{
		ksort($aFolderContent);
	}
	
	return $aFolderContent;
}

/**
 * @param $outerDir
 * @param $initialDir
 * @param array $filters
 * @return array
 */
function getDirectoryTree($outerDir, $initialDir, $filters = array()) {
	$dirs = array_diff(scandir($outerDir), array_merge(Array(".", "..", '.git', '_thumbs'), $filters));
	$dir_array = Array();
	foreach ($dirs as $d) {
		if (is_dir($outerDir . '/' . $d)) {
			$dir_array[] = array_values(getDirectoryTree($outerDir . '/' . $d, $initialDir, $filters));
		}
		else {
			$dir_array[] = str_replace($initialDir . '/', '', $outerDir . '/' . $d);
		}
	}
	return $dir_array;
}

/**
 * @param $array
 * @return array|bool
 */
function array_flatten($array) {
	if (!is_array($array)) {
		return FALSE;
	}
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result = array_merge($result, array_flatten($value));
		}
		else {
			$result[$key] = $value;
		}
	}
	return $result;
}

/**
 * Suppression d'un répertoire et de l'ensemble de ses fichiers
 * @param string $dir répertoire à supprimer
 * @param bool $bDeleteMainDir
 */
function rrmdir($dir, $aExclude = array(), $bDeleteMainDir = true) {
	foreach(glob($dir . '/*') as $file) {
		if(is_dir($file))
			rrmdir($file, $aExclude);
		elseif (!in_array($file, $aExclude))
			unlink($file);
	}
	if ($bDeleteMainDir) {
		rmdir($dir);
	}
}

function import_file_profiling($file,$aHeaderWait){
	$nb_colonne=sizeof($aHeaderWait);
	$aContentFile=parse_csv_file($file,0,$nb_colonne);
	$diff_header=array_diff($aHeaderWait,$aContentFile[1]);
	$aReturn=array();
	//cas fichier vide
	if(sizeof($aContentFile)<2){
		return array("erreur"=>"fichier vide");
	}
	//cas manque colonne
	else if(sizeof($aContentFile[1])<$nb_colonne){
		return array("erreur"=>"manque colonne");
	}
	//cas mauvaises colonnes		
	else if(!empty($diff_header)){		
		return array("erreur"=>"les colonnes attendus sont les suivantes: ".implode(", ",$aHeaderWait));
	}
	//sinon, on récupère pour chaque id de faq le profiling correspondant
	else{
		for($i=1;$i<=sizeof($aContentFile);$i++){
			$profiling=array();
			for($j=2;$j<$nb_colonne;$j++){
				if($aContentFile[$i][$j]=='1'){
					$profiling[]=$aHeaderWait[$j];
				}
			}
			$aReturn[$aContentFile[$i][0]]=implode("|",$profiling);
		}
	}
	return($aReturn);
}

function save_Array_csv($aContenu,$file){
	$sContent="";
	foreach($aContenu as $ligne){
		$sContent.=implode(";", $ligne);
		$sContent.="\n";
	}
	if (!file_put_contents($file, $sContent)) {
        return false;
    }
    return true;
}

/**
 * déplacement des fichiers de faq dans un répertoire selon l'id d'une faq
 * @param string $dir répertoire à supprimer
 * @param bool $bDeleteMainDir
 */
function mvFaq($rep,$rep_dest,$aId) {
	foreach($aId as $file) {
		rename($rep.$file,$rep_dest.$file);
	}
}


function tri_date_heure_desc($b, $a){
	$return=0;
	if(!preg_match("#^.+([0-9]{2}_[0-9]{2}_[0-9]{4}__[0-9]{2}_[0-9]{2}_[0-9]{2}).+$#",$a,$tabA)){
		return -1;
	}
	if(!preg_match("#^.+([0-9]{2}_[0-9]{2}_[0-9]{4}__[0-9]{2}_[0-9]{2}_[0-9]{2}).+$#",$b,$tabB)){
		return 1;
	}
	$aTime=DateTime::createFromFormat("d_m_Y__H_i_s",$tabA[1]);
	$bTime=DateTime::createFromFormat("d_m_Y__H_i_s",$tabB[1]);
	$aTimestamp=$aTime->getTimestamp();
	$bTimestamp=$bTime->getTimestamp();
	if($aTimestamp<$bTimestamp){
		$return= -1;
	}
	if($aTimestamp>$bTimestamp){
		$return= 1;
	}
	return $return;
}
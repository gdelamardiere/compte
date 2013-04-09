<?php
require_once(ROOT.'classes/database.php');


class reports{
	private $pdo;
	private $requete_prepare;
	private $requete;
	public $resultat=array();
	public $listeCategorie=array(); 
	public $listeOperations=array(); 
	public $listeRegroupements=array(); 
	public $listeKeywords = array();
	public $listeRegex = array();
	public $listeExcel=array();
	public $sFilterCategorie="";
	public $sFilterOperations="";
	public $sFilterRegroupements="";
	public $sFilterCatRegroupements="";
	private $aAllowedTypes=array("DEBIT","CREDIT");
	public $listeCatByRegroupement=array();

	function __construct(){
		$this->pdo=database::getInstance();
		$this->listeCategories();
		$this->listeOperations();
		$this->listeRegroupement();
	}


	
//liste
	function listeCategories(){
		if(empty($this->listeCategorie)){
			$requete=$this->pdo->prepare("SELECT id_cat,libelle
										from liste_cat ORDER BY libelle");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_cat']]=$result['libelle'];
			}
			$this->listeCategorie= $resultat;
		}
		return $this->listeCategorie;		
	}

	function listeOperations(){
		if(empty($this->listeOperations)){
			$requete=$this->pdo->prepare("SELECT id_operations,nom_operations
										from operations  ORDER BY nom_operations");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_operations']]=$result['nom_operations'];
			}
			$this->listeOperations= $resultat;
		}
		return $this->listeOperations;		
	}


	function listeExcel(){
		if(empty($this->listeExcel)){
			$requete=$this->pdo->prepare("SELECT id_excel,libelle,position
										from import_excel");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_excel']]=$result;
			}
			$this->listeExcel= $resultat;
		}
		return $this->listeExcel;		
	}

	function listeRegroupement(){
		if(empty($this->listeRegroupement)){
			$requete=$this->pdo->prepare("SELECT id_regroupement,nom
										from regroupement");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_regroupement']]=$result['nom'];
			}
			$this->listeRegroupement= $resultat;
		}
		return $this->listeRegroupement;		
	}

	function listeCatByRegroupement($id_regroupement=0){
		if(empty($this->listeCatByRegroupement)){
			$requete=$this->pdo->prepare("SELECT c.id_cat,c.libelle,rc.id_regroupement
										from r_regroupement_cat rc
										INNER JOIN liste_cat c on c.id_cat=rc.id_cat
										ORDER BY rc.id_regroupement,c.libelle
										");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_regroupement']][$result['id_cat']]=$result['libelle'];
			}
			$this->listeRegroupement= $resultat;
		}
		if($id_regroupement==0){
			return $this->listeRegroupement;
		}
		else{
			return $this->listeRegroupement[$id_regroupement];
		}
				
	}

	function listeCatInRegroupement($sListeRegroupement){
		$requete=$this->pdo->prepare("SELECT distinct c.id_cat,c.libelle
										from liste_cat c
										INNER JOIN r_regroupement_cat   rc on c.id_cat=rc.id_cat
										where rc.id_regroupement in(".$sListeRegroupement.")
										ORDER BY c.libelle										
										");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_cat']]=$result['libelle'];
		}
		return $resultat;				
	}

	function listeReleve(){
		$requete=$this->pdo->prepare("SELECT id as id_releve,concat(mois_releve,'-',annee_releve) as date
										from releve ORDER BY annee_releve, mois_releve DESC");
		$requete->execute();
		$resultat=$requete->fetchall(PDO::FETCH_ASSOC);
		return $resultat;
	}

function listeAnnee(){
		$requete=$this->pdo->prepare("SELECT distinct annee_releve
										from releve ORDER BY annee_releve DESC");
		$requete->execute();
		$resultat=$requete->fetchall(PDO::FETCH_ASSOC);
		return $resultat;
	}

	function getListeIdAnnee($id_releve){
		$requete=$this->pdo->prepare("SELECT id
										from releve
										WHERE (annee_releve = (SELECT annee_releve from releve where id = :id) 
												AND mois_releve <= (SELECT mois_releve from releve where id = :id))
										OR (annee_releve = (SELECT annee_releve from releve where id = :id) -1
												AND mois_releve >= (SELECT mois_releve from releve where id = :id))
												ORDER BY annee_releve DESC,mois_releve DESC");
		$requete->execute(array("id"=>$id_releve));
		$resultat="";
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat.=($resultat=="")?$result['id']:",".$result['id'];
		}
		return $resultat;
	}

	function getListeIdForYear($annee){
		$requete=$this->pdo->prepare("SELECT id
										from releve
										WHERE annee_releve = :annee
										ORDER BY mois_releve ASC");
		$requete->execute(array("annee"=>$annee));
		$resultat="";
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat.=($resultat=="")?$result['id']:",".$result['id'];
		}
		return $resultat;
	}

	function listeKeywords(){
		if(empty($this->listeKeywords)){
			$requete=$this->pdo->prepare("SELECT *
										from keywords ORDER BY value");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_keywords']]=array("value"=>$result['value'],"id_cat"=>$result['id_cat']);
			}
			$this->listeKeywords= $resultat;
		}
		return $this->listeKeywords;
	}
function listeRegex(){
		if(empty($this->listeRegex)){
			$requete=$this->pdo->prepare("SELECT *
										from regex_replace");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_keywords']]=$result;
			}
			$this->listeRegex= $resultat;
		}
		return $this->listeRegex;
	}


	function get_liste_filtre(){
		$requete=$this->pdo->prepare("SELECT id_filtre,nom_filtre from filtres ORDER BY nom_filtre");
		$resultat=array();
		$requete->execute();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_filtre']]=$result['nom_filtre'];
		}
		return $resultat;
	}

	/*fin liste*/


/*filtres*/


	function setFiltersCategorie($listeCategorie){
		$this->sFilterCategorie="";
		foreach($listeCategorie as $key=>$id){
			if(array_key_exists($id,$this->listeCategorie)){
				$this->sFilterCategorie.=($this->sFilterCategorie=="")?$id:",".$id;
			}
		}
		if(!empty($this->sFilterCategorie)){
			$this->sFilterCategorie=" AND rd.id_cat in(".$this->sFilterCategorie.") ";
		}
	}

	function setFiltersOperations($listeOperations){
		$this->sFilterOperations="";
		foreach($listeOperations as $key=>$id){
			if(array_key_exists($id,$this->listeOperations)){
				$this->sFilterOperations.=($this->sFilterOperations=="")?$id:",".$id;
			}
		}
		if(!empty($this->sFilterOperations)){
			$this->sFilterOperations=" AND rd.id_operations in(".$this->sFilterOperations.") ";
		}
	}

	function setFiltersRegroupements($listeRegroupements){
		$this->sFilterRegroupements="";
		foreach($listeRegroupements as $key=>$id){
			if(array_key_exists($id,$this->listeRegroupement)){
				$this->sFilterRegroupements.=($this->sFilterRegroupements=="")?$id:",".$id;
			}
		}
		if(!empty($this->sFilterRegroupements)){
			$this->sFilterRegroupements=" AND rc.id_regroupement in(".$this->sFilterRegroupements.") ";
		}
	}

	function setFiltersCatRegroupements($slisteRegroupements){
		$this->sFilterCatRegroupements="";
		$listeCats=$this->listeCatInRegroupement($slisteRegroupements);
		foreach($listeCats as $id=>$libelle){
			if(array_key_exists($id,$this->listeCategorie)){
				$this->sFilterCatRegroupements.=($this->sFilterCatRegroupements=="")?$id:",".$id;
			}	
		}
		if(!empty($this->sFilterCatRegroupements)){
			$this->sFilterCatRegroupements=" AND rd.id_cat in(".$this->sFilterCatRegroupements.") ";
		}		
	}


/*fin filtres*/


/*Par type*/

	function getByType($sListeReleve,$filtre_type=""){
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT type, SUM(montant) as montant 
										from releve_detail rd
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by type");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['type']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByType($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT id_releve,type, SUM(montant) as montant 
										from releve_detail rd
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type."
										Group by id_releve,type");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']]= $result;
		}
		return $resultat;
	}



/*Par operations*/

	function getByOperations($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT nom_operations as operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rd.id_operations");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['operations']]= $result['montant'];
		}
		return $resultat;
	}


	

	function CompareByOperations($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT id_releve,nom_operations as operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by id_releve,rd.id_operations");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][]= $result;
		}
		return $resultat;
	}

/*par regroupeemnts*/


	function getByRegroupements($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT r.nom as regroupement, SUM(montant) as montant 
										from releve_detail rd,
										 regroupement r
										 inner join r_regroupement_cat rc on rc.id_regroupement=r.id_regroupement
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterRegroupements."
										and rd.id_cat=rc.id_cat										
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rc.id_regroupement");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['regroupement']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByRegroupements($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT id_releve,r.nom as libelle, SUM(montant) as montant 
										from regroupement r,
										 r_regroupement_cat rc,
										 releve_detail rd
										 inner join releve re on rd.id_releve=re.id
										where id_releve in(".$sListeReleve.") 
										AND rc.id_regroupement=r.id_regroupement
										and rc.id_cat=rd.id_cat	
										".$this->sFilterRegroupements."										
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by id_releve,rc.id_regroupement
										order by annee_releve, mois_releve ASC");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}


/*Par categorie*/

	function getByCategorie($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rd.id_cat");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByCategorie($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT id_releve,c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										left join releve r on rd.id_releve=r.id
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by id_releve,rd.id_cat
										order by annee_releve, mois_releve ASC");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByCategorieAnnee($anne1,$anne2,$filtre_type=""){
		$listeIdAnne1=$this->getListeIdForYear($anne1);
		$listeIdAnne2=$this->getListeIdForYear($anne2);
		return $this->CompareByCategoriePerso($listeIdAnne1,$listeIdAnne2,$filtre_type);
	}
	function CompareByRegroupementAnnee($anne1,$anne2,$filtre_type=""){
		$listeIdAnne1=$this->getListeIdForYear($anne1);
		$listeIdAnne2=$this->getListeIdForYear($anne2);
		return $this->CompareByRegroupementPerso($listeIdAnne1,$listeIdAnne2,$filtre_type);
	}
	

	function CompareByCategoriePerso($listeId1,$listeId2,$filtre_type=""){
		if(is_array($listeId1)){
			$listeId1=implode(",",$listeId1);
		}
		if(is_array($listeId2)){
			$listeId2=implode(",",$listeId2);
		}
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$listeId1.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rd.id_cat");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat["liste1"][$result['libelle']]= $result['montant'];
		}

		$requete=$this->pdo->prepare("SELECT c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$listeId2.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rd.id_cat");
		$requete->execute();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat["liste2"][$result['libelle']]= $result['montant'];
		}

		return $resultat;
	}


	function CompareByRegroupementPerso($listeId1,$listeId2,$filtre_type=""){
		if(is_array($listeId1)){
			$listeId1=implode(",",$listeId1);
		}
		if(is_array($listeId2)){
			$listeId2=implode(",",$listeId2);
		}
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT r.nom as libelle, SUM(montant) as montant 
										from regroupement r,
										 r_regroupement_cat rc,
										 releve_detail rd
										 inner join releve re on rd.id_releve=re.id
										where id_releve in(".$listeId1.") 
										AND rc.id_regroupement=r.id_regroupement
										and rc.id_cat=rd.id_cat	
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rc.id_regroupement");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat["liste1"][$result['libelle']]= $result['montant'];
		}

		$requete=$this->pdo->prepare("SELECT r.nom as libelle, SUM(montant) as montant 
										from regroupement r,
										 r_regroupement_cat rc,
										 releve_detail rd
										 inner join releve re on rd.id_releve=re.id
										where id_releve in(".$listeId2.") 
										AND rc.id_regroupement=r.id_regroupement
										and rc.id_cat=rd.id_cat	
										".$this->sFilterOperations.$this->sFilterCategorie.$this->sFilterCatRegroupements.$filtre_type." 
										Group by rc.id_regroupement");
		$requete->execute();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat["liste2"][$result['libelle']]= $result['montant'];
		}

		return $resultat;
	}







/*select*/


	

	function getSelectCategorie($id_selected=0){
		$select="";
		$liste_cat=$this->listeCategories();
		foreach($liste_cat as $id=>$value){
			$select.="<option value='".$id."' ".(($id_selected==$id)?"selected='selected'":"").">".$value."</option>";
		}
		return $select;
	}

	function getSelectOperations($id_selected=0){
		$select="";
		$liste_op=$this->listeOperations();
		foreach($liste_op as $id=>$value){
			$select.="<option value='".$id."' ".(($id_selected==$id)?"selected='selected'":"").">".$value."</option>";
		}
		return $select;
	}

	function getSelectCatByRegroupement($id_regroupement){
		$select="";
		$liste_cat_regroupement=$this->listeCatByRegroupement($id_regroupement);
		$liste_cat=$this->listeCategories();
		foreach($liste_cat as $id=>$value){
			$select.="<option value='".$id."' ".((array_key_exists($id,$liste_cat_regroupement))?"selected='selected'":"").">".$value."</option>";
		}
		return $select;
	}

/*fin select*/	


	static function getDateIdReleve($id_releve){
		$pdo=database::getInstance();
		$requete=$pdo->prepare("SELECT concat(mois_releve,'/',annee_releve) as date_releve
										from releve WHERE id=:id");
		$requete->execute(array('id'=>$id_releve));
		$resultat=$requete->fetch(PDO::FETCH_ASSOC);
		return $resultat['date_releve'];
	}


	function save_filtre($aData){
		$listeFiltre=array("nom_filtre","id_releve","coche_operations","coche_categorie","liste_graphe","filtre_annee_1","filtre_annee_2","filtre_perso_1","filtre_perso_2");
		$valueFiltre=array();
		foreach($listeFiltre as $key){
			if(isset($aData[$key])){
				if(is_array($aData[$key])){
					$val=implode(",",$aData[$key]);
				}
				else{
					$val=$aData[$key];
				}
				$valueFiltre[$key]=$val;
			}
			else{
				$valueFiltre[$key]='';
			}
		}
		$requete=$this->pdo->prepare('INSERT INTO filtres(nom_filtre,id_releve,coche_operations,coche_categorie,liste_graphe,filtre_annee_1,filtre_annee_2,filtre_perso_1,filtre_perso_2) 
										VALUE(:nom_filtre,:id_releve,:coche_operations,:coche_categorie,:liste_graphe,:filtre_annee_1,:filtre_annee_2,:filtre_perso_1,:filtre_perso_2)
										');
		$requete->execute($valueFiltre);
	}

	function get_filtre($id_filtre){
		$requete=$this->pdo->prepare("SELECT *
										from filtres where id_filtre=:id_filtre");
		$listeFiltreArray=array("nom_filtre","id_releve","coche_operations","coche_categorie","liste_graphe","filtre_perso_1","filtre_perso_2");
		$requete->execute(array("id_filtre"=>$id_filtre));
		$resultat=array();
		$result=$requete->fetch(PDO::FETCH_ASSOC);
		foreach($result as $key=>$val){
			if(in_array($key, $listeFiltreArray)){
				$resultat[$key]=explode(",",$val);
			}
			else{
				$resultat[$key]=$val;
			}
		}
		return $resultat;
	}




}









?>
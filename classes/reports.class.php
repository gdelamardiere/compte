<?php
require_once(ROOT.'classes/database.php');


class reports{
	private $pdo;
	private $requete_prepare;
	private $requete;
	public $resultat=array();
	public $listeCategorie=array(); 
	public $listeOperations=array(); 
	public $listeKeywords = array();
	public $listeRegex = array();
	public $listeExcel=array();
	public $sFilterCategorie="";
	public $sFilterOperations="";
	private $aAllowedTypes=array("DEBIT","CREDIT");

	function __construct(){
		$this->pdo=database::getInstance();
		$this->listeCategories();
		$this->listeOperations();
	}


	

/*Par type*/

	function getByType($sListeReleve,$filtre_type=""){
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT type, SUM(montant) as montant 
										from releve_detail rd
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type."
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
										Group by id_releve,rd.id_operations");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][]= $result;
		}
		return $resultat;
	}


/*Par operations*/

	function getByCategorie($sListeReleve,$filtre_type=""){		
		$filtre_type=(in_array($filtre_type,$this->aAllowedTypes))?" AND type='".$filtre_type."' ":"";
		$requete=$this->pdo->prepare("SELECT c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
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
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
										Group by rd.id_cat");
		$requete->execute();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat["liste2"][$result['libelle']]= $result['montant'];
		}

		return $resultat;
	}






	function listeCategories(){
		if(empty($this->listeCategorie)){
			$requete=$this->pdo->prepare("SELECT id_cat,libelle
										from liste_cat");
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
										from operations");
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

	function setFiltersCategorie($listeCategorie){
		$this->sFilterCategorie="";
		$cat_null=false;
		foreach($listeCategorie as $key=>$id){
			if(array_key_exists($id,$this->listeCategorie)){
				$this->sFilterCategorie.=($this->sFilterCategorie=="")?$id:",".$id;
			}
			if($id==1){
				$cat_null=true;
			}
		}
		if(!empty($this->sFilterCategorie)){
			if($cat_null){
				$this->sFilterCategorie=" AND (rd.id_cat in(".$this->sFilterCategorie.") or rd.id_cat is null) ";
			}
			else{
				$this->sFilterCategorie=" AND rd.id_cat in(".$this->sFilterCategorie.") ";
			}			
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



	function listeKeywords(){
		if(empty($this->listeKeywords)){
			$requete=$this->pdo->prepare("SELECT *
										from keywords");
			$requete->execute();
			$resultat=array();
			while($result=$requete->fetch(PDO::FETCH_ASSOC)){
				$resultat[$result['id_keywords']]=array("value"=>$result['value'],"id_cat"=>$result['id_cat']);
			}
			$this->listeKeywords= $resultat;
		}
		return $this->listeKeywords;
	}

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


	function getDateIdReleve($id_releve){
		$requete=$this->pdo->prepare("SELECT concat(mois_releve,'/',annee_releve) as date_releve
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

	function get_liste_filtre(){
		$requete=$this->pdo->prepare("SELECT id_filtre,nom_filtre from filtres ");
		$resultat=array();
		$requete->execute();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_filtre']]=$result['nom_filtre'];
		}
		return $resultat;
	}



}









?>
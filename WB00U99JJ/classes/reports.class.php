<?php
require_once(ROOT.'classes/database.php');


class reports{
	private $pdo;
	private $requete_prepare;
	private $requete;
	public $resultat=array();
	public $listeCategorie=array(); 
	public $listeOperations=array(); 
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
										where id_releve in(".$sListeReleve.") 
										".$this->sFilterOperations.$this->sFilterCategorie.$filtre_type." 
										Group by id_releve,rd.id_cat");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][$result['libelle']]= $result['montant'];
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




	function listeReleve(){
		$requete=$this->pdo->prepare("SELECT id as id_releve,concat(mois_releve,'-',annee_releve) as date
										from releve ORDER BY annee_releve, mois_releve DESC");
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
												");
		$requete->execute(array("id"=>$id_releve));
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





}









?>
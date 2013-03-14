<?php
require_once(ROOT.'classes/database.php');


class reports{
	private $pdo;
	private $requete_prepare;
	private $requete;
	public $resultat=array();

	function __construct(){
		$this->pdo=database::getInstance();
	}


	

/*Par type*/

	function getByType($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT type, SUM(montant) as montant 
										from releve_detail 
										where id_releve in(".$sListeReleve.") 
										".$filtre." 
										Group by type");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['type']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByType($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,type, SUM(montant) as montant 
										from releve_detail 
										where id_releve in(".$sListeReleve.") 
										".$filtre."
										Group by id_releve,type");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']]= $result;
		}
		return $resultat;
	}



/*Par operations*/

	function getByOperations($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT nom_operations as operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(".$sListeReleve.") 
										".$filtre." 
										Group by rd.id_operations");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['operations']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByOperations($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,nom_operations as operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(".$sListeReleve.") 
										".$filtre."
										Group by id_releve,rd.id_operations");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][]= $result;
		}
		return $resultat;
	}


/*Par operations*/

	function getByCategorie($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$sListeReleve.") 
										".$filtre." 
										Group by rd.id_cat");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByCategorie($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,c.libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(".$sListeReleve.") 
										".$filtre."
										Group by id_releve,rd.id_cat");
		$requete->execute();
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']][$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}

}









?>
<?php
require_once(ROOT.'classes/database.php');


class reports{
	private $pdo;
	private $requete_prepare;
	private $requete;
	public $resultat=array();

	function __construct(){
	}


	

/*Par type*/

	function getByType($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT type, SUM(montant) as montant 
										from releve_detail 
										where id_releve in(:id_releve) 
										".$filtre." 
										Group by type");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['type']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByType($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,type, SUM(montant) as montant 
										from releve_detail 
										where id_releve in(:id_releve) 
										".$filtre."
										Group by id_releve,type");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']]= $result;
		}
		return $resultat;
	}



/*Par operations*/

	function getByOperations($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(:id_releve) 
										".$filtre." 
										Group by id_operations");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['operations']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByOperations($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,operations, SUM(montant) as montant 
										from releve_detail rd
										left join operations o on rd.id_operations=o.id_operations
										where id_releve in(:id_releve) 
										".$filtre."
										Group by id_releve,id_operations");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']]= $result;
		}
		return $resultat;
	}


/*Par operations*/

	function getByCategorie($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(:id_releve) 
										".$filtre." 
										Group by id_cat");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['libelle']]= $result['montant'];
		}
		return $resultat;
	}

	function CompareByCategorie($sListeReleve,$filtre=""){
		$requete=$this->pdo->prepare("SELECT id_releve,libelle, SUM(montant) as montant 
										from releve_detail rd
										left join liste_cat c on rd.id_cat=c.id_cat
										where id_releve in(:id_releve) 
										".$filtre."
										Group by id_releve,id_cat");
		$requete->execute(array("id_releve"=>$sListeReleve));
		$resultat=array();
		while($result=$requete->fetch(PDO::FETCH_ASSOC)){
			$resultat[$result['id_releve']]= $result;
		}
		return $resultat;
	}

}









?>
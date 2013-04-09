<?php
require_once(ROOT.'lib/graphe.lib.php');
?>
<script type="text/javascript">		
function save(){
//$("#percentByCategorie").print();
var canvasData = $('#percentByCategorie').jqplotToImageStr();
var ajax = new XMLHttpRequest();
ajax.open("POST",'test.php',false);
ajax.setRequestHeader('Content-Type', 'application/upload');
ajax.send(canvasData ); 
}



$(document).ready(function(){


<?php 
if(in_array("type",$liste_graphes_retenus)){
	$data=$reports->getByType($id_selected);
	display_data_graphe('getByType',$data);
	display_graphe_percent('percentByType','getByType');
 }

//graphes par regroupements
if(in_array("regroupement",$liste_graphes_retenus)){
	$data=$reports->getByRegroupements($id_selected,"CREDIT");
	display_data_graphe('data_regroupement_credit',$data);
	display_graphe_chart("regroupement_credit","data_regroupement_credit");
	display_reset("reset_regroupement_credit","regroupement_credit");

	$data=$reports->getByRegroupements($id_selected,"DEBIT");
	display_data_graphe('data_regroupement_debit',$data);
	display_graphe_chart("regroupement_debit","data_regroupement_debit");
	display_reset("reset_regroupement_debit","regroupement_debit");
	
}




//graphes detail  regroupements
if(!empty($liste_details)){
	foreach($liste_regroupements as $id=>$nom){
		if(in_array($id,$liste_details)){
			$reports->setFiltersCatRegroupements($id);
			$data=$reports->getByCategorie($id_selected,"CREDIT");
			display_data_graphe('data_detail_credit_'.$id,$data);
			display_graphe_chart("detail_credit_".$id,"data_detail_credit_".$id);
			display_reset("zoom_detail_credit_".$id,"detail_credit_".$id);

			$data=$reports->getByCategorie($id_selected,"DEBIT");
			display_data_graphe('data_detail_debit_'.$id,$data);
			display_graphe_chart("detail_debit_".$id,"data_detail_debit_".$id);
			display_reset("zoom_detail_debit_".$id,"detail_debit_".$id);			
		}
	}
}
$reports->setFiltersCatRegroupements(0);


//graphes par categorie
if(in_array("categorie",$liste_graphes_retenus)){
	$data=$reports->getByCategorie($id_selected,"CREDIT");
	display_data_graphe('data_categorie_credit',$data);
	display_graphe_chart("categorie_credit","data_categorie_credit");
	display_reset("reset_categorie_credit","categorie_credit");

	$data=$reports->getByCategorie($id_selected,"DEBIT");
	display_data_graphe('data_categorie_debit',$data);
	display_graphe_chart("categorie_debit","data_categorie_debit");
	display_reset("reset_categorie_debit","categorie_debit");
} 



//graphes sur un an
if(in_array("comparatif_sur_annee",$liste_graphes_retenus)){

	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByRegroupements($reports->getListeIdAnnee($id_selected),"DEBIT");
	display_graphe_comparatif_annuel("annuel_debit",$data);
	
	display_reset("zoom_annuel_debit","annuel_debit");



	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByRegroupements($reports->getListeIdAnnee($id_selected),"CREDIT");
	display_graphe_comparatif_annuel("annuel_credit",$data);
	display_reset("zoom_annuel_credit","annuel_credit");

 }

 if(in_array("comparatif_sur_annee",$liste_graphes_retenus) && in_array("categorie",$liste_graphes_retenus)){
//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"DEBIT");
	display_graphe_comparatif_annuel("annuel_cat_debit",$data);
	display_reset("zoom_annuel_cat_debit","annuel_cat_debit");

	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"CREDIT");
	display_graphe_comparatif_annuel("annuel_cat_credit",$data);
	display_reset("zoom_annuel_cat_credit","annuel_cat_credit");
 }

 if(isset($id_filtre_annee1) && isset($id_filtre_annee2) && $id_filtre_annee1!=0 && $id_filtre_annee2!=0){
	$id_anne2=max($id_filtre_annee1,$id_filtre_annee2);
	$id_anne1=min($id_filtre_annee1,$id_filtre_annee2);
	$id_filtre_annee1=$id_anne1;
	$id_filtre_annee2=$id_anne2;
	$data=$reports->CompareByRegroupementAnnee($id_filtre_annee1,$id_filtre_annee2,"CREDIT");
	$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
	display_graphe_comparatif_2annee('comparatif_annees_credit',$data,$aLabelSeries);
	display_reset("reset_comparatif_annees_credit","comparatif_annees_credit");
	$data=$reports->CompareByRegroupementAnnee($id_filtre_annee1,$id_filtre_annee2,"DEBIT");
	$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
	display_graphe_comparatif_2annee('comparatif_annees_debit',$data,$aLabelSeries);
	display_reset("reset_comparatif_annees_debit","comparatif_annees_debit");

 }

  if(isset($id_filtre_annee1) && isset($id_filtre_annee2) && $id_filtre_annee1!=0 && $id_filtre_annee2!=0 && in_array("categorie",$liste_graphes_retenus)){
	$id_anne2=max($id_filtre_annee1,$id_filtre_annee2);
	$id_anne1=min($id_filtre_annee1,$id_filtre_annee2);
	$id_filtre_annee1=$id_anne1;
	$id_filtre_annee2=$id_anne2;
	$data=$reports->CompareByCategorieAnnee($id_filtre_annee1,$id_filtre_annee2,"CREDIT");
	$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
	display_graphe_comparatif_2annee('comparatif_annees_credit_cat',$data,$aLabelSeries);
	display_reset("reset_comparatif_annees_credit_cat","comparatif_annees_credit_cat");
	$data=$reports->CompareByCategorieAnnee($id_filtre_annee1,$id_filtre_annee2,"DEBIT");
	$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
	display_graphe_comparatif_2annee('comparatif_annees_debit_cat',$data,$aLabelSeries);
	display_reset("reset_comparatif_annees_debit_cat","comparatif_annees_debit_cat");

 }


if(isset($id_filtre_perso1) && isset($id_filtre_perso2) && !empty($id_filtre_perso1) && !empty($id_filtre_perso2)){
	
	$aLabelSeries=array("serie 1","serie 2");
	$data=$reports->CompareByRegroupementPerso($id_filtre_perso1,$id_filtre_perso2,"CREDIT");
	display_graphe_comparatif_2annee('comparatif_perso_credit',$data,$aLabelSeries);
	display_reset("reset_comparatif_perso_credit","comparatif_perso_credit");
	$data=$reports->CompareByRegroupementPerso($id_filtre_perso1,$id_filtre_perso2,"DEBIT");
	display_graphe_comparatif_2annee('comparatif_perso_debit',$data,$aLabelSeries);
	display_reset("reset_comparatif_perso_debit","comparatif_perso_debit");

 }

 if(isset($id_filtre_perso1) && isset($id_filtre_perso2) && !empty($id_filtre_perso1) && !empty($id_filtre_perso2)  && in_array("categorie",$liste_graphes_retenus)){
	
	$aLabelSeries=array("serie 1","serie 2");
	$data=$reports->CompareByCategoriePerso($id_filtre_perso1,$id_filtre_perso2,"CREDIT");
	display_graphe_comparatif_2annee('comparatif_perso_credit_cat',$data,$aLabelSeries);
	display_reset("reset_comparatif_perso_credit_cat","comparatif_perso_credit_cat");
	$data=$reports->CompareByCategoriePerso($id_filtre_perso1,$id_filtre_perso2,"DEBIT");
	display_graphe_comparatif_2annee('comparatif_perso_debit_cat',$data,$aLabelSeries);
	display_reset("reset_comparatif_perso_debit_cat","comparatif_perso_debit_cat");

 }?>



if($('.checboxFiltre').length){
	$('.checboxFiltre').click(function() {
		var thisCheck = $(this);
		if (thisCheck.is(':checked'))
		{
			$('#label_'+thisCheck.attr('id')).attr("class","selected");
		}
		else{
			$('#label_'+thisCheck.attr('id')).attr("class","deselected");
		}
	});
}


if($('#import_fichier').length){
	 $(function () { 
	 	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); 
	});
}





if($('#import_fichier').length){
	 $( "#confirmation" ).dialog({
	autoOpen: false,
	height: 300,
	width: 350,
	modal: true,
	buttons: {
	"le remplacer": function() {
		$( this ).dialog( "close" );
		$('#modifier').val(0);
		$('#verif_submit').val(0);
		$('#import_fichier').submit();
	},
	"le modifier": function() {
		$( this ).dialog( "close" );
		$('#modifier').val(1);
		$('#verif_submit').val(0);
		$('#import_fichier').submit();
	},
	Annuller: function() {
	$( this ).dialog( "close" );
	}
	},
	close: function() {
	$( this ).dialog( "close" );
	}
	});
}



if($('#import_fichier').length){
	$('#import_fichier').submit(function() {
		mois=$('#mois_import').val();
		annee=$('#annee_import').val();
		verif_submit=$('#verif_submit').val();
		if(verif_submit == '1'){
			existeReleve();
		    return false;		
		}
		else{
			return true;
		}
	});
}

if($('.widget-header').length){
	$('.widget-header').click(function() {
		var parent=$(this).parent();
		var content = parent.find(".widget-content");
		var icone = parent.find(".reduction").find("i");
		if(content.is(':visible')){
			content.slideUp("slow");			
			icone.attr("class","icon-resize-full");
		}
		else{
			content.slideDown("slow");			
			icone.attr("class","icon-resize-small");
		}
	});
}

});

function existeReleve(){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'verif_insert_releve': "true", 'mois_releve': mois, 'annee_releve': annee },
		async : true
		}).done(function( msg ) {
			if(msg > 0){
				$('#id_releve').val(msg);
				$("#confirmation").dialog( "open" );
			}
			else{
				$('#verif_submit').val(0);
				$('#import_fichier').submit();
			}
	});
}

</script>
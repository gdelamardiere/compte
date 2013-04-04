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


<?php if(in_array("type",$liste_graphes_retenus)){?>
		var getByType = [
		<?php 
		$data=$reports->getByType($id_selected);
		foreach($data as $key => $value){
			echo "['".$key."', ".abs($value)."],";
		}
		?>
		];
		<?php display_graphe_percent('percentByType','getByType');?>
		
<?php }

//graphes par regroupements
if(in_array("regroupement",$liste_graphes_retenus)){?>
	//tableau de valeur graphe par regroupement
	var data_regroupement_credit = [
			<?php 
			$data=$reports->getByRegroupements($id_selected,"CREDIT");
			foreach($data as $key => $value){
				echo "['".$key."', ".abs($value)."],";
			}
			?>
	];
	//graphe regroupement credit	
	<?php
		display_graphe_chart("regroupement_credit","data_regroupement_credit");
		display_reset("reset_regroupement_credit","regroupement_credit");
	?>

	var data_regroupement_debit = [
			<?php 
			$data=$reports->getByRegroupements($id_selected,"DEBIT");
			foreach($data as $key => $value){
				echo "['".$key."', ".abs($value)."],";
			}
			?>
	];
	<?php
		display_graphe_chart("regroupement_debit","data_regroupement_debit");
		display_reset("reset_regroupement_debit","regroupement_debit");
	?>
<?php }




//graphes detail  regroupements
if(!empty($liste_details)){
	foreach($liste_regroupements as $id=>$nom){
		if(in_array($id,$liste_details)){
			$reports->setFiltersCatRegroupements($id);?>
			var data_detail_credit_<?php echo $id;?> = [
					<?php 
					$data=$reports->getByCategorie($id_selected,"CREDIT");
					foreach($data as $key => $value){
						echo "['".$key."', ".abs($value)."],";
					}
					?>
			];
			<?php
				display_graphe_chart("detail_credit_".$id,"data_detail_credit_".$id);
				display_reset("zoom_detail_credit_".$id,"detail_credit_".$id);
			?>

			var data_detail_debit_<?php echo $id;?> = [
					<?php 
					$data=$reports->getByCategorie($id_selected,"DEBIT");
					foreach($data as $key => $value){
						echo "['".$key."', ".abs($value)."],";
					}
					?>
			];
			<?php
				display_graphe_chart("detail_debit_".$id,"data_detail_debit_".$id);
				display_reset("zoom_detail_debit_".$id,"detail_debit_".$id);
			?>


<?php 
		}
	}
}
$reports->setFiltersCatRegroupements(0);


//graphes par categorie
if(in_array("categorie",$liste_graphes_retenus)){?>
	//tableau de valeur graphe par categorie
	var data_categorie_credit = [
			<?php 
			$data=$reports->getByCategorie($id_selected,"CREDIT");
			foreach($data as $key => $value){
				echo "['".$key."', ".abs($value)."],";
			}
			?>
	];
	//graphe categorie credit	
	<?php
		display_graphe_chart("categorie_credit","data_categorie_credit");
		display_reset("reset_categorie_credit","categorie_credit");
	?>

	var data_categorie_debit = [
			<?php 
			$data=$reports->getByCategorie($id_selected,"DEBIT");
			foreach($data as $key => $value){
				echo "['".$key."', ".abs($value)."],";
			}
			?>
	];
	//graphe categorie debit	
	<?php
		display_graphe_chart("categorie_debit","data_categorie_debit");
		display_reset("reset_categorie_debit","categorie_debit");
	?>

<?php } 



//graphes sur un an
if(in_array("comparatif_sur_annee",$liste_graphes_retenus)){

	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByRegroupements($reports->getListeIdAnnee($id_selected),"DEBIT");

	$aLibelle=array();
	$aLabelSeries=array();
	foreach($data as $id_releve=>$value){
		$aLabelSeries[]=$reports->getDateIdReleve($id_releve);
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){		
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";

	display_graphe_comparatif("annuel_debit",$aSeries,$aLabelSeries,$max);
	display_reset("zoom_annuel_debit","annuel_debit");



	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByRegroupements($reports->getListeIdAnnee($id_selected),"CREDIT");

	$aLibelle=array();
	$aLabelSeries=array();
	foreach($data as $id_releve=>$value){
		$aLabelSeries[]=$reports->getDateIdReleve($id_releve);
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){		
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";

	display_graphe_comparatif("annuel_credit",$aSeries,$aLabelSeries,$max);
	display_reset("zoom_annuel_credit","annuel_credit");
?>
	

	
	

<?php }if(in_array("comparatif_sur_annee",$liste_graphes_retenus) && in_array("categorie",$liste_graphes_retenus)){
//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"DEBIT");

	$aLibelle=array();
	$aLabelSeries=array();
	foreach($data as $id_releve=>$value){
		$aLabelSeries[]=$reports->getDateIdReleve($id_releve);
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){		
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";

	display_graphe_comparatif("annuel_cat_debit",$aSeries,$aLabelSeries,$max);
	display_reset("zoom_annuel_cat_debit","annuel_cat_debit");



	//tableau de valeur graphe par operations sur un an
	$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"CREDIT");

	$aLibelle=array();
	$aLabelSeries=array();
	foreach($data as $id_releve=>$value){
		$aLabelSeries[]=$reports->getDateIdReleve($id_releve);
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){		
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";

	display_graphe_comparatif("annuel_cat_credit",$aSeries,$aLabelSeries,$max);
	display_reset("zoom_annuel_cat_credit","annuel_cat_credit");?>






<?php }if(isset($id_filtre_annee1) && isset($id_filtre_annee2) && $id_filtre_annee1!=0 && $id_filtre_annee2!=0){
	$id_anne2=max($id_filtre_annee1,$id_filtre_annee2);
	$id_anne1=min($id_filtre_annee1,$id_filtre_annee2);
	$id_filtre_annee1=$id_anne1;
	$id_filtre_annee2=$id_anne2;
	$data=$reports->CompareByCategorieAnnee($id_filtre_annee1,$id_filtre_annee2,"DEBIT");

	$aLibelle=array();
	foreach($data as $id_releve=>$value){
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";
	
	


	?>


	comparatif_annees = $.jqplot('comparatif_annees', [<?php echo implode(",",$aSeries);?>], {
		series:[
		<?php foreach($aLabelSeries as $label){?>
			{
				highlighter:{
					formatString:'<?php echo $label;?> - %s €' ,
					tooltipLocation : 'n'
				}
			},
			<?php }?>

			], 
			seriesDefaults: {
				renderer:$.jqplot.BarRenderer,
				pointLabels: { 
					show: true 
				}
			},
			axesDefaults: {
				tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
				showHighlight: false,
				tickOptions: {
					angle: -30,
					fontSize: '10pt'
				}
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks: ticks
				}
				,yaxis: {
					tickOptions: {
						formatString: "%'d €"
					},
					rendererOptions: {
						forceTickAt0: true
					},
					min:0, 
	                max: <?php echo $max*1.1;?>//, 
	                // numberTicks: 20
	            }
	        }, 
	        cursor:{ 
	        	show: true,
	        	zoom:true, 
	        	showTooltip:false
	        } ,
	        pointLabels: {
	        	show: true
	        },
	        highlighter: {
	        	show: true, 
	        	showLabel: true, 
	        	tooltipAxes: 'y',
	        	sizeAdjust: 7.5 , 
	        }
	    });

$('#reset_comparatif_annees').click(function() { comparatif_annees.resetZoom() });



<?php }
if(isset($id_filtre_perso1) && isset($id_filtre_perso2) && !empty($id_filtre_perso1) && !empty($id_filtre_perso2)){
	$data=$reports->CompareByCategoriePerso($id_filtre_perso1,$id_filtre_perso2,"DEBIT");

	$aLibelle=array();
	foreach($data as $id_releve=>$value){
		foreach($value as $libelle => $montant){		
			if(!in_array($libelle, $aLibelle)){
				$aLibelle[]=$libelle;
			}
		}	
	}

	$aSeries=array();
	$aLabelSeries=array("serie 1","serie 2");
	$max=0;
	foreach($data as $id_releve=>$value){
		$temp=array();
		foreach($aLibelle as $libelle){
			$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
			$temp[]=$montant;
			$max=($max>$montant)?$max:$montant;
		}
		echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
		$aSeries[]="s".$id_releve;
	}

	echo "var ticks = ['".implode("','",$aLibelle)."']; ";
	
	


	?>


	comparatif_perso = $.jqplot('comparatif_perso', [<?php echo implode(",",$aSeries);?>], {
		series:[
		<?php foreach($aLabelSeries as $label){?>
			{
				highlighter:{
					formatString:'<?php echo $label;?> - %s €' ,
					tooltipLocation : 'n'
				}
			},
			<?php }?>

			], 
			seriesDefaults: {
				renderer:$.jqplot.BarRenderer,
				pointLabels: { 
					show: true 
				}
			},
			axesDefaults: {
				tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
				showHighlight: false,
				tickOptions: {
					angle: -30,
					fontSize: '10pt'
				}
			},
			axes: {
				xaxis: {
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks: ticks
				}
				,yaxis: {
					tickOptions: {
						formatString: "%'d €"
					},
					rendererOptions: {
						forceTickAt0: true
					},
					min:0, 
	                max: <?php echo $max*1.1;?>//, 
	                // numberTicks: 20
	            }
	        }, 
	        cursor:{ 
	        	show: true,
	        	zoom:true, 
	        	showTooltip:false
	        } ,
	        pointLabels: {
	        	show: true
	        },
	        highlighter: {
	        	show: true, 
	        	showLabel: true, 
	        	tooltipAxes: 'y',
	        	sizeAdjust: 7.5 , 
	        }
	    });

$('#reset_comparatif_perso').click(function() { comparatif_perso.resetZoom() });
<?php }?>



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
<?php
function display_graphe_chart($nom_div,$nom_data){
	echo "if($('#".$nom_div."').length>0 && ".$nom_data.".length>0){
		var ".$nom_div." = $.jqplot('".$nom_div."', [".$nom_data."], {
			// Turns on animatino for all series in this plot.
			animate: true,
	        // Will animate plot on calls to plot1.replot({resetAxes:true})
	        animateReplot: true,
	        series:[{
	        	renderer:$.jqplot.BarRenderer,
	        	rendererOptions: {
	        		varyBarColor: true
	        	}
	        }],
	        axesDefaults: {
	        	tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
	        	tickOptions: {
	        		angle: -30,
	        		fontSize: '10pt'
	        	}
	        },
	        axes: {
	        	xaxis: {
	        		renderer: $.jqplot.CategoryAxisRenderer
	        	}
	        	,yaxis: {
					tickOptions: {
						formatString: \"%'d €\"
					},
					rendererOptions: {
						forceTickAt0: true
					}
				}
	        }, 
	        cursor:{ 
	        	show: true,
	        	zoom:true, 
	        	showTooltip:false
	        } ,
	        highlighter: {
	        	show: true, 
	        	showLabel: true, 
	        	tooltipAxes: 'y',
	        	sizeAdjust: 5 , 
	        	tooltipLocation : 'n'
	        }
	    });
	}
	else if(".$nom_data.".length<=0){	
		selector_span=$('#".$nom_div."').parents('.span6');
		selector_span.hide();
		/*selector_row=selector_span.parent('.row');
		selector_row.children().attr('class','span10')*/
	}";

}

function display_graphe_percent($nom_div,$nom_data){
	echo "if($('#".$nom_div."').length>0 && ".$nom_data.".length>0){
		//graphe pourcent par categorie	
		var ".$nom_div." = $.jqplot('".$nom_div."', [".$nom_data."], 
			{ 
				seriesDefaults: {
			        // Make this a pie chart.
			        renderer: jQuery.jqplot.PieRenderer, 
			        rendererOptions: {
			          // Put data labels on the pie slices.
			          // By default, labels show the percentage of the slice.
			          showDataLabels: true
			      }
			  }, 
			  legend: { show:true, location: 'e' }
			}
		);
	}
	else if(".$nom_data.".length<=0){	
		selector_span=$('#".$nom_div."').parents('.span6');
		selector_span.hide();
		/*selector_row=selector_span.parent('.row');
		selector_row.children().attr('class','span10')*/
	}";

}


function display_reset($nom_bouton,$nom_graphe){
	echo " if($('#".$nom_bouton."').length>0){
				$('#".$nom_bouton."').click(function() { ".$nom_graphe.".resetZoom() });
			}";
}



function display_data_graphe($nom_data,$data){
	echo "var ".$nom_data." = [
		";		
		foreach($data as $key => $value){
			echo "['".$key."', ".abs($value)."],";
		}
		echo"
		];";
}




function display_graphe_comparatif_annuel($nom_div,$data){
	$aLibelle=array();
	$aLabelSeries=array();
	foreach($data as $id_releve=>$value){
		$aLabelSeries[]=reports::getDateIdReleve($id_releve);
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

	display_graphe_comparatif($nom_div,$aSeries,$aLabelSeries,$max);
}



function display_graphe_comparatif_2annee($nom_div,$data,$aLabelSeries){
	$aLibelle=array();
	foreach($data as $id_releve=>$value){
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

	display_graphe_comparatif($nom_div,$aSeries,$aLabelSeries,$max);
}






function display_graphe_comparatif($nom_div,$aSeries,$aLabelSeries,$max){
	echo "if($('#".$nom_div."').length>0 && ".$aLabelSeries.".length>0){
		var ".$nom_div." = $.jqplot('".$nom_div."', [".implode(",",$aSeries)."], {
			series:[";
				foreach($aLabelSeries as $label){
					echo "{
						highlighter:{
							formatString:'".$label." - %s €' ,
							tooltipLocation : 'n'
						}
					},";
				}
			echo"
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
				,
				yaxis: {
					tickOptions: {
						formatString: \"%'d €\"
					},
					rendererOptions: {
						forceTickAt0: true
					},
					min:0, 
		            max: ".($max*1.1)."//, 
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
	}
	";
}




?>
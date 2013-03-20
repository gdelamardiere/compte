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
	var getByType = [
	<?php 
	$data=$reports->getByType($id_selected);
	foreach($data as $key => $value){
		if($key=="")$key="non défini";
		echo "['".$key."', ".abs($value)."],";
	}
	?>
	];
	var pie1 = jQuery.jqplot ('percentByType', [getByType], 
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

	var getByCategorie = [
	<?php 
	$data=$reports->getByCategorie($id_selected,"DEBIT");
	foreach($data as $key => $value){
		if($key=="")$key="non défini";
		echo "['".$key."', ".abs($value)."],";
	}
	?>
	];
	var pie1 = jQuery.jqplot ('percentByCategorie', [getByCategorie], 
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


	var line1 = $.jqplot('prixByCategorie', [getByCategorie], {
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
		}, 
		cursor:{ 
			show: true,
			zoom:true, 
			showTooltip:false
		} ,
		highlighter: {
            show: true, 
            showLabel: true, 
            tooltipAxes: 'both',
            sizeAdjust: 5 , 
            tooltipLocation : 'n'
        }
	});
	$('#ByCategorie').click(function() { line1.resetZoom() });


	var getByOperations = [
	<?php 
	$data=$reports->getByOperations($id_selected,"DEBIT");
	foreach($data as $key => $value){
		if($key=="")$key="non défini";
		echo "['".$key."', ".abs($value)."],";
	}
	?>
	];
	var pie1 = jQuery.jqplot ('percentByOperations', [getByOperations], 
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


	var line1 = $.jqplot('prixByOperations', [getByOperations], {
		series:[{
			pointLabels: {
                show: true
            },
			renderer:$.jqplot.BarRenderer,
			rendererOptions: {
                varyBarColor: true
            }
		}],
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
				renderer: $.jqplot.CategoryAxisRenderer
			}
			,yaxis: {
                tickOptions: {
                    formatString: "%'d €"
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
		} 
	});
	$('#ByOperations').click(function() { line1.resetZoom() });

		
<?php

$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"DEBIT");

$aLibelle=array();
foreach($data as $id_releve=>$value){
	//var_dump($value);
	foreach($value as $libelle => $montant){		
		if(!in_array($libelle, $aLibelle)){
			$aLibelle[]=$libelle;
		}
	}	
}

$aSeries=array();
$aLabelSeries=array("s1","s2");
$max=0;
foreach($data as $id_releve=>$value){
	$temp=array();
	foreach($aLibelle as &$libelle){		
		if($libelle=="Non défini"){
			$libelle="";
		}
		$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
		$temp[]=$montant;
		$max=($max>$montant)?$max:$montant;
		if($libelle=="" || $libelle == null){
			$libelle="Non défini";
		}
	}
	echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
	$aSeries[]="s".$id_releve;
}

echo "var ticks = ['".implode("','",$aLibelle)."']; ";
	
	


?>
		
         
        plot2 = $.jqplot('plot2', [<?php echo implode(",",$aSeries);?>], {
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
  $('#annuel').click(function() { plot2.resetZoom() });

<?php if($id_filtre_annee1!=0 && $id_filtre_annee2!=0){

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
	foreach($aLibelle as &$libelle){
		if($libelle=="Non défini"){
			$libelle="";
		}
		$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
		$temp[]=$montant;
		$max=($max>$montant)?$max:$montant;
		if($libelle=="" || $libelle == null){
			$libelle="Non défini";
		}
	}
	echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
	$aSeries[]="s".$id_releve;
}

echo "var ticks = ['".implode("','",$aLibelle)."']; ";
	
	


?>
		
         
        plot2 = $.jqplot('comparatif_annees', [<?php echo implode(",",$aSeries);?>], {
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

  $('#reset_comparatif_annees').click(function() { plot2.resetZoom() });



<?php }if(!empty($id_filtre_perso1) && !empty($id_filtre_perso2)){
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
$aLabelSeries=array($id_filtre_annee1,$id_filtre_annee2);
$max=0;
foreach($data as $id_releve=>$value){
	$temp=array();
	foreach($aLibelle as &$libelle){
		
		if($libelle=="Non défini"){
			$libelle="";
		}
		$montant=(!empty($value[$libelle]))?abs($value[$libelle]):0;
		$temp[]=$montant;
		$max=($max>$montant)?$max:$montant;
		if($libelle=="" || $libelle == null){
			$libelle="Non défini";
		}
	}
	echo "var s".$id_releve." = ['".implode("','",$temp)."']; ";
	$aSeries[]="s".$id_releve;
}

echo "var ticks = ['".implode("','",$aLibelle)."']; ";
	
	


?>
		
         
        plot2 = $.jqplot('comparatif_perso', [<?php echo implode(",",$aSeries);?>], {
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

 $('#reset_comparatif_perso').click(function() { plot2.resetZoom() });
<?php }?>


      

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



});


// Create a jquery plugin that prints the given element.
jQuery.fn.print = function(){
	// NOTE: We are trimming the jQuery collection down to the
	// first element in the collection.
	if (this.size() > 1){
		this.eq( 0 ).print();
		return;
	} else if (!this.size()){
		return;
	}
var imgData = $('#percentByCategorie').jqplotToImageStr(); 
   // var chart = $(this).closest('div.quintile-outer-container').find('div.jqplot-target');
    // var imgelem = chart.jqplotToImageElem();
 //   var imageElemStr = chart.jqplotToImageElemStr();
    // var statsrows = $(this).closest('div.quintile-outer-container').find('table.stats-table tr');
    // var statsTable = $('<div></div>').append($(this).closest('div.quintile-outer-container').find('table.stats-table').clone());alert('3');
    // var rowstyles = window.getComputedStyle(statsrows.get(0), '');

	// ASSERT: At this point, we know that the current jQuery
	// collection (as defined by THIS), contains only one
	// printable element.
 
	// Create a random name for the print frame.
	var strFrameName = ("printer-" + (new Date()).getTime());
 
	// Create an iFrame with the new name.
	var jFrame = $( "<iframe name='" + strFrameName + "'>" );
 
	// Hide the frame (sort of) and attach to the body.
	jFrame
		.css( "width", "1px" )
		.css( "height", "1px" )
		.css( "position", "absolute" )
		.css( "left", "-9999px" )
		.appendTo( $( "body:first" ) )
	;
 
	// Get a FRAMES reference to the new frame.
	var objFrame = window.frames[ strFrameName ];
 
	// Get a reference to the DOM in the new frame.
	var objDoc = objFrame.document;
 
	// Grab all the style tags and copy to the new
	// document so that we capture look and feel of
	// the current document.
 
	// Create a temp document DIV to hold the style tags.
	// This is the only way I could find to get the style
	// tags into IE.
	var jStyleDiv = $( "<div>" ).append(
		$( "style" ).clone()
		);
 
	// Write the HTML for the document. In this, we will
	// write out the HTML of the current element.
	objDoc.open();
	objDoc.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
	objDoc.write( "<html>" );
	objDoc.write( "<body>" );
	objDoc.write( "<head>" );
	objDoc.write( "<title>" );
	objDoc.write( document.title );
	objDoc.write( "</title>" );
	objDoc.write( jStyleDiv.html() );
	objDoc.write( "</head>" );

	// Typically, would just write out the html.	
	// objDoc.write( this.html() );

	// We need to do specific manipulation for kcp quintiles.
	objDoc.write( '<div class="quintile-outer-container ui-widget ui-corner-all"> \
    <div class="quintile-content ui-widget-content ui-corner-bottom"> \
		<table class="quintile-display"> \
            <tr> \
                <td class="chart-cell">');

    objDoc.write(imgData);
    
    objDoc.write('</td> <td class="stats-cell">');

    // objDoc.write(statsTable.html());

    objDoc.write('</td></tr></table></div></div>');

	objDoc.write( "</body>" );
	objDoc.write( "</html>" );
	objDoc.close();


// objDoc.write( this.html() );
// objDoc.write( "</body>" );
// objDoc.write( "</html>" );
// objDoc.close();
 
 	// 
	// When the iframe is completely loaded, print it.
	// This seemed worked in IE 9, but caused problems in FF.
	//
	// $(objFrame).load(function() {
	// 	objFrame.focus();
	// 	objFrame.print();
	// });

	//
	// This works in all supported browsers.
	// Note, might have to adjust time.
	//
	setTimeout(
		function() {
			objFrame.focus();
			objFrame.print();
		}, 750);
 

	// Have the frame remove itself in about a minute so that
	// we don't build up too many of these frames.
	setTimeout(
		function(){
			jFrame.empty();
			jFrame.remove();
		},
		(60 * 1000)
		);
}
</script>
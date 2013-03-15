<?php
require_once('conf.php'); 
require_once(ROOT.'classes/reports.class.php');
header('Content-Type: text/html; charset=utf-8');

$reports=new reports();
$liste_cat=$reports->listeCategories();
$liste_releve=$reports->listeReleve();
$liste_id_cat=array();
foreach($liste_cat as $id_cat=>$libelle){
	$liste_id_cat[]=$id_cat;
}
$select_releve="";
$id_selected=(isset($_POST['id_releve']))?$_POST['id_releve']:$liste_releve[0]['id_releve'];
$liste_coche_categorie=(isset($_POST['coche_categorie']))?$_POST['coche_categorie']:$liste_id_cat;
var_dump($liste_id_cat);
foreach($liste_releve as $value){		
	$select_releve.="<option value='".$value['id_releve']."' ".(($value['id_releve']==$id_selected)?'selected="selected"':'').">".$value['date']."</option>";
}
require_once ('header.html');
	
?>
<div class="row">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Filtres</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">
				<form id="import_fichier" action="reports.php" method="POST">
					<div>
						<h3 style="display:inline;">Relevé à prendre en compte :</h3>
						<SELECT name='id_releve' ><?php echo $select_releve;?></SELECT>
					</div>
					<div>
						<h3>Liste des catégories à afficher</h3>
						<p><?php $i=1; foreach($liste_cat as $id_cat=>$libelle){
							$libelle=($libelle=="")?"non défini":$libelle;
							?>
							<span class="coche_categorie">
								<input type="checkbox" name="coche_categorie[]" id="coche_cat_<?php echo $id_cat;?>" checked="checked"/>
								<label for="coche_cat_<?php echo $id_cat;?>"><?php echo $libelle;?></label>
							</span>
						<?php 
						if($i==12){$i=0;echo"<br/>";}
							$i++;
						}
						?>
						</p>
					</div>
					<input type="submit" value="Envoyer">  
				</form>



			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<div class="row">

	<div class="span6" style="margin-left: 25%;">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Débit / Crédit</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="percentByType" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->

<div class="row">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage en catégorie</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="percentByCategorie" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Prix par catégorie</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="prixByCategorie" style="height:400px;width:500px; "></div>
				<div style="margin-top: 10px; float: right;"><input type="button" id="ByCategorie" value="reset du zoom"/></div>	

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->

	
</div> <!-- /row -->

<div class="row">

	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage en opérations</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="percentByOperations" style="height:400px;width:500px; "></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Prix par opérations</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="prixByOperations" style="height:400px;width:500px; "></div>
				<div style="margin-top: 10px; float: right;"><input type="button" id="ByOperations" value="reset du zoom"/></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->

	</div> <!-- /span6 -->
	
		
</div> <!-- /row -->

<div class="row">

	<div class="span12" >

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Comparatif des derniers mois</h3>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="plot2" style="height:700px;width:1000px; "></div>
				<div style="margin-top: 10px; float: right;"><input type="button" id="annuel" value="reset du zoom"/></div>
			</div> <!-- /widget-content -->

		</div> <!-- /widget -->	
			</div> <!-- /span6 -->
</div> <!-- /row -->




<input type="button" value="test" onclick="save()">





<?php
require_once ('footer.html');
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
	$data=$reports->getByCategorie($id_selected,"AND type='DEBIT'");
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
		series:[{renderer:$.jqplot.BarRenderer}],
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
            tooltipAxes: 'y',
            sizeAdjust: 7.5 , tooltipLocation : 'ne'
        }
	});
	$('#ByCategorie').click(function() { line1.resetZoom() });


	var getByOperations = [
	<?php 
	$data=$reports->getByOperations($id_selected,"AND type='DEBIT'");
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
			renderer:$.jqplot.BarRenderer
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

$data=$reports->CompareByCategorie($reports->getListeIdAnnee($id_selected),"AND type='DEBIT'");

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
$max=0;
foreach($data as $id_releve=>$value){
	$temp=array();
	foreach($aLibelle as &$libelle){
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
            sizeAdjust: 7.5 , tooltipLocation : 'ne'
        }
        });
        $('#annuel').click(function() { plot2.resetZoom() });





	   
    // var line1 = $.jqplot("prixByOperations", [getByOperations], {
    //     // Turns on animatino for all series in this plot.
    //     animate: true,
    //     // Will animate plot on calls to plot1.replot({resetAxes:true})
    //     animateReplot: true,
    //     cursor: {
    //         show: true,
    //         zoom: true,
    //         looseZoom: true,
    //         showTooltip: false
    //     },
    //     series:[
    //         {
    //             pointLabels: {
    //                 show: true
    //             },
    //             renderer: $.jqplot.BarRenderer,
    //             showHighlight: false,
    //             yaxis: 'y2axis',
    //             rendererOptions: {
    //                 // Speed up the animation a little bit.
    //                 // This is a number of milliseconds.  
    //                 // Default for bar series is 3000.  
    //                 animation: {
    //                     speed: 2500
    //                 },
    //                 barWidth: 15,
    //                 barPadding: -15,
    //                 barMargin: 0,
    //                 highlightMouseOver: false
    //             }
    //         }
    //     ],
    //     axesDefaults: {
    //         pad: 0
    //     },
    //     axes: {
    //         // These options will set up the x axis like a category axis.
    //         xaxis: {
    //             tickInterval: 1,
    //             drawMajorGridlines: false,
    //             drawMinorGridlines: true,
    //             drawMajorTickMarks: false,
    //             rendererOptions: {
    //             tickInset: 0.5,
    //             minorTicks: 1
    //         }
    //         },
    //         yaxis: {
    //             tickOptions: {
    //                 formatString: "$%'d"
    //             },
    //             rendererOptions: {
    //                 forceTickAt0: true
    //             }
    //         },
    //         y2axis: {
    //             tickOptions: {
    //                 formatString: "$%'d"
    //             },
    //             rendererOptions: {
    //                 // align the ticks on the y2 axis with the y axis.
    //                 alignTicks: true,
    //                 forceTickAt0: true
    //             }
    //         }
    //     },
    //     highlighter: {
    //         show: true, 
    //         showLabel: true, 
    //         tooltipAxes: 'y',
    //         sizeAdjust: 7.5 , tooltipLocation : 'ne'
    //     }
    // });
   






















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
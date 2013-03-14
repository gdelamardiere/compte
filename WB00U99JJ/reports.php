<?php
require_once('conf.php'); 
require_once(ROOT.'classes/reports.class.php');
header('Content-Type: text/html; charset=utf-8');

$reports=new reports();

require_once ('header.html');
?>

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
<div class="quintile-outer-container ui-widget ui-corner-all">
        <div class="quintile-toolbar ui-widget-header  ui-corner-top">
            <span class="quintile-title">Income Level:  First Quintile</span>
            <div class="quintile-toggle ui-icon ui-icon-arrowthickstop-1-n"></div>
            <div class="ui-icon ui-icon-newwin"></div>
        
	<div class="span6">

		<div class="widget">

			<div class="widget-header">
				<i class="icon-star"></i>
				<h3>Découpage en catégorie</h3>
				<span class="print" onclick='$("#percentByCategorie").print();'>test</span>
			</div> <!-- /widget-header -->

			<div class="widget-content">

				<div id="percentByCategorie" style="height:400px;width:500px; " class="jqplot-target"></div>

			</div> <!-- /widget-content -->

		</div> <!-- /widget -->




	</div> <!-- /span6 -->


</div>
                </div>
    </div> 

    

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

<div id="plot2" style="height:400px;width:500px; "></div>


 





<?php
require_once ('footer.html');
?>


<script type="text/javascript">
$(document).ready(function(){
	var getByType = [
	<?php 
	$data=$reports->getByType('2,3');
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
	$data=$reports->getByCategorie('2,3',"AND type='DEBIT'");
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
	$data=$reports->getByOperations('2,3',"AND type='DEBIT'");
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
			},yaxis: {
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

		

		var s1 = [2, 6, 7, 10];
        var s2 = [7, 5, 3, 2];
        var ticks = ['a', 'b', 'c', 'd'];
         
        plot2 = $.jqplot('plot2', [s1, s2], {
            seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });

// if browser supports canvas, show additional toolbar icons
        if (!$.jqplot.use_excanvas) {
            $('div.quintile-toolbar').append('<div class="ui-icon ui-icon-image"></div><div class="ui-icon ui-icon-print"></div>');
        }
  // Open and close the plot container.
        $('.quintile-toggle').each(function() {
            $(this).click(function(e) {
                if ($(this).hasClass('ui-icon-arrowthickstop-1-n')) {
                    $(this).parent().next('.quintile-content').effect('blind', {mode:'hide'}, 600);
                    // $('.quintile-content').jqplotEffect('blind', {mode: 'hide'}, 600);
                    $(this).removeClass('ui-icon-arrowthickstop-1-n');
                    $(this).addClass('ui-icon-arrowthickstop-1-s');
                }
                else if ($(this).hasClass('ui-icon-arrowthickstop-1-s')) {
                    $(this).parent().next('.quintile-content').effect('blind', {mode:'show'}, 600, function() {
                        $(this).find('div.jqplot-chart').data('jqplot').replot();
                    });
                    // $('.quintile-content').jqplotEffect('blind', {mode: 'show'}, 150);
                    $(this).removeClass('ui-icon-arrowthickstop-1-s');
                    $(this).addClass('ui-icon-arrowthickstop-1-n');
                }
            });
        });



        $('.ui-icon-print').click(function(){
            $(this).parent().next().print();
        });


        $('.ui-icon-image').each(function() {
            $(this).bind('click', function(evt) {
                var chart = $(this).closest('div.quintile-outer-container').find('div.jqplot-target');
                var imgelem = chart.jqplotToImageElem();
                var div = $('div.overlay-chart-container-content');
                div.empty();
                div.append(imgelem);
                $('div.overlay-shadow').fadeIn(600);
                div.parent().fadeIn(1000);
                div = null;
            });
        });


        $('.ui-icon-newwin').each(function(index) {
            $(this).bind('click', function(evt) {
                var url = 'kcp_pyramid_by_age.html?qidx='+index;
                window.open(url);
            });
        });

        $('div.overlay-chart-container-header div.ui-icon-closethick').click(function(){
            var div = $('div.overlay-chart-container-content');
            div.parent().fadeOut(600);
            $('div.overlay-shadow').fadeOut(1000);
        });





	   
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






</script>
	    </div> <!-- /container -->
	    
	</div> <!-- /main-inner -->
</div><!-- /main -->
<div class="footer">
	
	<div class="footer-inner">
		
		<div class="container">
			
			<div class="row">
				
    			<div class="span12">
    				&copy; 2012 <a href="http://bootstrapadmin.com/">Base Admin</a>.
    			</div> <!-- /span12 -->
    			
    		</div> <!-- /row -->
    		
		</div> <!-- /container -->
		
	</div> <!-- /footer-inner -->
	
</div> <!-- /footer -->
    


<script language="javascript" type="text/javascript" src="js/api/jquery.min.js"></script>
<script src="js/releve.js"></script>
<?php if($page=="reports"){?>
<script language="javascript" type="text/javascript" src="js/api/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/api/jquery.jqplot.css" />
<script type="text/javascript" src="js/api/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="js/api/plugins/jqplot.highlighter.min.js"></script>

<?php 
require_once("js/reports_js.php");
}

?>

  </body>


</html>

<?php
header('Content-Type: text/html; charset=utf-8');
require_once('conf.php'); 
require_once(ROOT.'classes/database.php');
  require_once ('header.html');
  ?>
    
	
	
	
	      <div class="row">
	      	
	      	<div class="span6">				
						
											
				<div class="widget">
						
					<div class="widget-header">
						<i class="icon-signal"></i>
						<h3>Chart</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">					
						<div id="area-chart" class="chart-holder"></div>					
					</div> <!-- /widget-content -->
				
				</div> <!-- /widget -->
	      		
		    </div> <!-- /span6 -->
	      	
	      	
	      	<div class="span6">	
	      		
	      		
	      		<div class="widget">
						
					<div class="widget-header">
						<i class="icon-bookmark"></i>
						<h3>Import nouveau fichier</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						<form id="import_fichier" action="import.php" enctype='multipart/form-data' method="POST">
							<label for="fichier">test</label>
							<input type="file" name="fichier_import">    
          					<input type="submit" value="Envoyer">  
						</form>
					
					</div> <!-- /widget-content -->   		
				
				
				</div> <!-- /widget -->
									
		      </div> <!-- /span6 -->
	      	
	      </div> <!-- /row -->
	

    

    


<?php
  require_once ('footer.html');
  ?>
    
    

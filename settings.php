<?php 
$liste_cat=$reports->listeCategories();
$liste_keywords=$reports->listeKeywords();
$liste_regex=$reports->listeRegex();
$liste_operations=$reports->listeOperations();
$liste_Excel=$reports->listeExcel();
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Modal header</h3>
	</div>
	<div class="modal-body">
		 <ul class="nav nav-tabs">
		    <li><a href="#settings_categories" data-toggle="tab">Catégories</a></li>
		    <li><a href="#settings_operations" data-toggle="tab">Opérations</a></li>
		    <li><a href="#settings_keywords" data-toggle="tab">Keywords</a></li>
		    <li><a href="#settings_regex" data-toggle="tab">Regex</a></li>
		    <li><a href="#settings_import" data-toggle="tab">import</a></li>
    	</ul>
    	<div id="myTabContent" class="tab-content">
			<div id="settings_categories" class="tab-pane fade active in">
				<?php require_once(ROOT.'settings/categories.php');?>
			</div>			
			<div id="settings_operations" class="tab-pane fade">
				<?php require_once(ROOT.'settings/operations.php');?>
			</div>
			<div id="settings_keywords" class="tab-pane fade">
				<?php require_once(ROOT.'settings/keywords.php');?>
			</div>
			<div id="settings_regex" class="tab-pane fade">
				<?php require_once(ROOT.'settings/regex.php');?>
			</div>
			<div id="settings_import" class="tab-pane fade">
				<?php require_once(ROOT.'settings/import.php');?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="hidden" name="modif_settings" id="modif_settings" value='0'/>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary">Save changes</button>
	</div>
</div>
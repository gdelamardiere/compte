<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:1000px;left:36%;top:40%">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Configuration de l'application</h3>
	</div>
	<div class="modal-body" style="max-height:600px;">
		 <ul class="nav nav-tabs">
		    <li  class="active"><a href="#settings_categories" id="tab_settings_categories" data-toggle="tab">Catégories</a></li>
		    <li><a href="#settings_operations" id="tab_settings_operations" data-toggle="tab">Opérations</a></li>
		    <li><a href="#settings_keywords" id="tab_settings_keywords" data-toggle="tab">Keywords</a></li>
		    <li><a href="#settings_regex" id="tab_settings_regex" data-toggle="tab">Regex</a></li>
		    <li><a href="#settings_import" id="tab_settings_import" data-toggle="tab">import</a></li>
		    <li><a href="#settings_filtres" id="tab_settings_filtres" data-toggle="tab">filtres</a></li>
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
			<div id="settings_filtres" class="tab-pane fade">
				<?php require_once(ROOT.'settings/filtres.php');?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="hidden" name="modif_settings" id="modif_settings" value='0'/>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>
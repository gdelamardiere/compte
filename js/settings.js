$(document).ready(function(){

	/*categorie*/
	$('.settings_categories_libelle').change(function() { update_settings("categories","libelle",$(this).attr('id').replace('settings_categories_libelle_',''),$(this).val()));

	/*operations*/
	$('.settings_operations_libelle').change(function() { update_settings("operations","libelle",$(this).attr('id').replace('settings_operations_libelle_',''),$(this).val()));

	/*keywords*/
	$('.settings_keywords_value').change(function() { update_settings("keywords","value",$(this).attr('id').replace('settings_keywords_value_',''),$(this).val()));
	$('.settings_keywords_cat').change(function() { update_settings("keywords","id_cat",$(this).attr('id').replace('settings_keywords_cat_',''),$(this).val()));

	/*regex*/
	$('.settings_regex_regex').change(function() { update_settings("regex","regex",$(this).attr('id').replace('settings_regex_regex_',''),$(this).val()));
	$('.settings_regex_replace').change(function() { update_settings("regex","replace",$(this).attr('id').replace('settings_regex_replace_',''),$(this).val()));
	$('.settings_regex_ordre').change(function() { update_settings("regex","ordre",$(this).attr('id').replace('settings_regex_ordre_',''),$(this).val()));
	$('.settings_regex_operations').change(function() { update_settings("regex","id_operations",$(this).attr('id').replace('settings_regex_operations_',''),$(this).val()));


	/*excel*/
	$('.settings_excel_position').change(function() { update_settings("excel","position",$(this).attr('id').replace('settings_excel_position_',''),$(this).val()));

});

function update_settings(table,champ,id,valeur){
	alert(valeur);
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_table': table,'update_champ': champ, 'id': id, 'valeur': valeur }
		}).done(function( msg ) {
			alert(msg);
	});
}
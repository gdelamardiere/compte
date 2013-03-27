$(document).ready(function(){

	$('#myModal').on('hidden', function () {
		if($('#modif_settings').val()=='1'){
			actualiser();
		}   		
    })

    $('a[data-toggle="tab"]').click(function (e) {
    	change_onglet($(this).attr('id').replace('tab_settings_','') );
    })

});

function update_settings(table,champ,id,valeur){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_table': table,'update_champ': champ, 'id': id, 'valeur': valeur }
		}).done(function( msg ) {
			$('#modif_settings').val('1');
	});
}


function change_onglet(onglet){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'onglet': onglet}
		}).done(function( msg ) {
			$('#settings_'+onglet).html(msg);
	});
}

function ajout_cat(){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'new_cat': $('#new_cat').val() }
		}).done(function( msg ) {
			$('#modif_settings').val('1');
			change_onglet('categories');
	});
}

function ajout_operations(){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'new_operations': $('#new_operations').val() }
		}).done(function( msg ) {
			$('#modif_settings').val('1');
			change_onglet('operations');
	});
}


function ajout_keywords(){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'new_keywords': $('#new_keywords').val() ,'keywords_cat': $('#keywords_cat').val() }
		}).done(function( msg ) {
			$('#modif_settings').val('1');
			change_onglet('keywords');
	});
}

function ajout_regex(){	
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'new_regex': $('#new_regex').val() ,'regex_operations': $('#regex_operations').val(),'regex_type': $('#regex_type').val() }
		}).done(function( msg ) {
			$('#modif_settings').val('1');
			change_onglet('regex');
	});
}

function supprimer_settings_keywords(id){
	if (confirm("Voulez-vous Vraiment effacet ce mot-clé ?")) {
		$.ajax({
			type: "POST",
			url: "lib/releve.ajax.php",
			data: { 'supprimer_keywords': "true", 'id_keywords': id }
			}).done(function( msg ) {
				$('#modif_settings').val('1');
				change_onglet('keywords');
		});
	}
}




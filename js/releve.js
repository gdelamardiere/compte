function edition(){
	$('.lecture').hide();
	$('.edition').show();
}

function lecture(){
	$('.lecture').show();
	$('.edition').hide();
}

function update_categorie(id,sel){
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_categorie': "true", 'id': id, 'id_categorie': sel.value }
		}).done(function( msg ) {
		//alert( "Data Saved: " + msg );
	});
}

function update_operations(id,sel){
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_operations': "true", 'id': id, 'id_operations': sel.value }
		}).done(function( msg ) {
		//alert( "Data Saved: " + msg );
	});
}



function update_pointage(id,sel){
	switch(sel.value){
		case '1':
			src="img/valider.png";
			alt='valide';
			display=true;
			break;
		case '-1':
			src="img/erreur.gif";
			alt='erreur';			
			display=true;
			break;
		default :
			src="";		
			alt='aucun';	
			display=false;
			break;

	}
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_pointage': "true", 'id': id, 'pointe': sel.value }
		}).done(function( msg ) {
			$('#pointage_'+id).attr("src",src);
			$('#pointage_'+id).attr("alt",alt);
			if(display){
				$('#pointage_'+id).show();
			}
			else{
				$('#pointage_'+id).hide();
			}
	});
}


function supprimer_releve(id_releve){
	if (confirm("Voulez-vous Vraiment effacet cet import ?\n ATTENTION cette action est irréversible")) {
		$.ajax({
			type: "POST",
			url: "lib/releve.ajax.php",
			data: { 'supprimer_releve': "true", 'id_releve': id_releve }
			}).done(function( msg ) {
				actualiser();
		});
	}
}

function supprimer_ligne_releve(id_ligne_releve){
	if (confirm("Voulez-vous Vraiment effacet cet ligne ?\n ATTENTION cette action est irréversible")) {
		$.ajax({
			type: "POST",
			url: "lib/releve.ajax.php",
			data: { 'supprimer_ligne_releve': "true", 'id_ligne_releve': id_ligne_releve}
			}).done(function( msg ) {
				actualiser();
		});
	}
}


function actualiser(){
	location.reload();
}
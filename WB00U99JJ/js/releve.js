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

function actualiser(){
	location.reload();
}
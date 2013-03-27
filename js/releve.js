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
			$('#cat_'+id).html($("option:selected", sel).text());
			$('#detail_releve').tableFilterRefresh();
	});
}

function update_operations(id,sel){
	$.ajax({
		type: "POST",
		url: "lib/releve.ajax.php",
		data: { 'update_operations': "true", 'id': id, 'id_operations': sel.value }
		}).done(function( msg ) {	
			$('#operations_'+id).html($("option:selected", sel).text());	
			$('#detail_releve').tableFilterRefresh();
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
			$('#detail_releve').tableFilterRefresh();
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


if($('.tri_good').length){
	$('.tri_good').click(function() {
		$('.tri_good').find('i').hide();
		icone=$(this).find('i');
		class_icone=icone.attr("class");
		if(class_icone=="icon-arrow-up"){
			icone.attr("class","icon-arrow-down");			
			tri($(this).attr('id'),"ASC","good");
		}
		else{
			icone.attr("class","icon-arrow-up");
			tri($(this).attr('id'),"DESC","good");
		}		
		icone.show();
	});
}
if($('.tri_bad').length){
	$('.tri_bad').click(function() {
		$('.tri_bad').find('i').hide();
		icone=$(this).find('i');
		class_icone=icone.attr("class");
		if(class_icone=="icon-arrow-up"){
			icone.attr("class","icon-arrow-down");			
			tri($(this).attr('id'),"ASC","bad");
		}
		else{
			icone.attr("class","icon-arrow-up");
			tri($(this).attr('id'),"DESC","bad");
		}		
		icone.show();
	});
}

function tri(val,sens,tab){	
	fonct='get_detail_releve_'+tab;
	$.ajax({
			type: "POST",
			url: "lib/releve.ajax.php",
			data: { fonction : fonct, 'tri' : val, 'sens': sens, 'id_releve': $('#id_releve').val()}
			}).done(function( msg ) {
				$('#detail_releve_'+tab).html(msg);				
				$('#detail_releve').tableFilterRefresh();
		});
}

$(document).ready(function() {	
	if($('#detail_releve').length){
		var options = {
			clearFiltersControls: [$('#clearFilter')],   
			        
		};
		$('#detail_releve').tableFilter(options);
	}
});
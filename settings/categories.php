<div class="form_settings">
	<div class="ajout_settings">
		Nouvelle catégorie:  <input type='text' name="new_cat" id="new_cat"/>
		<input type="submit" name="button_cat" value="Ajouter" onclick='ajout_cat()' >
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_cat as $id=>$libelle){?>
			<tr><td><input type="text" onchange='update_settings("categories","libelle",<?php echo $id;?>,$(this).val())' value="<?php echo $libelle;?>"/></td></tr>
			<?php }?>
		</table>
	</div>
</div>
<div class="form_settings">
	<div class="ajout_settings">
		Nouvelle catégorie:  <input type='text' name="new_cat"/>
		<input type="submit" name="button_cat" value="Ajouter">
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_cat as $id=>$libelle){?>
			<tr><td><input type="text" name="settings_categories_libelle" id="settings_categories_libelle_<?php echo $id;?>" value="<?php echo $libelle;?>"/></td></tr>
			<?php }?>
		</table>
	</div>
</div>
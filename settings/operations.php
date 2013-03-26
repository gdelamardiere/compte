<div class="form_settings">
	<div class="ajout_settings">
		Nouvelle opération:  <input type='text' name="new_operations"/>
		<input type="submit" name="button_operations" value="Ajouter">
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_operations as $id=>$libelle){?>
			<tr><td><input type="text" name="settings_operations_libelle" id="settings_operations_libelle_<?php echo $id;?>" value="<?php echo $libelle;?>"/></td></tr>
			<?php }?>
		</table>
	</div>
</div>
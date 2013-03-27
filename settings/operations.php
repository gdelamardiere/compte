<div class="form_settings">
	<div class="ajout_settings">
		Nouvelle opération:  <input type='text' name="new_operations" id="new_operations"/>
		<input type="submit" name="button_operations" value="Ajouter" onclick='ajout_operations()' >
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_operations as $id=>$libelle){?>
			<tr><td><input type="text" onchange='update_settings("operations","libelle",<?php echo $id;?>,$(this).val())' value="<?php echo $libelle;?>"/></td></tr>
			<?php }?>
		</table>
	</div>
</div>
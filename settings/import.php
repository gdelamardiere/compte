<div class="form_settings">
	<div class="info_settings">
		Modification des colonnes du fichier d'import
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td><td>Colonne du fichier</td></tr>
			<?php foreach($liste_Excel as $id=>$tab){?>
			<tr><td><?php echo $tab['libelle'];?></td><td><input type="text" onchange='update_settings("excel","position",<?php echo $id;?>,$(this).val())' value="<?php echo $tab['position'];?>"/></td></tr>
			<?php }?>
		</table>
	</div>
</div>
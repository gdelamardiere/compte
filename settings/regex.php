<?php
$select_Operations=$reports->getSelectOperations();
?>

<div class="settings_warning">
	ATTTENTION, La modification des regex peut entraîner un disfonctionnement du site.<br>
	Assurez-vous de renseigner une regex valide<br/>
	Ces modifications sont pour les utilisateurs avancés
</div>

<div class="form_settings">
	<div class="ajout_settings">
		<input type='text' name="new_regex" id="new_regex"/> 
		<SELECT name='regex_operations' id='regex_operations' ><?php echo $select_Operations;?></SELECT>
		<SELECT name='regex_type' id='regex_type' >
			<option value='DEBIT' selected='selected'>DEBIT</option>
			<option value='CREDIT'>CREDIT</option>
		</SELECT>
			<input type="submit" name="button_regex" value="Ajouter" onclick='ajout_regex()' >
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>regex</td><td>replace</td><td>ordre</td><td>operations</td><td>type</td></tr>
			<?php foreach($liste_regex as $id=>$tab){?>
			<tr>
				<td>
					<input type="text" onchange='update_settings("regex","regex",<?php echo $id;?>,$(this).val())' value="<?php echo $tab['regex'];?>"/>
				</td>
				<td>
					<input type="text" onchange='update_settings("regex","replace",<?php echo $id;?>,$(this).val())' value="<?php echo $tab['replace'];?>"/>
				</td>
				<td>
					<input type="text" onchange='update_settings("regex","ordre",<?php echo $id;?>,$(this).val())' value="<?php echo $tab['ordre'];?>"/>
				</td>				
				<td>
					<SELECT onchange='update_settings("regex","id_operations",<?php echo $id;?>,$(this).val())' >
						<?php echo $reports->getSelectOperations($tab['id_operations']);?>
					</SELECT>
				</td>
				<td>
					<SELECT onchange='update_settings("regex","type",<?php echo $id;?>,$(this).val())' >
						<option value='DEBIT' <?php echo ($tab['id_operations']=="DEBIT")?"selected='selected'":"";?> >DEBIT</option>
						<option value='CREDIT' <?php echo ($tab['id_operations']=="CREDIT")?"selected='selected'":"";?> >CREDIT</option>
					</SELECT>
				</td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>
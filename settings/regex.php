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
		<input type='text' name="new_keywords"/> <SELECT name='keywords_cat' ><?php echo $select_Operations;?></SELECT>
			<input type="submit" name="button_keywords" value="Ajouter">
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>regex</td><td>replace</td><td>ordre</td><td>operations</td><td>type</td></tr>
			<?php foreach($liste_regex as $id=>$tab){?>
			<tr>
				<td>
					<input type="text" name="settings_regex_regex" id="settings_regex_regex_<?php echo $id;?>" value="<?php echo $tab['regex'];?>"/>
				</td>
				<td>
					<input type="text" name="settings_regex_replace" id="settings_regex_replace_<?php echo $id;?>" value="<?php echo $tab['replace'];?>"/>
				</td>
				<td>
					<input type="text" name="settings_regex_ordre" id="settings_regex_ordre_<?php echo $id;?>" value="<?php echo $tab['ordre'];?>"/>
				</td>				
				<td>
					<SELECT name="settings_regex_operations" id="settings_regex_operations_<?php echo $id;?>" >
						<?php echo $reports->getSelectOperations($tab['id_operations']);?>
					</SELECT>
				</td>
				<td>
					<SELECT name="settings_regex_type" id="settings_regex_type_<?php echo $id;?>" >
						<option value='DEBIT' <?php echo ($tab['id_operations']=="DEBIT")?"selected='selected'":"";?> >DEBIT</option>
						<option value='CREDIT' <?php echo ($tab['id_operations']=="CREDIT")?"selected='selected'":"";?> >CREDIT</option>
					</SELECT>
				</td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>
<?php
$select_categorie=$reports->getSelectCategorie();
?>



<div class="form_settings">
	<div class="ajout_settings">
		<input type='text' name="new_keywords"/> <SELECT name='keywords_cat' ><?php echo $select_categorie;?></SELECT>
			<input type="submit" name="button_keywords" value="Ajouter">
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Value</td><td>Catégorie</td><td>Supprimer</td></tr>
			<?php foreach($liste_keywords as $id=>$tab){?>
			<tr>
				<td>
					<input type="text" name="settings_keywords_value" id="settings_keywords_value_<?php echo $id;?>" value="<?php echo $tab['value'];?>"/>
				</td>
				<td>
					<SELECT name="settings_keywords_cat" id="settings_keywords_cat_<?php echo $id;?>" >
						<?php echo $reports->getSelectCategorie($tab['id_cat']);?>
					</SELECT>
				</td>
				<td>
					<a href='#' onclick='supprimer_settings_keywords("<?php echo $id;?>")'>
						<img width='20' id='delete_<?php echo $id;?>' src='img/erreur.gif' alt ='supprimer'/>
					</a>
				</td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>
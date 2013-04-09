<?php
$liste_regroupements=$reports->listeRegroupement();
?>

<div class="form_settings">
	<div class="ajout_settings">
		Nouveau regroupement:  <input type='text' name="new_regroupements" id="new_regroupements"/>
		<input type="submit" name="button_regroupements" value="Ajouter" onclick='ajout_regroupements()' >
	</div>
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_regroupements as $id=>$libelle){?>
			<tr>
				<td>
					<input type="text" onchange='update_settings("regroupements","libelle",<?php echo $id;?>,$(this).val())' value="<?php echo $libelle;?>"/>
				</td>
				<td>
					<SELECT onchange='update_regroupements(<?php echo $id;?>,$(this).val())' multiple  >
						<?php echo $reports->getSelectCatByRegroupement($id);?>
					</SELECT>
				</td>
				<td>
					<a href='#' onclick='supprimer_settings_regroupements("<?php echo $id;?>")'>
						<img width='20' id='delete_<?php echo $id;?>' src='img/erreur.gif' alt ='supprimer'/>
					</a>
				</td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>
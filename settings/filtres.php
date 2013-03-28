<div class="form_settings">	
	<div class="modif_settings">
		<table>
			<tr><td>Libellé</td></tr>
			<?php foreach($liste_filtre as $id=>$nom_filtre){?>
			<tr>
				<td>
					<input type="text" onchange='update_settings("filtres","nom_filtre",<?php echo $id;?>,$(this).val())' value="<?php echo $nom_filtre;?>"/>
				</td>
				<td>
					<a href='#' onclick='supprimer_settings_filtres("<?php echo $id;?>")'>
						<img width='20' id='delete_<?php echo $id;?>' src='img/erreur.gif' alt ='supprimer'/>
					</a>
				</td>
			</tr>
			<?php }?>
		</table>
	</div>
</div>
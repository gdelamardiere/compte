<?php foreach($aReleveErreur as $value){
				switch($value['type']){
					case 'DEBIT':
						$debit+=$value['montant'];
						break;
					case 'CREDIT':
						$credit+=$value['montant'];
						break;
				}
				echo "<tr>
				<td  style='text-align:center'>".$value['date']."</td>
				<td>".$value['libelle']."</td>
				<td><SELECT style='width: 150px;'  id='".$value['id']."' onchange=\"update_operations('".$value['id']."',this);\" >";
				echo "<option value=''></option>";
				foreach($aOperations as $operations){
					echo "<option value='".$operations['id_operations']."'>".$operations['nom_operations']."</option>";
				}
				echo "</SELECT></td>
				<td class='odd'>".$value['montant']."</td>
				<td style='text-align:center'>".$value['type']."</td> 
				<td><SELECT style='width: 200px;'  id='".$value['id']."' onchange=\"update_categorie('".$value['id']."',this);\" >";
				foreach($aCat as $categorie){
					echo "<option value='".$categorie['id_cat']."' ".(($categorie['id_cat']==$value['id_cat'])?'selected="selected"':'').">"
					.$categorie['libelle']."</option>";
				}
				echo "</SELECT></td>			
				 
				<td><SELECT style='width: 80px;' onchange=\"update_pointage('".$value['id']."',this);\" >";
				foreach($aPointage as $key=>$pointage){
					echo "<option value='".$key."' ".(($key==$value['pointe'])?'selected="selected"':'').">".$pointage."</option>";
				}
				echo "</SELECT></td>
				<td style='text-align:center'>
					<a href='#' onclick='supprimer_ligne_releve(".$value['id'].")'>
						<img width='20' id='delete_".$value['id']."' src='img/erreur.gif' alt ='supprimer'/>
					</a>
					
				</td> 
				</tr>";
			}?>  
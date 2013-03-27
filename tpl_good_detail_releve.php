<?php foreach($aReleve as $value){
				switch($value['pointe']){
					case '1':
						$img="<img width='20' id='pointage_".$value['id']."' src='img/valider.png' alt ='valide'/>";
						break;
					case '-1':
						$img="<img width='20' id='pointage_".$value['id']."' src='img/erreur.gif' alt ='erreur'/>";
						break;
					default :
						$img="<img width='20' id='pointage_".$value['id']."' src='' alt ='aucun' style='display:none'/>";
						break;

				}
				switch($value['type']){
					case 'DEBIT':
						$debit+=$value['montant'];
						break;
					case 'CREDIT':
						$credit+=$value['montant'];
						break;
				}

				echo "<tr id='ligne_".$value['id']."'>				
				<td>".$value['date']."</td>
				<td>".$value['libelle']."</td>
				<td class='lecture' id='operations_".$value['id']."'>".$value['operations']."</td>
				<td class='edition' >
					<SELECT id='".$value['id']."' onchange=\"update_operations('".$value['id']."',this);\" >";
						echo "<option value=''></option>";
						echo $reports->getSelectOperations($value['id_operations']);
					echo "</SELECT>
				</td>
				<td class='odd'>".$value['montant']." &euro;</td>
				<td>".$value['type']."</td> 

				<td class='lecture' id='cat_".$value['id']."'>".$value['categorie']."</td> 
				<td class='edition'><SELECT onchange=\"update_categorie('".$value['id']."',this);\" >";
				foreach($aCat as $categorie){
					echo "<option value='".$categorie['id_cat']."' ".(($categorie['id_cat']==$value['id_cat'])?'selected="selected"':'').">"
					.$categorie['libelle']."</option>";
				}
				echo "</SELECT></td>

				<td class='lecture'>".$img."</td> 
				<td class='edition'><SELECT onchange=\"update_pointage('".$value['id']."',this);\" >";
				foreach($aPointage as $key=>$pointage){
					echo "<option value='".$key."' ".(($key==$value['pointe'])?'selected="selected"':'').">".$pointage."</option>";
				}
				echo "</SELECT></td>
				<td>
					<a href='#' onclick='supprimer_ligne_releve(".$value['id'].")'>
						<img width='20' id='delete_".$value['id']."' src='img/erreur.gif' alt ='supprimer'/>
					</a>
					
				</td> 
				<td style='display:none'></td>
				</tr>";
			}?>  
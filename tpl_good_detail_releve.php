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
				<td  style='text-align:center'>".$value['date']."</td>
				<td>".$value['libelle']."</td>				
				<td class='".(($value['montant']<0)?'odd':'green')."' style='text-align:right'>".$value['montant']." &euro;</td>
				<td style='text-align:center'>".$value['type']."</td> 
				<td style='text-align:center'>".$value['regroupements']."</td>
				<td class='lecture' id='operations_".$value['id']."'>".$value['operations']."</td>
				<td style='display:none' id='cat2_".$value['id']."'>".$value['categorie']."</td>
				<td class='edition' >
					<SELECT style='width: 150px;' id='".$value['id']."' onchange=\"update_operations('".$value['id']."',this);\" >";
						echo "<option value=''></option>";
						echo $reports->getSelectOperations($value['id_operations']);
					echo "</SELECT>
				</td>
				<td class='lecture' id='cat_".$value['id']."'>".$value['categorie']."</td> 
				<td class='edition'><SELECT style='width:200px;'  onchange=\"update_categorie('".$value['id']."',this);\" >";
				foreach($aCat as $categorie){
					echo "<option value='".$categorie['id_cat']."' ".(($categorie['id_cat']==$value['id_cat'])?'selected="selected"':'').">"
					.$categorie['libelle']."</option>";
				}
				echo "</SELECT>
				</td>

				<td style='text-align:center' class='lecture'>".$img."</td> 
				<td class='edition'><SELECT style='width: 80px;'  onchange=\"update_pointage('".$value['id']."',this);\" >";
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
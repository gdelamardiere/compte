<?php
$page = "liste_import";
require_once ('header.php');
$aListeImport = $reports->listeImport();
?>


<div class="detail_releve">
    <table style="text-align:center;width:1000px;"  id="detail_releve">
        <thead>
            <tr>
                <th>Import<br/>du</th>
                <th>Compte</th>
                <th filter='false'>Fichier</th>
                <th filter='false'>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($aListeImport as $value) {
                echo "<tr id='" . $value['id'] . "'>
				<td>" . $value['date_crea'] . "</td>
                                <td>" . $value['libelle'] . "</td>
				<td>
					<a href='" . SITE_FRONT . $value['fichier'] . "'>
						<img width='20' src='img/editer.jpg' alt ='éditer'/>
					</a>
				</td>
				<td>
					<a href='#' onclick='supprimer_import(" . $value['id'] . ")'>
						<img width='20'  src='img/erreur.gif' alt ='supprimer'/>
					</a>

				</td>
				</tr>";
            }
            ?>
        </tbody>

    </table>
</div>
<?php
require_once ('footer.php');
?>
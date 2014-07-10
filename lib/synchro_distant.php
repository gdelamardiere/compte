<?php
require_once(__DIR__."/../conf.php");
/*
 * Vérification de l'existence de $_FILES qui contient les informations sur le fichier envoyé
 */
if(isset($_FILES)){
    /*
     * Vérification que le fichier provient bien du serveur
     */
    if(is_uploaded_file($_FILES['My_File']['tmp_name'])){
        /*
         * Déplacement du fichier au bon endroit 
         */
        move_uploaded_file($_FILES['My_File']['tmp_name'],REP_SYNCHRO_SAVE_LOCAL.'/synchro'.time().".sql");
    }
    rename(REP_SYNCHRO.SYNCHRO_SQL,REP_SYNCHRO_SAVE_DISTANT.'/synchro'.time().".sql");
    touch(REP_SYNCHRO.SYNCHRO_SQL);
}
?>
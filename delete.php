<?php
// ensures utf-8, baby
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

        // write what we recieved from post into session



        // get the ID of the recipe to be deleted
        query("SET NAMES 'utf8'");
        $result = query("SELECT * FROM `rec-table` WHERE `name` = ?", $_POST['whichOne']);
        $id = $result[0]['id'];
        
        // delete recipe
        query("SET NAMES 'utf8'");
        $result = query("DELETE FROM `rec-table` WHERE `id` = ?", $id);
        
        // delete associated ingredients
        query("SET NAMES 'utf8'");
        $result = query("DELETE FROM `ingr-table` WHERE `ingr-rec-id` = ?", $id);

        redirect('all.php');
      
?>
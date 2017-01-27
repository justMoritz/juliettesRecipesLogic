<?php
// ensures utf-8, baby
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

        // search database and select compare the name set to lowercase with the input
        query("SET NAMES 'utf8'");
        $result = query("SELECT `name` FROM `rec-table`");

    



        renderRecipe('header.php', "dynList.php", 'footer.php', 
                     ["files" => $result,
                      "title" => "Recipe Index"
                      ]);

?>

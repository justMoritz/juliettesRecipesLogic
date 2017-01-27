<?php

    // configuration
    require("../includes/config.php"); 
    
    
    // is able to read all files from path 
    $path    = '../templates/';
    $names = scandir($path);
    
    // gets all 'name' entries from from database
    $files = query("SELECT name from rectable where 1") ; 
    
    
    
    
    if (isset($_GET["main"]))
    {
        render( $_GET["main"] .".php", ["title" => $_GET["main"]]);
    }
    


    else
    {
        // render portfolio
        render("forms/list_form2.php", ["title" => "Index", "files" => $files, "names" => $names ]);
    }

?>

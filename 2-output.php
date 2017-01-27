<?php

// follup up-from edit_handle.php

// ensures utf-8
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $_SESSION = $_POST;    
        }

        // if this gets called without supposed to be called, sent to home
        if(!isset($_SESSION['loginId'])){
            redirect('index.php');
        }

        $featured = "";
        if(isset($_SESSION['featured'])){
           $featured = $_SESSION['featured'];
        }

        // search database and select compare the name set to lowercase with the input
        query("SET NAMES 'utf8'");
        $dupEntry = query("REPLACE `recipe-table` (`id`, `name`, `recipeInstructions`, `recipeYield`, `cookTime`, `prepTime`, `description`, `image`, `featured`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)
              ",
              htmlspecialchars(ampReplace(rtrim($_SESSION['recipeName']))),
              htmlspecialchars($_SESSION['recipeInstructions']),
              htmlspecialchars($_SESSION['recipeYield']),
              htmlspecialchars($_SESSION['cookTime']),
              htmlspecialchars($_SESSION['prepTime']),
              htmlspecialchars($_SESSION['description']),
              $_SESSION['imageName'],
              $featured
             );

        $insertedID = query("SELECT LAST_INSERT_ID()");
        $insertedID = ($insertedID[0]["LAST_INSERT_ID()"]);

        $allIngrArray = [];

        $array = $_SESSION;

        $stringBuilder = "";
        $insertString = "";
        $inBuilder = "";
        $iaBuilder = "";
        $iuBuilder = "";
        $icBuilder = "";

        $cateIDs = [];


        $cateCounter = 1;
        $ingrCounter = 1;
        $amntCounter = 1;
        $unitCounter = 1;
        $catCounter = 1;

        // loops through entire input to enter the categories into DB and make array with them
        foreach ($array as $key => $value) {
            // if we find a category
            if (doesContain($key, 'ingr_category')){
                query("INSERT INTO `cate-table` (`cate-id`, `cate-name`) VALUES (NULL, ?)", $value); ;
                $temp = query("SELECT LAST_INSERT_ID()");
                array_push($cateIDs, $temp[0]["LAST_INSERT_ID()"]);
                
                // gets everything after the last _, the X in  in ingr_category_X
                $last_start = strrpos($key, '_');
                $last_field = substr($key, $last_start);
                //echo ltrim($last_field, "_");
            }    
        }


        // loops through entire input to build string to enter ingredients into database
        foreach ($array as $key => $value) {
            
            // when it finds name, amount, unit, category, it teporarily stores it
            if (doesContain($key, 'ingr_name_')){
                // if we recieved and empty field, write a space in it
                if ($value === ""){$value = " ";}
                $inBuilder = $value; 
                $ingrCounter++;
            }
            if (doesContain($key, 'ingr_amnt_')){
                // if we recieved and empty field, write a space in it
                if ($value === ""){$value = " ";}
                $iaBuilder = $value; 
                $amntCounter++;
            }
            if (doesContain($key, 'ingr_unit_')){
                // if we recieved and empty field, write a space in it
                if ($value === ""){$value = " ";}
                $iuBuilder = $value; 
                $unitCounter++;
            }
            if (doesContain($key, 'ingr_cate_')){
                // we are basically replacing the ingr_cate_ID from the form with the unique category ID from the database.
                //var_dump($cateIDs);
                $icBuilder = $cateIDs[($value-1)]; 
                $catCounter++;
            }
            
            // when all those things are not empty, it means we found an entire ingredient
            if ($inBuilder !== "" && $iaBuilder !== "" && $iuBuilder !== "" && $icBuilder !== ""){
                $stringBuilder = "(NULL, '$insertedID', '$inBuilder', '$iaBuilder', '$iuBuilder', $icBuilder)"; 
                $insertString = $insertString.$stringBuilder.',';
                // resetting the variables
                $inBuilder = "";
                $iaBuilder = "";
                $iuBuilder = "";
                $icBuilder = "";  
            } 
        }

//      var_dump($cateIDs);
        // strips the comma off the last element of the insert string
        $insertString = rtrim($insertString, ",");


//        echo $insertString;

        // inserts ingredients into Database, yo.
        $ingrInsertString = 
            "INSERT INTO `ingredients-table` (`ingredients-id`, `ingredients-recipe-id`, `ingredients-name`, `ingrediens-amnt`, `ingrediens-unit`, `ingredients-cate`) VALUES".$insertString;

        query($ingrInsertString);
        
            

// remember recipe name   
$currentRecipe = str_replace(' ', '-', ampReplace(rtrim($_SESSION['recipeName'])));

// initiate variable for old Name.
$oldName = '';

// remember session ID 
$logInID = $_SESSION['loginId'];
if(isset( $_SESSION['oldName'])){
    $oldName =  $_SESSION['oldName'];   
}

// empty the session for the next recipe
$_SESSION = [];

// reset Session ID so not lost
$_SESSION['loginId'] = $logInID;
$_SESSION['oldName'] = $oldName;

// redirect to recipe
if (!startsWith($currentRecipe, 'DUP')){
    redirect('index.php?recipe='.$currentRecipe);
}
//if we are dealing with a duplicate, send user back to editor 
else{
    redirect('editor.php?recipe='.$currentRecipe);
}




?>

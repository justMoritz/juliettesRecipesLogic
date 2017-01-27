<?php
// ensures utf-8, baby
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

    
    $_SESSION = $_GET;
    $_SESSION['result'] = 'yes';


     $_SESSION['door'] = $_SESSION['i'];
    
    
    $allArray = [];
    $evenMoreAll = [];
    
    $counter = 0;
    // loops through each ingredient recieved
    foreach ($_SESSION['door'] as $ingr){
        // makes a query for each ingredient and returns an array with the ID that contains heach
        query("SET NAMES 'utf8'");
        $result = query("SELECT `ingr-rec-id` FROM `ingr-table` WHERE LOWER(`ingr-name`) LIKE ?", '%'.$ingr.'%');
        
        // adds a new key into the array with the name of each of the selected ingredients
        // also makes this key an empty array
        $allArray[$counter] = [];
        // into wich each of the results is written
        foreach($result as $indResult){
            array_push($allArray[$counter], $indResult['ingr-rec-id']);   
            // removes duplicates if a recipe has for example 2x water
            $allArray[$counter] = array_unique($allArray[$counter]);
            // this one just writes each occurence into one looong array ... 
            array_push($evenMoreAll, $indResult['ingr-rec-id']);
        }    
        $counter++;
    }

    // first element to compare is the first index of the all-array
    $compArray = $allArray[0];
    // loops through each sub-array, compares the one values, keeps only the duplicates
    for($i=0; $i<count($allArray); $i++){
        // this makes sure we don't go too far, stop when we are at the last one
        if(isset($allArray[$i+1])){
            // merges both arrays
            $compArray = array_merge($compArray, $allArray[$i+1]);
            // keeps only the unique values of both arrays
            // (values present in both arrays)
            $valuesUnique = array_unique(array_diff_assoc($compArray, array_unique($compArray)));
            // sets that sets that to be the array to be merged into next time
            $compArray = $valuesUnique;
        }
    }

    
    $j = 0;
    $recArray = [];
    foreach($compArray as $compi){

        // creates objects of ingredients
        $ingrepass[$j] = new recipeObject;
        $ingrepass[$j]->ingreQuery($compi);
        
        // creates objects for name purposes
        $recResult = new recipeObject;
        
        $recResult->infoQuery($compi);
        
        // builds an array containing all the infoArray Properties
        array_push($recArray, $recResult);

        // only add Recipe Name to Database and build the Object 
        // if the query returned something that is both actually set 
        // and also not "", AKA we found a Recipe Name.    
        if (isset($recArray[$j]->infoArray['name'])){
            if($recArray[$j]->infoArray['name'] !== ""){
                $displaypass[$j] = $recArray[$j]->infoArray; 
            }
        }
        $j++;
    }
    
    $sessionDoor = $_SESSION['door'];

    $ingredients = new iconsObject;
 
    // if no recipe is found, make sure we don't pass anything that doesn't exist.
    if(!isset($ingrepass) || !isset($displaypass) || $ingrepass === NULL || $displaypass === NULL){
        $ingrepass = [];
        $displaypass = [];
    }

    
    // here we render the page we need, passing to it an array below of the results we got from our query:
    ["title" => "Results", "qarr" => $sessionDoor ,"ingrepass" => $ingrepass, "displaypass" => $displaypass , "fromSearch" => "false", "ingr" => $ingredients  ]);
    
//}

        
   
?>
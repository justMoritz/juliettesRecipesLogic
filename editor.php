<?php
// ensures utf-8, baby

// see edit_handle or handle.php for the next step

header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 



        // this functions only if the user is logged in
        if(isset($_SESSION['loginId'])){
            if($_SESSION['loginId'] === "htaccessUser"){



                $json = "";

                // get parameter gets parsed to replace dashes with spaces
                $input = $_GET['recipe'];
                $recipeName = str_replace('-', ' ', $input);
                $recipeName = umlautReverser($recipeName);

                // search database and select compare the name set to lowercase with the input
                query("SET NAMES 'utf8'");
                $result = query("SELECT `id` FROM `rec-table` WHERE LOWER(`name`) LIKE ?", $recipeName);


                // if the query didn't work, apologize
                if(!isset($result[0]['id'])){
                    error_message("The recipe “".$recipeName."” does not exist, yo.");  
                }
                // if not, make use of that ID
                $recID = $result[0]['id'];

                // creates the objects we need in the next step
                $recoje = new recipeObject;
                $ingrIcons = new ingrObject;

                // gets the basic recipe information for the inputted ID
                $recoje->infoQuery($recID);

                // gets the ingredient informatino for the inputted ID
                $recoje->ingreQuery($recID);





                // render the page, passing the queried items as an array
                    ["info" => $recoje->infoArray, "ingr" => $recoje->ingredientArray]);
            }else{
            // if not, send to home
            redirect('index.php');
        }
        }else{
            // if not, send to home
            redirect('index.php');
        }

?>

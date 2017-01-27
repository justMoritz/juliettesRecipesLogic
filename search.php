<?php

    // configuration
    require("../includes/config.php"); 
    
    
    // if form was submitted
    // POST, REDIRECT, GET
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // remember session ID 
        if (isset($_SESSION['loginId'])){
            $logInID = $_SESSION['loginId'];
        }

        // write what we recieved from post into session
        $_SESSION = $_POST;

        // reset Session ID so not lost
        if (isset($logInID)){
            $_SESSION['loginId'] = $logInID; 
        }

        // redirect to self
        // redirects to a generic, unique query 
        redirect('search.php?query='.(round(microtime(true)/rand(100, 10000))));  
    }
    else{
        // handles improper input
        // $_SESSION['search'] comes from being written from post above
        if(!isset($_SESSION['search'])){
                redirect('index.php');   
        }
        if(isset($_SESSION['search'])){
            if(IsNullOrEmptyString($_SESSION['search'])){
                redirect('index.php');   
            }
        }

        $results = $_SESSION['search'];
        if (empty($results))
        {
            // send some apologetic message to the user
        }
        else
        {
        
            // gets all 'name' entries from from database
            query("SET NAMES 'utf8'");
            $names = query("SELECT name FROM `recipe-table` WHERE 1") ;
             
            $inputtcompare = strtolower($_SESSION['search']); 
              
            $N = 0;
            $displaypass = "";
            $ingrepass = "";
            $qarr = [];
      
            $j = 0;
            
            for( $i=0; $i < count($names); $i++)
            {
                $nametocompare = strtolower(implode($names[$i])); 
                
                if (strpos($nametocompare, $inputtcompare) !== false)
                {
                    $plaintextname = implode($names[$i]);

                    // if the extracted name starts with DUP, don't add it to be displayed
                    if(!startsWith($names[$i]['name'], "DUP")){
                        $displaypass[$j] = $names[$i]; 
                    }

                    $recID = query("SELECT `id` FROM `recipe-table` WHERE name = ?", $plaintextname ) ;  
                    $recID =($recID[0]['id']);
                    
                    $ingrepass[$j] = new recipeObject;
                    $ingrepass[$j]->ingreQuery($recID);

                    $j++;
                }  
            }  

            // here we render the page we need, passing to it an array below of the results we got from our serach:
            ["title" => "Results", "qarr" => "", "ingrepass" => $ingrepass, "displaypass" => $displaypass , "fromSearch" => "true"  ]);
       
        }
  
    }


?>

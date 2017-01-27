<?php
// ensures utf-8

// see output.php for next step

header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

        // if this gets called without supposed to be called, sent to home
        // i.e., if user is not logged in
        if(!isset($_SESSION['loginId'])){
            redirect('index.php');
        }


        // remember session ID 
        $logInID = $_SESSION['loginId'];

        // write what we recieved from post into session
        $_SESSION = $_POST;

        // reset Session ID so not lost
        $_SESSION['loginId'] = $logInID;

        // prepares empty string to be written into when pictures are uploaded
        $_SESSION['imageName'] = "";

        // replaces Dashes with the proper en-dash
        $_SESSION['recipeName'] = enDash($_SESSION['recipeName']);


        if (isset($_FILES['fileToUpload'])) {
                $myFile = $_FILES['fileToUpload'];
                $fileCount = count($myFile["name"]);

                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName =  strtolower(str_replace(' ', '-', $myFile["name"][$i]));
                    move_uploaded_file($myFile["tmp_name"][$i], "picuploads/" . $fileName);
                    $_SESSION['imageName'] = $_SESSION['imageName'].', '. $fileName;
                }
            }
        
        // if we didn't get anything empty
        if ( $_SESSION['recipeName'] !=="" &&
             $_SESSION['recipeInstructions'] !=="" &&
             $_SESSION['recipeYield'] !=="" &&
             $_SESSION['cookTime'] !=="" &&
             $_SESSION['prepTime'] !=="" &&
            $_SESSION['description'] !==""
           ){
            if($_SESSION['prepTime'] ===""){$_SESSION['prepTime'] = " ";}
            
            // make query into database with the name
            query("SET NAMES 'utf8'");
            $result = query("SELECT * FROM `rec-table` WHERE `name` = ?", ampReplace($_SESSION['recipeName']));

            // if the result got SOMETHING in it, the entry in DB exists already
            if(isset($result[0])){

                // changes the name to create a new entry into the database with this name
                // this will not be publically visible, but it will allow us to go and send the 
                // user to an edit for to let him know that he needs to fill out the form better

                // we are also remembering the old name
                $_SESSION['oldName'] = ampReplace($_SESSION['recipeName']);

                $newName = "DUP_".round(microtime(true))."_".$_SESSION['recipeName'];
                $_SESSION['recipeName'] = ampReplace($newName); 
                redirect('output.php');     

                // in the output.php (the file actually doing the writing), we check if the name entered started with DUP, and if it did, then we re-direct the the editor.

            }
            // if it's empty, the entry does not exist and we just make the entry
            else{
                redirect('output.php');       
            }
            
        }
        else{
            redirect('entry.php?error');
        }
            
?>
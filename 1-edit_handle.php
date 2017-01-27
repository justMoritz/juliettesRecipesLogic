<?php


/*
 * this file is pulled up when an EDITING form in the CMS is posted
 *
 * after data is processed in this program...
 *
 * ... the next step is output.php
 *
 */

// ensures utf-8
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 

        // if this gets called without supposed to be called, sent to home
        // (means no user was logged in)
        if(!isset($_SESSION['loggedinId'])){
            redirect('index.php');
        }

        // remember session ID 
        $loggedInID = $_SESSION['loggedinId'];

        // write what we recieved from post into session
        $_SESSION = $_POST;

        // reset Session ID so not lost
        $_SESSION['loggedinId'] = $loggedInID;

        

        // deletes the old ingredients, so we don't have any duplicates
        query("SET NAMES 'utf8'");
        query("DELETE FROM `ingredients-table` WHERE `ingredients-recipe-id` = ?", $_SESSION['idString']);

        if (isset($_FILES['fileToUpload'])) {
                $myFile = $_FILES['fileToUpload'];
                $fileCount = count($myFile["name"]);

                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName =  strtolower(str_replace(' ', '-', $myFile["name"][$i]));
                    move_uploaded_file($myFile["tmp_name"][$i], "uploads/" . $fileName);
                    $_SESSION['imageName'] = $_SESSION['imageName'].', '. $fileName;
                }
            }

        redirect('output.php');       

            
?>
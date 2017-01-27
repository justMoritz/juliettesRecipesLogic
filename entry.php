<?php
// ensures utf-8, baby
header('Content-Type: text/html; charset=utf-8');
    // configuration
        require("../includes/config.php"); 


        /// this functions only if the user is logged in
        if(isset($_SESSION['loginId'])){
            if($_SESSION['loginId'] === "htaccessUser"){

                renderRecipe('edit_header.php', "dynEntry.php", 'edit_footer.php', 
                                 ["info" => "blank"
                                  ]);
                
            }
            else{
            // if not, send to home
            redirect('index.php');
        }
        }
        else{
            // if not, send to home
            redirect('index.php');
        }

?>

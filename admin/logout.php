<?php

    session_start();
    
    // change
    if(isset($_SESSION['admin_login'])){
        unset($_SESSION['admin_login']);

        header('location: ../login.php');
        die(); //exits the current script
    }

?>
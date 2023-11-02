<?php

    session_start();

    if(isset($_SESSION['user_login'])){
        unset($_SESSION['user_login']);
        unset($_SESSION['user_email']);
        unset($_SESSION['auth_user']);

        setcookie("user_email", "", time()-60*60);
        $_COOKIE['user_email'] = "";
        header('location: ./login.php');
        die(); //exits the current script
    }

?>
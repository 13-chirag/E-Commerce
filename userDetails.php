<?php
    
    require("connection.inc.php");

    if(array_key_exists('user_email', $_COOKIE)){
        $_SESSION['user_email'] = $_COOKIE['user_email'];
        $_SESSION['user_login'] = "yes";
    }

    if(isset($_SESSION['user_login'])){
        $loginemail = $_SESSION['user_email'];
        $query = "SELECT * FROM users WHERE email='$loginemail'";
        $result = mysqli_query($db_con, $query);

        if(mysqli_num_rows($result) > 0){
            
            $userData = mysqli_fetch_array($result);

            $userid = $userData['id'];
            $username = $userData['name'];
            $useremail = $userData['email'];

            $_SESSION['auth_user'] = array(
                'user_id' => $userid,
                'name' => $username,
                'email' => $useremail
            );

        }
    }

?>
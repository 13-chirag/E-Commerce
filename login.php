
<?php
session_start();
require('connection.inc.php');

$email = $password = $err_msg =  "";
$emailErr = $passwordErr = "";
$pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //validations

    // email
    if (empty($_POST['email'])) {
        $emailErr = "<p class='text-danger'>Email required</p>";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "<p class='text-danger'>Enter valid email address!</p>";
    } else {
        $email = mysqli_real_escape_string($db_con, test_input($_POST["email"]));
    }

    //password
    if (empty($_POST['password'])) {
        $passwordErr = "<p class='text-danger'>Password Required!</p>";
    } else if (!preg_match($pattern, $_POST['password'])) {
        // if(strlen($_POST['password'])<8 or strlen($_POST['password'])>12){
        //     $passwordErr = "<p class='text-danger'>Password should be 8-12 characters long!</p>";
        // }
        // else{
        //     $passwordErr = "<p class='text-danger'>Password should atleast contain one Uppercase, Lowercase, digit, specialCharacter!</p>";
        // }
        $passwordErr = "<p class='text-danger'> Enter a valid password!</p>";
    }

    $password = mysqli_real_escape_string($db_con, test_input($_POST["password"]));


    if (isset($_POST["submit"])) {
        $query = "SELECT * FROM `users_admin` WHERE email='$email' AND password='$password'";

        $result = mysqli_query($db_con, $query);
        $count = mysqli_num_rows($result);

        if ($emailErr == "" && $passwordErr == "") {
            if ($count > 0) {
                $_SESSION['admin_login'] = "yes";

                // setcookie("id", $id, time()+ 60*60*24);
                header("location:./admin/categories.php");
            } else {
                $err_msg = "<p class='text-danger'>Please enter correct login details!</p>";
            }
        }

        $queryPassword = "SELECT password FROM users where email='$email'";
        $respass = mysqli_query($db_con, $queryPassword);
        $res = mysqli_fetch_assoc($respass);

        $db_pass = $res['password'];
        $passwordVerify = hash('sha512', $password);
        
        
        if(password_verify($passwordVerify, $db_pass)){
            $_SESSION['user_login'] = 'yes';
            $_SESSION['user_email'] = $email;

            //if checkbox clicked
            if(isset($_POST['stayLoggedIn'])){
                setcookie("user_email", $email, time()+60*60*24);
            }

            header('Location: index.php');
        }
        else{
            $err_msg = "<p class='text-danger'>Please enter correct login details!</p>";
        }
        

        // $queryuser = "SELECT * FROM users WHERE email='$email' AND password='$passwordVerify'";
        // $result2 = mysqli_query($db_con, $queryuser);
        // $count2 = mysqli_num_rows($result2);

    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <style>
        body {
            /* url bk-repeat bk-position bk-attachment */
            margin: 0;
            padding: 0;
            background-size: cover;
            height: 100%;
        }

        .container {
            /* text-align: center; */
            /* width: 430px; */
            margin-top: 140px;
            box-shadow: 0px 0px 15px 7px grey;
            border-radius: 6px;
            background-color: #e5eedd;
        }

        input {
            margin: 0px 0 10px 0;
        }

        .heading {
            text-shadow: 5px 2px white;
            text-align: center;
        }
        #login{
            margin-top: 15px;
            border: 3px solid white;
            background-color: #bfe997;
        }
        #login:hover{
            background-color: white;
        }
        #navbar{
            background-color: #87d142;
        }
    </style>
</head>

<body class="body">
    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Fresh Express</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-success nav-link" aria-current="page" href="Register.php">Register</a>
                    </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container col-lg-5 col-md-6 col-sm-8 col-8">

        <h2 class="heading">Login Form</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <!-- email -->
            <div class="form-group">
                <label class="form-label" for="email">EMAIL</label>
                <input class="form-control" type="email" name="email" placeholder="Enter email" value="<?php if (isset($_POST['email'])) {
                        echo htmlentities($_POST['email']);
                } ?>">
                <div><?php echo $emailErr; ?></div>
            </div>

            <!-- password -->
            <div class="form-group">
                <label class="form-label" for="password">PASSWORD</label>
                <input id="pass1" class="form-control" type="password" name="password" placeholder="Enter password">
                <img class="pass-img1" src="./images/lock.svg" alt="" width="15px" height="15px" id="lock1">
                <div><?php echo $passwordErr; ?></div>
            </div>

            <!-- stay logged in -->
            <div class="form-group">
                STAY LOGGED IN: <input width="10px" type="checkbox" name="stayLoggedIn" value="1">
            </div>

            <!-- login button -->
            <div class="form-group text-center">
                <input id="login" type="submit" name="submit" value="LOGIN" class="btn">
            </div>

            <!-- error msg -->
            <div>
                <?php echo "$err_msg"; ?>
            </div>
        </form>
    </div>

    <script>
        let icon1 = document.getElementById('lock1');
        let pass1 = document.getElementById('pass1');

        icon1.onclick = function(){
            if(pass1.type == 'password'){
                pass1.type = "text";
            }
            else{
                pass1.type = "password";
            }
        }
    </script>
</body>

</html>
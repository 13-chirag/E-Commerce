<?php
    require("connection.inc.php");

    $nameErr = $emailErr = $mobileErr = $passwordErr = "";
    $name =$email = $mobile = $password = $err_msg = "";

    $patternName = '/^[A-Za-z]+/';
    $patternEmail = '/^[a-zA-Z0-9_\-\.]+[@][a-z]+[\.][a-z]{2,3}/';
    $patternMobile = '/^[0-9]{10}$/';
    $patternPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/";

    if(isset($_POST['submit'])){

        // validations
        //name
        if(empty($_POST['name'])){
            $nameErr = "<p class=text-danger>Name Required!</p>";
        }
        else if(!preg_match($patternName, $_POST['name'])){
            $nameErr = "<p class='text-danger'>Enter valid name</p>";
        }
        else{
            $name = mysqli_real_escape_string($db_con, test_input($_POST["name"]));
        }

        // email
        if (empty($_POST['email'])) {
            $emailErr = "<p class='text-danger'>Email required</p>";
        } else if (!preg_match($patternEmail, $_POST['email'])) {
            $emailErr = "<p class='text-danger'>Enter valid email address!</p>";
        } else {
            $email = mysqli_real_escape_string($db_con, test_input($_POST["email"]));
        }

        //mobile
        if(empty($_POST['mobile'])){
            $mobileErr = "<p class='text-danger'>Mobile No. required</p>";
        }
        else if(!preg_match($patternMobile, $_POST['mobile'])){
            $mobileErr = "<p class='text-danger'>Enter a valid mobile number!</p>";
        }
        else {
            $mobile = mysqli_real_escape_string($db_con, test_input($_POST["mobile"]));
        }

        //password
        if(empty($_POST['password'])){
            $passwordErr = "<p class='text-danger'>Password required!</p>";
        }
        else if(!preg_match($patternPassword, $_POST['password'])){
            $passwordErr = "<p class='text-danger'>Enter a strong Password!</p>";
        }
        else {
            $password = mysqli_real_escape_string($db_con, test_input($_POST["password"]));
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    //to insert user into database with hashing password and checking if user already exists or not
    if($nameErr=="" && $emailErr == "" && $mobileErr=="" && $passwordErr == "" && $name != '' && $email != "" && $mobile!="" && $password != "" ){
        //to check email availablity

        $queryEmailCheck = "SELECT id FROM users WHERE email='$email'";
        $res = mysqli_query($db_con, $queryEmailCheck);
        $row = mysqli_num_rows($res);

        if($row>0){
            $emailErr = "<p class='text-danger'>Email address already exists!</p>";
            // die();
        }
        else{
            //hashing password using SHA512

            // encryting password
            $passwordHashed = hash('sha512', $password);
            $passwordHashed = password_hash($passwordHashed, PASSWORD_DEFAULT);

            //inserting data
            $queryInsert = "INSERT INTO users (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$passwordHashed')";

            $result = mysqli_query($db_con,$queryInsert);

            //to check if proccess went right
            if(!$result){
                $err_msg = "<p class='text-danger'>Could not sign you up!. Please try again later!</p>";
                $err_msg = "<p class='text-danger'>" .mysqli_error($db_con). "</p>";
                // die();
            }
            else{
                header('Location: login.php');
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

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
                margin-top: 80px;
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
            <a class="navbar-brand" href="./index.php">Fresh Express</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-outline-success nav-link active" aria-current="page" href="login.php">Login</a>
                    </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container col-lg-5 col-md-6 col-sm-8 col-8 bg-success-subtle">

        <h2 class="heading">Register Yourself</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <!-- name -->
            <div class="form-group">
                <label class="form-label" for="name">NAME</label>
                <input class="form-control" type="text" name="name" placeholder="Enter name" value="<?php if (isset($_POST['name'])) {
                        echo htmlentities($_POST['name']);
                } ?>">
                <div><?php echo $nameErr; ?></div>
            </div>

            <!-- email -->
            <div class="form-group">
                <label class="form-label" for="email">EMAIL</label>
                <input class="form-control" type="email" name="email" placeholder="Enter email" value="<?php if (isset($_POST['email'])) {
                        echo htmlentities($_POST['email']);
                } ?>">
                <div><?php echo $emailErr; ?></div>
            </div>

            <!-- mobile -->
            <div class="form-group">
                <label class="form-label" for="mobile">MOBILE No.</label>
                <input class="form-control" type="text" name="mobile" placeholder="Enter mobile number" value="<?php if (isset($_POST['mobile'])) {
                        echo htmlentities($_POST['mobile']);
                } ?>">
                <div><?php echo $mobileErr; ?></div>
            </div>

            <!-- password -->
            <div class="form-group">
                <label class="form-label" for="password">PASSWORD</label>
                <input id="pass2" class="form-control" type="password" name="password" placeholder="Enter password">
                <img class="pass-img2" src="./images/lock.svg" alt="" width="15px" height="15px" id="lock2">
                <div><?php echo $passwordErr; ?></div>
            </div>

            <!-- login button -->
            <div class="form-group text-center">
                <input id="login" type="submit" name="submit" value="REGISTER" class="btn">
            </div>

            <!-- error msg -->
            <div>
                <?php echo "$err_msg"; ?>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        let icon2 = document.getElementById('lock2');
        let pass2 = document.getElementById('pass2');

        icon2.onclick = function(){
            if(pass2.type == 'password'){
                pass2.type = "text";
            }
            else{
                pass2.type = "password";
            }
        }
    </script>
</body>
</html>
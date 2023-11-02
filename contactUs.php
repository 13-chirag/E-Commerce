<?php
    require('connection.inc.php');
    require('header.php');

    $firstnameErr = $lastnameErr = $emailErr = $phoneErr = $commentsErr = "";
    $firstname = $lastname =$email = $phone = $comments = "";

    $patternName = '/^[A-Za-z]+/';
    $patternEmail = '/^[a-zA-Z0-9_\-\.]+[@][a-z]+[\.][a-z]{2,3}/';
    $patternMobile = '/^[0-9]{10}$/';

    if(isset($_POST['contact_form'])){

        // validations
        //first name
        if(empty($_POST['firstname'])){
            $firstnameErr = "<p class=text-danger>First Name Required!</p>";
        }
        else if(!preg_match($patternName, $_POST['firstname'])){
            $firstnameErr = "<p class='text-danger'>Enter valid first name</p>";
        }
        else{
            $firstname = mysqli_real_escape_string($db_con, test_input($_POST["firstname"]));
        }

        //last name
        if(empty($_POST['lastname'])){
            $lastnameErr = "<p class=text-danger>Last Name Required!</p>";
        }
        else if(!preg_match($patternName, $_POST['lastname'])){
            $lastnameErr = "<p class='text-danger'>Enter valid last name</p>";
        }
        else{
            $lastname = mysqli_real_escape_string($db_con, test_input($_POST["lastname"]));
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
        if(empty($_POST['phone'])){
            $phoneErr = "<p class='text-danger'>Mobile No. required</p>";
        }
        else if(!preg_match($patternMobile, $_POST['phone'])){
            $phoneErr = "<p class='text-danger'>Enter a valid mobile number!</p>";
        }
        else {
            $phone = mysqli_real_escape_string($db_con, test_input($_POST["phone"]));
        }

        //comments
        if(empty($_POST['comments'])){
            $commentsErr = "<p class='text-danger'>comments required!</p>";
        }
        else {
            $comments = mysqli_real_escape_string($db_con, test_input($_POST["comments"]));
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
    if($firstnameErr=="" && $lastnameErr=="" && $emailErr == "" && $phoneErr=="" && $commentsErr == "" && $firstname != '' && $lastname != '' && $email != "" && $phone!="" && $comments != "" ){
        //to check email availablity

        $firstname = mysqli_real_escape_string($db_con, $firstname);
        $lastname = mysqli_real_escape_string($db_con, $lastname);
        $email = mysqli_real_escape_string($db_con, $email);
        $phone = mysqli_real_escape_string($db_con, $phone);
        $comments = mysqli_real_escape_string($db_con, $comments);

        $query_insert = "INSERT INTO contact_form (first_name, last_name, email, mobile, comment) VALUES ('$firstname', '$lastname', '$email', '$phone', '$comments')";
        mysqli_query($db_con, $query_insert);
    }
?>

<div id="contactus" class="py-3 mb-3 py-md-2 bg-light mx-auto">
                    <div class="container shadow bg-white p-3 mt-3">
                        <h4 style="color: #87d142;">
                            Contact Form
                        </h4>
                        <hr>

                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control" placeholder="Enter First Name"/>
                                    <div><?php echo $firstnameErr; ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control" placeholder="Enter Last Name"/>
                                    <div><?php echo $lastnameErr; ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number"/>
                                    <div><?php echo $phoneErr; ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Email Address"/>
                                    <div><?php echo $emailErr; ?></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Comments</label>
                                    <textarea name="comments" class="form-control" rows="2"></textarea>
                                    <div><?php echo $commentsErr; ?></div>
                                </div>

                                <div>
                                    <button class="btn btn-outline-success" name="contact_form">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
<?php
    require('footer.php');
?>
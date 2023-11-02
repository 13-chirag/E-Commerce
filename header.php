<?php
    session_start();
    require("connection.inc.php");
    include("userDetails.php");

    if(array_key_exists("user_email", $_COOKIE)){
        $_SESSION['user_email'] = $_COOKIE['user_email'];
        $_SESSION['user_login'] = "yes";
    }

    //to show the cart products count
    if(isset($_SESSION['auth_user'])){
        $user_id = $_SESSION['auth_user']['user_id'];
        $count_query = "SELECT count(*) FROM carts WHERE user_id='$user_id'";
        $count_run = mysqli_query($db_con, $count_query);
        $count_result = mysqli_fetch_assoc($count_run);
    }
     

    //to bring categories for dropdown
    $queryCat = "SELECT * FROM categories WHERE status=1";
    $res = mysqli_query($db_con, $queryCat);

    // to create array to bring all categories available in db
    $categ_arr = array();
    while($row = mysqli_fetch_assoc($res)){
        $categ_arr[] = $row;
    }


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecommerce Navbar Design</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>
<body>

    <div class="main-navbar shadow-sm sticky-top">
        <div class="top-navbar">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 my-auto d-none d-sm-none d-md-block d-lg-block">
                        <a class="brand-name" href="./index.php" style="text-decoration: none;"><h5>Fresh Express</h5></a>
                    </div>

                    <div class="col-md-10 my-auto">
                        <ul class="nav justify-content-end">
                            
                            <li class="nav-item">
                                
                                <a class="nav-link" <?php if(isset($_SESSION['user_login'])){ ?> 
                                    href="./cart.php"
                            <?php
                            }
                            else{?>
                                    href='login.php'
                            <?php }?>
                            >
                                    <i class="fa fa-shopping-cart"></i> Cart
                                    <?php
                                        if(isset($_SESSION['user_login'])){
                                    ?>
                                            <span><?php print_r("(".$count_result['count(*)'].")") ?></span>
                                    <?php
                                        }
                                    ?>
                                    </a>
                            
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user"></i><?php if(isset($_SESSION['user_email'])){
                                            echo " ".$_SESSION['auth_user']['name'];
                                    } else{
                                        echo " Login/Register";
                                    }?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                    <li <?php
                                    if(isset($_SESSION['user_login'])){
                                    ?> style="display: none" <?php }?>><a class="dropdown-item" href="./login.php"><i class="fa fa-user"></i>Login</a></li>

                                    <li <?php
                                    if(isset($_SESSION['user_login'])){
                                    ?> style="display: none" <?php }?>><a class="dropdown-item" href="./Register.php"><i class="fa fa-user"></i>Register</a></li>

                                    <?php
                                    if(isset($_SESSION['user_login'])){
                                    ?>

                                        <li><a class="dropdown-item" href="./cart.php"><i class="fa fa-shopping-cart"></i> My Cart</a></li>
                                        <li><a class="dropdown-item" href="./myOrders.php"><i class="fa fa-list"></i> My Orders</a></li>
                                        <li><a class="dropdown-item" href="./logoutUser.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                                    <?php 
                                    }
                                    ?>                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand d-block d-sm-block d-md-none d-lg-none" href="./index.php">
                    Fresh Express
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                     Available Categories 
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php
                                        foreach($categ_arr as $list){
                                    ?>
                                        <li class="nav-item"><a class="nav-link" href="categories.php?category=<?php echo $list['categories'];?>&id=<?php echo $list['id'];?>"> <?php echo $list['categories'];?></a></li>
                                    <?php
                                        }
                                    ?>
                                    
                                </ul>
                            </li>
                            

                        <li class="nav-item">
                            <a class="nav-link" href="./index.php#new_products">New Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categories.php">All Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./contactUs.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>



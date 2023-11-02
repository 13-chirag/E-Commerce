<?php
    session_start();
    require('connection.inc.php');

    $user_id = $_SESSION['auth_user']['user_id'];
                    
    if(isset($_SESSION['user_login'])){

        if(isset($_POST['scope'])){
            $scope = $_POST['scope'];
            switch($scope){
                
                case "add":
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];

                    $user_id = $_SESSION['auth_user']['user_id'];

                    //checking whether the product already exists into the cart, if not then only insert/add the product to the cart
                    $chek_existing_cart = "SELECT * FROM carts WHERE prod_id = '$product_id' AND user_id = '$user_id'";
                    $run_exists = mysqli_query($db_con, $chek_existing_cart);

                    if(mysqli_num_rows($run_exists) > 0){
                        echo "already exists";
                    }
                    else{
                        //inserting the product into the cart
                        $insert_query = "INSERT INTO carts (user_id, prod_id, prod_qty) VALUES ('$user_id', '$product_id', '$quantity')";
                        $run = mysqli_query($db_con, $insert_query);

                        if($run){
                            echo "productAddedToCart";
                        }
                    }
                    break;

                    case "update":
                        $product_id = $_POST['product_id'];
                        $quantity = $_POST['quantity'];

                        $user_id = $_SESSION['auth_user']['user_id'];

                        $chek_existing_cart = "SELECT * FROM carts WHERE prod_id = '$product_id' AND user_id = '$user_id'";
                        $run_exists = mysqli_query($db_con, $chek_existing_cart);

                        //need to check whether user with that prod id exists, if exists then only update the quantity
                        if(mysqli_num_rows($run_exists) > 0){
                            $update_query = "UPDATE carts SET prod_qty = '$quantity' WHERE prod_id = '$product_id' AND user_id = '$user_id'";

                            $update_run = mysqli_query($db_con, $update_query);

                            if($update_run){
                                echo "quantity updated";
                            }
                        }
                        else{
                            echo "something went wrong";
                        }
                    break;


                    case "remove":
                        $cart_id = $_POST['cart_id'];

                        $user_id = $_SESSION['auth_user']['user_id'];
                        
                        //to check item exists in the database for that particular user
                        $chek_existing_cart = "SELECT * FROM carts WHERE id = '$cart_id' AND user_id = '$user_id'";
                        $run_exists = mysqli_query($db_con, $chek_existing_cart);

                        //need to check whether user with that prod id exists, if exists then only update the quantity
                        if(mysqli_num_rows($run_exists) > 0){
                            $delete_query = "DELETE FROM carts WHERE id = '$cart_id'";

                            $delete_run = mysqli_query($db_con, $delete_query);

                            if($delete_run){
                                echo "item deleted";
                            }
                        }
                        else{
                            echo "something went wrong";
                        }
                    break;
                
            }
        }
    }
    else{
            header('location: login.php');
    }

?>
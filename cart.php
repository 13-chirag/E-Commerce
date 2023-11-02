
<?php 
    require("header.php");

    if(isset($_SESSION['user_login'])){

    }
    else{
        echo "<script>location.href='index.php';</script>";
    }

    function getCartItems(){
        global $db_con;

        $userId = $_SESSION['auth_user']['user_id'];

        $query = "SELECT c.id as cid, c.prod_id, c.prod_qty, p.id as pid, p.product_name, p.quantity, p.image, p.mrp FROM carts c, products p WHERE c.prod_id = p.id AND c.user_id = '$userId' ORDER BY c.id DESC";

        $query_run = mysqli_query($db_con, $query);

        return $query_run;
    }


?>
<div class="py-3 py-md-5 bg-light">
        <div class="container">

            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="shopping-cart">

                        <div class="cart-header d-none d-sm-none d-md-block d-lg-block shadow-sm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 style="font-weight: bold">Products</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4 style="font-weight: bold">Price</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4 style="font-weight: bold">Quantity</h4>
                                </div>
                                <div class="col-md-2">
                                    <h4 style="font-weight: bold">Remove</h4>
                                </div>
                            </div>
                        </div>

                        <?php
                            $items = getCartItems();

                            // loop to get all items of particular user
                            if(mysqli_num_rows($items)<1){
                                    ?>
                                        <p class="alert alert-warning">Your cart is empty:-</p>
                                    <?php
                                        
                            }
                            else if(mysqli_num_rows($items) > 0){
                                foreach($items as $citem){
                                    // print_r($citem);
                                    
                            ?>
                                <div class="cart-item">
                                    <div class="row">
                                        <div class="col-md-6 my-auto">
                                            <a href="">
                                                <label class="product-name">
                                                    <img height="190px" src="./media/products/<?php echo $citem['image']; ?>" class="w-50" alt="Img">
    
                                                    <?php echo "<span style='margin-left: 30px; color: black; font-size: 20px;'>".$citem['product_name']."</span>"; ?>
                                                </label>
    
                                            
                                        </a>
                                    </div>
                                    <div class="col-md-2 my-auto">
                                        <label class="price"><?php echo "Rs.".$citem['mrp'];?></label>
                                    </div>
                                    <div class="col-md-2 col-7 my-auto">
                                            <input min="1" max="<?php echo $citem['quantity']; ?>" class="w-50 input_quantity" type="number" value="<?php echo $citem['prod_qty']; ?>" onKeyDown="return false"><br>
                                            <button class="btn btn-sm mt-1 updateQty" value="<?php echo $citem['prod_id']; ?>">Update</button>
                                    </div>
                                    <div class="col-md-2 col-5 my-auto">
                                        <div class="remove">
                                            <button class="btn btn-danger deleteItem" value="<?php echo $citem['cid']; ?>"><i class="fa fa-trash me-1"></i> Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <?php
                                }
                            }
                        ?>   
                    </div>
                </div>
            </div>

        </div>

        <div class="container pt-3">
            <a href="index.php" class="btn btn-warning">Continue Adding Products</a>
            <?php
                            $items = getCartItems();

                            // loop to get all items of particular user
                            if(mysqli_num_rows($items)>0){
                                ?>
                                    <a href="checkout.php" class="btn btn-success float-end mr-5" onclick='<?php $_SESSION['order'] = 'ongoing'; ?>'>Proceed To Checkout</a>

            <?php
                            }
            ?>

        </div>
    </div>
<?php
?>

<?php
    require('footer.php');
?>
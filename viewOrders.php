
<?php
    require('connection.inc.php');
    require('header.php');


    function getOrders(){
        global $db_con;
        $userId = $_SESSION['auth_user']['user_id'];

        $query = "SELECT * FROM orders WHERE user_id='$userId'";
        $query_run = mysqli_query($db_con, $query);
        return $query_run;
    }

    if(isset($_GET['order'])){
        $order_no = $_GET['order'];

        $queryOrder = $_SESSION['auth_user']['user_id'];

        $queryOrderNo = "SELECT * FROM orders WHERE order_no='$order_no' AND user_id = '$user_id'";
        $result2 = mysqli_query($db_con, $queryOrderNo);

        if(mysqli_num_rows($result2) <= 0){
            echo "<p class='alert alert-danger'>Some went wrong!</p>";
            die();
        }

    }
    else{
        echo "<p class='alert alert-danger'>Some went wrong!</p>";
        die();
    }
    $data = mysqli_fetch_array($result2);
?>

<div class="py-3 py-md-4 checkout">
        <div class="container">
            <h4 class="d-inline">Your Order</h4>
            <a href="./myOrders.php"><button class="btn btn-warning float-end"><i class="fa fa-reply"></i> Back</button></a>
            <hr>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="shadow bg-white p-3">

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
                                    <h4 style="font-weight: bold">Items Price</h4>
                                </div>
                            </div>
                        </div>
                        <?php
                        //to get products from products table and ordered items from order-items table

                        $user_id = $_SESSION['auth_user']['user_id'];

                        $order_query = "SELECT o.id as oid, o.order_no, o.user_id, oi.*, p.* FROM orders o, order_items oi, products p WHERE o.user_id='$user_id' AND oi.order_id=o.id AND p.id=oi.prod_id AND o.order_no='$order_no'";

                        $order_query_run = mysqli_query($db_con, $order_query);
                        // $result = mysqli_fetch_array($order_query_run);
                        // print_r($result);

                        // loop to get all items of particular user
                        
                        $toPay = 0;
                        foreach($order_query_run as $item){
                            $totalAmount = $item['price']*$item['qty'];
                            $toPay = $toPay+$totalAmount;
                        ?>
                        
                           
                           <div class="cart-item">
                                    <div class="row">
                                        <div class="col-md-6 my-auto">
                                            <a href="">
                                                <label class="product-name">
                                                    <img src="./media/products/<?php echo $item['image']; ?>" class="w-25" alt="Img">
    
                                                    <?php echo "<span style='margin-left: 30px; color: black; font-size: 17px;'>".$item['product_name']."</span>"; ?>
                                                </label>
    
                                            
                                        </a>
                                    </div>
                                    <div class="col-md-2 my-auto">
                                        <label class=""><?php echo "Rs.".$item['price'];?></label>
                                    </div>
                                    <div class="col-md-2 col-7 my-auto" style="padding-left: 38px;">
                                            
                                            <label><?php echo $item['qty']; ?></label>
                                    </div>
                                    <div class="col-md-2 col-5 my-auto" style="padding-left: 38px;">
                                        <label><?php echo "Rs.".$totalAmount; ?></label>
                                    </div>
                                </div>
                            </div>
                           

                        <?php
                            }
                        ?>
                        </div>
                        <hr>
                        

                        <h4 style="color: #87d142;">
                            Total Price :
                            <span class="float-end" style="margin-right: 80px;">Rs.<?php echo $toPay; ?></span>
                        </h4>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 style="color: #87d142;">
                            User Details
                        </h4>
                        <hr>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name</label>
                                    <input type="text" name="fullname" class="form-control" value="<?php echo $data['name']; ?>"/>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" value="<?php echo $data['phone']; ?>"/>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>"/>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Order No</label>
                                    <input type="number" name="pincode" class="form-control" value="<?php echo $data['order_no']; ?>"/>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Full Address</label>
                                    <div class="border p-1">
                                        <?php echo $data['address']; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Mode of Payment</label>
                                    <input type="text" name="mode" class="form-control" value="<?php echo $data['payment_mode']; ?>"/>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Order Status</label>
                                        <div class="border p-1">
                                        <?php
                                            if($data['order_status']==0){
                                                echo "<span class='text-warning'>Under Process</span>";
                                            }
                                            else if($data['order_status']==1){
                                                echo "<span class='text-success'>completed</span>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
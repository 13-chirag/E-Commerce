
<?php
    require('connection.inc.php');
    require('header.php');

    // mail
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';


    function getCartItems(){
        global $db_con;

        $userId = $_SESSION['auth_user']['user_id'];

        $query = "SELECT c.id as cid, c.prod_id, c.prod_qty, p.id as pid, p.product_name, p.image, p.mrp FROM carts c, products p WHERE c.prod_id = p.id AND c.user_id = '$userId' ORDER BY c.id DESC";

        $query_run = mysqli_query($db_con, $query);

        return $query_run;
    }

        
    
        $nameErr = $emailErr = $phoneErr = $pinErr = $addressErr = "";
        //validations to form
        // if($_SERVER["REQUEST_METHOD"] == "POST"){
        //     if (empty($_POST['fullname'])) {
        //         $nameErr = "<p class='text-danger'>Name required</p>";
        //     }
        //     if (empty($_POST['phone'])) {
        //         $phoneErr = "<p class='text-danger'>Phone No. required</p>";
        //     }
        //     if (empty($_POST['email'])) {
        //         $emailErr = "<p class='text-danger'>Email required</p>";
        //     }
        //     if (empty($_POST['pincode'])) {
        //         $pinErr = "<p class='text-danger'>Pincode required</p>";
        //     }
        //     if (empty($_POST['address'])) {
        //         $addressErr = "<p class='text-danger'>Address required</p>";
        //     }
    
        //     if(isset($_POST['placeOrderBtn'])){
        //         if($nameErr == "" && $phoneErr == "" && $emailErr == "" && $pinErr == "" && $addressErr == ""){
        //             echo "<script>location.href='placeOrder.php';</script>";
        //         }
        //     }
        // }
    
        //to get total price
        function getTotalPrice(){
            // global $db_con;
            include('connection.inc.php');
    
            $userId = $_SESSION['auth_user']['user_id'];
    
            $query = "SELECT c.id as cid, c.prod_id, c.prod_qty, p.id as pid, p.product_name, p.image, p.mrp FROM carts c, products p WHERE c.prod_id = p.id AND c.user_id = '$userId' ORDER BY c.id DESC";
    
            $query_run = mysqli_query($db_con, $query);
    
            return $query_run;
        }
    
        if(isset($_SESSION['user_login'])){
            if(isset($_POST['placeOrderBtn'])){
                $name = mysqli_real_escape_string($db_con, $_POST['fullname']);
                $phone = mysqli_real_escape_string($db_con, $_POST['phone']);
                $email = mysqli_real_escape_string($db_con, $_POST['email']);
                $pincode = mysqli_real_escape_string($db_con, $_POST['pincode']);
                $address = mysqli_real_escape_string($db_con, $_POST['address']);
                $payment_mode = mysqli_real_escape_string($db_con, $_POST['payment_mode']);
        
    
                //order_no
                $order_no = rand(11111,99999);
    
                //user_id
                $user_id = $_SESSION['auth_user']['user_id'];
        
                //total price
                $items = getTotalPrice();
                $toPay = 0;
                foreach($items as $citem){
                    $totalAmount = $citem['mrp']*$citem['prod_qty'];
                    $toPay = $toPay+$totalAmount;
                }
                
                $query = "INSERT INTO orders (order_no, user_id, name, email, phone, address, pincode, total_price, payment_mode) VALUES ('$order_no', '$user_id', '$name', '$email', '$phone', '$address', '$pincode', '$toPay', '$payment_mode')";
        
                $query_run = mysqli_query($db_con, $query);
        
                if($query_run){
                    $order_id = mysqli_insert_id($db_con);
        
                    foreach($items as $citem){
                        $prod_id = $citem['prod_id'];
                        $prod_qty = $citem['prod_qty'];
                        $price = $citem['mrp'];
        
                        //inserting order items
                        $insert_items_query = "INSERT INTO order_items (order_id, prod_id, qty, price) VALUES ('$order_id', '$prod_id', '$prod_qty', '$price')";
        
                        $insert_items_run = mysqli_query($db_con, $insert_items_query);
    
                        //to update quantities
                        $product_query = "SELECT * FROM products WHERE id='$prod_id' LIMIT 1";
                        $product_query_run = mysqli_query($db_con, $product_query);
    
                        $productData = mysqli_fetch_array($product_query_run);
                        $current_qty = $productData['quantity'];
    
                        $new_qty = $current_qty - $prod_qty;
    
                        $updatedQuery = "UPDATE products SET quantity='$new_qty' WHERE id='$prod_id'";
    
                        $updateQuery_run = mysqli_query($db_con, $updatedQuery);
                    }
        
                   //after placing the order the cart should get empty
                    $deleteCart = "DELETE FROM carts WHERE user_id='$user_id'";
                    $deleteCart_run = mysqli_query($db_con, $deleteCart);
        
                    $_SESSION['message'] = "Order Placed Successfully";

                    //to send email
                    if($_POST['payment_mode']=='COD'){

                        $user_id = $_SESSION['auth_user']['user_id'];

                        $queryOrder = "SELECT o.id as oid, o.order_no, o.user_id, oi.*, p.* FROM orders o, order_items oi, products p WHERE o.user_id='$user_id' AND oi.order_id=o.id AND p.id=oi.prod_id AND o.order_no='$order_no'";

                        $order_query_run = mysqli_query($db_con, $queryOrder);

                        echo "<p class='m-3 alert alert-success text-center'>Order Placed Successfully, with payment mode as Cash on Delivery</p>";
                        echo "<meta http-equiv=\"refresh\" content=\"3;url=index.php\"/>";

                        $emailTo = "bijavoy704@qianhost.com";
                        $sub = "Order Confirmed";
                        

                        $table = '<table style="border: 1px solid black; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>MRP</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>';

                        $toPay = 0;
                        while($item=mysqli_fetch_array($order_query_run)){
                            $totalAmount = $item['mrp']*$item['qty'];
                            $toPay = $toPay+$totalAmount;

                            $table .= '<tr>
                                    <td>'.$item['product_name'].'</td>
                                    <td>'.$item['qty'].'</td>
                                    <td>'.$item['price'].'</td>
                                    <td>'.$totalAmount.'</td>
                            </tr>';
                        }
                        $table .= '</tbody></table>';

                        $body = $_POST["email"]." Your Order has been successfully placed.
                        <h3>Order Details</h3>
                        <p>Order No - $order_no </p>".$table. "<br> Total Amount -".$toPay;
                        $headers = "From: chiragsonavale13@gmail.com";

                        $mail = new PHPMailer(true);
                        $mail->IsHTML(true);
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = '';
                        $mail->Password = '';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;


                        $mail->setFrom('chiragsonavale13@gmail.com');
                        $mail->addAddress($emailTo);
                        $mail->Subject = $sub;
                        $mail->Body = $body;
                        $mail->send();

                        die();
                    }
                    else if($_POST['payment_mode']=='Online'){
                        //header is not wroking so use this
                        echo "<p class='m-3 alert alert-success text-center'>Order Placed Successfully, with payment mode as Online Payment</p>";
                        echo "<meta http-equiv=\"refresh\" content=\"3;url=index.php\"/>";

                        $user_id = $_SESSION['auth_user']['user_id'];

                        $queryOrder = "SELECT o.id as oid, o.order_no, o.user_id, oi.*, p.* FROM orders o, order_items oi, products p WHERE o.user_id='$user_id' AND oi.order_id=o.id AND p.id=oi.prod_id AND o.order_no='$order_no'";

                        $order_query_run = mysqli_query($db_con, $queryOrder);
                        $emailTo = "bijavoy704@qianhost.com";
                        $sub = "Order Confirmed";
                        

                        $table = '<table style="border: 1px solid black; border-collapse: collapse;">
                        <thead>
                            <tr style="border: 1px solid black; border-collapse: collapse;">
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>MRP</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>';

                        $toPay = 0;
                        while($item=mysqli_fetch_array($order_query_run)){
                            $totalAmount = $item['mrp']*$item['qty'];
                            $toPay = $toPay+$totalAmount;

                            $table .= '<tr style="border: 1px solid black; border-collapse: collapse;">
                                    <td>'.$item['product_name'].'</td>
                                    <td>'.$item['qty'].'</td>
                                    <td>'.$item['price'].'</td>
                                    <td>'.$totalAmount.'</td>
                            </tr>';
                        }
                        $table .= '</tbody></table>';

                        $body = $_POST["email"]." Your Order has been successfully placed.
                        <h3>Order Details</h3>
                        <p>Order No - $order_no </p>".$table. "<br> Total Amount -".$toPay;
                        $headers = "From: Ecom project";

                        $mail = new PHPMailer(true);
                        $mail->IsHTML(true);
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = '';
                        $mail->Password = '';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;


                        $mail->setFrom('chiragsonavale13@gmail.com');
                        $mail->addAddress($emailTo);
                        $mail->Subject = $sub;
                        $mail->Body = $body;
                        $mail->send();
                        die();
                    }
        
                }
            }
        }
        else{
            echo "<script>location.href='index.php';</script>";
        }
    

    
?>

<div class="py-3 py-md-4 checkout">
        <div class="container">
            <h4>Checkout</h4>
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

                            
                            

                                $items = getCartItems();
                                //to check whether products are available
                                if(mysqli_num_rows($items)<1){
                                    ?>
                                        <p class="alert alert-warning text-center">No products selected</p>
                                        <?php die();?>
                                    <?php
                                        
                                    }
                                else if(mysqli_num_rows($items) > 0){

                                
                                // loop to get all items of particular user
                                
                                $toPay = 0;
                                foreach($items as $citem){
                                    $totalAmount = $citem['mrp']*$citem['prod_qty'];
                                    $toPay = $toPay+$totalAmount;
                                ?>
                                
                                <div class="cart-item">
                                            <div class="row">
                                                <div class="col-md-6 my-auto">
                                                    <a href="">
                                                        <label class="product-name">
                                                            <img src="./media/products/<?php echo $citem['image']; ?>" class="w-25" alt="Img">
            
                                                            <?php echo "<span style='margin-left: 30px; color: black; font-size: 17px;'>".$citem['product_name']."</span>"; ?>
                                                        </label>
            
                                                    
                                                </a>
                                            </div>
                                            <div class="col-md-2 my-auto">
                                                <label class="price"><?php echo "Rs.".$citem['mrp'];?></label>
                                            </div>
                                            <div class="col-md-2 col-7 my-auto" style="padding-left: 38px;">
                                                    
                                                    <label><?php echo $citem['prod_qty']; ?></label>
                                            </div>
                                            <div class="col-md-2 col-5 my-auto" style="padding-left: 38px;">
                                                <label><?php echo "Rs.".$totalAmount; ?></label>
                                            </div>
                                        </div>
                                    </div>
                           

                        <?php
                        }}
                        ?>
                        </div>
                        <hr>
                        

                        <h4 style="color: #87d142;">
                            Total Price :
                            <span class="float-end" style="margin-right: 80px;">Rs.<?php if(isset($_GET['quantity']) && $_GET['quantity'] != '' && $_GET['product_id'] != ''){ echo $row['mrp'];} else {echo $toPay;}; ?></span>
                        </h4>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 style="color: #87d142;">
                            Basic Information
                        </h4>
                        <hr>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name</label>
                                    <input type="text" name="fullname" class="form-control" placeholder="Enter Full Name"/>
                                    <!-- <div><?php echo $nameErr; ?></div> -->
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number" required/>
                                    <!-- <div><?php echo $phoneErr; ?></div> -->
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Email Address" required/>
                                    <!-- <div><?php echo $emailErr; ?></div> -->
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Pin-code (Zip-code)</label>
                                    <input type="number" name="pincode" class="form-control" placeholder="Enter Pin-code" required/>
                                    <!-- <div><?php echo $pinErr; ?></div> -->
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Full Address</label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                    <!-- <div><?php echo $addressErr; ?></div> -->
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="me-2">Select Payment Mode: </label>
                                    <input type="radio" name="payment_mode" checked value="COD"> <span>Cash on Delivery</span>
                                    <input class="ms-2" type="radio" name="payment_mode" value="Online"> <span>Online Payment</span>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-md-flex align-items-start">
                                        <a href=""><button name="placeOrderBtn" type="submit" class="btn btn-outline-success" id="payment">Place Order</button></a>

                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

<?php
    require('footer.php');

?>

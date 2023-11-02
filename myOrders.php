
<?php
    require('connection.inc.php');
    require('header.php');


    function getOrders(){
        global $db_con;
        $userId = $_SESSION['auth_user']['user_id'];

        $query = "SELECT * FROM orders WHERE user_id='$userId' ORDER BY id DESC";
        $query_run = mysqli_query($db_con, $query);
        return $query_run;
    }

    //to delete the order
    if(isset($_GET['type'])){
        $type = $_GET['type'];
        $id = mysqli_real_escape_string($db_con, $_GET['id']);

        //to add quantity back to normal
        
        $order_query = "SELECT o.*, oi.*, p.id as pid, p.* FROM orders o, order_items oi, products p WHERE oi.order_id='$id' AND oi.order_id=o.id AND p.id=oi.prod_id";

        $order_query_run = mysqli_query($db_con, $order_query);
        // echo "something";

        
        foreach($order_query_run as $item){
            $updated_quantity = $item['quantity'] + $item['qty'];
            echo $item['quantity'];
            echo $item['qty'];
            $pid = $item['pid'];
            $update_query = "UPDATE products SET quantity='$updated_quantity' WHERE id=$pid;";
            mysqli_query($db_con, $update_query);
        }

        if($type=='delete'){
            $query_delete = "DELETE FROM orders WHERE id='$id'";
            mysqli_query($db_con, $query_delete);
            echo "to check delete";
        }
    }

    
?>

<div class="py-5 d-flex align-items-center justify-content-center">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order No</th>
                            <th>Total Price</th>
                            <th>Ordered At</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $orders = getOrders();

                            if(mysqli_num_rows($orders) > 0){
                                foreach($orders as $item){
                        ?>
                            <tr>
                                <td><?php echo $item['id']; ?></td>
                                <td><?php echo $item['order_no']; ?></td>
                                <td><?php echo $item['total_price']; ?></td>
                                <td><?php echo $item['created_at']; ?></td>
                                <td>
                                    <a href="viewOrders.php?order=<?php echo $item['order_no'];?>" class="btn btn-outline-success me-2">View details</a>
                                    <?php
                                        if($item['order_status']==0){
                                    ?>
                                            <a onclick='return checkDelete();' href="?type=delete&id=<?php echo $item['id']; ?>"><button class='btn btn-outline-danger' >Cancel Order</button></a>
                                    <?php
                                        }
                                    ?>
                                    
                                </td>
                            </tr>
                    </tbody>
                    <?php
                                }
                            }
                            else{
                                echo "<p class='alert alert-danger'>No Orders available</p>";
                            }
                        ?>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function checkDelete(){
        console.log("Hello");
        return confirm("Are you sure, you want to cancel the order?");
    }
</script>

    <?php 
    require('footer.php'); 
    ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
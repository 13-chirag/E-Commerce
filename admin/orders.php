<?php
    require('header.inc.php');


    //to delete the contact form
    if(isset($_GET['type']) && $_GET['type'] != ''){
        $type = mysqli_real_escape_string($db_con, $_GET['type']);

        //to delete the record
        if(isset($_GET['id'])){
            if($type == 'delete'){
                $id = mysqli_real_escape_string($db_con, $_GET['id']);
    
                $delete_query = "DELETE FROM orders WHERE id='$id' AND status='0'";
                $result = mysqli_query($db_con, $delete_query);

                if($result){
                    header('location:orders.php');
                }
            }
        }
    }

    //to get orders 
    $query = "SELECT * FROM orders";

    if(isset($_GET['status'])){
        $status = $_GET['status'];
        $query .= " WHERE order_status='$status'";
    }
    $result = mysqli_query($db_con, $query);
?>

<div class="mt-3 container border p-2 rounded">
    <h4 class="d-inline p-2">Orders</h4>

    <div class="dropdown d-inline float-end">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Filter Orders
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?status=1">Completed Orders</a></li>
            <li><a class="dropdown-item" href="?status=0">Under Process</a></li>
            <li><a class="dropdown-item" href="?">All</a></li>
        </ul>
    </div>
</div>
<div class="container card">
    <div class="table-stats order-table ov-h">
        <table class="table ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Order No</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Order Status</th>
                    <th>View Order</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 while($row = mysqli_fetch_assoc($result)) {?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['order_no']; ?></td>
                    <td><?php echo "Rs.".$row['total_price']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php if($row['order_status'] == 0){
                        echo 'Under Process';
                    }
                    if($row['order_status'] == 1){
                        echo 'Completed';
                    } ?></td>
                    <td>
                        <a href="viewOrdersA.php?order=<?php echo $row['order_no'];?>" class="btn btn-outline-success me-2">View details</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php
require('footer.inc.php');
?>




<?php
    require('header.inc.php');

    $categories_id="";

    //to update the status of products
    if(isset($_GET['type']) && $_GET['type'] != ''){
        $type = mysqli_real_escape_string($db_con, $_GET['type']);


        if($type == 'status'){
            $operation = mysqli_real_escape_string($db_con, $_GET['operation']);
            $id = mysqli_real_escape_string($db_con, $_GET['id']);

            if($operation == 'active'){
                $status = '1';
            }
            else if($operation == 'inactive'){
                $status = '0';
            }

            //update status query
            $update_status = "UPDATE products SET status='$status' WHERE id='$id'";
            mysqli_query($db_con, $update_status);

        }

        //to edit 

        //to delete the record
        if($type == 'delete'){
            $id = mysqli_real_escape_string($db_con, $_GET['id']);

            $delete_query = "DELETE FROM products WHERE id='$id'";
            $res = mysqli_query($db_con, $delete_query);

            if($res){
                header('location:products.php');
            }
        }

        
    }


    // to bring products at admin side
   
    //here sorting wrt to category or else 
    
    if(isset($_GET['type']) && $_GET['type'] == 'category'){


        //to list the products based on category id's
        if($type == 'category'){
            $id = mysqli_real_escape_string($db_con, $_GET['id']);

            $queryP = "SELECT products.*, categories.categories FROM products, categories WHERE products.categories_id=categories.id AND categories_id='$id'";
            $result = mysqli_query($db_con, $queryP);

        }
    }
    else{
        $query = "SELECT products.*, categories.categories FROM products, categories WHERE products.categories_id = categories.id ORDER BY products.id DESC";
        $result = mysqli_query($db_con, $query);
    }
    
?>


<div class="container p-2 mt-3 card d-flex flex-row align-items-centers">
    <h4>Products</h4>
    <a style="margin-left: auto;" href="manage_products.php" class="btn btn-success">Add Products</a>

</div>
<div class="container card">
        <table class="table card-body">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categories</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>MRP</th>
                    <th>Quantity</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 while($row = mysqli_fetch_assoc($result)) {?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['categories']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><img width="60px" height="60px" src="../media/products/<?php echo $row['image']; ?>"/></td>
                    <td><?php echo "Rs.".$row['mrp']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>
                        <?php
                            if($row['status']==1){
                                echo "<a class='btn btn-outline-success' href='?type=status&operation=inactive&id=".$row['id']."'>Active</a>&nbsp";
                            }
                            else{
                                echo "<a class='btn btn-outline-warning' href='?type=status&operation=active&id=".$row['id']."'>Inactive</a>&nbsp";
                            }

                             //edit category option
                            echo "&nbsp<a class='btn btn-outline-primary' href='manage_products.php?id=".$row['id']."'>Edit</a>&nbsp";

                            // delete category option
                            echo "<a onclick='return checkDelete();' class='btn btn-outline-danger' href='?type=delete&id=".$row['id']."'>Delete</a>";

                         ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</div>

<?php
require('footer.inc.php');
?>
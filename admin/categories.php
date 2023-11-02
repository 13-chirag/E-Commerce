<?php
    require('header.inc.php');

    //to update the status of categories
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
            $update_status = "UPDATE categories SET status='$status' WHERE id='$id'";
            mysqli_query($db_con, $update_status);

        }

        //to edit 

        //to delete the record
        if($type == 'delete'){
            $id = mysqli_real_escape_string($db_con, $_GET['id']);

            $delete_query = "DELETE FROM categories WHERE id='$id'";
            $res = mysqli_query($db_con, $delete_query);

            if($res){
                header('location:categories.php');
            }
        }
    }



    //to bring categories at admin side

    $query = "SELECT * FROM categories ORDER BY id DESC";
    $result = mysqli_query($db_con, $query);
?>

<div class="container p-2 mt-3 card col-8 d-flex flex-row align-items-center">
    <h4>Categories</h4>
    <a style="margin-left: auto;" href="manage_categories.php" class="btn btn-success ">Add Categories</a>
</div>
<div class="container card col-8">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categories</th>
                    <th>Image</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 while($row = mysqli_fetch_assoc($result)) {?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo "<a class='' href='products.php?type=category&id=".$row['id']."'>".$row['categories']."</a> "?></td>
                    <td><img width="60px" height="60px" src="../media/categories/<?php echo $row['image']; ?>"/></td>
                    <td>
                        <?php
                            if($row['status']==1){
                                echo "<a class='btn btn-outline-success' href='?type=status&operation=inactive&id=".$row['id']."'>Active</a>&nbsp";
                            }
                            else{
                                echo "<a class='btn btn-outline-warning' href='?type=status&operation=active&id=".$row['id']."'>Inactive</a>&nbsp";
                            }

                             //edit category option
                            echo "&nbsp<a class='btn btn-outline-primary' href='manage_categories.php?id=".$row['id']."'>Edit</a>&nbsp";

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
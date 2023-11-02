<?php
    require('header.inc.php');

    //to delete the contact form
    if(isset($_GET['type']) && $_GET['type'] != ''){
        $type = mysqli_real_escape_string($db_con, $_GET['type']);

        //to delete the record
        if(isset($_GET['id'])){
            if($type == 'delete'){
                $id = mysqli_real_escape_string($db_con, $_GET['id']);
    
                $delete_query = "DELETE FROM contact_form WHERE id='$id'";
                $result = mysqli_query($db_con, $delete_query);

                if($result){
                    header('location:contact_us.php');
                }
            }
        }
    }



    //to bring contact forms at admin side

    $query = "SELECT * FROM contact_form";
    $result = mysqli_query($db_con, $query);
?>

<div class="container card p-2 mt-3">
    <h4>Contact Us</h4>
</div>
<div class="container card">
    <div class="table-stats order-table ov-h">
        <table class="table ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 while($row = mysqli_fetch_assoc($result)) {?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['comment']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <?php
                            // delete category option
                            echo "<a onclick = 'return checkDelete()' id='delete' class='btn btn-outline-danger' href='?type=delete&id=".$row['id']."'>Delete</a>";

                         ?>
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




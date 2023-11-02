<?php
    require('header.inc.php');


    $categories_id = "";
    $product_name = "";
    $mrp="";
    $quantity = "";
    $image = "";
    $short_description="";
    $description = "";
    $status = "";
    $err_msg="";

    //to edit category==> after clicking on edit
    if(isset($_GET['id']) && $_GET['id'] != ""){
        $id = mysqli_real_escape_string($db_con, $_GET['id']);
        $edit_query = "SELECT * FROM products WHERE id='$id'";
        $result = mysqli_query($db_con, $edit_query);
        
        //1.to check if the id in url does not exists($id=dsjd), if not then redirect to categories page
        $check = mysqli_num_rows($result);
        if($check>0){
            $row = mysqli_fetch_assoc($result);
            $categories_id = $row['categories_id'];
            $product_name = $row['product_name'];
            $mrp = $row['mrp'];
            $quantity = $row['quantity'];
            $short_description = $row['short_desc'];
            $description = $row['description'];
            $status = $row['status'];
        }
        else{
            header('location:products.php');
            die();
        }
    }

    //to insert new product
    if(isset($_POST['submit'])){
        $categories_id = mysqli_real_escape_string($db_con, $_POST['categories_id']);
        $categories_id = trim($categories_id);

        $product_name = mysqli_real_escape_string($db_con, $_POST['product_name']);
        $product_name = trim($product_name);

        $mrp = mysqli_real_escape_string($db_con, $_POST['mrp']);
        $mrp = trim($mrp);

        $quantity = mysqli_real_escape_string($db_con, $_POST['quantity']);
        $quantity = trim($quantity);


        $short_description = mysqli_real_escape_string($db_con, $_POST['short_description']);
        $short_description = trim($short_description);

        $description = mysqli_real_escape_string($db_con, $_POST['description']);
        $description = trim($description);
        
        $status = mysqli_real_escape_string($db_con, $_POST['status']);

        //2. to check if the product already exists or not
        $check_query = "SELECT * FROM products WHERE product_name='$product_name'";
        $result = mysqli_query($db_con, $check_query);
        $check = mysqli_num_rows($result);
        if($check>0){
            //3. if the category you are editing, you do not change category_name and submit then also it will give error,thus to avoid this===> (if you keep category name same and submit while editing)
            if(isset($_GET['id']) && $_GET['id'] != ''){

                $getData = mysqli_fetch_assoc($result);
                if($id==$getData['id']){
                    
                }
                else{
                    $err_msg = "<p class='text-danger'>This product already exists!</p>";
                }
            }
            else{
                $err_msg = "<p class='text-danger'>This product already exists!</p>";
            }
        }
        
        if($err_msg==""){
            if(isset($_GET['id']) && $_GET['id'] != ""){

                //to keep image while editing
                if($_FILES['image']['name'] != ''){
                    $image = rand(111111111, 999999999).'_'.$_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../media/products/'.$image);

                    $update_query = "UPDATE products SET categories_id='$categories_id', product_name='$product_name', mrp='$mrp', quantity='$quantity', short_desc='$short_description', description='$description', status='$status', image='$image' WHERE id='$id'";
                }
                else{
                    $update_query = "UPDATE products SET categories_id='$categories_id', product_name='$product_name', mrp='$mrp', quantity='$quantity', short_desc='$short_description', description='$description', status='$status' WHERE id='$id'";
                }
                mysqli_query($db_con, $update_query);
            }
            else{
                $image = rand(111111111, 999999999).'_'.$_FILES['image']['name'];

                move_uploaded_file($_FILES['image']['tmp_name'], '../media/products/'.$image);


                $insert_category = "INSERT INTO products(categories_id, product_name, mrp, image,  quantity, short_desc, description, status) VALUES('$categories_id', '$product_name', '$mrp', '$image', '$quantity', '$short_description', '$description','$status')";
                mysqli_query($db_con, $insert_category);
            }
            header('location:products.php');
            die();
        }

    }

    
?>

<div class="card container">
    <div class="card-body">
        <div class="card-header"><strong>Products Form</strong></div>
        <!-- //for image  -->
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="" class="form-control-label">Categories</label>
                <div class="dropdown">
                    <select id="" class="form-select" name="categories_id">
                        <option value="" selected disabled>Select Categories</option>
                        <?php

                            $q = "SELECT id,categories FROM categories";
                            $res = mysqli_query($db_con, $q);

                            while($row = mysqli_fetch_assoc($res)){

                                //to display categories while editing products
                                if($row['id']==$categories_id){
                                    echo "<option selected value=" .$row['id']. ">" .$row['categories']. "</option>";
                                }
                                else{
                                    echo "<option value=" .$row['id']. ">" .$row['categories']. "</option>";
                                }

                            } 

                        ?>
                    </select>
                </div>

                <label class="form-control-label">Product Name</label>
                <input type="text" name="product_name" placeholder="Enter product's name" class="form-control" value="<?php echo $product_name; ?>">

                <label class="form-control-label">MRP</label>
                <input type="text" name="mrp" placeholder="Enter product's mrp" class="form-control" value="<?php echo $mrp; ?>">


                <label class="form-control-label">Quantity</label>
                <input type="text" name="quantity" placeholder="Enter product's quantity" class="form-control" value="<?php echo $quantity; ?>">

                <label class="form-control-label">Image</label>
                <input type="file" name="image" class="form-control" value="<?php echo $image; ?>">

                <label class="form-control-label">short_description</label>
                <textarea name="short_description" placeholder="Enter product's short description" class="form-control"><?php echo $short_description; ?></textarea>

                <label class="form-control-label">Description</label>
                <textarea name="description" placeholder="Enter product's description" class="form-control"><?php echo $description; ?></textarea>

                <label for="" class="form-control-label">Status</label>
                <div class="dropdown">
                <select id="" class="form-select" name="status">
                    <option value="" selected disabled>Choose Status</option>
                    <option value="1" <?php if(isset($status) && $status=="1") echo "selected"; ?> name="active">Active</option>
                    <option value="0" <?php if(isset($status) && $status=="0") echo "selected"; ?>>Inactive</option>
                </select>
                </div>
            </div>
            <input type="submit" name="submit" class="btn btn-success mt-2" value="Add Product">
            <div>
                <?php echo $err_msg; ?>
            </div>
        </form>
    </div>

</div>
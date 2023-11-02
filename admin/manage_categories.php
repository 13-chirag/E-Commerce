<?php
    require('header.inc.php');

    $category = "";
    $status = "";
    $image = "";
    $err_msg="";

    //to edit category==> after clicking on edit
    if(isset($_GET['id']) && $_GET['id'] != ""){
        $id = mysqli_real_escape_string($db_con, $_GET['id']);
        $edit_query = "SELECT * FROM categories WHERE id='$id'";
        $result = mysqli_query($db_con, $edit_query);
        
        //1.to check if the id in url does not exists($id=dsjd), if not then redirect to categories page
        $check = mysqli_num_rows($result);
        if($check>0){
            $row = mysqli_fetch_assoc($result);
            $category = $row['categories'];
            $status = $row['status'];
        }
        else{
            header('location:categories.php');
            die();
        }
    }

    //to insert new category
    if(isset($_POST['submit'])){
        $categories = mysqli_real_escape_string($db_con, $_POST['categories']);
        $categories = trim($categories);
        $status = mysqli_real_escape_string($db_con, $_POST['status']);

        //2. to check if the category already exists or not
        $check_query = "SELECT * FROM categories WHERE categories='$categories'";
        $result = mysqli_query($db_con, $check_query);
        $check = mysqli_num_rows($result);
        if($check>0){
            //3. if the category you are editing, you do not change category_name and submit then also it will give error,thus to avoid this===> (if you keep category name same and submit while editing)
            if(isset($_GET['id']) && $_GET['id'] != ''){
                $getData = mysqli_fetch_assoc($result);
                if($id==$getData['id']){
                    
                }
                else{
                    $err_msg = "<p class='text-danger'>This category already exists!</p>";
                }
            }
            else{
                $err_msg = "<p class='text-danger'>This category already exists!</p>";
            }
        }
        
        if($err_msg==""){
            if(isset($_GET['id']) && $_GET['id'] != ""){

                if($_FILES['image']['name'] != ''){
                    $image = rand(111111111, 999999999).'_'.$_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../media/categories/'.$image);

                    $update_query = "UPDATE categories SET categories='$categories', image='$image', status='$status' WHERE id='$id'";
                    mysqli_query($db_con, $update_query);
                }
                else{
                    $update_query = "UPDATE categories SET categories='$categories', status='$status' WHERE id='$id'";
                    mysqli_query($db_con, $update_query);
                }

            }
            else{

                $image = rand(111111111, 999999999).'_'.$_FILES['image']['name'];

                move_uploaded_file($_FILES['image']['tmp_name'], '../media/categories/'.$image);

                $insert_category = "INSERT INTO categories(categories, image, status) VALUES('$categories','$image', '$status')";
                mysqli_query($db_con, $insert_category);
            }
            header('location:categories.php');
            die();
        }

    }

    
?>

<div class="card container">
    <div class="card-body">
        <div class="card-header"><strong>Categories Form</strong></div>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-control-label">Categories</label>
                <input type="text" name="categories" placeholder="Enter categories name" class="form-control" value="<?php echo $category; ?>">

                <!-- //image -->
                <label class="form-control-label">Image</label>
                <input type="file" name="image" class="form-control" value="<?php echo $image; ?>">

                <label for="" class="form-control-label">Status</label>
                <div class="dropdown">
                <select id="" class="form-select" name="status">
                    <option value="" selected disabled>Choose Status</option>
                    <option value="1" <?php if(isset($status) && $status=="1") echo "selected"; ?> name="active">Active</option>
                    <option value="0" <?php if(isset($status) && $status=="0") echo "selected"; ?>>Inactive</option>
                </select>
                </div>
            </div>
            <input type="submit" name="submit" class="btn btn-success mt-3" value="Add Category">
            <div>
                <?php echo $err_msg; ?>
            </div>
        </form>
    </div>

</div>
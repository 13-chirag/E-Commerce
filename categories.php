<?php
require('header.php');

if(isset($_GET['id'])){
    $cat_id = mysqli_real_escape_string($db_con, $_GET['id']);
    $cat_name = mysqli_real_escape_string($db_con, $_GET['category']);
}

//type and limit we will pass as arguments
function get_product($cat_id='', $product_id=''){
    // include("connection.inc.php");
    global $db_con;
    //pagination
    $per_page=8;
    $start=0;
    if(isset($_GET['start'])){
        $start = $_GET['start'];
        $start--;
        $start=$start*$per_page;
    }
    
    $query = "SELECT * FROM products WHERE status=1";

    if($product_id != ''){
        $query .= " AND id = $product_id limit $start, $per_page";
    }
    if($cat_id != ''){
        $query .= " AND categories_id = $cat_id ORDER BY id DESC limit $start, $per_page";
    }
    else{
        $query .= " ORDER BY id DESC limit $start, $per_page";
    }
    $res = mysqli_query($db_con, $query);

    $data = array();
    while($row = mysqli_fetch_assoc($res)){
        $data[] = $row;
    }
    return $data;
}

    //to get available categories
    $queryCat = "SELECT * FROM categories WHERE status=1";
    $res = mysqli_query($db_con, $queryCat);

    // to create array to bring all categories available in db
    $categ_arr = array();
    while($row = mysqli_fetch_assoc($res)){
        $categ_arr[] = $row;
    }

    //pagination
    $per_page = 8;
    $queryPro = "SELECT * FROM products WHERE status=1";
    
    if(isset($_GET['category'])){
        $id = $_GET['id'];
        $queryPro .= " AND categories_id=$id";
    }
    $record = mysqli_num_rows(mysqli_query($db_con, $queryPro));
    $pagi = ceil($record/$per_page);

    // $queryPagi = "SELECT * FROM products WHERE status=1 limit 0,$per_page";
    // $resultPagi = mysqli_query($db_con, $queryPagi);

?>

<div id="new_products" class="py-3 py-md-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if(isset($_GET['category'])){
                            $cat_name = mysqli_real_escape_string($db_con, $_GET['category']);
                    ?>
                        <h4 class="mb-4"><?php echo $cat_name; ?></h4>
                    <?php
                        }else{
                    ?>
                            <h4 class="mb-4">All Products</h4>
                    <?php
                        }
                    ?>
                    
                </div>

                <?php
                    if(isset($_GET['id'])){
                        $get_product = get_product($cat_id);
                    }else{
                        $get_product = get_product();
                    }
                    // print_r($get_product);
                    foreach($get_product as $list){
                            if(count($get_product)>0){
                                if($list['quantity']<1){
                                    ?>
                                    <div class="col-6 col-md-3">
                                        <div class="category-card rounded-2">
                                            <a href="">
                                                <div class="category-card-img">
                                                    <img height="190px" src="./images/soldOut.png" class="w-100" alt="sold out">
                                                </div>
                                                <div class="category-card-body">
                                                    <h5><?php echo $list['product_name']; ?></h5>
                                                    <h6><h5>&#8377;<?php echo $list['mrp']; ?></h6>
                                                </div>
                                            </a>
                                            <div class="category-card-body text-center">
                                                    <label for="" style="padding-bottom: 15px;">Out of Stock</label>
                                            </div>
                                        </div>
                                    </div>
                                <?php }else{?>

                                
                            <div class="col-6 col-md-3">
                            <div class="category-card rounded-2">
                                <a href="products.php?id=<?php echo $list['id']; ?>">
                                    <div class="category-card-img">
                                        <img height="190px" src="<?php echo './media/products/'.$list['image']; ?>" class="w-100" alt="dairy">
                                    </div>
                                    <div class="category-card-body">
                                        <h5><?php echo $list['product_name']; ?></h5>
                                        <h6><h5>&#8377;<?php echo $list['mrp']; ?></h6>
                                    </div>
                                </a>
                                <div class="category-card-body text-center">
                                        <button class="btn btn-primary addToCartBtn" value="<?php echo $list['id']; ?>">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                        <?php
                            }}else{
                                echo "No Products available in this category!";
                            }
                        ?>

                <?php } ?>
            </div>
            <div>
                <?php  
                    if(isset($_GET['category'])){ 
                ?>
                        <ul class="pagination mt-30 d-flex justify-content-center">
                            <?php
                            
                                for($i=1; $i<=$pagi;$i++){
                            ?>
                            <li class="page-item"><a href="?category=<?php echo $_GET['category']; ?>&id=<?php echo $_GET['id']; ?>&start=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                <?php
                    }
                    else{
                ?>
                <ul class="pagination mt-30 d-flex justify-content-center">
                    <?php
                    
                        for($i=1; $i<=$pagi;$i++){
                    ?>
                    <li class="page-item"><a href="?start=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
                <?php }?>
            </div>
        </div>
    </div>

<?php
    require("footer.php");
?>
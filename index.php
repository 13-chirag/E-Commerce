<?php
require('header.php');

//type and limit we will pass as arguments
function get_product($type='', $limit=''){
    include("connection.inc.php");
    $query = "SELECT * FROM products WHERE status=1";

    if($type == "latest"){
        $query .= " ORDER BY id DESC";
    }
    if($limit !=''){
        $query .= " LIMIT $limit";
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



?>


    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./images/bg1.jpg" height="500px" class="d-block w-100" alt="slider">
                <div class="carousel-caption d-none d-md-block">
                    <div class="custom-carousel-content">
                        <h1>
                            <span>Best Grocery Products</span>
                            Buy Online  &amp; Eat Fresh 
                        </h1>
                        <p>
                            Discover the freshest farm-to-table experience on our website, where we source delicious, locally grown vegetables directly from dedicated farmers. 
                        </p>
                        <div>
                            <a href="./categories.php" class="btn btn-slider">
                                Get Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/bg2.jpg" height="500px" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <div class="custom-carousel-content">
                        <h1>
                            <span>Best Grocery Products</span>
                            Buy Online &amp; Eat Fresh 
                        </h1>
                        <p>
                            Discover the freshest farm-to-table experience on our website, where we source delicious, locally grown vegetables directly from dedicated farmers.
                        </p>
                        <div>
                            <a href="#" class="btn btn-slider">
                                Get Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="./images/bg1.jpg" height="500px" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <div class="custom-carousel-content">
                        <h1>
                            <span>Best Grocery Products </span>
                            Buy Online &amp; Eat Fresh 
                        </h1>
                        <p>
                            Discover the freshest farm-to-table experience on our website, where we source delicious, locally grown vegetables directly from dedicated farmers.
                        </p>
                        <div>
                            <a href="./categories.php" class="btn btn-slider">
                                Get Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- Categorise div start here -->

    <div id="categories" class="pt-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-4">Our Categories</h4>
                </div>
                <?php
                    foreach($categ_arr as $list){
                ?>
                <div class="col-6 col-md-3">
                    <div id="hover-category" class="category-card rounded-2">
                        <a href="categories.php?category=<?php echo $list['categories']; ?>&id=<?php echo $list['id']; ?>">
                            <div class="category-card-img">
                                <img height="190px" src="<?php echo './media/categories/'.$list['image']; ?>" class="w-100" alt="dairy">
                            </div>
                            <div class="category-card-body">
                                <h5><?php echo $list['categories']; ?></h5>
                            </div>
                        </a>
                    </div>
                    
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>

    <!-- New products -->
    <div id="new_products" class="py-3 py-md-2 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-4">New Products</h4>
                </div>

                <?php
                    $get_product = get_product('latest', 4);
                    // print_r($get_product);
                    foreach($get_product as $list){
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
                <?php
                        }else{
                ?>
                
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

                <?php }} ?>
            </div>
        </div>
    </div>


<?php
    require("footer.php");
?>
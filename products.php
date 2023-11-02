<?php
require('header.php');
$product_id = mysqli_real_escape_string($db_con, $_GET['id']);

//type and limit we will pass as arguments
function get_product($product_id=''){
    include("connection.inc.php");
    $query = "SELECT * FROM products WHERE status=1";

    if($product_id != ''){
        $query .= " AND id = $product_id";
    }
    $res = mysqli_query($db_con, $query);

    $data = array();
    while($row = mysqli_fetch_assoc($res)){
        $data[] = $row;
    }
    return $data;
}

    $get_product = get_product($product_id); 

    //to get available categories
    $queryCat = "SELECT * FROM categories WHERE status=1";
    $res = mysqli_query($db_con, $queryCat);

    // to create array to bring all categories available in db
    $categ_arr = array();
    while($row = mysqli_fetch_assoc($res)){
        $categ_arr[] = $row;
    }

?>

<div class="py-3 py-md-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mt-3">
                    <div class="bg-white border">
                        <img src="./media/products/<?php echo $get_product['0']['image']; ?>" class="w-75" alt="Img">
                    </div>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="product-view">
                        <h4 class="product-name">
                            <?php echo $get_product['0']['product_name']; ?>
                        </h4>
                        <hr>
                        <div>
                            <span class="selling-price">&#8377;<?php echo $get_product['0']['mrp']; ?></span>
                        </div>
                        <div class="mt-2">
                            <div class="input-group" style="width: 180px;">
                                <label for="" class="form-labe mt-2">Quantity:</label>

                                <input value="<?php echo ""; ?>" min="1" max="<?php echo $get_product['0']['quantity']; ?>"  style="margin-left: 5px;" class="form-control input_quantity text-start" type="number" value="1" onKeyDown="return false">
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href=""> <button class="btn btn1 addToCartBtn" value="<?php echo $get_product['0']['id']; ?>"><i class="fa fa-shopping-cart"></i> Add To Cart</button></a>
                        </div>
                        <div class="mt-3">
                            <h5 class="mb-0">Small Description</h5>
                            <p>
                                <?php echo $get_product['0']['short_desc']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h4>Description</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                <?php echo $get_product['0']['description']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    require("footer.php");
?>
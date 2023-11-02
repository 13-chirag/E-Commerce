$(document).ready(function(){

    
    // on add to cart button click
    $(document).on('click', '.addToCartBtn', function(e){
        e.preventDefault();

        if($(".input_quantity").val() > 0){
            var quantity = $(".input_quantity").val();
        }
        else{
            var quantity = 1;
        }
        

        var product_id = $(this).val();

        // alert(product_id);
        // alert(quantity);

        //ajax call
        $.ajax({
            method: "POST",
            url: "handleCart.php",
            data: {
                "product_id": product_id,
                "quantity": quantity,
                "scope": "add"
            },
            success: function (response){
                console.log(response);
                    if(response == "already exists"){
                        alert("Product already in the cart!");
                    }
                    if(response == "productAddedToCart"){
                        alert("Product added to the cart");
                        window.location.reload();
                    }
                }
            });
    });

    //Update quantity
    $(document).on('click', '.updateQty', function(){

        var quantity = $(".input_quantity").val();

        var product_id = $(this).val();

        // to update in database
        $.ajax({
            method: "POST",
            url: "handleCart.php",
            data: {
                "product_id": product_id,
                "quantity": quantity,
                "scope": "update"
            },
            success: function (response){
                    if(response == "quantity updated"){
                        // alert("Product quantity Uupdated!");
                    }
                }
            });
    })


    //Remove added product
    $(document).on('click', '.deleteItem', function(){
        var cart_id = $(this).val();
        // alert(cart_id);

        // to remove item in database
        $.ajax({
            method: "POST",
            url: "handleCart.php",
            data: {
                "cart_id": cart_id,
                "scope": "remove"
            },
            success: function (response){
                    if(response == "item deleted"){
                        alert("Product removed from cart!");
                        window.location.reload();
                    }
                }
            });
    })

});
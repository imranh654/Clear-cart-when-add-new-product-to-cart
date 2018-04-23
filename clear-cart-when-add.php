<?php
/**
 * Hook: Empty cart before adding a new product to cart WITHOUT throwing woocommerce_cart_is_empty
 */
add_action ('woocommerce_add_to_cart', 'lenura_empty_cart_before_add', 0);
function lenura_empty_cart_before_add() {
    global $woocommerce;
    // Get 'product_id' and 'quantity' for the current woocommerce_add_to_cart operation
    if (isset($_GET["add-to-cart"])) {
        $prodId = (int)$_GET["add-to-cart"];
    } else if (isset($_POST["add-to-cart"])) {
        $prodId = (int)$_POST["add-to-cart"];
    } else {
        $prodId = null;
    }
    if (isset($_GET["quantity"])) {
        $prodQty = (int)$_GET["quantity"] ;
    } else if (isset($_POST["quantity"])) {
        $prodQty = (int)$_POST["quantity"];
    } else {
        $prodQty = 1;
    }
    error_log('prodID: ' . $prodId); // FIXME
    error_log('prodQty: ' . $prodQty); // FIXME
    // If cart is empty
    if ($woocommerce->cart->get_cart_contents_count() == 0) {
        // Simply add the product (nothing to do here)
    // If cart is NOT empty
    } else {
    
        $cartQty = $woocommerce->cart->get_cart_item_quantities();
        $cartItems = $woocommerce->cart->cart_contents;
        // Check if desired product is in cart already
        if (array_key_exists($prodId,$cartQty)) {
            // Then first adjust its quantity
            foreach ($cartItems as $k => $v) {
                if ($cartItems[$k]['product_id'] == $prodId) {
                    $woocommerce->cart->set_quantity($k,$prodQty);
                }
            }
            // And only after that, set other products to zero quantity
            foreach ($cartItems as $k => $v) {
                if ($cartItems[$k]['product_id'] != $prodId) {
                    $woocommerce->cart->set_quantity($k,'0');
                }
            }
        }
    }
}
?>

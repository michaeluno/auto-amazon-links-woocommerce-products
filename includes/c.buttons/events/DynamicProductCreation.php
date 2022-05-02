<?php
namespace AutoAmazonLinks\WooCommerceProducts\Buttons\Events;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;
use AutoAmazonLinks\WooCommerceProducts\Commons\Utility;

/**
 * @since 0.3.0
 */
class DynamicProductCreation implements MemberInterface {

    use Utility;

    public function run() {
        if ( ! $this->___isAddToCartQueryRequest() ) {
            return;
        }
        add_filter( 'woocommerce_add_to_cart_product_id', [ $this, 'replyToGetDynamicProduct' ] );
    }
        private function ___isAddToCartQueryRequest() {
            if ( isset( $_POST[ 'product_id' ] ) ) {    /** Ajax requests @see WC_AJAX::add_to_cart() */
                return true;
            }
            if ( isset( $_REQUEST[ 'add-to-cart' ] ) && is_numeric( wp_unslash( $_REQUEST[ 'add-to-cart' ] ) ) ) {    /** $_GET requests @see WC_Form_Handler::add_to_cart_action() */
                return true;
            }
            return false;
        }

    public function replyToGetDynamicProduct( $iProductID ) {

        if ( 0 !== $iProductID ) {
            return $iProductID;
        }

        if ( ! isset( $_REQUEST[ 'product_sku' ] ) ) {
            return $iProductID;
        }

        // At this point, 0 is given, which indicates to create a dynamic product
        $_oUtil        = new \AmazonAutoLinks_Utility();
        $_aRequest     = $_oUtil->getArrayMappedRecursive( 'sanitize_text_field', $_REQUEST );
        $_iWCProductID = wc_get_product_id_by_sku( $_oUtil->getElement( $_aRequest, [ 'product_sku' ] ) );
        if ( $_iWCProductID ) {
            return $_iWCProductID;
        }

        // If the product does not exist, create it with the given minimum product data.
        $_aRequest[ 'product_id' ] = $_aRequest[ 'product_sku' ];
        $_aRequest[ 'title' ]      = stripslashes( $_aRequest[ 'label' ] );
        $_aRequest[ 'ASIN' ]       = $_aRequest[ 'asin' ];
        $_iWCProductID             = $this->getWooCommerceProductCreatedFromUnitProduct( $_aRequest );
        if ( $_iWCProductID ) {
            update_post_meta( $_iWCProductID, '_updated_time', $_aRequest[ 'updated_date' ] );
        }
        return $_iWCProductID;

    }

}
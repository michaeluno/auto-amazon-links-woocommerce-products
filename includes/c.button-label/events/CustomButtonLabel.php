<?php
namespace AutoAmazonLinks\WooCommerceProducts\ButtonLabel\Events;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Modifies the "Add to Cart" button label
 *
 * @since 1.1.0
 */
class CustomButtonLabel implements MemberInterface {

    /**
     * @since  1.1.0
     * @return void
     */
    public function run() {

        // We need to wait for the option object to be ready so make it load later
        add_action( 'aal_action_loaded_plugin', array( $this, 'replyToLoad' ) );

    }

    /**
     * @since 1.1.0
     */
    public function replyToLoad() {

        // Check the option and return if not enabled
        $_oOption  = \AmazonAutoLinks_Option::getInstance();
        $_bEnabled = $_oOption->get( [ 'woocommerce', 'custom_button_label_enable' ], false );
        if ( ! $_bEnabled ) {
            return;
        }

        add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'replyToEditAddToCartTextSingle' ], 100, 2 );
        add_filter( 'woocommerce_product_add_to_cart_text', [ $this, 'replyToEditAddToCartTextInLoops' ], 100, 2 );

        add_filter( 'aal/wcp/filter/cart_text', [ $this, 'replyToGetCartLabel' ] );

    }

    /**
     * @param  string $sLabel
     * @return string
     * @sicne  1.1.0
     */
    public function replyToGetCartLabel( $sLabel ) {
        return $this->___getCartButtonLabel();
    }

    /**
     * @return string
     * @since  1.1.0
     */
    private function ___getCartButtonLabel() {
        $_oOption  = \AmazonAutoLinks_Option::getInstance();
        return $_oOption->get( [ 'woocommerce', 'custom_button_label_text' ], __( 'Add to Cart', 'woocommerce' ) );
    }

    /**
     * @since  1.1.0
     * @return string
     */
    public function replyToEditAddToCartTextSingle( $sButtonText, $oProduct ) {
        return $this->___getCartButtonLabel();
    }

    /**
     * @since  1.1.0
     * @return string
     */
    public function replyToEditAddToCartTextInLoops( $sButtonText, $oProduct ) {
        return $this->___getCartButtonLabel();
    }


}
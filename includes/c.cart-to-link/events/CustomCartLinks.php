<?php

namespace AutoAmazonLinks\WooCommerceProducts\CartToLink\Events;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Provides means to customize the Add-to-Cart button links
 *
 * Some users might want just link to the product page rather than adding the product to the on-site cart.
 *
 * @since 1.1.0
 */
class CustomCartLinks implements MemberInterface {

    /**
     * @since 1.1.0
     */
    public function run() {

        // We need to wait for the option object to be ready so make it load later
        add_action( 'aal_action_loaded_plugin', array( $this, 'replyToLoad' ) );

    }

    public function replyToLoad() {

        // Check the option and return if not enabled
        $_oOption  = \AmazonAutoLinks_Option::getInstance();
        $_bEnabled = $_oOption->get( [ 'woocommerce', 'cart_to_link_enable' ], false );
        if ( ! $_bEnabled ) {
            return;
        }

        add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'replyToEditAddToCartButtonLinkInLoops' ], 100, 3);

        // Replace the Add to Cart form with a button
        add_filter( 'woocommerce_before_add_to_cart_form', [ $this, 'replyToAddBeforeCartForm' ] );
        add_filter( 'woocommerce_after_add_to_cart_form', [ $this, 'replyToAddAfterCartForm' ] );

        // Add to Cart url
        add_filter( 'woocommerce_product_add_to_cart_url', [ $this, 'replyToEditAddToCartURL' ], 10, 2 );

    }

    /**
     * @since  1.1.0
     * @param  \WC_Product $oProduct
     * @return string The modified `<a>` tag.
     */
    public function replyToEditAddToCartButtonLinkInLoops( $sLink, $oProduct, $aArguments ) {
        return "<a href='" . esc_url( $this->___getCartHref( $oProduct ) ) .  "' rel='nofollow' target='_blank' class='button product_type_simple add_to_cart_button text_replaceable' title='" . esc_attr( $oProduct->get_title() ) . "' >"
                // . $this->getCartButtonLabel()
                . apply_filters( 'aal/wcp/filter/cart_text', strip_tags( $sLink ) )
            .  "</a>";
    }

     /**
     * @since  1.1.0
     * @return void
     */
    public function replyToAddBeforeCartForm() {
        echo "<div class='add-to-cart-form' style='display:none;'>";
    }
    /**
     * @since  1.1.0
     * @return void
     */
    public function replyToAddAfterCartForm() {
        echo "</div>"; // end hidden

        if ( empty( $GLOBALS[ 'product' ] ) ) {
            return;
        }

        // Add the "Add to Cart" link
        echo "<form class='cart'>"
                . "<a href=" . esc_url( $this->___getCartHref( $GLOBALS[ 'product' ] ) ) . " class='button alt' rel='nofollow' target='_blank' title='" . esc_attr( $GLOBALS[ 'product' ]->get_title() ) . "'>"
                    // . $this->getCartButtonLabel()
                    . apply_filters( 'aal/wcp/filter/cart_text', __( 'Add to Cart', 'woocommerce' ) )
                . "</a>"
            . "</form>";
    }

    /**
     * Modifies the Add to Cart URL
     *
     * The Store Front WooCommerce default theme adds a sticky form at the top and this filter is needed for it.
     *
     * @param  string $sPermalink
     * @param  \WC_Product $oProduct
     * @return string
     * @sinec  1.1.0
     */
    public function replyToEditAddToCartURL( $sPermalink, $oProduct ) {
        return $this->___getCartHref( $oProduct );
    }

    /**
     * @since  1.1.0
     * @return string
     */
    // private function ___getCartButtonLabel() {
    //     return __( 'Buy Now', 'amazon-auto-links' );
    // }

    /**
     * @since  1.1.0
     * @param  \WC_Product $oProduct
     * @return string
     */
    private function ___getCartHref( $oProduct ) {
        $_oUtil   = new \AmazonAutoLinks_PluginUtility();
        $_sSKU    = $oProduct->get_sku(); // ASIN|locale|currency|language
        $_aItem   = explode( '|', $_sSKU );
        $_sASIN   = $_oUtil->getElement( $_aItem, 0 );
        $_sLocale = $_oUtil->getElement( $_aItem, 1 );
        $_oOption = \AmazonAutoLinks_Option::getInstance();
        $_oLocale = new \AmazonAutoLinks_Locale( $_sLocale );
        return $_oLocale->getMarketPlaceURL( 'dp/' . $_sASIN . '/?tag=' . $_oOption->getAssociateID( $_sLocale ) );
    }

}
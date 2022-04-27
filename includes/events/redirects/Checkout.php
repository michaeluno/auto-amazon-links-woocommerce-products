<?php

namespace AutoAmazonLinks\WooCommerceProducts\Events\Redirects;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * @since 0.1.0
 */
class Checkout implements MemberInterface {

    /**
     * @since 0.1.0
     */
    public function run() {
        if ( did_action( 'wp' ) ) {
            $this->replyToCheckPage();
            return;
        }
        add_action( 'wp', [ $this, 'replyToCheckPage' ] );
    }

    /**
     * @since 0.1.0
     */
    public function replyToCheckPage() {

        if ( ! is_checkout() ) {
            return;
        }
        exit( wp_redirect( $this->___getCartQueryURL() ) );

    }
        /**
         * @since  0.1.0
         * @return string
         */
        private function ___getCartQueryURL() {

            $_oUtil           = new \AmazonAutoLinks_PluginUtility();
            $_oOption         = \AmazonAutoLinks_Option::getInstance();
            $_sLocale         = $_oOption->getMainLocale();
            $_oLocale         = new \AmazonAutoLinks_Locale( $_sLocale );
            $_sCartBaseURL    = $_oLocale->getAddToCartURL();
            $_sPAAPIPublicKey = $_oOption->getPAAPIAccessKey( $_sLocale );
            $_aQuery          = array(
                'AssociateTag'      => $_oOption->getAssociateID( $_sLocale ),
                'SubscriptionId'    => $_sPAAPIPublicKey,
                'AWSAccessKeyId'    => $_sPAAPIPublicKey,
                // 'OfferListingId'    => $sOfferListingID, // not implemented -> maybe add a custom meta for this in AAL core
            );

            $_iIndex  = 0;
            foreach ( \WC()->cart->get_cart() as $_sCartItemKey => $_aCartItem ) {

                $_iIndex++;
                $_iProductID = ( integer ) $_aCartItem[ 'data' ]->get_id();
                $_oProduct   = wc_get_product( $_iProductID );
                $_sSKU       = $_oProduct->get_sku();
                $_aItemInfo  = explode( '|', $_sSKU );  // ASIN|locale|currency|language

                $_sASIN      = get_post_meta( $_iProductID, '_asin', true );
                $_sASIN      = $_sASIN ? $_sASIN : $_oUtil->getElement( $_aItemInfo, [ 0 ] );

                $_aQuery[ 'ASIN.' . $_iIndex ]     = $_sASIN;
                $_aQuery[ 'Quantity.' . $_iIndex ] = $_oUtil->getElement( $_aCartItem, [ 'quantity' ], 1 );

            }
            return add_query_arg( $_aQuery, $_sCartBaseURL );

        }

}
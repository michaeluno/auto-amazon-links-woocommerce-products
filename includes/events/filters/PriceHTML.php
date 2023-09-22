<?php

namespace AutoAmazonLinks\WooCommerceProducts\Events\Filters;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Handles WooCommerce product meta data adjustments.
 *
 * @since 0.2.0
 */
class PriceHTML implements MemberInterface {

    /**
     * @since 0.2.0
     * @var   array
     */
    public $aCurrentTemplateParts = [];

    /**
     * @since 0.2.0
     */
    public function run() {

        if ( is_admin() ) {
            return;
        }
        add_filter( 'woocommerce_get_price_html', [ $this, 'replyToGetPriceHTML' ], 10, 2 );
        add_filter( 'woocommerce_cart_item_price', [ $this, 'replyToGetPriceHTMLInCartTable' ], 10, 3 );
        add_action( 'woocommerce_after_cart_table', [ $this, 'replyToInsertPricingDisclaimer' ] );

        add_action( 'woocommerce_before_template_part', [ $this, 'replyToCaptureCurrentTemplatePart' ] );
        add_action( 'woocommerce_after_template_part', [ $this, 'replyToRemoveCurrentTemplatePart' ] );

    }

    /**
     * @since 0.2.0
     * @param $sTemplatePartName
     */
    public function replyToCaptureCurrentTemplatePart( $sTemplatePartName ) {
        $this->aCurrentTemplateParts[] = $sTemplatePartName;
    }
    /**
     * @since 0.2.0
     * @param $sTemplatePartName
     */
    public function replyToRemoveCurrentTemplatePart( $sTemplatePartName ) {
        // iterate the array in reverse order @see https://stackoverflow.com/a/25769831
        for ( end( $this->aCurrentTemplateParts ); ( $_iCurrentIndex = key( $this->aCurrentTemplateParts ) ) !== null; prev( $this->aCurrentTemplateParts ) ) {
            $_sCurrentElement = current( $this->aCurrentTemplateParts );
            if ( $sTemplatePartName === $_sCurrentElement ) {
                unset( $this->aCurrentTemplateParts[ $_iCurrentIndex ] );
                break;
            }
        }
    }


    /**
     * @since  0.2.0
     * @param  string $sPrice
     * @param  array  $aCartItem
     * @param  string $sCartItemKey
     * @return string
     */
    public function replyToGetPriceHTMLInCartTable( $sPrice, $aCartItem, $sCartItemKey ) {
        return $this->___getPriceHTML( $sPrice, \AmazonAutoLinks_PluginUtility::getElement( $aCartItem, [ 'product_id' ] ) );
    }

    /**
     * @since  0.2.0
     * @param  string $sPriceHTML
     * @param  \WC_Product $oWCProduct
     * @return string
     */
    public function replyToGetPriceHTML( $sPriceHTML, $oWCProduct ) {
        return $this->___getPriceHTML( $sPriceHTML, $oWCProduct->get_id() );
    }

    /**
     * @since  0.2.0
     * @param  string  $sPriceHTML
     * @param  integer $iProductID
     * @return string
     */
    private function ___getPriceHTML( $sPriceHTML, $iProductID ) {
        $_iUpdatedTime  = ( integer ) get_post_meta( absint( $iProductID ), '_updated_time', true );
        $_sUpdatedDate  = \AmazonAutoLinks_PluginUtility::getSiteReadableDate( $_iUpdatedTime, get_option( 'date_format' ) . ' H:i', true );
        $_sUpdatedDate  = 'n/a' === $_sUpdatedDate ? $_sUpdatedDate : $_sUpdatedDate . ' ' . 'GMT ' . \AmazonAutoLinks_PluginUtility::getGMTOffsetString();
        return $sPriceHTML
            . apply_filters( 'aal/wcp/filter/pricing_disclaimer_tooltip', ' ' . $this->___getPricingDisclaimer( $_sUpdatedDate ) );
    }
        /**
         * @since  0.2.0
         * @param  string  $sUpdatedDate Human-readable time of the updated time
         * @return string
         */
        private function ___getPricingDisclaimer( $sUpdatedDate ) {
            return ""
                    . "<span class='pricing-disclaimer' style='display: inline-block;'>"
                    . "("
                        . sprintf(
                            __( 'as of %1$s', 'amazon-auto-links' ),
                           $sUpdatedDate
                        )
                        . $this->___getDisclaimerTooltip()
                    . ")"
                    . "</span>"
                ;
        }
            /**
             * @since  0.2.0
             * @return string
             */
            private function ___getDisclaimerTooltip() {

                $_sURL    = trim( apply_filters( 'aal_filter_unit_output_disclaimer_link_url', '' ) );

                // When a product is shown in columns, WooCommerce encloses the entire <li> contents in an <a> tag
                // and the pricing disclaimer output includes an <a> tag, which causes invalid HTML.
                if ( empty( $_sURL ) || in_array( 'loop/price.php', $this->aCurrentTemplateParts, true ) ) {
                    return " - <span title='" . esc_attr( $this->___getDisclaimerText() ) . "' class='amazon-disclaimer-tooltip'>"
                            . __( 'More info', 'amazon-auto-links' )
                        . "</span>";
                }

                $_sHref   = "href='" . esc_url( $_sURL ) . "'";
                $_sTarget = " target='_blank'";
                return " - <a {$_sHref}{$_sTarget} title='" . esc_attr( $this->___getDisclaimerText() ) . "' class='amazon-disclaimer-tooltip'>"
                        . __( 'More info', 'amazon-auto-links' )
                    . "</a>";

            }

            /**
             * @sicne  0.2.0
             * @return string
             */
            private function ___getDisclaimerText() {
                $_sPricingDisclaimer = __( "Product prices and availability are accurate as of the date/time indicated and are subject to change. Any price and availability information displayed on [relevant Amazon Site(s), as applicable] at the time of purchase will apply to the purchase of this product.", 'amazon-auto-links' );
                return apply_filters( 'aal/wcp/filter/pricing_disclaimer_text', $_sPricingDisclaimer );
            }

    /**
     * @since    0.2.0
     * @callback add_action() woocommerce_after_cart_table
     */
    public function replyToInsertPricingDisclaimer() {
        $_sPricingDisclaimer = apply_filters( 'aal/wcp/filter/pricing_disclaimer_notice', $this->___getDisclaimerText() . ' '  . $this->___getDisclaimerTooltip() );
        if ( '' === $_sPricingDisclaimer ) {
            return;
        }
        wc_print_notice( $_sPricingDisclaimer, 'notice' );
    }
            
}
<?php
namespace AutoAmazonLinks\WooCommerceProducts\Buttons\Events;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;
use AutoAmazonLinks\WooCommerceProducts\Commons\Utility;

/**
 * @since 0.3.0
 */
class ItemFormatTags implements MemberInterface {

    use Utility;
    
    /**
     * @since 0.3.0
     */
    public function run() {
        add_filter( 'aal_filter_unit_item_format_tag_replacements', [ $this, 'replyToGetItemFormatTagReplacements' ], 10, 3 );
    }

    /**
     * @since  0.3.0
     * @param  array $aReplacements
     * @param  array $aProduct
     * @param  \AmazonAutoLinks_UnitOutput_Base $oUnitOutput
     * @return array
     */
    public function replyToGetItemFormatTagReplacements( $aReplacements, $aProduct, $oUnitOutput ) {
        $aReplacements[ '%wc_button%' ] = ( boolean ) get_post_meta( $oUnitOutput->oUnitOption->get( 'id' ), '_unit_to_wc_products_enable', true )
            ? $this->___getWooCommerceCartButton( $aProduct, $oUnitOutput )
            : '';
        return $aReplacements;
    }

    /**
     * @since  0.3.0
     * @param  array  $aProduct
     * @param  \AmazonAutoLinks_UnitOutput_Base $oUnitOutput
     * @return string
     */
    private function ___getWooCommerceCartButton( $aProduct, $oUnitOutput ) {

        $_oUtil            = new \AmazonAutoLinks_PluginUtility();
        $_iProductID       = 0;
        $_aAttributes      = [
            // Required
            'href'                => '?add-to-cart=' . $_iProductID,
            'data-quantity'       => 1,
            'class'               => 'button product_type_simple add_to_cart_button ajax_add_to_cart text_replaceable',
            'rel'                 => 'nofollow',
            'data-product_id'     => $_iProductID,
            'data-product_sku'    => $_oUtil->getElement( $aProduct, [ 'product_id' ], '' ),
            'data-label'          => $_oUtil->getElement( $aProduct, [ 'title' ], '' ),

            // For a case that the WC product hasn't been created
            'data-_unit_id'                => $_oUtil->getElement( $aProduct, [ '_unit_id' ] ),
            'data-updated_date'            => $_oUtil->getElement( $aProduct, [ 'updated_date' ] ),
            'data-price_amount'            => $_oUtil->getElement( $aProduct, [ 'price_amount' ] ),
            'data-formatted_price'         => $_oUtil->getElement( $aProduct, [ 'formatted_price' ] ),
            'data-discounted_price'        => $_oUtil->getElement( $aProduct, [ 'discounted_price' ] ),
            'data-discounted_price_amount' => $_oUtil->getElement( $aProduct, [ 'discounted_price_amount' ] ),
            'data-rating'                  => $_oUtil->getElement( $aProduct, [ 'rating' ] ),
            'data-number_of_reviews'       => $_oUtil->getElement( $aProduct, [ 'number_of_reviews' ] ),
            'data-product_url'             => $aProduct[ 'product_url' ],
            'data-thumbnail_url'           => $aProduct[ 'thumbnail_url' ],
            'data-asin'                    => $aProduct[ 'ASIN' ],

        ];
        return "<a " . $_oUtil->getAttributes( $_aAttributes ) .  ">"
                . apply_filters( 'woocommerce_product_single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $this )  // @see \WC_Product::single_add_to_cart_text()
            . "</a>";

    }


}
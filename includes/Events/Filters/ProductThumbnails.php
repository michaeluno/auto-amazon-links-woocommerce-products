<?php

namespace AutoAmazonLinks\WooCommerceProducts\Events\Filters;

/**
 * Handles WooCommerce product meta data adjustments.
 */
class ProductThumbnails {

    public function run() {
        add_filter( 'woocommerce_product_get_image', [ $this, 'replyToGetProductThumbnail' ], 10, 2 );
        add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'replyToGetSingleProductThumbnailHTML' ], 10, 2 );
    }

    public function replyToGetSingleProductThumbnailHTML( $sImgHTML, $iThumbnailID ) {
        if ( ! isset( $GLOBALS[ 'product' ] ) || ! ( $GLOBALS[ 'product' ] instanceof \WC_Product ) ) {
            return $sImgHTML;
        }
        $_sURLThumbnail = get_post_meta( $GLOBALS[ 'product' ]->get_id(), '_thumbnail_url', true );
        if ( ! \AmazonAutoLinks_PluginUtility::isImageSRC( $_sURLThumbnail ) ) {
            return $sImgHTML;
        }
        $_sURL = \AmazonAutoLinks_Unit_Utility::getImageURLBySize( $_sURLThumbnail, 500 );
        return "<img src='" . esc_url( $_sURL ) . "'/>";
    }

    /**
     * @param string      $sImgTag
     * @param \WC_Product $oWCProduct
     */
    public function replyToGetProductThumbnail( $sImgTag, $oWCProduct ) {
        $_sURLThumbnail = get_post_meta( $oWCProduct->get_id(), '_thumbnail_url', true );
        if ( ! \AmazonAutoLinks_PluginUtility::isImageSRC( $_sURLThumbnail ) ) {
            return $sImgTag;
        }
        $_sURL = \AmazonAutoLinks_Unit_Utility::getImageURLBySize( $_sURLThumbnail, 324 );
        return "<img src='" . esc_url( $_sURL ) . "'/>";
    }

}
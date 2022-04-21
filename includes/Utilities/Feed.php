<?php

namespace AutoAmazonLinks\WooCommerceProducts\Utilities;

class Feed {

    public $sFeedURL = '';

    /**
     * @var array Stores fetched items.
     */
    public $aItems   = [];

    /**
     * Sets up properties and hooks.
     */
    public function __construct( $sFeedURL ) {
        $this->sFeedURL = $sFeedURL;
    }

    /**
     * Fetch items from the external source and stores them.
     */
    public function fetch() {
        $_oHTTP     = new \AmazonAutoLinks_HTTPClient( $this->sFeedURL, 86400, [], 'aal_woocommerce_products' );
        $_sHTTPBody = $_oHTTP->get();
        if ( ! strlen( $_sHTTPBody ) ) {
            return;
        }
        $_aItems      = json_decode( $_sHTTPBody, true );
        $this->aItems = is_array( $_aItems ) && count( $_aItems ) ? $_aItems : [];
    }

    /**
     * @return array
     */
    public function getItems() {
        return $this->aItems;
    }

}
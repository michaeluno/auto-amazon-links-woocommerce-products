<?php

namespace AutoAmazonLinks\WooCommerceProducts\Events\Redirects;

class Checkout {

    public function run() {
        if ( did_action( 'wp' ) ) {
            $this->replyToCheckPage();
            return;
        }
        add_action( 'wp', [ $this, 'replyToCheckPage' ] );
    }

    public function replyToCheckPage() {
        if ( ! is_checkout() ) {
            return;
        }
        // @todo
        $_sCartURL = 'https://google.com';
        exit( wp_redirect( $_sCartURL ) );
    }
    
}
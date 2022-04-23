<?php

namespace AutoAmazonLinks\WooCommerceProducts\Events\Redirects;

use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

class Checkout implements MemberInterface {

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
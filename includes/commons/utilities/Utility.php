<?php
namespace AutoAmazonLinks\WooCommerceProducts\Commons;

/**
 * @since 0.3.0
 */
trait Utility {

    /**
     * Extracts the first-found comma-separated digits from a string.
     * Possible Cases:
     *  - discount + proper: <span class="amazon-prices"><span class="proper-price"><s>$3,610 ($361 / item)</s></span> <span class="offered-price">$2,796 ($280 / item)</span></span>
     *  - proper (regular):  <span class="amazon-prices"><span class="proper-price">$7,240</span></span>
     *  - range:             <span class="amazon-prices"><span>$200 - $1,096</span></span>
     * @param  string $sReadablePrice
     * @return string
     */
    static public function getPriceAmountExtracted( $sReadablePrice ) {
        preg_match( "/[0-9,.]+/", $sReadablePrice, $_aMatches );    // extracts the first occurrence of digits with comma
        return isset( $_aMatches[ 0 ] )
            ? $_aMatches[ 0 ]
            : '';
    }

}
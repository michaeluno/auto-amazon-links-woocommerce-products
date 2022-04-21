<?php
namespace AutoAmazonLinks\WooCommerceProducts\Events;

use AutoAmazonLinks\WooCommerceProducts\Commons\LoaderAbstract;

class Loader extends LoaderAbstract {

    static public $sDirPath = __DIR__;

    /**
     * @since 0.1.0
     * @var   array A list of component loader classes to load.
     */
    public $aComponents = [];

    /**
     * @since 0.1.0
     * @var   string[] A list of component member classes to load.
     */
    public $aMembers = [
        __NAMESPACE__ . '\\Actions\\UnitToProducts',
        __NAMESPACE__ . '\\Filters\\ProductThumbnails',
        __NAMESPACE__ . '\\Redirects\\Checkout',
    ];

}
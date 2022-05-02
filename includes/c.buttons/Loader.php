<?php

namespace AutoAmazonLinks\WooCommerceProducts\Buttons;

use AutoAmazonLinks\WooCommerceProducts\Commons\LoaderAbstract;

/**
 * @since 0.3.0
 */
class Loader extends LoaderAbstract {

    /**
     * @since 0.3.0
     * @var   string
     */
    static public $sDirPath = __DIR__;

    /**
     * @since 0.3.0
     * @var   string[] A list of component members.
     */
    public $aMembers = [
         __NAMESPACE__ . '\\Events\\ItemFormatTags'             => null,
         __NAMESPACE__ . '\\Events\\DynamicProductCreation'     => null,
    ];

}
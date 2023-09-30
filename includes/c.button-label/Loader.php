<?php

namespace AutoAmazonLinks\WooCommerceProducts\ButtonLabel;

use AutoAmazonLinks\WooCommerceProducts\Commons\LoaderAbstract;

/**
 * Allows the user to change the "Add to Cart" button text
 *
 * @since 1.1.0
 */
class Loader extends LoaderAbstract {

    /**
     * @since 1.1.0
     * @var   string
     */
    static public $sDirPath = __DIR__;

    /**
     * @since 1.1.0
     * @var   string[] A list of component members.
     */
    public $aMembers = [
         __NAMESPACE__ . '\\Events\\CustomButtonLabel' => null,
         __NAMESPACE__ . '\\Admin\\SettingUI'          => null,
    ];

}
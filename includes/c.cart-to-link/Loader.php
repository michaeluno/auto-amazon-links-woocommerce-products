<?php

namespace AutoAmazonLinks\WooCommerceProducts\CartToLink;

use AutoAmazonLinks\WooCommerceProducts\Commons\LoaderAbstract;

/**
 * Converts the cart button to a simple external Amazon link
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
         __NAMESPACE__ . '\\Events\\CustomCartLinks' => null,
         __NAMESPACE__ . '\\Admin\\SettingUI'        => null,
    ];

    /**
     * @since  1.1.0
     * @return void
     */
    // public function run() {
    //     parent::run();
    // }
}
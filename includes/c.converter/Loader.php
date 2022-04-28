<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter;

use AutoAmazonLinks\WooCommerceProducts\Commons\LoaderAbstract;
use AutoAmazonLinks\WooCommerceProducts\Converter\Admin\PostMetaBoxes\Main;

/**
 * @since 0.1.0
 */
class Loader extends LoaderAbstract {

    /**
     * @since 0.1.0
     * @var   string
     */
    static public $sDirPath = __DIR__;

    /**
     * @since 0.1.0
     * @var   string[] A list of component members.
     */
    public $aMembers = [
         '\\AutoAmazonLinks\\WooCommerceProducts\\Converter\\Events\\Filters\\UnitOutput'     => null,
         '\\AutoAmazonLinks\\WooCommerceProducts\\Converter\\Events\\Actions\\UnitToProducts' => null,
         '\\AutoAmazonLinks\\WooCommerceProducts\\Converter\\Events\\Actions\\Runner'         => null,
    ];

    /**
     * Loads the component members.
     * @since 0.1.0
     */
    public function run() {

        parent::run();

        $_oMetaBoxMain = new Main(
            null, // meta box ID - null to auto-generate
            __( 'Unit to WooCommerce Product Converter', 'amazon-auto-links' ),
            [ \AmazonAutoLinks_Registry::$aPostTypes[ 'unit' ] ],
            'normal', // context (what kind of meta-box this is)
            'low' // priority
        );
        $this->aMembers[ get_class( $_oMetaBoxMain ) ] = $_oMetaBoxMain;

    }

}
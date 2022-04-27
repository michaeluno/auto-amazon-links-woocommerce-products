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
     * @since 0.1.0
     * @var   string The post type slug.
     */
    static public $sPostTypeSlug = 'unit_to_wc_products';

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

        // @deprecated 0.1.0
        // $_oPostType = new PostType(
        //     self::$sPostTypeSlug,  // slug
        //     null,        // post type argument. This is defined in the class.
        //     \AutoAmazonLinks\WooCommerceProducts\App::$sFilePath // script path
        // );
        // $this->aMembers[ get_class( $_oPostType ) ] = $_oPostType;
        // $_oMetaBoxUnitSelector = new UnitSelector(
        //     null, // meta box ID - null to auto-generate
        //     __( 'Units', 'amazon-auto-links' ),
        //     [ self::$sPostTypeSlug ],
        //     'normal', // context (what kind of meta-box this is)
        //     'default' // priority
        // );
        // $this->aMembers[ get_class( $_oMetaBoxUnitSelector ) ] = $_oMetaBoxUnitSelector;
    }

}
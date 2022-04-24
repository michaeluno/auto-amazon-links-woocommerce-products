<?php

namespace AutoAmazonLinks\WooCommerceProducts\Commons\Admin;

/**
 * @since 0.1.0
 */
abstract class FormFieldsAbstract implements FormFieldsInterface {

    /**
     * @since 0.1.0
     * @var   \AmazonAutoLinks_AdminPageFramework_Factory
     */
    public $oFactory;

    /**
     * Sets up properties.
     * @since 0.1.0
     */
    public function __construct( \AmazonAutoLinks_AdminPageFramework_Factory $oAPFFactory  ) {
        $this->oFactory = $oAPFFactory;
    }

    /**
     * @since  0.1.0
     * @return array
     */
    public function get() {
        return [];
    }

}
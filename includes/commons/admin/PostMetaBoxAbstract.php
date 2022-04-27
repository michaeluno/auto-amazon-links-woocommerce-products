<?php
namespace AutoAmazonLinks\WooCommerceProducts\Commons\Admin;

/**
 * @since 0.1.0
 */
class PostMetaBoxAbstract extends \AmazonAutoLinks_AdminPageFramework_MetaBox {

    /**
     * @since 0.1.0
     */
    protected $_aFieldClasses = [];

    /**
     * @since 0.1.0
     */
    public function setUp() {
        foreach( $this->_aFieldClasses as $_sClassName ) {
            $_oFields = new $_sClassName( $this );
            foreach( $_oFields->get() as $_aField ) {
                $this->addSettingFields( $_aField );
            }
        }
    }

}
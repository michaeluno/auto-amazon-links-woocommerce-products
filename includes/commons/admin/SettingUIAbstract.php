<?php

namespace AutoAmazonLinks\WooCommerceProducts\Commons\Admin;

use AutoAmazonLinks\WooCommerceProducts\App;
use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Provides common methods for the setting UI.
 *
 * @since 1.1.0
 */
abstract class SettingUIAbstract implements MemberInterface {

    /**
     * A list of class names.
     *
     * @var   array
     * @since 1.1.0
     */
    protected $_aFieldClasses = [];

    /**
     * Adds the setting UI for this component
     *
     * @since 1.1.0
     */
    public function run() {
        add_action( 'load_' . \AmazonAutoLinks_Registry::$aAdminPages[ 'main' ] . '_' . App::$aTabs[ 'main' ], [ $this, 'replyToLoadTab' ], 20 );
    }

    /**
     * @since 1.1.0
     */
    public function replyToLoadTab( $oFactory ) {
        foreach( $this->_aFieldClasses as $_sClassName ) {
            $_oFields = new $_sClassName( $oFactory );
            foreach( $_oFields->get() as $_aField ) {
                $oFactory->addSettingFields( $_aField );
            }
        }
    }

}
<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter\Admin\PostMetaBoxes;

use AutoAmazonLinks\WooCommerceProducts\Commons\Admin\PostMetaBoxAbstract;

/**
 * @since 0.1.0
 */
class Main extends PostMetaBoxAbstract {

    /**
     * @since 0.1.0
     * @var   string[]
     */
    protected $_aFieldClasses = [
        '\\AutoAmazonLinks\\WooCommerceProducts\\Converter\\Admin\\FormFields\\Main',
    ];

    /**
     * @param  array $aInputs
     * @param  array $aOldInputs
     * @param  \AmazonAutoLinks_AdminPageFramework_MetaBox $oFactory
     * @return array
     */
    public function validate( $aInputs, $aOldInputs, $oFactory ) {

        if ( ! $this->oUtil->getElement( $aInputs, [ '_unit_to_wc_products_enable' ] ) ) {
            return $aInputs;
        }
        // At this point, the option is enabled.

        // If the option is previously enabled, no need to take action.
        if ( $this->oUtil->getElement( $aOldInputs, [ '_unit_to_wc_products_enable' ] ) ) {
            return $aInputs;
        }

        // At this point, the user newly enabled the option.
        \AmazonAutoLinks_Event_Scheduler::prefetch( \AmazonAutoLinks_PluginUtility::getCurrentPostID() );
        return $aInputs;

    }

}
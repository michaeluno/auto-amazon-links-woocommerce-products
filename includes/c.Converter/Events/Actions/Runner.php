<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter\Events\Actions;

use AutoAmazonLinks\WooCommerceProducts\App;
use AutoAmazonLinks\WooCommerceProducts\Commons\MemberInterface;

/**
 * Keeps running the task of outputting units that enables the Unit to WooCommerce Products option
 * with a certain interval (once a day by default).
 * @since 0.1.0
 */
class Runner implements MemberInterface {

    /**
     * @since 0.1.0
     * @var   \AmazonAutoLinks_Unit_Utility
     */
    public $oUtil;

    public $sActionHook = 'aal/wcp/converter/action/run';

    /**
     * How often the event should subsequently recur.
     * @var string Either `hourly`, `twicedaily`, `daily`, `weekly`
     */
    public $sRecurrence = 'daily';

    /**
     * @since 0.1.0
     */
    public function run() {

        add_action( $this->sActionHook, [ $this, 'replyToDoAction' ] );
        add_action( 'publish_' . \AmazonAutoLinks_Registry::$aPostTypes[ 'unit' ], [ $this, 'replyOnUnitPublish' ] );
        register_activation_hook( App::$sFilePath, [ $this, 'replyOnActivation' ] );
        register_deactivation_hook( App::$sFilePath, function() {
            wp_clear_scheduled_hook( $this->sActionHook );
        } );

    }

    /**
     * Sets the '_unit_to_wc_products_updated_time' meta value.
     * This is important to sort by `meta_value_num`. If the value does not exist, WordPress drops the found record.
     * @param integer $iUnitID
     */
    public function replyOnUnitPublish( $iUnitID ) {
        if ( ! metadata_exists( 'post', $iUnitID, '_unit_to_wc_products_updated_time' ) ) {
            update_post_meta( $iUnitID, '_unit_to_wc_products_updated_time', 0 );
        }
    }

    /**
     * @remark For unknown reasons, when using a closure function for a callback, the event is not get scheduled although the closure function is called
     * and the schedule result return value yields true. To un-schedule callback seems to be fine with a closure.
     */
    public function replyOnActivation() {
        if ( wp_next_scheduled( $this->sActionHook ) ) {
            return;
        }
        wp_schedule_event( time(), $this->sRecurrence, $this->sActionHook );
    }

    /**
     * @since 0.1.0
     */
    public function replyToDoAction() {
        $this->oUtil = new \AmazonAutoLinks_Unit_Utility;
        $_aUnitIDs   = $this->___getUnitIDsEnabledUnitToWCProductConversion();
        foreach( $_aUnitIDs as $_iUnitID ) {
            apply_filters( 'aal_filter_output_products', [], [ 'id' => $_iUnitID ] );
        }
    }
        /**
         * @since  0.1.0
         * @return array
         */
        private function ___getUnitIDsEnabledUnitToWCProductConversion() {
            $_oQuery  = new \WP_Query( [
                'post_status'    => 'publish',
                'post_type'      => \AmazonAutoLinks_Registry::$aPostTypes[ 'unit' ],
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'meta_query'     => [
                    [
                        'key'       => '_unit_to_wc_products_enable',
                        'value'     => true,
                    ],
                ],
                'order'          => 'ASC',
                'orderby'        => 'meta_value_num',
                'meta_key'       => '_unit_to_wc_products_updated_time',
            ] );
            return $_oQuery->posts;
        }

}
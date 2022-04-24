<?php
/**
 * Plugin Name:       Auto Amazon Links - WooCommerce Products (Beta)
 * Description:       Converts units to WooCommerce products.
 * Author:            Michael Uno (miunosoft)
 * Author URI:        https://michaeluno.jp
 * Version:           0.1.0
 * GitHub Plugin URI: https://github.com/michaeluno/auto-amazon-links-woocommerce-products
 */
namespace AutoAmazonLinks\WooCommerceProducts;

/**
 * @since 0.1.0
 */
class App {

    static public $sDirPath  = __DIR__;
    static public $sFilePath = __FILE__;

    /**
     * @sicne 0.1.0
     * @var   array A list of this component member classes to load.
     */
    public $aMembers = [
        __NAMESPACE__ . '\\Converter\\Loader' => null,
        __NAMESPACE__ . '\\Events\\Filters\\ProductThumbnails' => null,
        __NAMESPACE__ . '\\Events\\Redirects\\Checkout' => null,
    ];

    /**
     * @since 0.1.0
     * @var   array The autoload class list.
     */
    static public $aClasses = [];

    /**
     * Run the application.
     * @since 0.1.0
     */
    public function run() {
        if ( did_action( 'plugins_loaded' ) ) { // When activating the plugin, this hook is already triggered,
            $this->replyToLoad();
            return;
        }
        add_action( 'plugins_loaded', [ $this, 'replyToLoad' ], 9 ); // The priority of 9 is because this plugin needs to be loaded before Auto Amazon Links' background routines, especially prefetching units
    }
        /**
         * @since 0.1.0
         */
        public function replyToLoad() {
            if ( ! $this->___canLoad() ) {
                return;
            }
            $this->___autoLoadClasses();
            $this->___loadComponents();
        }
            /**
             * @since  0.1.0
             * @return boolean
             */
            private function ___canLoad() {
                // WooCommerce is required
                if ( ! function_exists( '\WC' ) ) {
                    return false;
                }
                if ( ! class_exists( '\WC_Product_Simple' ) ) {
                    return false;
                }
                // Auto Amazon Links 5.2.7 or above is required
                if ( ! class_exists( '\AmazonAutoLinks_Registry' ) ) {
                    return false;
                }
                if ( version_compare( \AmazonAutoLinks_Registry::VERSION, '5.2.7b', '<' ) ) {
                    return false;
                }
                return true;
            }
            /**
             * @since 0.1.0
             */
            private function ___autoLoadClasses() {
                self::$aClasses = include( __DIR__ . '/includes/class-map.php' );
                spl_autoload_register( function( $sCalledUnknownClassName ) {
                    if ( ! isset( self::$aClasses[ $sCalledUnknownClassName ] ) ) {
                        return;
                    }
                    include( self::$aClasses[ $sCalledUnknownClassName ] );
                } );
            }
            /**
             * @since 0.1.0
             */
            private function ___loadComponents() {
                foreach( $this->aMembers as $_sClassName => $_mThing ) {
                    $_oMember = new $_sClassName();
                    $_oMember->run();
                    $this->aMembers[ get_class( $_oMember ) ] = $_oMember;
                }
            }

}

$_oApp = new App();
$_oApp->run();
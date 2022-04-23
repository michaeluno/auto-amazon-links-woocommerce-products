<?php
/**
 * Plugin Name: Auto Amazon Links - WooCommerce Products (Prototype)
 * Description: Converts units to WooCommerce products.
 * Author:      Michael Uno (miunosoft)
 * Author URI:  https://michaeluno.jp
 * Version:     0.1.0
 */
namespace AutoAmazonLinks\WooCommerceProducts;

/**
 * @since 0.1.0
 */
class App {

    static public $sDirPath  = __DIR__;
    static public $sFilePath = __FILE__;

    /**
     * @var array
     */
    static public $aUnitFeeds = [];

    /**
     * @var string Either `hourly`, `twicedaily`, `daily`, `weekly`
     * @deprecated 0.1.0
     */
    // static public $sIntervalChecks = 'daily';

    /**
     * @var string The custom action hook name to create products from units.
     * @deprecated 0.1.0
     */
    static public $sActionCreateProducts = 'aal/wcp/action/create_products';

    /**
     * @sicne 0.1.0
     * @var   array A list of this component member classes to load.
     */
    public $aMembers = [
        __NAMESPACE__ . '\\Converter\\Loader' => null,
        // __NAMESPACE__ . '\\Events\\Actions\\UnitToProducts' => null, // @deprecated
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

        // When activating the plugin, this hook is already triggered,
        if ( did_action( 'plugins_loaded' ) ) {
            $this->replyToLoad();
            return;
        }
        add_action( 'plugins_loaded', [ $this, 'replyToLoad' ] );

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
                if ( ! class_exists( '\WC_Product_Simple' ) ) {
                    return false;
                }
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
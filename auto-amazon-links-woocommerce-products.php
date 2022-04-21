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
     */
    static public $sIntervalChecks = 'daily';

    /**
     * @var string The custom action hook name to create products from units.
     */
    static public $sActionCreateProducts = 'aal/wcp/action/create_products';

    /**
     * @since 0.1.0
     * @var   array A list of component loader classes to load. Use a relative namespace path to the class.
     */
    public $aComponents = [
        __NAMESPACE__ . '\\Events\Loader',
    ];

    /**
     * @sicne 0.1.0
     * @var   array A list of this component member classes to load.
     */
    public $aMembers = [];

    /**
     * @since 0.1.0
     * @var   array
     */
    static public $aClasses = [];

    /**
     * Enable the application.
     */
    public function run() {
        add_action( 'plugins_loaded', [ $this, 'replyToLoad' ] );
        register_activation_hook( self::$sFilePath, [ $this, 'replyOnActivation' ] );
        register_deactivation_hook( self::$sFilePath, [ $this, 'replyOnDeactivation' ] );
    }

    public function replyToLoad() {
        if ( ! $this->___canLoad() ) {
            return;
        }
        $this->___autoLoadClasses();
        $this->___loadComponents();
    }
        private function ___canLoad() {
            if ( ! class_exists( '\WC_Product_Simple' ) ) {
                return false;
            }
            if ( ! class_exists( '\AmazonAutoLinks_Registry' ) ) {
                return false;
            }
            if ( version_compare( \AmazonAutoLinks_Registry::VERSION, '5.2.6b', '<' ) ) {
                return false;
            }
            return true;
        }
        private function ___autoLoadClasses() {
            self::$aClasses = include( __DIR__ . '/includes/class-map.php' );
            spl_autoload_register( function( $sCalledUnknownClassName ) {
                if ( ! isset( self::$aClasses[ $sCalledUnknownClassName ] ) ) {
                    return;
                }
                include( self::$aClasses[ $sCalledUnknownClassName ] );
            } );
        }
        private function ___loadComponents() {
            foreach( array_merge( $this->aMembers, $this->aComponents ) as $_sClassName ) {
                $_oComponent = new $_sClassName();
                $_oComponent->run();
            }
        }

    public function replyOnActivation() {
        if ( wp_next_scheduled( self::$sActionCreateProducts ) ) {
            return;
        }
        wp_schedule_event( time(), self::$sIntervalChecks, self::$sActionCreateProducts );
    }
    public function replyOnDeactivation() {
        wp_clear_scheduled_hook( self::$sActionCreateProducts );
    }

}

$_oApp = new App();
$_oApp->run();
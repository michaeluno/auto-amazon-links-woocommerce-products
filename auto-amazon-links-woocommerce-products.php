<?php
/**
 * Plugin Name:       Auto Amazon Links - WooCommerce Products
 * Description:       Converts unit products to WooCommerce products.
 * Author:            Michael Uno (miunosoft)
 * Author URI:        https://michaeluno.jp
 * Version:           1.1.0b03
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * GitHub Plugin URI: https://github.com/michaeluno/auto-amazon-links-woocommerce-products
 */

namespace AutoAmazonLinks\WooCommerceProducts;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * @since 0.1.0
 */
class App {

    const NAME = 'Auto Amazon Links - WooCommerce Products';

    static public $sDirPath  = __DIR__;
    static public $sFilePath = __FILE__;

    /**
     * @sicne 0.1.0
     * @var   array A list of this component member classes to load.
     */
    public $aMembers = [
        __NAMESPACE__ . '\\Converter\\Loader' => null,
        __NAMESPACE__ . '\\Buttons\\Loader' => null,
        __NAMESPACE__ . '\\CartToLink\\Loader' => null,
        __NAMESPACE__ . '\\ButtonLabel\\Loader' => null,
        __NAMESPACE__ . '\\Events\\Filters\\ProductThumbnails' => null,
        __NAMESPACE__ . '\\Events\\Filters\\PriceHTML' => null,
        __NAMESPACE__ . '\\Events\\Redirects\\Checkout' => null,
        __NAMESPACE__ . '\\Events\\Actions\\SettingTab' => null,
    ];

    /**
     * @since 0.1.0
     * @var   array The autoload class list.
     */
    static public $aClasses = [];

    /**
     * @since 1.1.0
     * @var   array The tab slugs used for the admin setting UI.
     */
    static public $aTabs = [
        'main' => 'woocommerce'
    ];

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
            try {
                $this->___tryCheckPluginCanLoad();
            } catch( \Exception $_oException ) {
                add_filter( 'plugin_row_meta', function( $aPluginMeta, $sPluginFilePath ) use( $_oException ) {
                    if ( plugin_basename( __FILE__ ) !== $sPluginFilePath ) {
                        return $aPluginMeta;
                    }
                    $_sInsert = "<div class='notice notice-error inline'><p class=''>"
                        . $_oException->getMessage()
                        . "</p></div>";
                    $aPluginMeta[ 0 ] = isset( $aPluginMeta[ 0 ] ) ? $_sInsert . $aPluginMeta[ 0 ] : '';
                    return $aPluginMeta;
                }, 10, 2 );
                return;
            }
            $this->___autoLoadClasses();
            $this->___loadComponents();
        }
            /**
             * @since  0.3.0
             * @throws \Exception
             */
            private function ___tryCheckPluginCanLoad() {
                if ( ! function_exists( '\WC' ) || ! class_exists( '\WC_Product_Simple' ) ) {
                    throw new \Exception( __( 'Please activate WooCommerce.', 'auto-amazon-links-woocommerce-products' ) );
                }
                $_sRequiredVersion = '5.2.8';
                if ( ! class_exists( '\AmazonAutoLinks_Registry' ) || version_compare( \AmazonAutoLinks_Registry::VERSION, $_sRequiredVersion, '<' ) ) {
                    throw new \Exception( sprintf( __( 'The plugin requires Auto Amazon Links %1$s or above.', 'auto-amazon-links-woocommerce-products' ), $_sRequiredVersion ) );
                }
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
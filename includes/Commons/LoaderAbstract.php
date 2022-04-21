<?php
namespace AutoAmazonLinks\WooCommerceProducts\Commons;

/**
 * Component loader base class.
 * @since 0.1.0
 */
abstract class LoaderAbstract implements LoaderInterface {

    /**
     * @since  0.1.0
     * @remark Each loader class should define this property for component members to refer to.
     * @var    string The component directory path.
     */
    static public $sDirPath    = __DIR__;

    /**
     * @since 0.1.0
     * @var   array A list of component loader classes to load.
     */
    public $aComponents = [];

    /**
     * @sicne 0.1.0
     * @var   array A list of this component member classes to load.
     */
    public $aMembers   = [];

    /**
     * Loads the component.
     * @since 0.1.0
     */
    public function run() {
        foreach( array_merge( $this->aMembers, $this->aComponents ) as $_sClassName ) {
\AmazonAutoLinks_Debug::log( $_sClassName . ' ' . class_exists( $_sClassName ) );
            $_oComponent = new $_sClassName();
            $_oComponent->run();
        }
    }

}
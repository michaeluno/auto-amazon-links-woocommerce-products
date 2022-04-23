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
    static public $sDirPath = __DIR__;

    /**
     * @sicne 0.1.0
     * @var   array A list of this component members.
     */
    public $aMembers = [];

    /**
     * Loads the component.
     * @since 0.1.0
     */
    public function run() {
        foreach( $this->aMembers as $_sClassName => $_mThing ) {
            $_oMember = new $_sClassName();
            $_oMember->run();
            $this->aMembers[ $_sClassName ] = $_oMember;
        }
    }

}
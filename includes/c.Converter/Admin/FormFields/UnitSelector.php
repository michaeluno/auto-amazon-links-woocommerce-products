<?php
namespace AutoAmazonLinks\WooCommerceProducts\Converter\Admin\FormFields;

use AutoAmazonLinks\WooCommerceProducts\Commons\Admin\FormFieldsAbstract;

/**
 * @since 0.1.0
 * @deprecated 0.1.0
 */
class UnitSelector extends FormFieldsAbstract {

    /**
     * @since  0.1.0
     * @return array
     */
    public function get() {
        add_filter( 'field_definition_' . $this->oFactory->oProp->sClassName . '_unit_ids',  function( $aFieldset ){
            $aFieldset[ 'label' ] = \AmazonAutoLinks_PluginUtility::getPostsLabelsByPostType(
                \AmazonAutoLinks_Registry::$aPostTypes[ 'unit' ]
            );
            return $aFieldset;
        } );
        return [
            [
                'field_id' => 'unit_ids',
                'type'     => 'select',
                'title'    => __( 'Select Units', 'amazon-auto-links' ),
                'label'    => [],   // set in a callback
                // 'is_multiple' => true,
                // 'attributes'  => [
                //     'select' => [
                //         'style' => 'min-width: 200px; min-height: 100px',
                //     ],
                // ],
            ],
        ];
    }

}
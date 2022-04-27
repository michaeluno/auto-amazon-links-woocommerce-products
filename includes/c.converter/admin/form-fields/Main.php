<?php
namespace AutoAmazonLinks\WooCommerceProducts\Converter\Admin\FormFields;

use AutoAmazonLinks\WooCommerceProducts\Commons\Admin\FormFieldsAbstract;

/**
 * @since 0.1.0
 */
class Main extends FormFieldsAbstract {

    /**
     * @since  0.1.0
     * @return array
     */
    public function get() {
        return [
            [
                'field_id' => '_unit_to_wc_products_enable',
                'type'     => 'radio',
                'title'    => __( 'Enable', 'amazon-auto-links' ),
                'label'    => [
                    1 => __( 'On', 'amazon-auto-links' ),
                    0 => __( 'Off', 'amazon-auto-links' ),
                ],
                'default'  => 0,
            ],
        ];
    }

}
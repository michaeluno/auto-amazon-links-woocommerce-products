<?php
namespace AutoAmazonLinks\WooCommerceProducts\ButtonLabel\Admin\FormFields;

use AutoAmazonLinks\WooCommerceProducts\Commons\Admin\FormFieldsAbstract;

/**
 * @since 1.1.0
 */
class Main extends FormFieldsAbstract {

    /**
     * @since  1.1.0
     * @return array
     */
    public function get() {
        return [
            [
                'field_id' => 'custom_button_label_enable',
                'type'     => 'revealer',
                'select_type' => 'radio',
                'title'    => __( 'Custom Button Label', 'auto-amazon-links-woocommerce-products' ),
                'label'    => [
                    1 => __( 'On', 'amazon-auto-links' ),
                    0 => __( 'Off', 'amazon-auto-links' ),
                ],
                'selectors'     => array(
                    1 => '.visibility-custom-button-label-text'
                ),
                'default'  => 0,
            ],
            [
                'field_id' => 'custom_button_label_text',
                'type'     => 'text',
                'title'    => __( 'Button Label', 'auto-amazon-links-woocommerce-products' ),
                'class'         => array(
                    'fieldrow' => 'visibility-custom-button-label-text',
                ),
                'default'  => __( 'Add to Cart', 'woocommerce' ),
            ],
        ];
    }

}
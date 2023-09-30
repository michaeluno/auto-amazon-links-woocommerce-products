<?php
namespace AutoAmazonLinks\WooCommerceProducts\CartToLink\Admin\FormFields;

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
                'field_id' => 'cart_to_link_enable',
                'type'     => 'radio',
                'title'    => __( 'Cart to Link', 'auto-amazon-links-woocommerce-products' ),
                'label'    => [
                    1 => __( 'On', 'amazon-auto-links' ),
                    0 => __( 'Off', 'amazon-auto-links' ),
                ],
                'description' => __( 'When a site visitor presses the Add to Cart button, enabling this will take the visitor to the Amazon store.', 'auto-amazon-links-woocommerce-products' ),
                'default'  => 0,
            ],
        ];
    }

}
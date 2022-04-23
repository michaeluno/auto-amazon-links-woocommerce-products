<?php

namespace AutoAmazonLinks\WooCommerceProducts\Converter\Admin\PostMetaBoxes;

use AutoAmazonLinks\WooCommerceProducts\Commons\Admin\PostMetaBoxAbstract;

/**
 * @since 0.1.0
 */
class Main extends PostMetaBoxAbstract {

    /**
     * @since 0.1.0
     * @var   string[]
     */
    protected $_aFieldClasses = [
        '\\AutoAmazonLinks\\WooCommerceProducts\\Converter\\Admin\\FormFields\\Main',
    ];

}
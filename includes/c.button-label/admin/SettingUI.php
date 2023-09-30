<?php
namespace AutoAmazonLinks\WooCommerceProducts\ButtonLabel\Admin;
use  AutoAmazonLinks\WooCommerceProducts\Commons\Admin\SettingUIAbstract;

class SettingUI extends SettingUIAbstract {

    /**
     * @since 1.1.0
     * @var   string[]
     */
    protected $_aFieldClasses = [
        '\\AutoAmazonLinks\\WooCommerceProducts\\ButtonLabel\\Admin\\FormFields\\Main',
    ];

}
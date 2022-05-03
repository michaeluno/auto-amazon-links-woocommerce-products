=== Auto Amazon Links - WooCommerce Products ===
Contributors:       Michael Uno, miunosoft
Donate link:        https://en.michaeluno.jp/donate
Tags:               amazon, affiliates, amazon associates, ads, WooCommerce
Requires at least:  3.4
Requires PHP:       7.3
Tested up to:       5.9.3
Requires MySQL:     5.0.3
Stable tag:         1.0.0
License:            GPLv2 or later
License URI:        https://www.gnu.org/licenses/gpl-2.0.html

Adds on-site shopping carts using Auto Amazon Links and WooCommerce.

## Description

### On-site shopping carts

This WordPress plugin allows you to add on-site shopping carts by integrating [WooCommerce](https://wordpress.org/plugins/woocommerce/) with [Auto Amazon Links](https://wordpress.org/plugins/amazon-auto-links/).

Are you building a website that lists recommended Amazon products with an affiliate link? If so, you might want to try implementing on-site shopping carts which let site visitors gather and buy products all at once, not sending them to Amazon per click on a single product basis, which may dramatically increase the conversion rates. This plugin brings that ability to your site.

### How it works
This plugin performs periodical checks in the background once a day and converts Auto Amazon Links unit products to WooCommerce products. Once WooCommerce products are created, it's all set. The visitors can add those products to the cart. When the visitor clicks on **Proceed to Checkout** on the checkout page, it goes to the Amazon store with all the added products ready to be purchased.

### Requirements
Please make sure the both plugins are installed and activated.
- [Auto Amazon Links](https://wordpress.org/plugins/amazon-auto-links/) 5.2.8 or above.
- [WooCommerce](https://wordpress.org/plugins/woocommerce/)

## Installation

### Install
#### Installing through the UI of WordPress
1. Navigate to **Dashboard** -> **Plugins** -> **Add New**.
1. Type "*Auto Amazon Links*" in the search bar.
1. *Auto Amazon Links - WooCommerce Products* should be listed and click on **Install Now**.
1. The **Activate** button will appear and press it.

#### Installing by uploading the zip file
1. Download [amazon-auto-links-woocommerce-products.zip](https://downloads.wordpress.org/plugin/amazon-auto-links-woocommerce-products.latest-stable.zip).
1. Navigate to **Dashboard** -> **Plugins** -> **Add New**.
1. Click on the **Upload Plugin** and upload the zip file.
1. The **Activate Plugin** button will appear and press it.

#### Using FTP or Control Panel File Manager
1. Extract the files of [amazon-auto-links-woocommerce-products.zip](https://downloads.wordpress.org/plugin/amazon-auto-links-woocommerce-products.latest-stable.zip) to the `wp-content` directory. The plugin directory named `amazon-auto-links` containing files should be placed inside `wp-content`. The structure should look like,
 - /wp-content/amazon-auto-links-woocommerce-products/amazon-auto-links-woocommerce-products.php
 - /wp-content/amazon-auto-links-woocommerce-products/readme.txt
 - continues...

## Getting Started

### Convert Products Automatically
1. Have units created with Auto Amazon Links.
2. Navigate to **Dashboard** -> **Auto Amazon Links** -> **Manage Units** and click on **Edit** of the unit you want to convert to WooCommerce products.
3. Find the **Unit to WooCommerce Product Converter** section and enable the option.

### Convert Products Manually
1. Navigate to the unit listing table (**Manage Units**) and click **Renew Cache** in the row of the subject unit enabling the **Unit to WooCommerce Product Converter** unit option.

### Display Add to Cart Button in Auto Amazon Links Unit Outputs
1. Navigate to **Dashboard** -> **Auto Amazon Links** -> **Manage Units** and click on **Edit** of the unit enabling the **Unit to WooCommerce Product Converter** unit option.
2. In the **Item Format** text field in the **Template** section, insert `%wc_button%` and save the unit.

## Frequently asked questions

= I have a feature request. Where can I submit it? =
Please post it from [here](https://github.com/michaeluno/auto-amazon-links-woocommerce-products/issues/new?assignees=&labels=suggestion&template=feature_request.yml).

## Other Notes


## Screenshots

1. **Unit Option**
2. **Add to Cart**

## Changelog

#### 1.0.0
- Released.

#### Old Log
For old change logs, see [here](https://github.com/michaeluno/amazon-auto-links-woocommerce-products/blob/master/CHANGELOG.md).
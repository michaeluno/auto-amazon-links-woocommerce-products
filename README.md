# Auto Amazon Links - WooCommerce Products
Integrates WooCommerce with Auto Amazon Links

This WordPress plugin converts Auto Amazon Links units to WooCommerce products.

## Basic Behaviors

- The plugin runs periodical checks in the background once a day with units enabling the **Unit to WooCommerce Product Converter** unit option whether there are updated products. When a new product update is detected, it schedules a product conversion event.
- When the **Renew Cache** action link in the unit listing table is clicked, the plugin attempts to update products.
- When the `%wc_button%` tag is inserted in the **Item Format** unit option for units enabling the **Unit to WooCommerce Product Converter** option, it shows a WooCommerce button that adds the Auto Amazon Links product to the cart.  

## Usage

### Convert Products Automatically
1. Have units created with Auto Amazon Links.
2. Navigate to **Dashboard** -> **Auto Amazon Links** -> **Manage Units** and click on **Edit** of the unit you want to convert to WooCommerce products.
3. Find the **Unit to WooCommerce Product Converter** section and enable the option.

### Convert Products Manually 
1. Navigate to the unit listing table (**Manage Units**) and click **Renew Cache** in the row of the subject unit enabling the **Unit to WooCommerce Product Converter** unit option.

### Display Add to Cart Button in Auto Amazon Links Unit Outputs
1. Navigate to **Dashboard** -> **Auto Amazon Links** -> **Manage Units** and click on **Edit** of the unit enabling the **Unit to WooCommerce Product Converter** unit option.
2. In the **Item Format** text field in the **Template** section, insert `%wc_button%` and save the unit. 

## License
[GPL v2 or later](./LICENSE).
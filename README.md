# Tuxedo Software Update Licensing

TSUL is a WordPress plugin for managing software update licenses. It works as a standalone plugin and also has WooCommerce and Easy Digital Downloads integration. An updater plugin generator is included to create a client side update plugin for updating WordPress themes and plugins.

While this is a 1.0 release, there is still much to be completed. Tracking is limited, and at the moment only collects and displays the raw data. It would be nice to compile this tracking data into more usable metrics.

Licenses are generated per user per order, but the system will use the first available and active license it can find. Both acitvation limits and expirations are supported, if using WooCommerce or Easy Digital Downloads these can be adjusted per variable product.

TSUL identifies users with an *update key*. This key is automatically generated for the user when they access their licensing information on the front-end for either WooCommerce or Easy Digital Downloads. WooCommerce update licensing information is available to customers under their *My Account* page. Easy Digital Downloads update licensing information can be placed on any page using the shortcode [update_licenses]. Additional update key options are provided to adiministrators under the edit user page in the WordPress admin.

All updates are managed by creating a license rule. These rules tell the system how to handle an update request, such as if a license is required and where the update file is located.

The *Automatic Update* option signals to the client WordPress install that this update should be done automatically.

The *Open Update* option controls whether the update requires a license or not. If selected then the update information can be retrieved without an update key and no licensing check will be performed. When used with an e-Commerce solution this will disable automatic license generation for the product.

The *Product ID* option is a numeric ID used to identify the update, and when using an e-Commerce solution this ID should match the ID of the product in that system. This will allow TSUL to identify a product through WooCommerce or Easy Digital Downloads and automatically generate a license for it at the time of purchase.

If the product is a *variable product* (known as *child products* in TSUL), editing the license rule after creating or saving will display any variable product information. Different *activation limits* and *expiries* can be set for these.

### Update Plugin Generator

Included under the *Tools* menu is an update plugin creator, which will generate a plugin for your customers that will handle plugin and theme updates. The *Header ID* option is used to identify products (using numerical IDs) in headers of themes and plugins. An example would be "Tuxedo Update ID". Say you have created an update rule with a *Product ID* of 999. To identify this to the update plugin your theme or plugin header information should include "Tuxedo Update ID: 999".

Example for a plugin would look like:

    <?php
    /*
    Plugin Name: Example Plugin Name
    Plugin URI: https://exampleplugin.com
    Description: Example plugin
    Version: 0.1.0
    Author: The Example Plugin Team
    Author URI: http://author.exampleplugin.com
    Text Domain: example-plugin
    Domain Path: /languages
    Tuxedo Update ID: 999
    */

### REST API

TSUL uses the REST API for update requests. A namespace of *tuxedo-updater/v1* with a route of */get-updates/* is registered for this purpose.

This route takes the following inputs:

* update_key - Update key. (not required)
* ids - Comma separated list of product IDs. (required)
* versions - Comma separated list of current versions requesting updates, per product ID. (required)
* activation_id - Human readable ID for the current activation. (not required)

And gives the following output in JSON encoding:

    @return array {
        Response from update server, update key and error info.
    
        @type array            $id          {
            Update product info, array key is the product id.
        
            @type string           $package     Download file url.
            @type string           $url         Update info url.
            @type string           $new_version Update version.
            @type bool             $autoupdate  Should product be updated automatically?
            @type int              $expires     Amount of days the license will expire in, -1 for never.
        }
    
        @type array            $update_key  {
            Update key and error info.
    
            @type bool             $found       If update key is found.
            @type bool             $disabled    If update key is disabled.
    
            @type array            $error       {
                Error info.
    
                @type string           $code        Error code (INVALID_UPDATE_KEY_FORMAT).
                @type string           $message     Human readable error message.
            }
        }
    }

If no product license is found for the set of passed in parameters then nothing will be returned for those products.

Additionally standard WordPress errors may be received or an *ID_VERSION_MISMATCH* error, which means the amount of product ids passed in does not equal the amount of versions passed in.

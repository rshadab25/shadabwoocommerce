# SkyVerge Plugin Admin
A self-contained package for the WordPress administrative area for SkyVerge plugins.

## Requirements
- PHP 5.6+
- WooCommerce 3.0+

## Installation

1. Require via composer:
    ```json
    {
        "repositories": [
            {
              "type": "vcs",
              "url": "https://github.com/skyverge/wordpress-plugin-admin"
            }
        ],
        "require": {
            "skyverge/wordpress-admin": "1.0.0"
        }
    }
    ```
1. Require the loader:
    ```php
    require_once( 'vendor/skyverge/wordpress-plugin-admin/load.php' );
    ```
    - Must be required _before_ `plugins_loaded`
    - Must be required _after_ any environmental checks, like PHP version (5.6+) or WooCommerce active/version checks

#### Customization

##### Filters

## Development

* Compile assets: `gulp compile`

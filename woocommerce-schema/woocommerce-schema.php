<?php
/*
Plugin Name: WooCommerce Schema
Plugin URI:   https://github.com/seojake/woocommerce-schema
Description:  A light and simple plugin that adds Schema.org markup to your WooCommerce products.
Version:      1.0.0
Author:       Jake Punton
Author URI:   https://seojake.com/
License:      GNU General Public License v3.0
License URI:  https://www.gnu.org/licenses/gpl-3.0.en.html
Text Domain:  woo_schema
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('WOOSCHEMA__PLUGIN_DIR', plugin_dir_path( __FILE__));

require_once(WOOSCHEMA__PLUGIN_DIR . 'product.php'); // Product Schema

/* Display welcome message */

function woocommerce_schema_activation() {
    set_transient( 'woocommerce_schema_activation_message', true, 0 );
} register_activation_hook( __FILE__, 'woocommerce_schema_activation' );

function woocommerce_schema_activiation_message(){

    /* Check transient, if available display notice */
    if( get_transient( 'woocommerce_schema_activation_message' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p><strong>Welcome to WooCommerce Schema!</strong> Go to a product or product category to add your own Schema, or sit back and let it generate automatically.</p>
        </div>
    <?php }
} add_action( 'admin_notices', 'woocommerce_schema_activiation_message' );

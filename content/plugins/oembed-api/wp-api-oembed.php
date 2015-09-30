<?php
/**
 * Plugin Name: oEmbed API
 * Plugin URI:  https://github.com/swissspidy/oEmbed-API
 * Description: Allow others to easily embed your blog posts on their sites using oEmbed.
 * Version:     0.9.0-20150930
 * Author:      Pascal Birchler
 * Author URI:  https://pascalbirchler.com
 * License:     GPLv2+
 * Text Domain: oembed-api
 * Domain Path: /languages
 *
 * @package WP_oEmbed
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Init our plugin.
 *
 * @codeCoverageIgnore
 */
function oembed_api_init() {
	require_once( dirname( __FILE__ ) . '/includes/functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/default-filters.php' );
}

/**
 * Deactivate the oEmbed feature plugin.
 *
 * @codeCoverageIgnore
 */
function wp_oembed_maybe_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

// Bail early if functionality is already built into core.
if ( function_exists( 'get_oembed_endpoint_url' ) ) {
	add_action( 'admin_notices', 'wp_oembed_maybe_deactivate' );
} else {
	register_activation_hook( __FILE__, 'oembed_api_activate_plugin' );
	register_deactivation_hook( __FILE__, 'oembed_api_deactivate_plugin' );

	add_action( 'plugins_loaded', 'oembed_api_init' );
}

/**
 * Add our rewrite endpoint on plugin activation.
 *
 * @codeCoverageIgnore
 */
function oembed_api_activate_plugin() {
	wp_oembed_rewrite_endpoint();
	flush_rewrite_rules( false );
}

/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @codeCoverageIgnore
 */
function oembed_api_deactivate_plugin() {
	flush_rewrite_rules( false );
}

/**
 * Add our rewrite endpoint to permalinks and pages.
 */
function wp_oembed_rewrite_endpoint() {
	add_rewrite_endpoint( 'embed', EP_PERMALINK | EP_PAGES | EP_ATTACHMENT );
}

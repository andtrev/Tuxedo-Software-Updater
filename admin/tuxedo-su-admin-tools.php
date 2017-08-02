<?php
/**
 * Tuxedo Software Update Licensing tools admin page.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Display tools admin page.
 *
 * @since 1.0.0
 */
function tux_su_tools_admin_page() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	?>
	<div id="tux-jquery-ui" class="wrap">
		<h1 class="wp-heading-inline">
			<?php esc_html_e( 'Tuxedo Software Updater Tools', 'tuxedo-software-updater' ); ?>
		</h1>
		<hr class="wp-header-end">
		<div id="tux-su-tools-interface" style="max-width:320px;text-align:center;">
			<br><br>
			<p>
				<button class="button button-large button-primary" onclick="jQuery('#tux-su-tools-interface,#tux-su-reprocess-orders-interface').slideToggle();"><?php esc_html_e( 'Reprocess Orders', 'tuxedo-software-updater' ); ?></button>
			</p>
			<br>
			<p>
				<button class="button button-large button-primary" onclick="jQuery('#tux-su-tools-interface,#tux-su-create-update-plugin-interface').slideToggle();"><?php esc_html_e( 'Create Update Plugin', 'tuxedo-software-updater' ); ?></button>
			</p>
		</div>
		<div id="tux-su-create-update-plugin-interface" style="display:none;">
			<h2><?php esc_html_e( 'Create Update Plugin', 'tuxedo-software-updater' ); ?></h2>
			<p>
				<?php esc_html_e( 'This will create the code for an update plugin for use on customer/client WordPress installs.', 'tuxedo-software-updater' ); ?><br>
				<?php esc_html_e( 'Download and save the file into a folder named for your version of the plugin.', 'tuxedo-software-updater' ); ?><br>
				<?php esc_html_e( 'Translations for the plugin may be placed in a folder named "languages" in the plugin folder.', 'tuxedo-software-updater' ); ?><br>
				<?php esc_html_e( 'ZIP up the plugin folder and deploy.', 'tuxedo-software-updater' ); ?><br><br>
				<?php esc_html_e( 'For themes and plugins using the updater add the header ID along with the numerical ID of the product to their header info.', 'tuxedo-software-updater' ); ?><br>
			</p>
			<p>
				<label><?php esc_html_e( 'Plugin Name', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-name" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Plugin URI', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-uri" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Description', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-description" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Version', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-version" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Author', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-author" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Author URI', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-author-uri" type="text" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'License', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-license" type="text" value="GPLv2 or later" style="width:300px;">
			</p>
			<p>
				<label><?php esc_html_e( 'Text Domain', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-text-domain" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'Used for translation.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Ex', 'tuxedo-software-updater' ); ?>: tuxedo-theme-plugin-update
				</span>
			</p>
			<p>
				<label><?php esc_html_e( 'Prefix', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-prefix" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'Used in the code to differentiate from other plugins.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Should be short and contain only A-Z and a-z.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'No spaces or special characters.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Ex', 'tuxedo-software-updater' ); ?>: Tuxedo
				</span>
			</p>
			<p>
				<label><?php esc_html_e( 'REST API URL', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-rest-api-url" type="text" style="width:300px;" value="<?php echo esc_attr( get_rest_url() ); ?>">
			</p>
			<p>
				<label><?php esc_html_e( 'Header ID', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-id-header" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'Used to identify products (using numerical IDs) in headers of themes and plugins.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Ex', 'tuxedo-software-updater' ); ?>: Tuxedo Update ID
				</span>
			</p>
			<p>
				<label><?php esc_html_e( 'Settings Menu Name', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-settings-menu-name" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'Menu text, appears in the admin menu under Settings.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Ex', 'tuxedo-software-updater' ); ?>: Tuxedo Update
				</span>
			</p>
			<p>
				<label><?php esc_html_e( 'Admin Page Header Text', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-admin-header-text" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'Header text, appears at the top of the admin page.', 'tuxedo-software-updater' ); ?><br>
					<?php esc_html_e( 'Ex', 'tuxedo-software-updater' ); ?>: Tuxedo Update Settings
				</span>
			</p>
			<p>
				<label><?php esc_html_e( 'Admin Page Support URI', 'tuxedo-software-updater' ); ?>:</label><br>
				<input id="plugin-support-uri" type="text" style="width:300px;"><br>
				<span class="description">
					<?php esc_html_e( 'URI for support link at the bottom of the admin page.', 'tuxedo-software-updater' ); ?>
				</span>
			</p>
			<p>
				<br>
				<a href="" id="update-plugin-code-download" download="" style="display:none;">-</a>
				<button id="tux-create-update-plugin" class="button button-large button-primary">
					<?php esc_html_e( 'Download Update Plugin', 'tuxedo-software-updater' ); ?>
				</button>
			</p>
			<br>
			<button id="tux-su-reprocess-order-back" class="button" onclick="jQuery('#tux-su-tools-interface,#tux-su-create-update-plugin-interface').slideToggle();"><?php esc_html_e( 'Back', 'tuxedo-software-updater' ); ?></button>
		</div>
		<div id="tux-su-reprocess-orders-interface" style="display:none;">
			<h2><?php esc_html_e( 'Reprocess Orders', 'tuxedo-software-updater' ); ?></h2>
			<?php if ( class_exists( 'WooCommerce' ) || class_exists( 'Easy_Digital_Downloads' ) ) : ?>
				<p>
					<?php esc_html_e( 'This will process any completed orders and generate a license if necessary.', 'tuxedo-software-updater' ); ?>
				</p>
				<h3 id="tux-process-report"></h3><br>
				<p class="tux-date-inputs">
					<label><?php esc_html_e( 'Start Date', 'tuxedo-software-updater' ); ?>:</label><br>
					<input type="text" id="tux-start-date" class="tux-date-picker" style="width:300px;">
				</p>
				<p class="tux-date-inputs">
					<label><?php esc_html_e( 'End Date', 'tuxedo-software-updater' ); ?>:</label><br>
					<input type="text" id="tux-end-date" class="tux-date-picker" style="width:300px;">
				</p>
				<p>
					<button id="tux-process-orders" class="button button-large button-primary">
						<?php esc_html_e( 'Reprocess Orders', 'tuxedo-software-updater' ); ?>
					</button>
					<button id="tux-process-orders-cancel" class="button button-large" style="display:none;">
						<?php esc_html_e( 'Cancel', 'tuxedo-software-updater' ); ?>
					</button>
					<br><span class="description">
						<?php esc_html_e( 'This process may take awhile.', 'tuxedo-software-updater' ); ?>
					</span>
				</p>
			<?php else : ?>
				<p><?php esc_html_e( 'WooCommerce not detected.', 'tuxedo-software-updater' ); ?></p>
			<?php endif; ?>
			<br>
			<button id="tux-su-reprocess-order-back" class="button" onclick="jQuery('#tux-su-tools-interface,#tux-su-reprocess-orders-interface').slideToggle();"><?php esc_html_e( 'Back', 'tuxedo-software-updater' ); ?></button>
		</div>
	</div>
	<script>
		jQuery(document).ready(function($){
			$('#tux-create-update-plugin').click(function(){
				var update_code = "%php%\n" +
				"/**\n" +
				" * Plugin Name: %plugin-name%\n" +
				" * Plugin URI:  %plugin-uri%\n" +
				" * Description: %plugin-description%\n" +
				" * Version:     %plugin-version%\n" +
				" * Author:      %plugin-author%\n" +
				" * Author URI:  %plugin-author-uri%\n" +
				" * License:     %plugin-license%\n" +
				" * Text Domain: %plugin-text-domain%\n" +
				" * Domain Path: /languages\n" +
				" *\n" +
				" * (C) 2017, Trevor Anderson\n" +
				" *\n" +
				" * This program is free software; you can redistribute it and/or\n" +
				" * modify it under the terms of the GNU General Public License\n" +
				" * as published by the Free Software Foundation; either version 2\n" +
				" * of the License, or (at your option) any later version.\n" +
				" *\n" +
				" * This program is distributed in the hope that it will be useful,\n" +
				" * but WITHOUT ANY WARRANTY; without even the implied warranty of\n" +
				" * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the\n" +
				" * GNU General Public License for more details.\n" +
				" *\n" +
				" * You should have received a copy of the GNU General Public License\n" +
				" * along with this program; if not, write to the Free Software\n" +
				" * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.\n" +
				" *\n" +
				" * @package %plugin-prefix%ThemePluginUpdater\n" +
				" * @version %plugin-version%\n" +
				" */\n" +
				"\n" +
				"/**\n" +
				" * Updater manager class.\n" +
				" *\n" +
				" * Bootstraps the plugin.\n" +
				" *\n" +
				" * @since 1.0.0\n" +
				" */\n" +
				"class %plugin-prefix%ThemePluginUpdate {\n" +
				"\n" +
				"	/**\n" +
				"	 * Update api url.\n" +
				"	 *\n" +
				"	 * @since  1.0.0\n" +
				"	 * @access private\n" +
				"	 * @var string\n" +
				"	 */\n" +
				"	private $api_url = '%plugin-rest-api-url%';\n" +
				"\n" +
				"	/**\n" +
				"	 * Theme and plugin header for update id.\n" +
				"	 *\n" +
				"	 * @since  1.0.0\n" +
				"	 * @access private\n" +
				"	 * @var string\n" +
				"	 */\n" +
				"	private $id_header = '%plugin-id-header%';\n" +
				"\n" +
				"	/**\n" +
				"	 * %plugin-prefix%ThemePluginUpdate instance.\n" +
				"	 *\n" +
				"	 * @since  1.0.0\n" +
				"	 * @access private\n" +
				"	 * @static\n" +
				"	 * @var %plugin-prefix%ThemePluginUpdate\n" +
				"	 */\n" +
				"	private static $instance = false;\n" +
				"\n" +
				"	/**\n" +
				"	 * Get the instance.\n" +
				"	 *\n" +
				"	 * Returns the current instance, creates one if it\n" +
				"	 * doesn't exist. Ensures only one instance of\n" +
				"	 * %plugin-prefix%ThemePluginUpdate is loaded or can be loaded.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 * @static\n" +
				"	 *\n" +
				"	 * @return %plugin-prefix%ThemePluginUpdate\n" +
				"	 */\n" +
				"	public static function get_instance() {\n" +
				"\n" +
				"		if ( ! self::$instance ) {\n" +
				"			self::$instance = new self();\n" +
				"		}\n" +
				"\n" +
				"		return self::$instance;\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Constructor.\n" +
				"	 *\n" +
				"	 * Initializes and adds functions to filter and action hooks.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function __construct() {\n" +
				"\n" +
				"		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );\n" +
				"		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );\n" +
				"		add_filter( 'extra_plugin_headers', array( $this, 'extra_headers' ) );\n" +
				"		add_filter( 'extra_theme_headers', array( $this, 'extra_headers' ) );\n" +
				"		add_filter( 'site_transient_update_themes', array( $this, 'add_theme_updates' ) );\n" +
				"		add_filter( 'transient_update_themes', array( $this, 'add_theme_updates' ) );\n" +
				"		add_filter( 'site_transient_update_plugins', array( $this, 'add_plugin_updates' ) );\n" +
				"		add_filter( 'transient_update_plugins', array( $this, 'add_plugin_updates' ) );\n" +
				"		add_action( 'load-plugins.php', array( $this, 'admin_plugins' ), 30 );\n" +
				"		add_action( 'core_upgrade_preamble', array( $this, 'fix_dashboard_updates_view_version_links' ) );\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Load text domain.\n" +
				"	 *\n" +
				"	 * Attached to the plugins_loaded action.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function load_plugin_textdomain() {\n" +
				"\n" +
				"		load_plugin_textdomain( '%plugin-text-domain%', false, basename( dirname( __FILE__ ) ) . '/languages/' );\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Add ID header to plugins and themes.\n" +
				"	 *\n" +
				"	 * Attached to the extra_plugin_headers and extra_theme_headers filters.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @param array $headers Plugin and theme headers.\n" +
				"	 *\n" +
				"	 * @return array Plugin and theme headers.\n" +
				"	 */\n" +
				"	public function extra_headers( $headers ) {\n" +
				"\n" +
				"		$headers['%plugin-prefix%UpdateID'] = $this->id_header;\n" +
				"\n" +
				"		return $headers;\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Add admin page.\n" +
				"	 *\n" +
				"	 * Adds the upload settings page as settings sub-menu.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function add_admin_menu_page() {\n" +
				"\n" +
				"		$hook_suffix = add_options_page(\n" +
				"			__( '%plugin-settings-menu-name%', '%plugin-text-domain%' ),\n" +
				"			__( '%plugin-settings-menu-name%', '%plugin-text-domain%' ),\n" +
				"			'update_plugins',\n" +
				"			'%plugin-prefix-lowercase%-update',\n" +
				"			array( $this, 'admin_page' )\n" +
				"		);\n" +
				"\n" +
				"		if ( false !== $hook_suffix ) {\n" +
				"			add_action( \"load-{$hook_suffix}\", array( $this, 'admin_save_update_keys' ) );\n" +
				"		}\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Admin page.\n" +
				"	 *\n" +
				"	 * Outputs upload settings admin page.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function admin_page() {\n" +
				"\n" +
				"		if ( ! current_user_can( 'update_plugins' ) ) {\n" +
				"			return;\n" +
				"		}\n" +
				"\n" +
				"		$updates = get_option( '%plugin-prefix-lowercase%_updates' );\n" +
				"		if ( empty( $updates ) ) {\n" +
				"			$updates = $this->get_updates();\n" +
				"		}\n" +
				"\n" +
				"		$updatable = array(\n" +
				"			'plugins' => array(),\n" +
				"			'themes'  => array(),\n" +
				"		);\n" +
				"\n" +
				"		$installed = get_plugins();\n" +
				"		foreach ( $installed as $id => $plugin ) {\n" +
				"			if ( ! empty( $plugin[ $this->id_header ] ) ) {\n" +
				"				$expires = __( 'No info', '%plugin-text-domain%' );\n" +
				"				if ( isset( $updates['plugins'][ $id ]->expires, $updates['cache_time'] ) ) {\n" +
				"					if ( $updates['plugins'][ $id ]->expires < 0 ) {\n" +
				"						$expires = __( 'Never', '%plugin-text-domain%' );\n" +
				"					} else {\n" +
				"						$expires = ( (int) $updates['plugins'][ $id ]->expires - floor( ( time() - $updates['cache_time'] ) / DAY_IN_SECONDS ) ) . ' ' . __( 'days', '%plugin-text-domain%r' );\n" +
				"					}\n" +
				"				}\n" +
				"				$updatable['plugins'][] = array(\n" +
				"					'id'     => absint( $plugin[ $this->id_header ] ),\n" +
				"					'name'   => $plugin['Name'],\n" +
				"					'notice' => isset( $updates['plugins'][ $id ] ) ?\n" +
				"						'<span class=\"dashicons dashicons-yes\" style=\"color:green;width:18px;height:18px;font-size:18px;\"></span> ' . $expires :\n" +
				"						'<span class=\"dashicons dashicons-no\" style=\"color:red;width:18px;height:18px;font-size:18px;\"></span> ' .\n" +
				"						'<span style=\"color:red;\">' . __( 'No license', '%plugin-text-domain%r' ) . '</span>',\n" +
				"				);\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		$installed = wp_get_themes();\n" +
				"		foreach ( $installed as $theme ) {\n" +
				"			$theme_id_header = $theme->get( $this->id_header );\n" +
				"			if ( ! empty( $theme_id_header ) ) {\n" +
				"				$id      = $theme->get_stylesheet();\n" +
				"				$expires = __( 'No info', '%plugin-text-domain%' );\n" +
				"				if ( isset( $updates['themes'][ $id ]['expires'], $updates['cache_time'] ) ) {\n" +
				"					if ( $updates['themes'][ $id ]['expires'] < 0 ) {\n" +
				"						$expires = __( 'Never', '%plugin-text-domain%' );\n" +
				"					} else {\n" +
				"						$expires = ( (int) $updates['themes'][ $id ]['expires'] - floor( ( time() - $updates['cache_time'] ) / DAY_IN_SECONDS ) ) . ' ' . __( 'days', '%plugin-text-domain%r' );\n" +
				"					}\n" +
				"				}\n" +
				"				$updatable['themes'][] = array(\n" +
				"					'id'     => absint( $theme_id_header ),\n" +
				"					'name'   => $theme->Name,\n" +
				"					'notice' => isset( $updates['themes'][ $id ] ) ?\n" +
				"						'<span class=\"dashicons dashicons-yes\" style=\"color:green;width:18px;height:18px;font-size:18px;\"></span> ' . $expires :\n" +
				"						'<span class=\"dashicons dashicons-no\" style=\"color:red;width:18px;height:18px;font-size:18px;\"></span> ' .\n" +
				"						'<span style=\"color:red;\">' . __( 'No license', '%plugin-text-domain%r' ) . '</span>',\n" +
				"				);\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		?>\n" +
				"		<style>\n" +
				"			.theme-plugin-list {\n" +
				"				width: 400px;\n" +
				"				max-width: 100%;\n" +
				"			}\n" +
				"\n" +
				"			.tpl-title-container {\n" +
				"				width: 100%;\n" +
				"				border-bottom: 2px solid #0073aa;\n" +
				"			}\n" +
				"\n" +
				"			.tpl-info-container {\n" +
				"				border-bottom: 1px dashed #555;\n" +
				"			}\n" +
				"\n" +
				"			.tpl-title {\n" +
				"				padding: 4px 2%;\n" +
				"				width: 46%;\n" +
				"				float: left;\n" +
				"			}\n" +
				"\n" +
				"			.tpl-info {\n" +
				"				padding: 8px 2%;\n" +
				"				width: 46%;\n" +
				"				float: left;\n" +
				"			}\n" +
				"		</style>\n" +
				"		<div class=\"wrap\">\n" +
				"			<h1 class=\"wp-heading-inline\">%php% esc_html_e( '%plugin-admin-header-text%', '%plugin-text-domain%' ); ?></h1>\n" +
				"			<hr class=\"wp-header-end\">\n" +
				"			%php% if ( ! empty( $updates['update_key']['error']['message'] ) ) : ?>\n" +
				"				<div class=\"notice notice-error\">\n" +
				"					<p>\n" +
				"						%php% echo esc_html( $updates['update_key']['error']['message'] ); ?>\n" +
				"					</p>\n" +
				"				</div>\n" +
				"			%php% endif; ?>\n" +
				"			<h3>%php% esc_html_e( 'Themes', '%plugin-text-domain%' ); ?></h3>\n" +
				"			%php% if ( ! empty( $updatable['themes'] ) ) : ?>\n" +
				"				<div class=\"theme-plugin-list\">\n" +
				"					<div class=\"tpl-title-container\">\n" +
				"						<div class=\"tpl-title\">\n" +
				"							%php% esc_html_e( 'Name', '%plugin-text-domain%' ); ?>\n" +
				"						</div>\n" +
				"						<div class=\"tpl-title\">\n" +
				"							%php% esc_html_e( 'License / Expiration', '%plugin-text-domain%' ); ?>\n" +
				"						</div>\n" +
				"						<div class=\"clear\"></div>\n" +
				"					</div>\n" +
				"					<div class=\"tpl-info-container\">\n" +
				"						%php% foreach ( $updatable['themes'] as $theme ) : ?>\n" +
				"							<div class=\"tpl-info\">\n" +
				"								%php% echo esc_html( $theme['name'] ); ?>\n" +
				"							</div>\n" +
				"							<div class=\"tpl-info\">\n" +
				"								%php% echo $theme['notice']; ?>\n" +
				"							</div>\n" +
				"							<div class=\"clear\"></div>\n" +
				"						%php% endforeach; ?>\n" +
				"					</div>\n" +
				"				</div>\n" +
				"			%php% else : ?>\n" +
				"				<p>\n" +
				"					%php% esc_html_e( 'No themes found.', '%plugin-text-domain%' ); ?>\n" +
				"				</p>\n" +
				"			%php% endif; ?>\n" +
				"			<br>\n" +
				"			<h3>%php% esc_html_e( 'Plugins', '%plugin-text-domain%' ); ?></h3>\n" +
				"			%php% if ( ! empty( $updatable['plugins'] ) ) : ?>\n" +
				"				<div class=\"theme-plugin-list\">\n" +
				"					<div class=\"tpl-title-container\">\n" +
				"						<div class=\"tpl-title\">\n" +
				"							%php% esc_html_e( 'Name', '%plugin-text-domain%' ); ?>\n" +
				"						</div>\n" +
				"						<div class=\"tpl-title\">\n" +
				"							%php% esc_html_e( 'License / Expiration', '%plugin-text-domain%' ); ?>\n" +
				"						</div>\n" +
				"						<div class=\"clear\"></div>\n" +
				"					</div>\n" +
				"					<div class=\"tpl-info-container\">\n" +
				"						%php% foreach ( $updatable['plugins'] as $plugin ) : ?>\n" +
				"							<div class=\"tpl-info\">\n" +
				"								%php% echo esc_html( $plugin['name'] ); ?>\n" +
				"							</div>\n" +
				"							<div class=\"tpl-info\">\n" +
				"								%php% echo $plugin['notice']; ?>\n" +
				"							</div>\n" +
				"							<div class=\"clear\"></div>\n" +
				"						%php% endforeach; ?>\n" +
				"					</div>\n" +
				"				</div>\n" +
				"			%php% else : ?>\n" +
				"				<p>\n" +
				"					%php% esc_html_e( 'No plugins found.', '%plugin-text-domain%' ); ?>\n" +
				"				</p>\n" +
				"			%php% endif; ?>\n" +
				"			<br>\n" +
				"			<form action=\"%php% echo esc_url( admin_url( 'options-general.php?page=%plugin-prefix-lowercase%-update' ) ); ?>\" method=\"post\">\n" +
				"				%php% wp_nonce_field( '%plugin-prefix-lowercase%_save_update_keys', '%plugin-prefix-lowercase%_update_nonce' ); ?>\n" +
				"				<h3>\n" +
				"					<label for=\"%plugin-prefix-lowercase%_update_key\">%php% esc_html_e( 'Update Key', '%plugin-text-domain%' ); ?></label>\n" +
				"				</h3>\n" +
				"				<input id=\"%plugin-prefix-lowercase%_update_key\" name=\"%plugin-prefix-lowercase%_update_key\" type=\"text\" style=\"width:300px;max-width:85%;\" value=\"%php% echo esc_attr( ! empty( $updates['update_key']['key'] ) ? $updates['update_key']['key'] : '' ); ?>\">\n" +
				"				%php% if ( ! empty( $updates['update_key']['key'] ) && isset( $updates['update_key']['found'] ) ) :\n" +
				"					echo $updates['update_key']['found'] ? '<span class=\"dashicons dashicons-yes\" style=\"color:green;width:27px;height:27px;font-size:27px;\"></span>' : '<span class=\"dashicons dashicons-no\" style=\"color:red;width:27px;height:27px;font-size:27px;\"></span>';\n" +
				"				endif; ?>\n" +
				"			%php% if ( ! empty( $updates['update_key']['key'] ) && ! empty( $updates['update_key']['disabled'] ) ) : ?>\n" +
				"				<p>\n" +
				"					<span class=\"dashicons dashicons-no\" style=\"color:red;\"></span> <span style=\"color:red;\">%php% esc_html_e( 'Updates for this key have been disabled.', '%plugin-text-domain%' ); ?></span><br>\n" +
				"					%php% esc_html_e( 'Please contact the site\\'s support with any questions or for more information.', '%plugin-text-domain%' ); ?>\n" +
				"				</p>\n" +
				"			%php% endif; ?>\n" +
				"			<p>\n" +
				"				<input type=\"submit\" class=\"button button-primary button-large\" name=\"%plugin-prefix-lowercase%_save_key\" value=\"%php% esc_attr_e( 'Save Changes / Reload Updates', '%plugin-text-domain%' ); ?>\">\n" +
				"			</p>\n" +
				"			</form>\n" +
				"			<br>\n" +
				"			<h3><span class=\"dashicons dashicons-sos\" style=\"color:#d54e21;\"></span> <a href=\"%plugin-support-uri%\">%php% esc_html_e( 'Support', '%plugin-text-domain%' ); ?></a></h3>\n" +
				"		</div>\n" +
				"		%php%\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Save admin page changes.\n" +
				"	 *\n" +
				"	 * Save update key changes, if any. Will always reload updates from the update server.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function admin_save_update_keys() {\n" +
				"\n" +
				"		if ( current_user_can( 'update_plugins' ) && isset( $_POST['%plugin-prefix-lowercase%_update_key'], $_POST['%plugin-prefix-lowercase%_update_nonce'] ) && wp_verify_nonce( $_POST['%plugin-prefix-lowercase%_update_nonce'], '%plugin-prefix-lowercase%_save_update_keys' ) ) {\n" +
				"			$update_key = sanitize_text_field( $_POST['%plugin-prefix-lowercase%_update_key'] );\n" +
				"			$this->get_updates( $update_key );\n" +
				"		}\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Get updates from update server.\n" +
				"	 *\n" +
				"	 * Pass $update_key to update the key and reload updates from the update server.\n" +
				"	 * Otherwise a cached response from the update server may be returned.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @global string $wp_version  WordPress version.\n" +
				"	 *\n" +
				"	 * @param string  $update_key  Optional. Update key. Default null.\n" +
				"	 *\n" +
				"	 * @return array {\n" +
				"	 * Response from update server, update key and error info.\n" +
				"	 *\n" +
				"	 * @type array    $plugins     {\n" +
				"	 * Plugin updates.\n" +
				"	 *\n" +
				"	 * @type object   $id          {\n" +
				"	 * Update product info, array key is the product id.\n" +
				"	 *\n" +
				"	 * @type string   $package     Download file url.\n" +
				"	 * @type string   $url         Update info url.\n" +
				"	 * @type string   $new_version Update version.\n" +
				"	 * @type bool     $autoupdate  Should product be updated automatically?\n" +
				"	 * @type int      $expires     Amount of days the license will expire in, -1 for never.\n" +
				"	 * }\n" +
				"	 * }\n" +
				"	 * @type array    $themes      {\n" +
				"	 * Theme updates.\n" +
				"	 *\n" +
				"	 * @type array    $id          {\n" +
				"	 * Update product info, array key is the product id.\n" +
				"	 *\n" +
				"	 * @type string   $package     Download file url.\n" +
				"	 * @type string   $url         Update info url.\n" +
				"	 * @type string   $new_version Update version.\n" +
				"	 * @type bool     $autoupdate  Should product be updated automatically?\n" +
				"	 * @type int      $expires     Amount of days the license will expire in, -1 for never.\n" +
				"	 * }\n" +
				"	 * }\n" +
				"	 * @type array    $update_key  {\n" +
				"	 * Update key and error info.\n" +
				"	 *\n" +
				"	 * @type string   $key         Update key.\n" +
				"	 * @type bool     $found       If update key is found.\n" +
				"	 * @type bool     $disabled    If update key is disabled.\n" +
				"	 * @type array    $error       {\n" +
				"	 * Error info.\n" +
				"	 *\n" +
				"	 * @type string   $code        Error code (INVALID_UPDATE_KEY_FORMAT or SERVER_ERROR).\n" +
				"	 * @type string   $message     Human readable error message.\n" +
				"	 * }\n" +
				"	 * }\n" +
				"	 * }\n" +
				"	 */\n" +
				"	public function get_updates( $update_key = null ) {\n" +
				"\n" +
				"		$doing_cron   = wp_doing_cron();\n" +
				"		$current_time = time();\n" +
				"		$cache_expire = 12 * HOUR_IN_SECONDS;\n" +
				"		if ( $doing_cron ) {\n" +
				"			$cache_expire = 0;\n" +
				"		} elseif ( is_admin() && function_exists( 'get_current_screen' ) ) {\n" +
				"			$screen = get_current_screen();\n" +
				"			if ( isset( $screen->id ) && ( 'update-core' === $screen->id || 'plugins' === $screen->id || 'themes' === $screen->id || 'settings_page_%plugin-prefix-lowercase%-update' === $screen->id ) ) {\n" +
				"				$cache_expire = HOUR_IN_SECONDS;\n" +
				"			}\n" +
				"		}\n" +
				"		$cache_expire = $current_time - $cache_expire;\n" +
				"		$updates      = get_option( '%plugin-prefix-lowercase%_updates' );\n" +
				"\n" +
				"		if ( null === $update_key && ! empty( $updates['cache_time'] ) && $updates['cache_time'] > $cache_expire ) {\n" +
				"			return $updates;\n" +
				"		}\n" +
				"\n" +
				"		global $wp_version;\n" +
				"		$updates_request = array(\n" +
				"			'update_key' => '',\n" +
				"		);\n" +
				"\n" +
				"		if ( null !== $update_key ) {\n" +
				"			$updates_request['update_key'] = $update_key;\n" +
				"		} else {\n" +
				"			if ( ! empty( $updates['update_key'] ) ) {\n" +
				"				$updates_request['update_key'] = $updates['update_key']['key'];\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		if ( ! function_exists( 'get_plugins' ) ) {\n" +
				"			require_once ABSPATH . 'wp-admin/includes/plugin.php';\n" +
				"		}\n" +
				"\n" +
				"		$installed = get_plugins();\n" +
				"		foreach ( $installed as $id => $plugin ) {\n" +
				"			if ( ! empty( $plugin[ $this->id_header ] ) ) {\n" +
				"				$update_id                                  = absint( $plugin[ $this->id_header ] );\n" +
				"				$updates_request['ids'][]                   = $update_id;\n" +
				"				$updates_request['versions'][ $update_id ]  = $plugin['Version'];\n" +
				"				$updates_request['wp_id'][ $update_id ]     = $id;\n" +
				"				$updates_request['is_plugin'][ $update_id ] = true;\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		$installed = wp_get_themes();\n" +
				"		foreach ( $installed as $theme ) {\n" +
				"			$theme_id_header = $theme->get( $this->id_header );\n" +
				"			if ( ! empty( $theme_id_header ) ) {\n" +
				"				$update_id                                  = absint( $theme->get( $this->id_header ) );\n" +
				"				$updates_request['ids'][]                   = $update_id;\n" +
				"				$updates_request['versions'][ $update_id ]  = $theme->Version;\n" +
				"				$updates_request['wp_id'][ $update_id ]     = $theme->get_stylesheet();\n" +
				"				$updates_request['is_plugin'][ $update_id ] = false;\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		$updates['plugins'] = array();\n" +
				"		$updates['themes']  = array();\n" +
				"		unset( $updates['update_key']['error'] );\n" +
				"\n" +
				"		if ( isset( $updates_request['ids'] ) && count( $updates_request['ids'] ) > 0 ) {\n" +
				"			if ( $doing_cron ) {\n" +
				"				$timeout = 30;\n" +
				"			} else {\n" +
				"				/* Three seconds, plus one extra second for every 10 plugins */\n" +
				"				$timeout = 3 + (int) ( count( $updates_request['ids'] ) / 10 );\n" +
				"			}\n" +
				"\n" +
				"			$options = array(\n" +
				"				'timeout'    => $timeout,\n" +
				"				'body'       => array(\n" +
				"					'ids'           => implode( ',', $updates_request['ids'] ),\n" +
				"					'versions'      => implode( ',', $updates_request['versions'] ),\n" +
				"					'update_key'    => isset( $updates_request['update_key'] ) ? $updates_request['update_key'] : '',\n" +
				"					'activation_id' => get_bloginfo( 'url' ),\n" +
				"				),\n" +
				"				'user-agent' => 'WordPress/' . $wp_version . ' PHP/' . phpversion() . ' (' . php_uname( 's' ) . ';)',\n" +
				"			);\n" +
				"\n" +
				"			$raw_response = wp_remote_post( trailingslashit( $this->api_url ) . 'tuxedo-updater/v1/get-updates/', $options );\n" +
				"\n" +
				"			if ( is_wp_error( $raw_response ) || 200 !== wp_remote_retrieve_response_code( $raw_response ) ) {\n" +
				"				$updates['update_key']['error'] = array(\n" +
				"					'code'    => 'SERVER_ERROR',\n" +
				"					'message' => __( 'Updates unavailable. Something may be wrong with the update server or this server&#8217;s configuration.', '%plugin-text-domain%' ),\n" +
				"				);\n" +
				"				$updates['update_key']['found'] = false;\n" +
				"				unset( $updates['update_key']['disabled'] );\n" +
				"			} else {\n" +
				"				$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );\n" +
				"\n" +
				"				$updates['update_key'] = $response['update_key'];\n" +
				"				unset( $response['update_key'] );\n" +
				"\n" +
				"				foreach ( $response as $id => $item ) {\n" +
				"					if ( isset( $updates_request['is_plugin'][ $id ] ) ) {\n" +
				"						if ( true === $updates_request['is_plugin'][ $id ] ) {\n" +
				"							$updates['plugins'][ $updates_request['wp_id'][ $id ] ]         = (object) $item;\n" +
				"							$updates['plugins'][ $updates_request['wp_id'][ $id ] ]->plugin = $updates_request['wp_id'][ $id ];\n" +
				"						}\n" +
				"						if ( false === $updates_request['is_plugin'][ $id ] ) {\n" +
				"							$updates['themes'][ $updates_request['wp_id'][ $id ] ]          = $item;\n" +
				"							$updates['themes'][ $updates_request['wp_id'][ $id ] ]['theme'] = $updates_request['wp_id'][ $id ];\n" +
				"						}\n" +
				"					}\n" +
				"				}\n" +
				"			}\n" +
				"		} // End if().\n" +
				"\n" +
				"		$updates['update_key']['key'] = $updates_request['update_key'];\n" +
				"		if ( isset( $updates['update_key']['error']['code'] ) && 'INVALID_UPDATE_KEY_FORMAT' === $updates['update_key']['error']['code'] ) {\n" +
				"			if ( empty( $updates['update_key']['key'] ) ) {\n" +
				"				$updates['update_key']['error']['message'] = '';\n" +
				"			} else {\n" +
				"				$updates['update_key']['error']['message'] = __( 'Invalid update key format. Please try entering your update key again.', '%plugin-text-domain%' );\n" +
				"			}\n" +
				"		}\n" +
				"		$updates['cache_time'] = $current_time;\n" +
				"\n" +
				"		update_option( '%plugin-prefix-lowercase%_updates', $updates, false );\n" +
				"\n" +
				"		return $updates;\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Add plugin updates to WordPress updates.\n" +
				"	 *\n" +
				"	 * Attached to the site_transient_update_plugins and transient_update_plugins filters.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @param object $value WordPress plugin update info.\n" +
				"	 *\n" +
				"	 * @return object\n" +
				"	 */\n" +
				"	public function add_plugin_updates( $value ) {\n" +
				"\n" +
				"		if ( ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) || ! isset( $value->response ) ) {\n" +
				"			return $value;\n" +
				"		}\n" +
				"\n" +
				"		$updates = $this->get_updates();\n" +
				"		if ( ! function_exists( 'get_plugins' ) ) {\n" +
				"			require_once ABSPATH . 'wp-admin/includes/plugin.php';\n" +
				"		}\n" +
				"		$installed = get_plugins();\n" +
				"		foreach ( $updates['plugins'] as $id => $plugin ) {\n" +
				"			if ( version_compare( $installed[ $id ]['Version'], $plugin->new_version, '>=' ) ) {\n" +
				"				unset( $updates['plugins'][ $id ] );\n" +
				"			}\n" +
				"		}\n" +
				"		$value->response = array_merge( $value->response, $updates['plugins'] );\n" +
				"\n" +
				"		return $value;\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Add theme updates to WordPress updates.\n" +
				"	 *\n" +
				"	 * Attached to the site_transient_update_themes and transient_update_themes filters.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @param object $value WordPress theme update info.\n" +
				"	 *\n" +
				"	 * @return object\n" +
				"	 */\n" +
				"	public function add_theme_updates( $value ) {\n" +
				"\n" +
				"		if ( ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) || ! isset( $value->response ) ) {\n" +
				"			return $value;\n" +
				"		}\n" +
				"\n" +
				"		$updates = $this->get_updates();\n" +
				"		$installed = wp_get_themes();\n" +
				"		foreach ( $updates['themes'] as $id => $theme ) {\n" +
				"			if ( version_compare( $installed[ $id ]->Version, $theme['new_version'], '>=' ) ) {\n" +
				"				unset( $updates['themes'][ $id ] );\n" +
				"			}\n" +
				"		}\n" +
				"		$value->response = array_merge( $value->response, $updates['themes'] );\n" +
				"\n" +
				"		return $value;\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Fix view version links in Dashboard -> Updates.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function fix_dashboard_updates_view_version_links() {\n" +
				"\n" +
				"		?>\n" +
				"		<script>\n" +
				"			jQuery(document).ready(function($){\n" +
				"				%php% $updates = $this->get_updates(); ?>\n" +
				"				%php% foreach ( $updates['plugins'] as $id => $plugin ) : ?>\n" +
				"				$('input[value=\"%php% echo esc_attr( $id ); ?>\"]').parent().next('.plugin-title').find('.open-plugin-details-modal').attr('href','%php% echo esc_attr( $plugin->url ); ?>').removeClass('thickbox open-plugin-details-modal');\n" +
				"				%php% endforeach; ?>\n" +
				"			});\n" +
				"		<\/script>\n" +
				"		%php%\n" +
				"\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Take over admin plugin update info.\n" +
				"	 *\n" +
				"	 * Switch from standard WordPress update info and add any error messages\n" +
				"	 * for plugins we are updating. Attached to the load-plugins.php action.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 */\n" +
				"	public function admin_plugins() {\n" +
				"\n" +
				"		$updates = get_option( '%plugin-prefix-lowercase%_updates' );\n" +
				"		$installed = get_plugins();\n" +
				"		foreach ( $updates['plugins'] as $id => $plugin ) {\n" +
				"			if ( version_compare( $installed[ $id ]['Version'], $plugin->new_version, '<' ) ) {\n" +
				"				remove_action( \"after_plugin_row_{$plugin->plugin}\", 'wp_plugin_update_row', 10 );\n" +
				"				add_action( \"after_plugin_row_{$plugin->plugin}\", array( $this, 'plugin_update_row' ), 10, 2 );\n" +
				"			}\n" +
				"		}\n" +
				"\n" +
				"		foreach ( $installed as $id => $plugin ) {\n" +
				"			if ( ! empty( $plugin[ $this->id_header ] ) && empty( $updates['plugins'][ $id ] ) ) {\n" +
				"				add_action( \"after_plugin_row_{$id}\", array( $this, 'plugin_error_row' ), 10, 2 );\n" +
				"			}\n" +
				"		}\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Output update info for plugins admin page.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @param string $file        Plugin basename.\n" +
				"	 * @param array  $plugin_data Plugin info.\n" +
				"	 *\n" +
				"	 * @return bool|void\n" +
				"	 */\n" +
				"	public function plugin_update_row( $file, $plugin_data ) {\n" +
				"\n" +
				"		$updates = get_option( '%plugin-prefix-lowercase%_updates' );\n" +
				"		if ( ! isset( $updates['plugins'][ $file ] ) ) {\n" +
				"			return false;\n" +
				"		}\n" +
				"\n" +
				"		$response = $updates['plugins'][ $file ];\n" +
				"\n" +
				"		$plugins_allowedtags = array(\n" +
				"			'a'       => array( 'href' => array(), 'title' => array() ),\n" +
				"			'abbr'    => array( 'title' => array() ),\n" +
				"			'acronym' => array( 'title' => array() ),\n" +
				"			'code'    => array(),\n" +
				"			'em'      => array(),\n" +
				"			'strong'  => array(),\n" +
				"		);\n" +
				"\n" +
				"		$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );\n" +
				"		$details_url = '';\n" +
				"		if ( ! empty( $response->url ) ) {\n" +
				"			$details_url = $response->url;\n" +
				"		} elseif ( ! empty( $plugin_data['PluginURI'] ) ) {\n" +
				"			$details_url = $plugin_data['PluginURI'];\n" +
				"		} elseif ( ! empty( $plugin_data['AuthorURI'] ) ) {\n" +
				"			$details_url = $plugin_data['AuthorURI'];\n" +
				"		}\n" +
				"		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );\n" +
				"\n" +
				"		if ( is_network_admin() || ! is_multisite() ) {\n" +
				"			if ( is_network_admin() ) {\n" +
				"				$active_class = is_plugin_active_for_network( $file ) ? ' active' : '';\n" +
				"			} else {\n" +
				"				$active_class = is_plugin_active( $file ) ? ' active' : '';\n" +
				"			}\n" +
				"\n" +
				"			echo '<tr class=\"plugin-update-tr' . $active_class . '\" id=\"' . esc_attr( $file . '-update' ) . '\" data-slug=\"' . esc_attr( $file ) . '\" data-plugin=\"' . esc_attr( $file ) . '\"><td colspan=\"' . esc_attr( $wp_list_table->get_column_count() ) . '\" class=\"plugin-update colspanchange\"><div class=\"update-message notice inline notice-warning notice-alt\"><p>';\n" +
				"\n" +
				"			if ( ! current_user_can( 'update_plugins' ) ) {\n" +
				"				/* translators: 1: plugin name */\n" +
				"				printf( __( 'There is a new version of %1$s available.', '%plugin-text-domain%' ), $plugin_name );\n" +
				"				if ( ! empty( $details_url ) ) {\n" +
				"					/* translators: 1: details URL, 2: additional link attributes, 3 version number */\n" +
				"					printf( __( ' <a href=\"%1$s\" %2$s>View version %3$s details</a>.', '%plugin-text-domain%' ),\n" +
				"						esc_url( $details_url ),\n" +
				"						sprintf( 'aria-label=\"%s\"',\n" +
				"							/* translators: 1: plugin name, 2: version number */\n" +
				"							esc_attr( sprintf( __( 'View %1$s version %2$s details', '%plugin-text-domain%' ), $plugin_name, $response->new_version ) )\n" +
				"						),\n" +
				"						$response->new_version\n" +
				"					);\n" +
				"				}\n" +
				"			} elseif ( empty( $response->package ) ) {\n" +
				"				/* translators: 1: plugin name */\n" +
				"				printf( __( 'There is a new version of %1$s available.', '%plugin-text-domain%' ), $plugin_name );\n" +
				"				if ( ! empty( $details_url ) ) {\n" +
				"					/* translators: 1: details URL, 2: additional link attributes, 3 version number */\n" +
				"					printf( __( ' <a href=\"%1$s\" %2$s>View version %3$s details</a>.', '%plugin-text-domain%' ),\n" +
				"						esc_url( $details_url ),\n" +
				"						sprintf( 'aria-label=\"%s\"',\n" +
				"							/* translators: 1: plugin name, 2: version number */\n" +
				"							esc_attr( sprintf( __( 'View %1$s version %2$s details', '%plugin-text-domain%' ), $plugin_name, $response->new_version ) )\n" +
				"						),\n" +
				"						$response->new_version\n" +
				"					);\n" +
				"				}\n" +
				"				echo '<em>' . __( 'Automatic update is unavailable for this plugin.', '%plugin-text-domain%' ) . '</em>';\n" +
				"			} else {\n" +
				"				/* translators: 1: plugin name */\n" +
				"				printf( __( 'There is a new version of %1$s available.', '%plugin-text-domain%' ), $plugin_name );\n" +
				"				if ( ! empty( $details_url ) ) {\n" +
				"					/* translators: 1: details URL, 2: additional link attributes, 3 version number */\n" +
				"					printf( __( ' <a href=\"%1$s\" %2$s>View version %3$s details</a>, or ', '%plugin-text-domain%' ),\n" +
				"						esc_url( $details_url ),\n" +
				"						sprintf( 'aria-label=\"%s\"',\n" +
				"							/* translators: 1: plugin name, 2: version number */\n" +
				"							esc_attr( sprintf( __( 'View %1$s version %2$s details', '%plugin-text-domain%' ), $plugin_name, $response->new_version ) )\n" +
				"						),\n" +
				"						$response->new_version\n" +
				"					);\n" +
				"				}\n" +
				"				/* translators: 1: update URL, 2: additional link attributes */\n" +
				"				printf( __( ' <a href=\"%1$s\" %2$s>Update now</a>.', '%plugin-text-domain%r' ),\n" +
				"					wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file, 'upgrade-plugin_' . $file ),\n" +
				"					sprintf( 'class=\"update-link\" aria-label=\"%s\"',\n" +
				"						/* translators: %s: plugin name */\n" +
				"						esc_attr( sprintf( __( 'Update %s now', '%plugin-text-domain%' ), $plugin_name ) )\n" +
				"					)\n" +
				"				);\n" +
				"			}\n" +
				"\n" +
				"			/** This action is documented in wp-admin/includes/update.php */\n" +
				"			do_action( \"in_plugin_update_message-{$file}\", $plugin_data, $response );\n" +
				"\n" +
				"			echo '</p></div></td></tr>';\n" +
				"		}\n" +
				"	}\n" +
				"\n" +
				"	/**\n" +
				"	 * Output error info for plugins admin page.\n" +
				"	 *\n" +
				"	 * @since 1.0.0\n" +
				"	 *\n" +
				"	 * @param string $file        Plugin basename.\n" +
				"	 * @param array  $plugin_data Plugin info.\n" +
				"	 *\n" +
				"	 * @return bool|void\n" +
				"	 */\n" +
				"	public function plugin_error_row( $file, $plugin_data ) {\n" +
				"\n" +
				"		$updates       = get_option( '%plugin-prefix-lowercase%_updates' );\n" +
				"		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );\n" +
				"\n" +
				"		if ( is_network_admin() || ! is_multisite() ) {\n" +
				"			if ( is_network_admin() ) {\n" +
				"				$active_class = is_plugin_active_for_network( $file ) ? ' active' : '';\n" +
				"			} else {\n" +
				"				$active_class = is_plugin_active( $file ) ? ' active' : '';\n" +
				"			}\n" +
				"\n" +
				"			echo '<tr class=\"plugin-update-tr' . $active_class . '\" id=\"' . esc_attr( $file . '-update' ) . '\" data-slug=\"' . esc_attr( $file ) . '\" data-plugin=\"' . esc_attr( $file ) . '\"><td colspan=\"' . esc_attr( $wp_list_table->get_column_count() ) . '\" class=\"plugin-update colspanchange\"><div class=\"update-message notice inline notice-alt notice-error\"><p>';\n" +
				"			if ( isset( $updates['update_key']['error']['code'] ) && 'SERVER_ERROR' === $updates['update_key']['error']['code'] ) {\n" +
				"				echo esc_html( $updates['update_key']['error']['message'] );\n" +
				"				/* translators: 1: update settings url */\n" +
				"				printf( ' ' . __( '<a href=\"%s\">Update settings</a>', '%plugin-text-domain%' ), esc_url( admin_url( 'options-general.php?page=%plugin-prefix-lowercase%-update' ) ) );\n" +
				"			} else {\n" +
				"				/* translators: 1: update settings url */\n" +
				"				printf( __( 'Updates unavailable. No license found. <a href=\"%s\">Update settings</a>', '%plugin-text-domain%' ), esc_url( admin_url( 'options-general.php?page=%plugin-prefix-lowercase%-update' ) ) );\n" +
				"			}\n" +
				"			echo '</p></div></td></tr>';\n" +
				"		}\n" +
				"	}\n" +
				"}\n" +
				"\n" +
				"// Instantiate the plugin class.\n" +
				"$%plugin-prefix-lowercase%_theme_plugin_update = %plugin-prefix%ThemePluginUpdate::get_instance();\n" +
				"\n";
				var plugin_map = {
					'%php%': '<?php echo '<?php'; ?>',
					'%plugin-name%': $('#plugin-name').val(),
					'%plugin-uri%': $('#plugin-uri').val(),
					'%plugin-description%': $('#plugin-description').val(),
					'%plugin-version%': $('#plugin-version').val(),
					'%plugin-author%': $('#plugin-author').val(),
					'%plugin-author-uri%': $('#plugin-author-uri').val(),
					'%plugin-license%': $('#plugin-license').val(),
					'%plugin-text-domain%': $('#plugin-text-domain').val(),
					'%plugin-prefix%': $('#plugin-prefix').val(),
					'%plugin-prefix-lowercase%': $('#plugin-prefix').val().toLowerCase(),
					'%plugin-rest-api-url%': $('#plugin-rest-api-url').val(),
					'%plugin-id-header%': $('#plugin-id-header').val(),
					'%plugin-settings-menu-name%': $('#plugin-settings-menu-name').val(),
					'%plugin-admin-header-text%': $('#plugin-admin-header-text').val(),
					'%plugin-support-uri%': $('#plugin-support-uri').val()
				};
				update_code = update_code.replace(/%php%|%plugin-name%|%plugin-uri%|%plugin-description%|%plugin-version%|%plugin-author%|%plugin-author-uri%|%plugin-license%|%plugin-text-domain%|%plugin-prefix%|%plugin-prefix-lowercase%|%plugin-rest-api-url%|%plugin-id-header%|%plugin-settings-menu-name%|%plugin-admin-header-text%|%plugin-support-uri%/g, function(matched){
					return plugin_map[matched];
				});
				$('#update-plugin-code-download').attr('href','data:text/plain;base64,' + window.btoa(update_code));
				$('#update-plugin-code-download').attr('download','class-' + $('#plugin-prefix').val().toLowerCase() + '-theme-plugin-update.php');
				document.getElementById('update-plugin-code-download').click();
			});
		});
		<?php if ( class_exists( 'WooCommerce' ) || class_exists( 'Easy_Digital_Downloads' ) ) : ?>
		var tux_order_page = 1;
		var tux_order_total = 0;
		var tux_process_cancel = false;
		var tux_su_tools_nonce = '<?php echo esc_attr( wp_create_nonce( 'tux_su_tools_ajax' ) ); ?>';
		jQuery(document).ready(function ($) {
			$('.tux-date-picker').datepicker({
				changeMonth: true,
				changeYear: true
			});
			$('#tux-process-orders').click(function () {
				$(this).hide();
				$('#tux-su-reprocess-order-back').hide();
				$('#tux-process-orders-cancel').show();
				tux_order_page = 1;
				tux_order_total = 0;
				$('.tux-date-inputs').hide();
				$('#tux-process-report').html('<?php esc_html_e( 'Processing orders', 'tuxedo-software-updater' ); ?>: <span id="tux-process-orders-count"></span>');
				tux_process_orders();
			});
			$('#tux-process-orders-cancel').click(function () {
				tux_process_cancel = true;
			});
		});
		function tux_process_orders() {
			jQuery.post(
				ajaxurl,
				{
					action: 'tux_su_process_orders',
					tux_su_tools_nonce: tux_su_tools_nonce,
					tux_su_page: tux_order_page,
					tux_su_start_date: jQuery('#tux-start-date').val(),
					tux_su_end_date: jQuery('#tux-end-date').val()
				},
				function (data) {
					var ret = jQuery.parseJSON(data);
					if (tux_process_cancel) {
						jQuery('#tux-process-report').html('<?php esc_html_e( 'Cancelled', 'tuxedo-software-updater' ); ?>: ' + tux_order_total + ' <?php esc_html_e( 'orders processed', 'tuxedo-software-updater' ); ?>');
						jQuery('#tux-process-orders-cancel').hide();
						jQuery('.tux-date-inputs,#tux-process-orders,#tux-su-reprocess-order-back').show();
						return;
					}
					if (ret.hasOwnProperty('error')) {
						switch (ret['error']['code']) {
							case 'ACCESS_DENIED':
							case 'PARAMETER_INCORRECT':
								jQuery('#tux-process-report').html('<?php esc_html_e( 'Error: Please try again.', 'tuxedo-software-updater' ); ?>');
								jQuery('#tux-process-orders-cancel').hide();
								jQuery('.tux-date-inputs,#tux-process-orders,tux-su-reprocess-order-back').show();
								return;
							case 'NO_DATA':
								if (1 === tux_order_page) {
									jQuery('#tux-process-report').html('<?php esc_html_e( 'No orders found', 'tuxedo-software-updater' ); ?>');
								} else {
									jQuery('#tux-process-report').html('<?php esc_html_e( 'Finished', 'tuxedo-software-updater' ); ?>: ' + tux_order_total + ' <?php esc_html_e( 'orders processed', 'tuxedo-software-updater' ); ?>');
								}
								jQuery('#tux-process-orders-cancel').hide();
								jQuery('.tux-date-inputs,#tux-process-orders,#tux-su-reprocess-order-back').show();
								return;
						}
					}
					tux_order_page++;
					tux_order_total += ret['processed'];
					jQuery('#tux-process-orders-count').html((Math.floor(ret['processed'] * ret['page'])) + ' / ' + ret['total'] + ' (' + (Math.floor((ret['processed'] * ret['page']) / ret['total']) * 100) + '%)');
					setTimeout(function () {
						tux_process_orders();
					}, 300);
				}
			);
		}
		<?php endif; ?>
	</script>
	<?php

}

/**
 * Enqueue jQuery date picker.
 *
 * @since 1.0.0
 */
function tux_su_admin_enqueue_tools() {

	wp_enqueue_style( 'tux-su-jquery-ui-css', plugins_url( '/css/jquery-ui.min.css', __FILE__ ) );
	wp_enqueue_script( 'jquery-ui-datepicker' );

}

/**
 * Process orders.
 *
 * @since 1.0.0
 */
function tux_su_tools_process_orders() {

	if ( ! current_user_can( 'administrator' ) ) {

		echo wp_json_encode( array(
				'error' => array(
					'code' => 'ACCESS_DENIED',
				),
			)
		);

		exit();

	}

	if ( ! isset( $_POST['tux_su_tools_nonce'], $_POST['tux_su_page'], $_POST['tux_su_start_date'], $_POST['tux_su_end_date'] ) || ! wp_verify_nonce( $_POST['tux_su_tools_nonce'], 'tux_su_tools_ajax' ) || absint( $_POST['tux_su_page'] ) < 1 ) {

		echo wp_json_encode( array(
				'error' => array(
					'code' => 'PARAMETER_INCORRECT',
				),
			)
		);

		exit();

	}

	if ( class_exists( 'WooCommerce' ) ) {

		$post_type = 'shop_order';
		$post_status = 'wc-completed';

	} elseif ( class_exists( 'Easy_Digital_Downloads' ) ) {

		$post_type = 'edd_payment';
		$post_status = 'publish';

	}

	if ( empty( $post_type ) || empty( $post_status ) ) {

		echo wp_json_encode( array(
				'error' => array(
					'code' => 'PARAMETER_INCORRECT',
				),
			)
		);

		exit();

	}

	$orders = new WP_Query( array(
		'posts_per_page' => 10,
		'paged'          => absint( $_POST['tux_su_page'] ),
		'post_type'      => $post_type,
		'post_status'    => $post_status,
		'date_query'     => array(
			array(
				'after'     => empty( $_POST['tux_su_start_date'] ) ? '0' : sanitize_text_field( $_POST['tux_su_start_date'] ),
				'before'    => empty( $_POST['tux_su_end_date'] ) ? current_time( 'mysql' ) : sanitize_text_field( $_POST['tux_su_end_date'] ) . ' 23:59:59',
				'inclusive' => true,
			),
		),
		'fields'         => 'ids',
	) );

	if ( $orders->post_count < 1 ) {

		echo wp_json_encode( array(
				'error' => array(
					'code' => 'NO_DATA',
				),
			)
		);

		exit();

	}

	foreach ( $orders->posts as $order_id ) {

		if ( class_exists( 'WooCommerce' ) ) {

			tux_su_woo_create_license_on_order_completed( $order_id );

		} elseif ( class_exists( 'Easy_Digital_Downloads' ) ) {

			tux_su_edd_create_license_on_order_completed( $order_id );

		}
	}

	echo wp_json_encode( array(
		'total'     => $orders->found_posts,
		'processed' => $orders->post_count,
		'page'      => absint( $_POST['tux_su_page'] ),
	) );
	exit();

}

add_action( 'wp_ajax_tux_su_process_orders', 'tux_su_tools_process_orders' );

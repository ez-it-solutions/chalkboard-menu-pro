<?php
/**
 * Plugin Name:       Chalkboard Menu Pro
 * Plugin URI:        https://github.com/ez-it-solutions/chalkboard-menu-pro
 * Description:       Beautiful chalkboard-style menu boards with flexible layouts, shortcode support, and page-builder integration.
 * Version:           0.1.0
 * Author:            Ez IT Solutions
 * Author URI:        https://ez-it-solutions.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chalkboard-menu-pro
 * Domain Path:       /languages
 *
 * @package Chalkboard_Menu_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Chalkboard_Menu_Pro' ) ) {
	/**
	 * Main plugin class for Chalkboard Menu Pro.
	 *
	 * This class is intentionally lightweight for the initial version.
	 * It registers assets, a shortcode, and a basic admin page that we will
	 * enhance with additional functionality in later iterations.
	 */
	class Chalkboard_Menu_Pro {

		/**
		 * Singleton instance.
		 *
		 * @var Chalkboard_Menu_Pro|null
		 */
		protected static $instance = null;

		/**
		 * Get singleton instance.
		 *
		 * @return Chalkboard_Menu_Pro
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Chalkboard_Menu_Pro constructor.
		 *
		 * Hooks are registered here to keep the global namespace minimal.
		 */
		protected function __construct() {
			$this->define_constants();

			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
			add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
			add_shortcode( 'chalkboard_menu_pro', array( $this, 'render_chalkboard_menu_shortcode' ) );
		}

		/**
		 * Register custom post types for boards and menu items.
		 */
		public function register_post_types() {
			register_post_type(
				'cmp_board',
				array(
					'labels'      => array(
						'name'          => __( 'Chalkboard Boards', 'chalkboard-menu-pro' ),
						'singular_name' => __( 'Chalkboard Board', 'chalkboard-menu-pro' ),
					),
					'public'      => false,
					'show_ui'     => true,
					'show_in_menu'=> false,
					'supports'    => array( 'title' ),
				)
			);

			register_post_type(
				'cmp_menu_item',
				array(
					'labels'      => array(
						'name'          => __( 'Chalkboard Menu Items', 'chalkboard-menu-pro' ),
						'singular_name' => __( 'Chalkboard Menu Item', 'chalkboard-menu-pro' ),
					),
					'public'      => false,
					'show_ui'     => true,
					'show_in_menu'=> false,
					'supports'    => array( 'title' ),
				)
			);
		}

		/**
		 * Define plugin-wide constants.
		 */
		protected function define_constants() {
			if ( ! defined( 'CMP_PLUGIN_VERSION' ) ) {
				define( 'CMP_PLUGIN_VERSION', '0.1.0' );
			}

			if ( ! defined( 'CMP_PLUGIN_FILE' ) ) {
				define( 'CMP_PLUGIN_FILE', __FILE__ );
			}

			if ( ! defined( 'CMP_PLUGIN_DIR' ) ) {
				define( 'CMP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'CMP_PLUGIN_URL' ) ) {
				define( 'CMP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
		}

		/**
		 * Load plugin text domain for translations.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'chalkboard-menu-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Register and enqueue frontend assets for the chalkboard menu.
		 */
		public function register_frontend_assets() {
			wp_register_style(
				'cmp-frontend',
				CMP_PLUGIN_URL . 'assets/css/frontend.css',
				array(),
				CMP_PLUGIN_VERSION
			);

			// Styles are only enqueued when the shortcode is used via wp_enqueue_style in the renderer.
		}

		/**
		 * Register and enqueue admin assets for the plugin dashboard screens.
		 */
		public function register_admin_assets( $hook ) {
			// Basic guard to only load assets on this plugin's pages.
			if ( false === strpos( $hook, 'chalkboard-menu-pro' ) ) {
				return;
			}

			wp_enqueue_style(
				'cmp-admin',
				CMP_PLUGIN_URL . 'assets/css/admin.css',
				array(),
				CMP_PLUGIN_VERSION
			);

			wp_enqueue_script(
				'cmp-admin',
				CMP_PLUGIN_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				CMP_PLUGIN_VERSION,
				true
			);
		}

		/**
		 * Register the top-level admin menu for Chalkboard Menu Pro.
		 */
		public function register_admin_menu() {
			$parent_slug = apply_filters( 'cmp_parent_menu_slug', 'ez-it-solutions' );

			add_submenu_page(
				$parent_slug,
				__( 'Chalkboard Menu Pro', 'chalkboard-menu-pro' ),
				__( 'Chalkboard Menu', 'chalkboard-menu-pro' ),
				'manage_options',
				'chalkboard-menu-pro',
				array( $this, 'render_admin_page' )
			);

			add_submenu_page(
				$parent_slug,
				__( 'Chalkboard Menu Items', 'chalkboard-menu-pro' ),
				__( 'Menu Items', 'chalkboard-menu-pro' ),
				'manage_options',
				'edit.php?post_type=cmp_menu_item'
			);
		}

		/**
		 * Render the main admin dashboard page.
		 *
		 * This page will evolve into a tabbed interface with light/dark mode,
		 * drag-and-drop builders, and presets. For now it acts as a branded
		 * landing page with basic usage instructions.
		 */
		public function render_admin_page() {
			?>
			<div class="cmp-admin-wrap cmp-theme-light" id="cmp-admin-root">
				<header class="cmp-admin-header">
					<h1><?php esc_html_e( 'Chalkboard Menu Pro', 'chalkboard-menu-pro' ); ?></h1>
					<p class="cmp-admin-tagline"><?php esc_html_e( 'Create beautiful chalkboard-style menus for your cafe, restaurant, or bar.', 'chalkboard-menu-pro' ); ?></p>
					<div class="cmp-admin-header-actions">
						<button type="button" class="button cmp-theme-toggle" id="cmp-theme-toggle">
							<span class="cmp-theme-toggle-label-light"><?php esc_html_e( 'Light', 'chalkboard-menu-pro' ); ?></span>
							<span class="cmp-theme-toggle-handle"></span>
							<span class="cmp-theme-toggle-label-dark"><?php esc_html_e( 'Dark', 'chalkboard-menu-pro' ); ?></span>
						</button>
					</div>
				</header>

				<div class="cmp-admin-content">
					<div class="cmp-admin-panel cmp-admin-panel-primary">
						<h2><?php esc_html_e( 'Getting Started', 'chalkboard-menu-pro' ); ?></h2>
						<ol class="cmp-admin-steps">
							<li><?php esc_html_e( 'Use the shortcode [chalkboard_menu_pro] in any page or post.', 'chalkboard-menu-pro' ); ?></li>
							<li><?php esc_html_e( 'Preview the sample chalkboard layout based on the default style.', 'chalkboard-menu-pro' ); ?></li>
							<li><?php esc_html_e( 'Return here as we add layout presets, item builders, and drag-and-drop controls.', 'chalkboard-menu-pro' ); ?></li>
						</ol>
					</div>

					<aside class="cmp-admin-panel cmp-admin-panel-secondary">
						<h3><?php esc_html_e( 'Ez IT Solutions Plugins', 'chalkboard-menu-pro' ); ?></h3>
						<p><?php esc_html_e( 'This plugin follows the same clean dashboard styling and light/dark modes used across Ez IT Solutions products.', 'chalkboard-menu-pro' ); ?></p>
					</aside>
				</div>
			</div>
			<?php
		}

		/**
		 * Render the [chalkboard_menu_pro] shortcode output.
		 *
		 * For v0.1.0 this is a static layout that visually approximates the
		 * provided design. Later we will replace the hardcoded data with
		 * user-configurable menu boards and styles.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Enclosed content (unused for now).
		 *
		 * @return string
		 */
		public function render_chalkboard_menu_shortcode( $atts, $content = '' ) {
			wp_enqueue_style( 'cmp-frontend' );

			ob_start();
			?>
			<div class="cmp-board cmp-board-style-classic">
				<div class="cmp-board-inner">
					<div class="cmp-board-column">
						<h2 class="cmp-board-heading">Espresso</h2>
						<ul class="cmp-board-list">
							<li>Latte</li>
							<li>Mocha</li>
							<li>Macchiato</li>
							<li>Cappuccino</li>
							<li>Americana</li>
							<li>Kaffe Lis</li>
						</ul>
					</div>

					<div class="cmp-board-column">
						<h2 class="cmp-board-heading">Tea</h2>
						<ul class="cmp-board-list">
							<li>Assorted Tea</li>
							<li>London Fog</li>
							<li>Chai Latte</li>
							<li>Matcha Latte</li>
							<li>Blooming Tea</li>
						</ul>

						<h2 class="cmp-board-heading">Specialty</h2>
						<ul class="cmp-board-list">
							<li>Coffee Frappe</li>
							<li>Kids Frappe</li>
							<li>Hot Chocolate</li>
							<li>Steamer</li>
							<li>Lemonade</li>
							<li>Aqua Fresca</li>
						</ul>
					</div>

					<div class="cmp-board-column">
						<h2 class="cmp-board-heading">Coffee</h2>
						<ul class="cmp-board-list">
							<li>Drip Coffee</li>
							<li>Cold Brew</li>
							<li>French Press</li>
							<li>Pour Over</li>
						</ul>

						<h2 class="cmp-board-heading">Smoothies</h2>
						<ul class="cmp-board-list">
							<li>Harvest Greens</li>
							<li>Perfectly Peach</li>
							<li>Berry Bliss</li>
							<li>Strawberry Fields</li>
						</ul>

						<h2 class="cmp-board-heading">Extras</h2>
						<ul class="cmp-board-list">
							<li>Extra Shot</li>
							<li>Non-Dairy</li>
							<li>Whipped Cream</li>
							<li>Add Flavor</li>
						</ul>
					</div>
				</div>
			</div>
			<?php

			return ob_get_clean();
		}
	}
}

/**
 * Initialize the plugin.
 */
function chalkboard_menu_pro() {
	return Chalkboard_Menu_Pro::instance();
}

chalkboard_menu_pro();

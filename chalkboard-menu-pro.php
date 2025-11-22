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
			$this->includes();

			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
			add_shortcode( 'chalkboard_menu_pro', array( $this, 'render_chalkboard_menu_shortcode' ) );
			
			// Initialize admin interface
			if ( is_admin() ) {
				CMP_Admin::init();
			}
		}

		/**
		 * Include required files.
		 */
		protected function includes() {
			require_once CMP_PLUGIN_DIR . 'includes/meta-boxes.php';
			
			// Load admin class
			if ( is_admin() ) {
				require_once CMP_PLUGIN_DIR . 'admin/class-cmp-admin.php';
			}
			
			// Load company info if available
			if ( file_exists( CMP_PLUGIN_DIR . 'includes/class-company-info.php' ) ) {
				require_once CMP_PLUGIN_DIR . 'includes/class-company-info.php';
				EZIT_Company_Info::init();
			}
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
		 * Render the [chalkboard_menu_pro] shortcode output.
		 *
		 * Supports board_id attribute to render a specific board from the database.
		 * Falls back to demo content if no board_id is provided or board not found.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Enclosed content (unused for now).
		 *
		 * @return string
		 */
		public function render_chalkboard_menu_shortcode( $atts, $content = '' ) {
			$atts = shortcode_atts(
				array(
					'board_id' => 0,
					'template' => '1', // Template number (1, 2, etc.)
				),
				$atts,
				'chalkboard_menu_pro'
			);

			$board_id = absint( $atts['board_id'] );
			$template = absint( $atts['template'] );
			$sections = array();

			// Try to load board from database if board_id is provided.
			if ( $board_id > 0 ) {
				$board_post = get_post( $board_id );
				if ( $board_post && 'cmp_board' === $board_post->post_type ) {
					$sections = get_post_meta( $board_id, '_cmp_board_sections', true );
					$template = get_post_meta( $board_id, '_cmp_board_template', true );
					if ( ! is_array( $sections ) ) {
						$sections = array();
					}
					if ( empty( $template ) ) {
						$template = 1;
					}
				}
			}

			// Fallback to demo content if no sections found.
			if ( empty( $sections ) ) {
				$sections = $this->get_demo_sections();
			}

			// Enqueue template-specific stylesheet
			$template_style_url = CMP_PLUGIN_URL . 'assets/images/boards/' . $template . '/style.css';
			$template_style_path = CMP_PLUGIN_DIR . 'assets/images/boards/' . $template . '/style.css';
			
			if ( file_exists( $template_style_path ) ) {
				wp_enqueue_style( 'cmp-board-' . $template, $template_style_url, array(), CMP_PLUGIN_VERSION );
			}

			// Render based on template
			ob_start();
			
			if ( $template == 2 ) {
				// Template 2: Ornate Frame Design
				$half = ceil( count( $sections ) / 2 );
				$left_sections = array_slice( $sections, 0, $half );
				$right_sections = array_slice( $sections, $half );
				?>
				<div class="cmp-board-2">
					<div class="menu-border">
						<div class="menu-background">
							<!-- Left Column -->
							<div class="menu-section">
								<?php foreach ( $left_sections as $section ) : ?>
									<?php if ( ! empty( $section['title'] ) || ! empty( $section['items'] ) ) : ?>
										<ul class="menu-items">
											<?php if ( ! empty( $section['title'] ) ) : ?>
												<li><p class="heading"><?php echo esc_html( $section['title'] ); ?></p></li>
											<?php endif; ?>
											<?php if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) : ?>
												<?php foreach ( $section['items'] as $item ) : ?>
													<li><p><?php echo esc_html( $item ); ?></p></li>
												<?php endforeach; ?>
											<?php endif; ?>
										</ul>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<!-- Right Column -->
							<div class="menu-section">
								<?php foreach ( $right_sections as $section ) : ?>
									<?php if ( ! empty( $section['title'] ) || ! empty( $section['items'] ) ) : ?>
										<ul class="menu-items">
											<?php if ( ! empty( $section['title'] ) ) : ?>
												<li><p class="heading"><?php echo esc_html( $section['title'] ); ?></p></li>
											<?php endif; ?>
											<?php if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) : ?>
												<?php foreach ( $section['items'] as $item ) : ?>
													<li><p><?php echo esc_html( $item ); ?></p></li>
												<?php endforeach; ?>
											<?php endif; ?>
										</ul>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			} else {
				// Template 1: Simple Chalkboard (default)
				?>
				<div class="cmp-board-1">
					<div class="cmp-board-inner">
						<?php foreach ( $sections as $section ) : ?>
							<?php if ( ! empty( $section['title'] ) || ! empty( $section['items'] ) ) : ?>
								<div class="cmp-board-column">
									<?php if ( ! empty( $section['title'] ) ) : ?>
										<h2 class="cmp-board-heading"><?php echo esc_html( $section['title'] ); ?></h2>
									<?php endif; ?>
									<?php if ( ! empty( $section['items'] ) && is_array( $section['items'] ) ) : ?>
										<ul class="cmp-board-list">
											<?php foreach ( $section['items'] as $item ) : ?>
												<li><?php echo esc_html( $item ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
			}

			return ob_get_clean();
		}

		/**
		 * Get demo sections for fallback display.
		 *
		 * @return array
		 */
		protected function get_demo_sections() {
			return array(
				array(
					'title' => 'Espresso',
					'items' => array( 'Latte', 'Mocha', 'Macchiato', 'Cappuccino', 'Americana', 'Kaffe Lis' ),
				),
				array(
					'title' => 'Tea',
					'items' => array( 'Assorted Tea', 'London Fog', 'Chai Latte', 'Matcha Latte', 'Blooming Tea' ),
				),
				array(
					'title' => 'Specialty',
					'items' => array( 'Coffee Frappe', 'Kids Frappe', 'Hot Chocolate', 'Steamer', 'Lemonade', 'Aqua Fresca' ),
				),
				array(
					'title' => 'Coffee',
					'items' => array( 'Drip Coffee', 'Cold Brew', 'French Press', 'Pour Over' ),
				),
				array(
					'title' => 'Smoothies',
					'items' => array( 'Harvest Greens', 'Perfectly Peach', 'Berry Bliss', 'Strawberry Fields' ),
				),
				array(
					'title' => 'Extras',
					'items' => array( 'Extra Shot', 'Non-Dairy', 'Whipped Cream', 'Add Flavor' ),
				),
			);
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

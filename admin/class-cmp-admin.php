<?php
/**
 * Chalkboard Menu Pro Admin
 * 
 * Main admin class that handles menu registration, asset loading,
 * and page rendering following Ez IT Solutions standards.
 * 
 * @package    Chalkboard_Menu_Pro
 * @subpackage Admin
 * @author     Chris Hultberg <chris@ez-it-solutions.com>
 * @copyright  2025 Ez IT Solutions
 * @license    GPL-3.0-or-later
 * @version    0.1.0
 * @since      0.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CMP_Admin {
    
    /**
     * Initialize admin
     */
    public static function init() {
        // Load dependencies
        self::load_dependencies();
        
        // Add admin menu
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        
        // Enqueue admin assets
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        
        // Hide admin notices on our pages
        add_action('admin_head', [__CLASS__, 'hide_admin_notices']);
        
        // AJAX handlers
        add_action('wp_ajax_cmp_toggle_theme', [__CLASS__, 'ajax_toggle_theme']);
    }
    
    /**
     * Load required dependencies
     */
    private static function load_dependencies() {
        // Load tab controller
        require_once CMP_PLUGIN_DIR . 'admin/class-tab-controller.php';
        
        // Load tab files
        require_once CMP_PLUGIN_DIR . 'admin/tabs/class-tab-dashboard.php';
        require_once CMP_PLUGIN_DIR . 'admin/tabs/class-tab-boards.php';
        require_once CMP_PLUGIN_DIR . 'admin/tabs/class-tab-items.php';
        
        // Register tabs
        CMP_Tab_Dashboard::register();
        CMP_Tab_Boards::register();
        CMP_Tab_Items::register();
    }
    
    /**
     * Add admin menu
     */
    public static function add_admin_menu() {
        $parent_slug = 'ez-it-solutions';
        
        // Check if parent menu exists
        global $menu;
        $parent_exists = false;
        foreach ($menu as $item) {
            if (isset($item[2]) && $item[2] === $parent_slug) {
                $parent_exists = true;
                break;
            }
        }
        
        // Create parent menu if needed
        if (!$parent_exists) {
            add_menu_page(
                'Ez IT Solutions',
                'Ez IT Solutions',
                'manage_options',
                $parent_slug,
                class_exists('EZIT_Company_Info') ? ['EZIT_Company_Info', 'render_page'] : '__return_null',
                'dashicons-admin-site-alt3',
                3
            );
            
            // Remove the duplicate submenu that WordPress auto-creates
            remove_submenu_page($parent_slug, $parent_slug);
            
            // Add Company Info as first submenu
            if (class_exists('EZIT_Company_Info')) {
                add_submenu_page(
                    $parent_slug,
                    __('Company Info', 'chalkboard-menu-pro'),
                    __('Company Info', 'chalkboard-menu-pro'),
                    'manage_options',
                    $parent_slug,
                    ['EZIT_Company_Info', 'render_page']
                );
            }
        }
        
        // Always add Chalkboard Menu Pro as submenu under Ez IT Solutions
        add_submenu_page(
            $parent_slug,
            __('Chalkboard Menu Pro', 'chalkboard-menu-pro'),
            __('Chalkboard Menu', 'chalkboard-menu-pro'),
            'manage_options',
            'chalkboard-menu-pro',
            [__CLASS__, 'render_page']
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public static function enqueue_assets($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'chalkboard-menu-pro') === false) {
            return;
        }
        
        // Enqueue WordPress core assets
        wp_enqueue_style('dashicons');
        wp_enqueue_script('jquery');
        
        // Localize script for AJAX
        wp_localize_script('jquery', 'cmpAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cmp_admin'),
        ]);
    }
    
    /**
     * Hide admin notices on our pages
     */
    public static function hide_admin_notices() {
        $screen = get_current_screen();
        if ($screen && (strpos($screen->id, 'chalkboard-menu-pro') !== false)) {
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }
    
    /**
     * AJAX handler for theme toggle
     */
    public static function ajax_toggle_theme() {
        check_ajax_referer('cmp_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $current_theme = get_option('cmp_theme', 'dark');
        $new_theme = $current_theme === 'dark' ? 'light' : 'dark';
        
        update_option('cmp_theme', $new_theme);
        
        wp_send_json_success([
            'theme' => $new_theme,
            'message' => __('Theme updated successfully', 'chalkboard-menu-pro')
        ]);
    }
    
    /**
     * Render main admin page
     */
    public static function render_page() {
        $current_theme = get_option('cmp_theme', 'dark');
        $theme_class = $current_theme === 'light' ? 'ezit-light' : 'ezit-dark';
        
        // Add theme class to body
        add_filter('admin_body_class', function($classes) use ($theme_class) {
            return $classes . ' ' . $theme_class;
        });
        
        ?>
        <div class="ezit-fullpage <?php echo esc_attr($theme_class); ?>">
            <!-- Header -->
            <div class="ezit-header">
                <div class="ezit-header-content">
                    <div class="ezit-header-left">
                        <h1 class="ezit-header-title">
                            <span class="dashicons dashicons-welcome-widgets-menus"></span>
                            <?php _e('Chalkboard Menu Pro', 'chalkboard-menu-pro'); ?>
                        </h1>
                        <p class="ezit-header-subtitle"><?php _e('Beautiful chalkboard-style menus for your cafe, restaurant, or bar', 'chalkboard-menu-pro'); ?></p>
                    </div>
                    
                    <div class="ezit-header-right">
                        <button id="ezit-theme-toggle" class="ezit-theme-toggle" onclick="cmpToggleTheme()">
                            <span class="ezit-theme-icon dashicons dashicons-<?php echo $current_theme === 'light' ? 'moon' : 'lightbulb'; ?>"></span>
                            <span class="ezit-theme-text"><?php echo $current_theme === 'light' ? __('Dark', 'chalkboard-menu-pro') : __('Light', 'chalkboard-menu-pro'); ?> <?php _e('Mode', 'chalkboard-menu-pro'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <?php CMP_Tab_Controller::render_navigation(); ?>
            
            <!-- Content -->
            <div class="ezit-content">
                <div class="ezit-main">
                    <?php CMP_Tab_Controller::render_content(); ?>
                </div>
                
                <!-- Sidebar -->
                <div class="ezit-sidebar">
                    <?php self::render_sidebar(); ?>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="ezit-footer">
                <div class="ezit-footer-content">
                    <div class="ezit-footer-section">
                        <h4>Ez IT Solutions</h4>
                        <p>Professional WordPress solutions for businesses.</p>
                    </div>
                    <div class="ezit-footer-section">
                        <h4>Support</h4>
                        <p><a href="mailto:support@ez-it-solutions.com">support@ez-it-solutions.com</a></p>
                        <p><a href="https://www.ez-it-solutions.com" target="_blank">Visit Website</a></p>
                    </div>
                    <div class="ezit-footer-section">
                        <h4>Quick Links</h4>
                        <p><a href="https://github.com/ez-it-solutions/chalkboard-menu-pro" target="_blank">GitHub Repository</a></p>
                        <p><a href="<?php echo admin_url('admin.php?page=ez-it-solutions'); ?>">Company Info</a></p>
                        <p><a href="https://www.ez-it-solutions.com/docs/chalkboard-menu-pro" target="_blank">Documentation</a></p>
                    </div>
                </div>
                <div class="ezit-footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Ez IT Solutions. All rights reserved.</p>
                </div>
            </div>
        </div>
        
        <?php
        // Load inline styles and scripts
        require_once CMP_PLUGIN_DIR . 'admin/styles.php';
        require_once CMP_PLUGIN_DIR . 'admin/scripts.php';
    }
    
    /**
     * Render sidebar
     */
    private static function render_sidebar() {
        ?>
        <!-- Quick Actions -->
        <div class="ezit-quick-launcher">
            <h3 class="ezit-widget-title">
                <span class="dashicons dashicons-admin-generic"></span>
                <?php _e('Quick Actions', 'chalkboard-menu-pro'); ?>
            </h3>
            <div class="ezit-quick-launcher-grid">
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_board'); ?>" class="ezit-quick-action">
                    <span class="dashicons dashicons-plus"></span>
                    <span class="ezit-quick-action-label"><?php _e('New Board', 'chalkboard-menu-pro'); ?></span>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=cmp_board'); ?>" class="ezit-quick-action">
                    <span class="dashicons dashicons-welcome-widgets-menus"></span>
                    <span class="ezit-quick-action-label"><?php _e('View Boards', 'chalkboard-menu-pro'); ?></span>
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_menu_item'); ?>" class="ezit-quick-action">
                    <span class="dashicons dashicons-plus"></span>
                    <span class="ezit-quick-action-label"><?php _e('New Item', 'chalkboard-menu-pro'); ?></span>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=cmp_menu_item'); ?>" class="ezit-quick-action">
                    <span class="dashicons dashicons-list-view"></span>
                    <span class="ezit-quick-action-label"><?php _e('View Items', 'chalkboard-menu-pro'); ?></span>
                </a>
            </div>
        </div>
        
        <!-- Resources Card -->
        <div class="ezit-sidebar-card">
            <h3>
                <span class="dashicons dashicons-book"></span>
                <?php _e('Resources', 'chalkboard-menu-pro'); ?>
            </h3>
            <ul class="ezit-sidebar-list">
                <li><a href="https://github.com/ez-it-solutions/chalkboard-menu-pro" target="_blank" class="ezit-sidebar-link">
                    <span class="dashicons dashicons-external"></span>
                    <?php _e('GitHub Repository', 'chalkboard-menu-pro'); ?>
                </a></li>
                <li><a href="https://www.ez-it-solutions.com/docs/chalkboard-menu-pro" target="_blank" class="ezit-sidebar-link">
                    <span class="dashicons dashicons-external"></span>
                    <?php _e('Documentation', 'chalkboard-menu-pro'); ?>
                </a></li>
                <li><a href="https://www.ez-it-solutions.com/support" target="_blank" class="ezit-sidebar-link">
                    <span class="dashicons dashicons-external"></span>
                    <?php _e('Get Support', 'chalkboard-menu-pro'); ?>
                </a></li>
            </ul>
        </div>
        
        <!-- Need Help Card -->
        <div class="ezit-sidebar-card">
            <h3>
                <span class="dashicons dashicons-sos"></span>
                <?php _e('Need Help?', 'chalkboard-menu-pro'); ?>
            </h3>
            <p><?php _e('Find answers in our documentation:', 'chalkboard-menu-pro'); ?></p>
            <div style="text-align: center; margin-top: 12px; padding: 10px;">
                <a href="https://www.ez-it-solutions.com/docs/chalkboard-menu-pro" target="_blank" class="ezit-btn ezit-btn-link ezit-btn-sm ezit-sidebar-doc-btn" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 10px 40px; white-space: nowrap;">
                    <span class="dashicons dashicons-book"></span>
                    <?php _e('View Documentation', 'chalkboard-menu-pro'); ?>
                </a>
            </div>
            <p style="margin-top: 16px; text-align: center;"><?php _e('Or contact Ez IT Solutions support:', 'chalkboard-menu-pro'); ?></p>
            <div style="display: grid; place-items: center;">
                <a href="mailto:support@ez-it-solutions.com" class="ezit-sidebar-link">
                    <span class="dashicons dashicons-email"></span>
                    support@ez-it-solutions.com
                </a>
            </div>
        </div>
        
        <!-- About Card -->
        <div class="ezit-sidebar-card">
            <h3>
                <span class="dashicons dashicons-admin-site-alt3"></span>
                <?php _e('About Ez IT Solutions', 'chalkboard-menu-pro'); ?>
            </h3>
            <p style="color: #9ca3af; font-size: 13px; line-height: 1.6;">
                <?php _e('This plugin follows the same clean dashboard styling and light/dark modes used across all Ez IT Solutions products.', 'chalkboard-menu-pro'); ?>
            </p>
        </div>
        <?php
    }
}

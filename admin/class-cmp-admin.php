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
        }
        
        // Add Chalkboard Menu Pro as submenu
        add_submenu_page(
            $parent_slug,
            __('Chalkboard Menu Pro', 'chalkboard-menu-pro'),
            __('Chalkboard Menu', 'chalkboard-menu-pro'),
            'manage_options',
            'chalkboard-menu-pro',
            [__CLASS__, 'render_page']
        );
        
        // Add Boards submenu
        add_submenu_page(
            $parent_slug,
            __('Chalkboard Boards', 'chalkboard-menu-pro'),
            __('Boards', 'chalkboard-menu-pro'),
            'manage_options',
            'edit.php?post_type=cmp_board'
        );
        
        // Add Menu Items submenu
        add_submenu_page(
            $parent_slug,
            __('Chalkboard Menu Items', 'chalkboard-menu-pro'),
            __('Menu Items', 'chalkboard-menu-pro'),
            'manage_options',
            'edit.php?post_type=cmp_menu_item'
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public static function enqueue_assets($hook) {
        // Only load on our admin pages
        if ($hook !== 'ez-it-solutions_page_chalkboard-menu-pro') {
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
            
            <!-- Content -->
            <div class="ezit-content">
                <div class="ezit-main">
                    <?php self::render_main_content(); ?>
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
                        <p><a href="https://www.ez-it-solutions.com/docs/chalkboard-menu-pro" target="_blank">Documentation</a></p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        // Load inline styles and scripts
        require_once CMP_PLUGIN_DIR . 'admin/styles.php';
        require_once CMP_PLUGIN_DIR . 'admin/scripts.php';
    }
    
    /**
     * Render main content area
     */
    private static function render_main_content() {
        ?>
        <h2 class="ezit-page-title"><?php _e('Getting Started', 'chalkboard-menu-pro'); ?></h2>
        <p class="ezit-description"><?php _e('Create beautiful chalkboard-style menus with drag-and-drop simplicity.', 'chalkboard-menu-pro'); ?></p>
        
        <!-- Stats Grid -->
        <div class="ezit-stats-grid">
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-welcome-widgets-menus"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value"><?php echo wp_count_posts('cmp_board')->publish; ?></div>
                    <div class="ezit-stat-label"><?php _e('Boards', 'chalkboard-menu-pro'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-list-view"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value"><?php echo wp_count_posts('cmp_menu_item')->publish; ?></div>
                    <div class="ezit-stat-label"><?php _e('Menu Items', 'chalkboard-menu-pro'); ?></div>
                </div>
            </div>
            
            <div class="ezit-stat-card">
                <div class="ezit-stat-icon">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </div>
                <div class="ezit-stat-info">
                    <div class="ezit-stat-value">2</div>
                    <div class="ezit-stat-label"><?php _e('Frame Styles', 'chalkboard-menu-pro'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Quick Start Card -->
        <div class="ezit-card">
            <h3>
                <span class="dashicons dashicons-info"></span>
                <?php _e('Quick Start Guide', 'chalkboard-menu-pro'); ?>
            </h3>
            <ol class="ezit-sidebar-list">
                <li><?php _e('Create a new Board and add sections with menu items', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Use the shortcode [chalkboard_menu_pro board_id="123"] in any page or post', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Customize the frame style and appearance to match your brand', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Preview your chalkboard menu on the frontend', 'chalkboard-menu-pro'); ?></li>
            </ol>
            
            <div class="ezit-quick-actions">
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_board'); ?>" class="ezit-action-btn ezit-action-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Create New Board', 'chalkboard-menu-pro'); ?>
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_menu_item'); ?>" class="ezit-action-btn">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Add Menu Item', 'chalkboard-menu-pro'); ?>
                </a>
            </div>
        </div>
        
        <!-- Features Card -->
        <div class="ezit-card" style="margin-top: 24px;">
            <h3>
                <span class="dashicons dashicons-star-filled"></span>
                <?php _e('Features', 'chalkboard-menu-pro'); ?>
            </h3>
            <ul class="ezit-sidebar-list">
                <li><?php _e('Multiple chalkboard frame styles (Classic, Minimal)', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Dynamic sections and menu items', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Link menu items to WordPress pages or custom URLs', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Shortcode support for easy embedding', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Light and dark admin modes', 'chalkboard-menu-pro'); ?></li>
                <li><?php _e('Responsive design for all devices', 'chalkboard-menu-pro'); ?></li>
            </ul>
        </div>
        <?php
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

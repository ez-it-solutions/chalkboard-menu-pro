<?php
/**
 * Tab Controller
 * 
 * Manages registration and rendering of admin tabs
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

class CMP_Tab_Controller {
    
    /**
     * Registered tabs
     * @var array
     */
    private static $tabs = [];
    
    /**
     * Current active tab
     * @var string
     */
    private static $current_tab = '';
    
    /**
     * Register a new tab
     * 
     * @param string $id Tab ID (slug)
     * @param array $args Tab configuration
     * @return void
     */
    public static function register_tab($id, $args = []) {
        $defaults = [
            'label' => ucfirst($id),
            'icon' => 'dashicons-admin-generic',
            'capability' => 'manage_options',
            'callback' => null,
            'priority' => 10,
            'visible' => true,
        ];
        
        $tab = wp_parse_args($args, $defaults);
        $tab['id'] = $id;
        
        self::$tabs[$id] = $tab;
    }
    
    /**
     * Get all registered tabs
     * 
     * @return array
     */
    public static function get_tabs() {
        // Sort by priority
        uasort(self::$tabs, function($a, $b) {
            return $a['priority'] - $b['priority'];
        });
        
        // Filter by capability and visibility
        return array_filter(self::$tabs, function($tab) {
            return $tab['visible'] && current_user_can($tab['capability']);
        });
    }
    
    /**
     * Get current tab
     * 
     * @return string
     */
    public static function get_current_tab() {
        if (empty(self::$current_tab)) {
            self::$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : self::get_default_tab();
        }
        return self::$current_tab;
    }
    
    /**
     * Get default tab (first registered tab)
     * 
     * @return string
     */
    public static function get_default_tab() {
        $tabs = self::get_tabs();
        if (empty($tabs)) {
            return 'dashboard';
        }
        $first_tab = reset($tabs);
        return $first_tab['id'];
    }
    
    /**
     * Render tab navigation
     * 
     * @return void
     */
    public static function render_navigation() {
        $tabs = self::get_tabs();
        $current_tab = self::get_current_tab();
        
        ?>
        <div class="ezit-nav-wrapper">
            <nav class="ezit-nav-tabs">
                <?php foreach ($tabs as $tab): ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=chalkboard-menu-pro&tab=' . $tab['id'])); ?>" 
                       class="ezit-nav-tab <?php echo $current_tab === $tab['id'] ? 'ezit-nav-tab-active' : ''; ?>"
                       onclick="cmpLoadTab(event, '<?php echo esc_js($tab['id']); ?>')">
                        <span class="dashicons <?php echo esc_attr($tab['icon']); ?>"></span>
                        <?php echo esc_html($tab['label']); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        <?php
    }
    
    /**
     * Render current tab content
     * 
     * @return void
     */
    public static function render_content() {
        $current_tab = self::get_current_tab();
        
        if (!isset(self::$tabs[$current_tab])) {
            echo '<div class="ezit-card"><p>' . __('Tab not found.', 'chalkboard-menu-pro') . '</p></div>';
            return;
        }
        
        $tab = self::$tabs[$current_tab];
        
        // Check capability
        if (!current_user_can($tab['capability'])) {
            echo '<div class="ezit-card"><p>' . __('You do not have permission to access this tab.', 'chalkboard-menu-pro') . '</p></div>';
            return;
        }
        
        // Call callback if specified
        if ($tab['callback'] && is_callable($tab['callback'])) {
            call_user_func($tab['callback']);
            return;
        }
        
        echo '<div class="ezit-card"><p>' . __('Tab content not configured.', 'chalkboard-menu-pro') . '</p></div>';
    }
    
    /**
     * Unregister a tab
     * 
     * @param string $id Tab ID
     * @return void
     */
    public static function unregister_tab($id) {
        unset(self::$tabs[$id]);
    }
    
    /**
     * Check if tab exists
     * 
     * @param string $id Tab ID
     * @return bool
     */
    public static function tab_exists($id) {
        return isset(self::$tabs[$id]);
    }
}

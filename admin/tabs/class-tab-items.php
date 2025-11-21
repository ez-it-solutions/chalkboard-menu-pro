<?php
/**
 * Menu Items Tab
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

class CMP_Tab_Items {
    
    /**
     * Register tab
     */
    public static function register() {
        CMP_Tab_Controller::register_tab('items', [
            'label' => __('Menu Items', 'chalkboard-menu-pro'),
            'icon' => 'dashicons-list-view',
            'callback' => [__CLASS__, 'render'],
            'priority' => 30,
        ]);
    }
    
    /**
     * Render tab content
     */
    public static function render() {
        // Get all menu items
        $items = get_posts([
            'post_type' => 'cmp_menu_item',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        ?>
        <h2 class="ezit-page-title"><?php _e('Menu Items', 'chalkboard-menu-pro'); ?></h2>
        <p class="ezit-description"><?php _e('Manage individual menu items that can be linked to WordPress pages or custom URLs.', 'chalkboard-menu-pro'); ?></p>
        
        <div class="ezit-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>
                    <span class="dashicons dashicons-list-view"></span>
                    <?php _e('All Menu Items', 'chalkboard-menu-pro'); ?>
                </h3>
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_menu_item'); ?>" class="ezit-action-btn ezit-action-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Add New Item', 'chalkboard-menu-pro'); ?>
                </a>
            </div>
            
            <?php if (empty($items)): ?>
                <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                    <span class="dashicons dashicons-list-view" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;"></span>
                    <p style="font-size: 16px; margin: 0;"><?php _e('No menu items found. Create your first menu item!', 'chalkboard-menu-pro'); ?></p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th style="width: 40%;"><?php _e('Item Name', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Link Type', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Link Target', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Date', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Actions', 'chalkboard-menu-pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): 
                            $link_type = get_post_meta($item->ID, '_cmp_link_type', true);
                            $link_url = get_post_meta($item->ID, '_cmp_link_url', true);
                            $link_page = get_post_meta($item->ID, '_cmp_link_page', true);
                            
                            $link_type_label = 'None';
                            $link_target = '—';
                            
                            if ($link_type === 'page' && $link_page) {
                                $link_type_label = 'WordPress Page';
                                $page = get_post($link_page);
                                $link_target = $page ? $page->post_title : '—';
                            } elseif ($link_type === 'url' && $link_url) {
                                $link_type_label = 'Custom URL';
                                $link_target = '<a href="' . esc_url($link_url) . '" target="_blank">' . esc_html($link_url) . '</a>';
                            }
                        ?>
                            <tr>
                                <td>
                                    <strong>
                                        <a href="<?php echo get_edit_post_link($item->ID); ?>">
                                            <?php echo esc_html($item->post_title); ?>
                                        </a>
                                    </strong>
                                </td>
                                <td><?php echo esc_html($link_type_label); ?></td>
                                <td><?php echo $link_target; ?></td>
                                <td><?php echo get_the_date('', $item); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($item->ID); ?>" class="button button-small">
                                        <?php _e('Edit', 'chalkboard-menu-pro'); ?>
                                    </a>
                                    <a href="<?php echo get_delete_post_link($item->ID); ?>" class="button button-small" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this item?', 'chalkboard-menu-pro'); ?>');">
                                        <?php _e('Delete', 'chalkboard-menu-pro'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }
}

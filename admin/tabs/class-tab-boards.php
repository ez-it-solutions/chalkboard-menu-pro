<?php
/**
 * Boards Tab
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

class CMP_Tab_Boards {
    
    /**
     * Register tab
     */
    public static function register() {
        CMP_Tab_Controller::register_tab('boards', [
            'label' => __('Boards', 'chalkboard-menu-pro'),
            'icon' => 'dashicons-welcome-widgets-menus',
            'callback' => [__CLASS__, 'render'],
            'priority' => 20,
        ]);
    }
    
    /**
     * Render tab content
     */
    public static function render() {
        // Get all boards
        $boards = get_posts([
            'post_type' => 'cmp_board',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        ?>
        <h2 class="ezit-page-title"><?php _e('Chalkboard Boards', 'chalkboard-menu-pro'); ?></h2>
        <p class="ezit-description"><?php _e('Manage your chalkboard menu boards. Each board can contain multiple sections and items.', 'chalkboard-menu-pro'); ?></p>
        
        <div class="ezit-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>
                    <span class="dashicons dashicons-welcome-widgets-menus"></span>
                    <?php _e('All Boards', 'chalkboard-menu-pro'); ?>
                </h3>
                <a href="<?php echo admin_url('post-new.php?post_type=cmp_board'); ?>" class="ezit-action-btn ezit-action-btn-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Add New Board', 'chalkboard-menu-pro'); ?>
                </a>
            </div>
            
            <?php if (empty($boards)): ?>
                <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                    <span class="dashicons dashicons-welcome-widgets-menus" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;"></span>
                    <p style="font-size: 16px; margin: 0;"><?php _e('No boards found. Create your first board to get started!', 'chalkboard-menu-pro'); ?></p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th style="width: 50%;"><?php _e('Board Name', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Sections', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Shortcode', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Date', 'chalkboard-menu-pro'); ?></th>
                            <th><?php _e('Actions', 'chalkboard-menu-pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($boards as $board): 
                            $sections = get_post_meta($board->ID, '_cmp_board_sections', true);
                            $section_count = is_array($sections) ? count($sections) : 0;
                        ?>
                            <tr>
                                <td>
                                    <strong>
                                        <a href="<?php echo get_edit_post_link($board->ID); ?>">
                                            <?php echo esc_html($board->post_title); ?>
                                        </a>
                                    </strong>
                                </td>
                                <td><?php echo $section_count; ?> <?php _e('sections', 'chalkboard-menu-pro'); ?></td>
                                <td>
                                    <code style="background: rgba(163, 230, 53, 0.1); padding: 4px 8px; border-radius: 4px; font-size: 12px;">[chalkboard_menu_pro board_id="<?php echo $board->ID; ?>"]</code>
                                </td>
                                <td><?php echo get_the_date('', $board); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($board->ID); ?>" class="button button-small">
                                        <?php _e('Edit', 'chalkboard-menu-pro'); ?>
                                    </a>
                                    <a href="<?php echo get_delete_post_link($board->ID); ?>" class="button button-small" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this board?', 'chalkboard-menu-pro'); ?>');">
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

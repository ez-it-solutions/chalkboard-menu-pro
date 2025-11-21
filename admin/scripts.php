<?php
/**
 * Admin Scripts
 * JavaScript functions for Chalkboard Menu Pro admin interface
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<script>
// Set body class and wpfooter background for CSS targeting
jQuery(document).ready(function() {
    var isLight = jQuery('.ezit-fullpage').hasClass('ezit-light');
    if (isLight) {
        jQuery('body').addClass('ezit-light-body');
    }
    // Set wpfooter background and text color
    var bgColor = isLight ? '#16a34a' : '#a3e635';
    var textColor = isLight ? '#ffffff' : '#000000';
    jQuery('#wpfooter').css('background-color', bgColor);
    jQuery('#wpfooter p, #wpfooter a').css({
        'color': textColor,
        'text-decoration': 'none'
    });
    
    // Calculate and set fullpage height dynamically
    function setFullpageHeight() {
        var wpbodyHeight = jQuery('#wpbody').outerHeight();
        var footerHeight = jQuery('.ezit-footer').outerHeight();
        var minHeight = wpbodyHeight + 20; // Add small buffer
        jQuery('.ezit-fullpage').css('min-height', minHeight + 'px');
    }
    
    setFullpageHeight();
    jQuery(window).on('resize', setFullpageHeight);
});

function cmpToggleTheme() {
    var button = jQuery('#ezit-theme-toggle');
    var icon = button.find('.ezit-theme-icon');
    var text = button.find('.ezit-theme-text');
    
    // Store current icon class
    var currentIcon = icon.hasClass('dashicons-moon') ? 'dashicons-moon' : 'dashicons-lightbulb';
    
    // Update button to show switching state
    button.prop('disabled', true).css('opacity', '0.7');
    icon.removeClass('dashicons-moon dashicons-lightbulb').addClass('dashicons-update');
    text.text('Switching...');
    
    jQuery.post(ajaxurl, {
        action: 'cmp_toggle_theme',
        nonce: cmpAdmin.nonce
    }, function(response) {
        if (response.success) {
            // Fade out before reload
            jQuery('.ezit-fullpage').css('opacity', '0');
            setTimeout(function() {
                location.reload();
            }, 300);
        } else {
            // Restore button on error
            button.prop('disabled', false).css('opacity', '1');
            icon.removeClass('dashicons-update').addClass(currentIcon);
            text.text(currentIcon === 'dashicons-moon' ? 'Dark Mode' : 'Light Mode');
        }
    });
}

function cmpLoadTab(event, tab) {
    event.preventDefault();
    
    // Show loading overlay with current theme
    var isLight = jQuery('.ezit-fullpage').hasClass('ezit-light');
    var overlay = jQuery('<div class="ezit-loading-overlay ' + (isLight ? 'ezit-light' : '') + '"><div class="ezit-loading-content"><div class="ezit-loading-spinner"></div><div class="ezit-loading-text">Loading...</div></div></div>');
    jQuery('body').append(overlay);
    setTimeout(function() {
        overlay.addClass('active');
    }, 10);
    
    // Navigate to tab
    window.location.href = '<?php echo admin_url('admin.php?page=chalkboard-menu-pro&tab='); ?>' + tab;
}

// Show message modal
function cmpShowMessage(message, type, callback) {
    // Detect current theme
    var isLight = jQuery('.ezit-fullpage').hasClass('ezit-light');
    var themeClass = isLight ? 'ezit-light' : 'ezit-dark';
    
    var overlay = jQuery('<div class="ezit-modal-overlay ' + themeClass + '"></div>');
    var modal = jQuery('<div class="ezit-modal"></div>');
    var content = jQuery('<div class="ezit-modal-content"></div>');
    var icon = jQuery('<div class="ezit-modal-icon"><span class="dashicons dashicons-' + (type === 'success' ? 'yes-alt' : 'warning') + '"></span></div>');
    var text = jQuery('<p class="ezit-modal-text"></p>').text(message);
    var buttons = jQuery('<div class="ezit-modal-buttons"></div>');
    var okBtn = jQuery('<button class="ezit-modal-btn ezit-modal-confirm">OK</button>');
    
    buttons.append(okBtn);
    content.append(icon).append(text).append(buttons);
    modal.append(content);
    overlay.append(modal);
    jQuery('body').append(overlay);
    
    // Animate in
    setTimeout(function() {
        overlay.addClass('ezit-modal-active');
    }, 10);
    
    // Handle OK
    okBtn.on('click', function() {
        overlay.removeClass('ezit-modal-active');
        setTimeout(function() {
            overlay.remove();
            if (typeof callback === 'function') {
                callback();
            }
        }, 200);
    });
    
    // Update icon color based on theme
    if (type === 'success') {
        icon.find('.dashicons').css('color', isLight ? '#16a34a' : '#a3e635');
    } else if (type === 'error') {
        icon.find('.dashicons').css('color', '#ef4444');
    }
}

// Custom confirmation modal
function cmpConfirm(message, callback) {
    // Detect current theme
    var isLight = jQuery('.ezit-fullpage').hasClass('ezit-light');
    var themeClass = isLight ? 'ezit-light' : 'ezit-dark';
    
    // Create modal overlay
    var overlay = jQuery('<div class="ezit-modal-overlay ' + themeClass + '"></div>');
    var modal = jQuery('<div class="ezit-modal"></div>');
    var content = jQuery('<div class="ezit-modal-content"></div>');
    var icon = jQuery('<div class="ezit-modal-icon"><span class="dashicons dashicons-warning"></span></div>');
    var text = jQuery('<p class="ezit-modal-text"></p>').text(message);
    var buttons = jQuery('<div class="ezit-modal-buttons"></div>');
    var confirmBtn = jQuery('<button class="ezit-modal-btn ezit-modal-confirm">Confirm</button>');
    var cancelBtn = jQuery('<button class="ezit-modal-btn ezit-modal-cancel">Cancel</button>');
    
    buttons.append(confirmBtn).append(cancelBtn);
    content.append(icon).append(text).append(buttons);
    modal.append(content);
    overlay.append(modal);
    jQuery('body').append(overlay);
    
    // Animate in
    setTimeout(function() {
        overlay.addClass('ezit-modal-active');
    }, 10);
    
    // Handle confirm
    confirmBtn.on('click', function() {
        overlay.removeClass('ezit-modal-active');
        setTimeout(function() {
            overlay.remove();
            if (typeof callback === 'function') {
                callback();
            }
        }, 200);
    });
    
    // Handle cancel
    cancelBtn.on('click', function() {
        overlay.removeClass('ezit-modal-active');
        setTimeout(function() {
            overlay.remove();
        }, 200);
    });
    
    // Handle overlay click
    overlay.on('click', function(e) {
        if (e.target === overlay[0]) {
            overlay.removeClass('ezit-modal-active');
            setTimeout(function() {
                overlay.remove();
            }, 200);
        }
    });
}
</script>

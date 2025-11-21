<?php
/**
 * Admin Styles for Chalkboard Menu Pro
 * Matches Ez IT Solutions styling with light/dark mode support
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
/* Remove WordPress default padding on our pages */
#wpcontent {
    padding-left: 0 !important;
    padding-bottom: 0 !important;
}

#wpbody-content {
    padding-bottom: 0 !important;
}

/* Base Styles - Ez IT Solutions Standard */

.ezit-fullpage {
    background: #0b0f12;
    color: #e5e7eb;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: background-color 0.3s ease, color 0.3s ease;
    position: relative;
}

.ezit-fullpage * {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

/* Fade in on page load */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.ezit-fullpage {
    animation: fadeIn 0.3s ease;
}

.ezit-light {
    background: #f3f4f6;
    color: #111827;
}

/* Header */
.ezit-header {
    background: #0b0f12;
    border-bottom: 3px solid #a3e635;
    padding: 45px 32px 32px 32px;
    box-shadow: 0 2px 8px rgba(163, 230, 53, 0.1);
}

.ezit-light .ezit-header {
    background: #f1f1f1;
    border-bottom: 3px solid #16a34a;
    box-shadow: 0 2px 8px rgba(22, 163, 74, 0.1);
}

.ezit-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

.ezit-header-title {
    font-size: 38px;
    font-weight: 700;
    margin: 0;
    color: #a3e635;
    display: flex;
    align-items: center;
    gap: 12px;
}

.ezit-light .ezit-header-title {
    color: #16a34a;
}

.ezit-header-title .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
}

.ezit-header-subtitle {
    margin: 10px 0 0 0;
    color: #ffffff;
    font-size: 14px;
}

.ezit-light .ezit-header-subtitle {
    color: #727272;
}

.ezit-theme-toggle {
    background: rgba(163, 230, 53, 0.1);
    border: 1px solid rgba(163, 230, 53, 0.3);
    color: #a3e635;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.ezit-theme-icon {
    font-family: dashicons;
    display: inline-block;
    line-height: 1;
    font-weight: 400;
    font-style: normal;
    speak: never;
    text-decoration: inherit;
    text-transform: none;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    width: 20px;
    height: 20px;
    font-size: 20px;
    vertical-align: top;
}

.ezit-theme-icon::before {
    content: "";
}

.ezit-theme-icon.dashicons-moon::before {
    content: "\f319";
}

.ezit-theme-icon.dashicons-lightbulb::before {
    content: "\f339";
}

.ezit-theme-icon.dashicons-update::before {
    content: "\f463";
}

.ezit-theme-icon.dashicons-update {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.ezit-theme-toggle:hover {
    background: rgba(163, 230, 53, 0.2);
    border-color: rgba(163, 230, 53, 0.5);
}

.ezit-light .ezit-theme-toggle {
    background: rgba(22, 163, 74, 0.1);
    border-color: rgba(22, 163, 74, 0.3);
    color: #16a34a;
}

.ezit-light .ezit-theme-toggle:hover {
    background: rgba(22, 163, 74, 0.2);
    border-color: rgba(22, 163, 74, 0.5);
}

/* Navigation Tabs */
.ezit-nav-wrapper {
    background: #1a1f26;
    border-bottom: 2px solid rgba(163, 230, 53, 0.2);
    padding: 0 32px;
}

.ezit-light .ezit-nav-wrapper {
    background: #ffffff;
    border-bottom: 2px solid rgba(22, 163, 74, 0.2);
}

.ezit-nav-tabs {
    display: flex;
    gap: 8px;
    max-width: 1400px;
    margin: 0 auto;
}

.ezit-nav-tab {
    padding: 14px 20px;
    color: #dbdbdb;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
}

.ezit-light .ezit-nav-tab {
    color: #4b5563;
}

.ezit-nav-tab:hover {
    color: #a3e635;
    background: rgba(163, 230, 53, 0.05);
}

.ezit-nav-tab:focus,
.ezit-nav-tab:active {
    color: #a3e635 !important;
    outline: none !important;
    box-shadow: none !important;
}

.ezit-nav-tab-active {
    color: #a3e635;
    border-bottom-color: #a3e635;
    background: rgba(163, 230, 53, 0.1);
}

.ezit-light .ezit-nav-tab:hover {
    color: #16a34a;
    background: rgba(22, 163, 74, 0.05);
}

.ezit-light .ezit-nav-tab:focus,
.ezit-light .ezit-nav-tab:active {
    color: #16a34a !important;
    outline: none !important;
    box-shadow: none !important;
}

.ezit-light .ezit-nav-tab-active {
    color: #16a34a;
    border-bottom-color: #16a34a;
    background: rgba(22, 163, 74, 0.1);
}

/* Content */
.ezit-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 32px;
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 32px;
    align-items: start;
    flex: 1;
    width: 100%;
}

.ezit-main {
    min-width: 0;
    max-width: 100%;
    display: flex;
    flex-direction: column;
}

.ezit-page-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 30px 0;
    color: #a3e635;
}

.ezit-light .ezit-page-title {
    color: #16a34a;
}

.ezit-description {
    margin: 0 0 24px 0;
    color: #9ca3af;
    font-size: 14px;
    line-height: 1.5;
}

.ezit-light .ezit-description {
    color: #6b7280;
}

/* Stats Grid */
.ezit-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.ezit-stat-card {
    background: #1a1f26;
    border: 1px solid rgba(163, 230, 53, 0.2);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    gap: 16px;
    align-items: center;
}

.ezit-light .ezit-stat-card {
    background: #ffffff;
    border-color: rgba(22, 163, 74, 0.2);
}

.ezit-stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(163, 230, 53, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a3e635;
}

.ezit-light .ezit-stat-icon {
    background: rgba(22, 163, 74, 0.1);
    color: #16a34a;
}

.ezit-stat-icon .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}

.ezit-stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #a3e635;
    line-height: 1;
}

.ezit-light .ezit-stat-value {
    color: #16a34a;
}

.ezit-stat-label {
    font-size: 13px;
    color: #dbdbdb;
    margin-top: 4px;
}

.ezit-light .ezit-stat-label {
    color: #6b7280;
}

/* Cards */
.ezit-card {
    background: #1a1f26;
    border: 1px solid rgba(163, 230, 53, 0.2);
    border-radius: 8px;
    padding: 24px;
}

.ezit-light .ezit-card {
    background: rgba(22, 163, 74, 0.04);
    border-color: rgba(22, 163, 74, 0.15);
}

.ezit-card h3 {
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 16px 0;
    color: #a3e635;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ezit-light .ezit-card h3 {
    color: #16a34a;
}

.ezit-card h3 .dashicons {
    color: #a3e635;
}

.ezit-light .ezit-card h3 .dashicons {
    color: #16a34a;
}

/* Quick Actions */
.ezit-quick-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.ezit-action-btn {
    background: rgba(163, 230, 53, 0.1);
    border: 1px solid rgba(163, 230, 53, 0.3);
    color: #a3e635;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
}

.ezit-action-btn:hover {
    background: rgba(163, 230, 53, 0.2);
    border-color: rgba(163, 230, 53, 0.5);
    color: #a3e635;
}

.ezit-action-btn-primary {
    background: #a3e635;
    color: #0b0f12;
    border-color: #a3e635;
}

.ezit-action-btn-primary:hover {
    background: #bef264;
    color: #0b0f12;
}

.ezit-light .ezit-action-btn {
    background: rgba(22, 163, 74, 0.1);
    border-color: rgba(22, 163, 74, 0.3);
    color: #16a34a;
}

.ezit-light .ezit-action-btn:hover {
    background: rgba(22, 163, 74, 0.2);
    color: #16a34a;
}

.ezit-light .ezit-action-btn-primary {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
}

.ezit-light .ezit-action-btn-primary:hover {
    background: #15803d;
    color: #ffffff;
}

/* Sidebar */
.ezit-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ezit-sidebar-card {
    background: #1a1f26;
    border: 1px solid rgba(163, 230, 53, 0.2);
    border-radius: 8px;
    padding: 20px;
    margin: 8px 0;
}

.ezit-light .ezit-sidebar-card {
    background: #ffffff;
    border-color: rgba(22, 163, 74, 0.2);
}

.ezit-widget-title {
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 12px 0;
    color: #a3e635;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ezit-light .ezit-widget-title {
    color: #16a34a;
}

.ezit-widget-title .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Quick Actions Launcher */
.ezit-quick-launcher {
    background: linear-gradient(135deg, rgba(163, 230, 53, 0.15) 0%, rgba(163, 230, 53, 0.05) 100%);
    border: 1px solid rgba(163, 230, 53, 0.3);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(163, 230, 53, 0.1);
}

.ezit-light .ezit-quick-launcher {
    background: rgba(22, 163, 74, 0.04);
    border-color: rgba(22, 163, 74, 0.3);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.1);
}

.ezit-quick-launcher-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.ezit-quick-action {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(163, 230, 53, 0.2);
    border-radius: 8px;
    padding: 12px 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: #e5e7eb;
}

.ezit-light .ezit-quick-action {
    background: rgba(255, 255, 255, 0.8);
    border-color: rgba(22, 163, 74, 0.2);
    color: #404348;
}

.ezit-quick-action:hover {
    background: rgba(163, 230, 53, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(163, 230, 53, 0.2);
    border-color: rgba(163, 230, 53, 0.4);
    color: #ffffff;
}

.ezit-light .ezit-quick-action:hover {
    background: rgb(255 255 255);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    border-color: rgb(0 0 0 / 40%);
    color: #16a34a;
}

.ezit-quick-action .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
}

.ezit-quick-action-label {
    font-size: 11px;
    font-weight: 600;
    line-height: 1.2;
}

.ezit-sidebar-card h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 12px 0;
    color: #a3e635;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ezit-light .ezit-sidebar-card h3 {
    color: #16a34a;
}

.ezit-sidebar-card h3 .dashicons {
    color: #a3e635;
}

.ezit-light .ezit-sidebar-card h3 .dashicons {
    color: #16a34a;
}

.ezit-sidebar-list {
    margin: 12px 0 0 0;
    padding-left: 20px;
    color: #9ca3af;
    font-size: 14px;
}

.ezit-light .ezit-sidebar-list {
    color: #4b5563;
}

.ezit-sidebar-list li {
    margin: 6px 0;
}

.ezit-sidebar-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #a3e635;
    text-decoration: none;
    font-size: 14px;
}

.ezit-sidebar-link:hover,
.ezit-sidebar-link:focus {
    color: #bef264 !important;
    text-decoration: none !important;
}

.ezit-light .ezit-sidebar-link {
    color: #16a34a;
}

.ezit-light .ezit-sidebar-link:hover,
.ezit-light .ezit-sidebar-link:focus {
    color: #15803d !important;
}

/* Help Button - Sidebar Documentation Button */
.ezit-sidebar-doc-btn {
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 40px;
    background: rgba(163, 230, 53, 0.15);
    color: #a3e635 !important;
    border: 1px solid rgba(163, 230, 53, 0.4);
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.ezit-sidebar-doc-btn:hover {
    background: rgba(163, 230, 53, 0.25);
    border-color: rgba(163, 230, 53, 0.6);
    color: #bef264 !important;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(163, 230, 53, 0.3);
}

.ezit-light .ezit-sidebar-doc-btn {
    background: rgba(22, 163, 74, 0.15);
    color: #16a34a !important;
    border-color: rgba(22, 163, 74, 0.4);
}

.ezit-light .ezit-sidebar-doc-btn:hover {
    background: rgba(22, 163, 74, 0.25);
    border-color: rgba(22, 163, 74, 0.6);
    color: #15803d !important;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.ezit-sidebar-doc-btn .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Footer */
.ezit-footer {
    background: #1a1f26;
    border-top: 2px solid #a3e635;
    padding: 32px 32px 47px;
    margin-top: auto;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
}

.ezit-light .ezit-footer {
    background: #ffffff;
    border-top: 2px solid #16a34a;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.ezit-footer-content {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 32px;
}

.ezit-footer-section h4 {
    color: #a3e635;
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 16px 0;
}

.ezit-light .ezit-footer-section h4 {
    color: #16a34a;
}

.ezit-footer-section p {
    color: #ffffff;
    font-size: 14px;
    line-height: 1.6;
    margin: 8px 0;
}

.ezit-light .ezit-footer-section p {
    color: #1f2937;
}

.ezit-footer-section a {
    color: #a3e635;
    font-size: 14px;
    line-height: 1.6;
    margin: 8px 0;
    text-decoration: none;
}

.ezit-footer-section a:hover {
    color: #ffffff;
}

.ezit-light .ezit-footer-section a {
    color: #16a34a;
}

.ezit-light .ezit-footer-section a:hover {
    color: #15803d;
}

.ezit-footer-bottom {
    max-width: 1400px;
    margin: 24px auto 0;
    padding: 24px 0;
    border-top: 1px solid #a3e635;
    text-align: center;
    color: #ffffff;
    font-size: 13px;
}

.ezit-light .ezit-footer-bottom {
    border-top-color: #16a34a;
    color: #1f2937;
}

/* Loading Overlay */
.ezit-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.ezit-loading-overlay.active {
    opacity: 1;
}

.ezit-loading-content {
    background: #1a1f26;
    padding: 48px 64px;
    border-radius: 12px;
    border: 3px solid #a3e635;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
}

.ezit-loading-overlay.ezit-light .ezit-loading-content {
    background: #ffffff;
    border-color: #16a34a;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
}

.ezit-loading-spinner {
    width: 64px;
    height: 64px;
    border: 5px solid rgba(163, 230, 53, 0.2);
    border-top-color: #a3e635;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.ezit-loading-overlay.ezit-light .ezit-loading-spinner {
    border: 5px solid rgba(22, 163, 74, 0.2);
    border-top-color: #16a34a;
}

.ezit-loading-text {
    color: #e5e7eb;
    font-size: 16px;
    font-weight: 600;
}

.ezit-loading-overlay.ezit-light .ezit-loading-text {
    color: #1f2937;
}
</style>

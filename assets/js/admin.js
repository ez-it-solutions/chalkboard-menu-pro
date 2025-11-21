(function ($) {
  'use strict';

  function applyStoredTheme($root) {
    var stored = window.localStorage ? window.localStorage.getItem('cmpAdminTheme') : null;
    if (!stored) {
      return;
    }
    $root.removeClass('cmp-theme-light cmp-theme-dark');
    if (stored === 'dark') {
      $root.addClass('cmp-theme-dark');
    } else {
      $root.addClass('cmp-theme-light');
    }
  }

  $(function () {
    var $root = $('#cmp-admin-root');
    if (!$root.length) {
      return;
    }

    applyStoredTheme($root);

    $('#cmp-theme-toggle').on('click', function () {
      var isDark = $root.hasClass('cmp-theme-dark');
      $root.toggleClass('cmp-theme-dark', !isDark);
      $root.toggleClass('cmp-theme-light', isDark);

      if (window.localStorage) {
        window.localStorage.setItem('cmpAdminTheme', !isDark ? 'dark' : 'light');
      }
    });
  });
})(jQuery);

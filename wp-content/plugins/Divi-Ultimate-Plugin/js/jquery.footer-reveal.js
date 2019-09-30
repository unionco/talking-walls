(function($) {

  $.fn.footerReveal = function(options) {

    var $this = $(this),
        $prev = $("#main-content"),
        $win = $(window),

        defaults = $.extend ({
          zIndex : 1
        }, options ),

        settings = $.extend(true, {}, defaults, options);

    if ($this.outerHeight() <= $win.outerHeight() && $this.offset().top >= $win.outerHeight()) {
      $this.css({
        'z-index' : defaults.zIndex,
        position : 'fixed',
        bottom : 0
      });

      $win.on('load resize footerRevealResize', function() {
        $this.css({
          'width' : $prev.outerWidth()
        });
        $prev.css({
          'margin-bottom' : $this.outerHeight()
        });
      });
    }

    return this;

  };

}) (jQuery);
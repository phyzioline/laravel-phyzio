

$(function () {
  "use strict";


  /* scrollar */

  new PerfectScrollbar(".notify-list")

  // new PerfectScrollbar(".mega-menu-widgets")



  /* toggle button - Enhanced for reliability and direct DOM manipulation */

  $(".btn-toggle").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    
    if ($("body").hasClass("toggled")) {
      $("body").removeClass("toggled");
      $(".sidebar-wrapper").unbind("hover");
    } else {
      $("body").addClass("toggled");
      $(".sidebar-wrapper").hover(
        function () {
          $("body").addClass("sidebar-hovered");
        }, 
        function () {
          $("body").removeClass("sidebar-hovered");
        }
      );
    }
  });

  // Backup toggle handler for icon clicks
  $(".btn-toggle i").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).closest(".btn-toggle").trigger("click");
  });
  
  // Additional direct click handler on the toggle container
  $(document).on("click", ".btn-toggle", function(e) {
    console.log("Toggle clicked!");
  });




  /* menu - Enhanced for both LTR (English) and RTL (Arabic) */

  // Initialize metisMenu - works for both LTR and RTL
  var metisMenuInitialized = false;
  
  function initMetisMenu() {
    // Prevent multiple initializations
    if (metisMenuInitialized) {
      return;
    }
    
    var $sidenav = $('#sidenav');
    if ($sidenav.length && typeof $.fn.metisMenu !== 'undefined') {
      try {
        // Destroy existing instance if any to prevent conflicts
        if ($sidenav.data('metisMenu')) {
          try {
            $sidenav.metisMenu('dispose');
          } catch(e) {
            // Ignore dispose errors
          }
        }
        
        // Initialize with default configuration (works for both LTR and RTL)
        $sidenav.metisMenu({
          toggle: true,
          doubleTapToGo: false,
          preventDefault: true
        });
        
        metisMenuInitialized = true;
        console.log('metisMenu initialized successfully');
      } catch(e) {
        console.error('Error initializing metisMenu:', e);
      }
    } else {
      // Retry if not ready yet
      if (typeof $.fn.metisMenu === 'undefined') {
        setTimeout(initMetisMenu, 100);
      }
    }
  }

  // Initialize when DOM is ready
  $(document).ready(function() {
    // Small delay to ensure all CSS is loaded
    setTimeout(function() {
      initMetisMenu();
    }, 50);
  });

  // Backup initialization on window load
  $(window).on('load', function() {
    if (!metisMenuInitialized) {
      setTimeout(function() {
        initMetisMenu();
      }, 100);
    }
  });

  $(".sidebar-close").on("click", function () {
    $("body").removeClass("toggled")
  })



  /* dark mode button */

  $(".dark-mode i").click(function () {
    $(this).text(function (i, v) {
      return v === 'dark_mode' ? 'light_mode' : 'dark_mode'
    })
  });


  $(".dark-mode").click(function () {
    $("html").attr("data-bs-theme", function (i, v) {
      return v === 'dark' ? 'light' : 'dark';
    })
  })


  /* sticky header - REMOVED for admin Arabic version */


  /* email */

  $(".email-toggle-btn").on("click", function() {
    $(".email-wrapper").toggleClass("email-toggled")
  }), $(".email-toggle-btn-mobile").on("click", function() {
    $(".email-wrapper").removeClass("email-toggled")
  }), $(".compose-mail-btn").on("click", function() {
    $(".compose-mail-popup").show()
  }), $(".compose-mail-close").on("click", function() {
    $(".compose-mail-popup").hide()
  }), 


  /* chat */

  $(".chat-toggle-btn").on("click", function() {
    $(".chat-wrapper").toggleClass("chat-toggled")
  }), $(".chat-toggle-btn-mobile").on("click", function() {
    $(".chat-wrapper").removeClass("chat-toggled")
  }),



  /* switcher */

  $("#BlueTheme").on("click", function () {
    $("html").attr("data-bs-theme", "blue-theme")
  }),

  $("#LightTheme").on("click", function () {
    $("html").attr("data-bs-theme", "light")
  }),

    $("#DarkTheme").on("click", function () {
      $("html").attr("data-bs-theme", "dark")
    }),

    $("#SemiDarkTheme").on("click", function () {
      $("html").attr("data-bs-theme", "semi-dark")
    }),

    $("#BoderedTheme").on("click", function () {
      $("html").attr("data-bs-theme", "bodered-theme")
    })



  /* search control */

  $(".search-control").click(function () {
    $(".search-popup").addClass("d-block");
    $(".search-close").addClass("d-block");
  });


  $(".search-close").click(function () {
    $(".search-popup").removeClass("d-block");
    $(".search-close").removeClass("d-block");
  });


  $(".mobile-search-btn").click(function () {
    $(".search-popup").addClass("d-block");
  });


  $(".mobile-search-close").click(function () {
    $(".search-popup").removeClass("d-block");
  });




  /* menu active */

  $(function () {
    for (var e = window.location, o = $(".metismenu li a").filter(function () {
      return this.href == e
    }).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
  });



});











(function ($) {
  "use strict";

  var toffedassen = toffedassen || {};
  toffedassen.init = function () {
    (toffedassen.$body = $(document.body)),
      (toffedassen.$window = $(window)),
      (toffedassen.$header = $("#masthead")),
      (toffedassen.ajaxXHR = null);

    // Scroll
    this.scrollTop();
    this.preload();

    // Parallax
    this.parallax();
    this.toffedassenToggleClass();
    this.photoSwipe();

    // Header
    this.canvasPanel();
    this.menuSideBar();
    this.instanceSearch();
    this.toggleModal();
    this.menuHover();
    this.stickyHeader();
    this.headerCss();

    // Blog
    this.blogLayout();

    // Shop
    this.shopView();
    this.shopLoadingAjax();
    this.viewPort();
    this.showFilterContent();
    this.filterAjax();
    this.productQuantity();
    this.addWishlist();
    this.showAddedToCartNotice();
    this.tooltip();
    this.productQuickView();
    this.productAttribute();
    this.filterScroll();

    // Single Product
    this.resizeProductGallery();
    this.productThumbnail();
    this.productGalleryCarousel();
    this.productvariation();
    this.singleProductCarousel();
    this.productImagesLightbox();
    this.stickyProductSummary();
    this.loginTab();
    this.addToCartAjax();

    // Login
    this.loginModalAuthenticate();

    // Portfolio
    this.portfolioMasonry();
    this.portfolioLoadingAjax();
    this.portfolioCarousel();
  };

  toffedassen.toffedassenToggleClass = function () {
    toffedassen.$window
      .on("resize", function () {
        var wWidth = $("#content").width(),
          space = 0;

        if (toffedassen.$window.width() <= 1600) {
          $(".menu-extra li.menu-item-search").removeAttr("id");
        }

        if (wWidth > 1170) {
          space = (wWidth - 1170) / 2;
        }

        $(".portfolio-carousel .swiper-container").css({
          "margin-right": space * -1,
          "margin-left": space * -1,
          "padding-right": space,
          "padding-left": space,
        });

        $(".portfolio-carousel .swiper-button-prev").css({
          left: space - 62,
        });

        $(".portfolio-carousel .swiper-button-next").css({
          right: space - 62,
        });

        if (toffedassenData.isRTL !== "1") {
          $(".gadgetexpress-right-offset").css({
            "margin-right": space * -1,
          });
        }
      })
      .trigger("resize");
  };

  toffedassen.resizeProductGallery = function () {
    if (!toffedassen.$body.hasClass("single-product-layout-6")) {
      return;
    }

    if (toffedassen.$window.width() <= 991) {
      return;
    }

    toffedassen.$window
      .on("resize", function () {
        var wWidth = $("#content").width(),
          wRight = 0;

        if (wWidth > 1170) {
          wRight = (wWidth - 1170) / 2;
        }

        $(".gadgetexpress-single-product-detail")
          .find(".woocommerce-product-gallery")
          .css({
            "margin-right": wRight * -1,
          });
      })
      .trigger("resize");
  };

  // Scroll Top
  toffedassen.scrollTop = function () {
    var $scrollTop = $("#scroll-top");
    toffedassen.$window.scroll(function () {
      if (toffedassen.$window.scrollTop() > toffedassen.$window.height()) {
        $scrollTop.addClass("show-scroll");
      } else {
        $scrollTop.removeClass("show-scroll");
      }
    });

    // Scroll effect button top
    $scrollTop.on("click", function (event) {
      event.preventDefault();
      $("html, body").stop().animate(
        {
          scrollTop: 0,
        },
        800
      );
    });
  };

  /**
   * Add page preloader
   */
  toffedassen.preload = function () {
    var $preloader = $("#preloader"),
      without_link = false;

    if (!$preloader.length) {
      return;
    }

    toffedassen.$body.on("click", "a", function () {
      without_link = false;
      if ($(this).hasClass("sp-without-preloader")) {
        without_link = true;
      }
    });

    toffedassen.$window.on("beforeunload", function (e) {
      if (without_link) {
        return;
      }
      $preloader.removeClass("out").fadeIn(500, function () {
        $preloader.addClass("loading");
      });
    });

    toffedassen.$window.on("pageshow", function () {
      $preloader.fadeOut(100, function () {
        $preloader.addClass("out loading");
      });
    });

    toffedassen.$window.on("beforeunload", function () {
      $preloader.fadeIn("slow");
    });

    NProgress.start();
    toffedassen.$window.on("load", function () {
      NProgress.done();
      $preloader.fadeOut(800);
    });
  };

  // Parallax
  toffedassen.parallax = function () {
    if (toffedassen.$window.width() < 1200) {
      return;
    }

    $(".page-header.parallax .feature-image").parallax("50%", 0.6);
    $(".gadgetexpress-sale-product.parallax").parallax("50%", 0.6);
  };

  // photoSwipe
  toffedassen.photoSwipe = function () {
    var $images = $(".gadgetexpress-photo-swipe");

    if (!$images.length) {
      return;
    }

    var $links = $images.find("a.photoswipe"),
      items = [];

    $links.each(function () {
      var $this = $(this),
        $w = $this.attr("data-width"),
        $h = $this.attr("data-height");

      items.push({
        src: $this.attr("href"),
        w: $w,
        h: $h,
      });
    });

    $images.on("click", "a.photoswipe", function (e) {
      e.preventDefault();

      var index = $links.index($(this)),
        options = {
          index: index,
          bgOpacity: 0.85,
          showHideOpacity: true,
          mainClass: "pswp--minimal-dark",
          barsSize: { top: 0, bottom: 0 },
          captionEl: false,
          fullscreenEl: false,
          shareEl: false,
          tapToClose: true,
          tapToToggleControls: false,
        };

      var lightBox = new PhotoSwipe(
        document.getElementById("pswp"),
        window.PhotoSwipeUI_Default,
        items,
        options
      );
      lightBox.init();
    });
  };

  // Off Canvas Panel
  toffedassen.canvasPanel = function () {
    /**
     * Off canvas cart toggle
     */
    toffedassen.$header.on("click", ".cart-contents", function (e) {
      e.preventDefault();
      toffedassen.openCanvasPanel($("#cart-panel"));
    });

    if (toffedassenData.open_cart_mini == "1") {
      $(document.body).on("added_to_cart", function () {
        toffedassen.openCanvasPanel($("#cart-panel"));
      });
    }

    toffedassen.$header.on("click", "#icon-menu-sidebar", function (e) {
      e.preventDefault();
      toffedassen.openCanvasPanel($("#menu-sidebar-panel"));
    });

    toffedassen.$header.on("click", "#icon-menu-mobile", function (e) {
      e.preventDefault();
      toffedassen.openCanvasPanel($("#menu-sidebar-panel"));
    });

    toffedassen.$body.on(
      "click",
      "#off-canvas-layer, .close-canvas-panel",
      function (e) {
        e.preventDefault();
        toffedassen.closeCanvasPanel();
      }
    );
  };

  toffedassen.openCanvasPanel = function ($panel) {
    toffedassen.$body.addClass("open-canvas-panel");
    $panel.addClass("open");
  };

  toffedassen.closeCanvasPanel = function () {
    toffedassen.$body.removeClass("open-canvas-panel");
    $(
      ".gadgetexpress-off-canvas-panel, #primary-mobile-nav, .catalog-sidebar, .woocommerce-products-header"
    ).removeClass("open");
  };

  // Toggle Menu Sidebar
  toffedassen.menuSideBar = function () {
    var $menuSidebar = $("#menu-sidebar-panel"),
      $item = $menuSidebar.find("li.menu-item-has-children > a");

    $menuSidebar
      .find(".menu .menu-item-has-children")
      .prepend(
        '<span class="toggle-menu-children"><i class="icon-plus"></i> </span>'
      );

    if (
      toffedassenData.menu_mobile_behaviour === "icon" &&
      toffedassen.$window.width() < 1200
    ) {
      $item = $menuSidebar.find(
        "li.menu-item-has-children .toggle-menu-children"
      );
    }

    toffedassen.mobileMenuSidebar($item);
  };

  toffedassen.mobileMenuSidebar = function ($item) {
    $item.on("click", function (e) {
      e.preventDefault();

      $(this)
        .closest("li")
        .siblings()
        .find("ul.sub-menu, ul.dropdown-submenu")
        .slideUp();
      $(this).closest("li").siblings().removeClass("active");

      $(this)
        .closest("li")
        .children("ul.sub-menu, ul.dropdown-submenu")
        .slideToggle();
      $(this).closest("li").toggleClass("active");
    });
  };

  /**
   * Product instance search
   */
  toffedassen.instanceSearch = function () {
    if (toffedassenData.ajax_search === "0") {
      return;
    }

    var xhr = null,
      searchCache = {},
      $modal = $(".search-modal"),
      $form = $modal.find("form"),
      $search = $form.find("input.search-field"),
      $results = $modal.find(".search-results");

    $modal
      .on("keyup", ".search-field", function (e) {
        var valid = false;

        if (typeof e.which == "undefined") {
          valid = true;
        } else if (typeof e.which == "number" && e.which > 0) {
          valid = !e.ctrlKey && !e.metaKey && !e.altKey;
        }

        if (!valid) {
          return;
        }

        if (xhr) {
          xhr.abort();
        }

        search();
      })
      .on("change", ".product-cats input", function () {
        if (xhr) {
          xhr.abort();
        }

        search();
      })
      .on("focusout", ".search-field", function () {
        if ($search.val().length < 2) {
          $modal.removeClass(
            "searching searched actived found-products found-no-product invalid-length"
          );
        }
      });

    outSearch();

    /**
     * Private function for search
     */
    function search() {
      var keyword = $search.val(),
        cat = "";

      if ($modal.find(".product-cats").length > 0) {
        cat = $modal.find(".product-cats input:checked").val();
      }

      if (keyword.length < 2) {
        $modal
          .removeClass(
            "searching searched actived found-products found-no-product"
          )
          .addClass("invalid-length");
        return;
      }

      $modal
        .removeClass("found-products found-no-product")
        .addClass("searching");

      var keycat = keyword + cat;

      if (keycat in searchCache) {
        var result = searchCache[keycat];

        $modal.removeClass("searching");

        $modal.addClass("found-products");

        $results.find(".woocommerce").html(result.products);

        $(document.body).trigger("gadgetexpress_ajax_search_request_success", [
          $results,
        ]);

        $results.find(".woocommerce, .buttons").slideDown(function () {
          $modal.removeClass("invalid-length");
        });

        $modal.addClass("searched actived");
      } else {
        xhr = $.ajax({
          url: toffedassenData.ajax_url,
          dataType: "json",
          method: "post",
          data: {
            action: "gadgetexpress_search_products",
            nonce: toffedassenData.nonce,
            term: keyword,
            cat: cat,
            search_type: toffedassenData.search_content_type,
          },
          success: function (response) {
            var $products = response.data;

            $modal.removeClass("searching");

            $modal.addClass("found-products");

            $results.find(".woocommerce").html($products);

            $results.find(".woocommerce, .buttons").slideDown(function () {
              $modal.removeClass("invalid-length");
            });

            $(document.body).trigger(
              "gadgetexpress_ajax_search_request_success",
              [$results]
            );

            // Cache
            searchCache[keycat] = {
              found: true,
              products: $products,
            };

            $modal.addClass("searched actived");
          },
        });
      }
    }

    /**
     * Private function for click out search
     */
    function outSearch() {
      if (
        toffedassen.$body.hasClass("header-layout-3") ||
        toffedassen.$body.hasClass("header-layout-5") ||
        toffedassen.$body.hasClass("header-layout-6")
      ) {
        return;
      }

      var $modal = $(".search-modal"),
        $search = $modal.find("input.search-field");
      if ($modal.length <= 0) {
        return;
      }

      toffedassen.$window.on("scroll", function () {
        if (toffedassen.$window.scrollTop() > 10) {
          $modal.removeClass("show found-products searched");
        }
      });

      $(document).on("click", function (e) {
        var target = e.target;
        if (
          !$(target).closest(".extra-menu-item").hasClass("menu-item-search")
        ) {
          $modal.removeClass(
            "searching searched found-products found-no-product invalid-length"
          );
        }
      });

      $modal.on("click", ".t-icon", function (e) {
        if ($modal.hasClass("actived")) {
          e.preventDefault();
          $search.val("");
          $modal.removeClass(
            "searching searched actived found-products found-no-product invalid-length"
          );
        }
      });
    }
  };

  /**
   *  Toggle modal
   */
  toffedassen.toggleModal = function () {
    toffedassen.$body.on("click", ".menu-extra-search", function (e) {
      e.preventDefault();
      toffedassen.openModal($(".search-modal"));
      $(this).addClass("show");
      $("#search-modal").find(".search-field").focus();
    });

    toffedassen.$body.on(
      "click",
      "#gadgetexpress-newsletter-icon",
      function (e) {
        e.preventDefault();
        toffedassen.openModal($("#footer-newsletter-modal"));
        $(this).addClass("hide");
      }
    );

    toffedassen.$body.on("click", "#menu-extra-login", function (e) {
      e.preventDefault();

      toffedassen.openModal($("#login-modal"));
      $(this).addClass("show");
    });

    toffedassen.$body.on("click", ".close-modal", function (e) {
      e.preventDefault();
      toffedassen.closeModal();
    });
  };

  /**
   * Main navigation sub-menu hover
   */
  toffedassen.menuHover = function () {
    var animations = {
      none: ["show", "hide"],
      fade: ["fadeIn", "fadeOut"],
      slide: ["slideDown", "slideUp"],
    };

    var animation = toffedassenData.menu_animation
      ? animations[toffedassenData.menu_animation]
      : "fade";

    $(".primary-nav li, .menu-extra li.menu-item-account")
      .on("mouseenter", function () {
        $(this)
          .addClass("active")
          .children(".dropdown-submenu")
          .stop(true, true)
          [animation[0]]();
      })
      .on("mouseleave", function () {
        $(this)
          .removeClass("active")
          .children(".dropdown-submenu")
          .stop(true, true)
          [animation[1]]();
      });
  };

  // Sticky Header
  toffedassen.stickyHeader = function () {
    if (!toffedassen.$body.hasClass("header-sticky")) {
      return;
    }

    if (toffedassen.$body.hasClass("header-left-sidebar")) {
      return;
    }

    if (toffedassen.$body.hasClass("page-template-template-coming-soon-page")) {
      return;
    }

    toffedassen.$window.on("scroll", function () {
      var scrollTop = 20,
        scroll = toffedassen.$window.scrollTop(),
        topbar,
        hHeader = toffedassen.$header.outerHeight(true);

      if (toffedassen.$body.hasClass("topbar-enable")) {
        topbar = $(".topbar").outerHeight(true);

        if (toffedassen.$window.width() < 1200) {
          topbar = $(".topbar-mobile").outerHeight(true);
        }

        scrollTop = scrollTop + topbar + hHeader;
      }

      if (scroll > scrollTop) {
        toffedassen.$header.addClass("minimized");
        $("#su-header-minimized").addClass("minimized");
      } else {
        toffedassen.$header.removeClass("minimized");
        $("#su-header-minimized").removeClass("minimized");
      }
    });

    toffedassen.$window
      .on("resize", function () {
        var hHeader = toffedassen.$header.outerHeight(true),
          $h = $("#su-header-minimized");

        if (!toffedassen.$body.hasClass("header-transparent")) {
          $h.height(hHeader);
        }
      })
      .trigger("resize");
  };

  // Header CSS
  toffedassen.headerCss = function () {
    if (!toffedassen.$body.hasClass("topbar-enable")) {
      return;
    }

    if (toffedassen.$body.hasClass("header-left-sidebar")) {
      return;
    }

    if (toffedassen.$body.hasClass("page-template-template-coming-soon-page")) {
      return;
    }

    var $headerTransparent = $(".header-transparent .site-header"),
      top = 0;

    if (toffedassen.$body.hasClass("admin-bar")) {
      top = 32;
    }

    toffedassen.$window
      .on("resize", function () {
        var topBar = $(".topbar").outerHeight(true);

        if (toffedassen.$window.width() < 1200) {
          topBar = $(".topbar-mobile").outerHeight(true);
        }

        $headerTransparent.css("top", top + topBar);
      })
      .trigger("resize");

    toffedassen.$window.on("scroll", function () {
      var scrollTop,
        scroll = toffedassen.$window.scrollTop(),
        hHeader = toffedassen.$header.outerHeight(true);

      var topBar = $(".topbar").outerHeight(true);

      if (toffedassen.$window.width() < 1200) {
        topBar = $(".topbar-mobile").outerHeight(true);
      }

      scrollTop = 20 + topBar + hHeader;

      if (scroll > scrollTop) {
        $headerTransparent.css("top", top);
      } else {
        $headerTransparent.css("top", top + topBar);
      }
    });
  };

  /**
   * Open modal
   *
   * @param $modal
   */
  toffedassen.openModal = function ($modal) {
    toffedassen.$body.addClass("modal-open");
    $modal.fadeIn();
    $modal.addClass("open");
  };

  /**
   * Close modal
   */
  toffedassen.closeModal = function () {
    toffedassen.$body.removeClass("modal-open");
    $(".gadgetexpress-modal").fadeOut(function () {
      toffedassen.$body
        .find(".menu-extra-search, #menu-extra-login")
        .removeClass("show");
      $("#gadgetexpress-newsletter-icon").removeClass("hide");
      $(this).removeClass("open");
    });
  };

  // Blog isotope
  toffedassen.blogLayout = function () {
    if (!toffedassen.$body.hasClass("blog-masonry")) {
      return;
    }

    var $blogList = toffedassen.$body.find(".gadgetexpress-post-list");

    $blogList.imagesLoaded(function () {
      $blogList.isotope({
        itemSelector: ".blog-wrapper",
        percentPosition: true,
        masonry: {
          columnWidth: ".blog-masonry-sizer",
          gutter: ".blog-gutter-sizer",
        },
      });
    });
  };

  /**
   * Shop
   */
  // Show Filter widget
  toffedassen.showFilterContent = function () {
    var $shopTopbar = $("#un-shop-topbar");

    toffedassen.$window
      .on("resize", function () {
        $shopTopbar.find(".widget-title").next().removeAttr("style");

        if (
          toffedassen.$body.hasClass("filter-mobile-enable") &&
          toffedassen.$window.width() < 1200
        ) {
          toffedassen.$body
            .find(".shop-toolbar #gadgetexpress-catalog-filter-mobile")
            .on("click", "a", function (e) {
              e.preventDefault();
              $(this).toggleClass("active");
              //	toffedassen.$body.toggleClass( 'show-filters-content-mobile' );
              toffedassen.$body.addClass("open-canvas-panel");

              if (toffedassen.$body.hasClass("full-content")) {
                $(".woocommerce-products-header").addClass("open");
              } else {
                $(".catalog-sidebar").addClass("open");
              }
            });
        } else {
          toffedassen.$body
            .find(".shop-toolbar #gadgetexpress-catalog-filter")
            .on("click", "a", function (e) {
              e.preventDefault();
              $(this).toggleClass("active");
              $shopTopbar.slideToggle();
              $shopTopbar.toggleClass("active");
              toffedassen.$body.toggleClass("show-filters-content");
            });
        }
      })
      .trigger("resize");
  };

  // Filter Ajax
  toffedassen.filterAjax = function () {
    if (!toffedassen.$body.hasClass("catalog-ajax-filter")) {
      return;
    }

    toffedassen.$body.on("price_slider_change", function (event, ui) {
      var form = $(".price_slider").closest("form").get(0),
        $form = $(form),
        url = $form.attr("action") + "?" + $form.serialize();

      $(document.body).trigger(
        "gadgetexpress_catelog_filter_ajax",
        url,
        $(this)
      );
    });

    toffedassen.$body.on("click", "#remove-filter-actived", function (e) {
      e.preventDefault();
      var url = $(this).attr("href");
      $(document.body).trigger(
        "gadgetexpress_catelog_filter_ajax",
        url,
        $(this)
      );
    });

    toffedassen.$body
      .find("#gadgetexpress-shop-toolbar")
      .find(".woocommerce-ordering")
      .on("click", "a", function (e) {
        e.preventDefault();
        $(this).addClass("active");
        var url = $(this).attr("href");
        $(document.body).trigger(
          "gadgetexpress_catelog_filter_ajax",
          url,
          $(this)
        );
      });

    toffedassen.$body
      .find("#un-shop-topbar, .catalog-sidebar")
      .on("click", "a", function (e) {
        var $widget = $(this).closest(".widget");
        if (
          $widget.hasClass("widget_product_tag_cloud") ||
          $widget.hasClass("widget_product_categories") ||
          $widget.hasClass("widget_layered_nav_filters") ||
          $widget.hasClass("widget_layered_nav") ||
          $widget.hasClass("product-sort-by") ||
          $widget.hasClass("gadgetexpress-price-filter-list")
        ) {
          e.preventDefault();
          $(this).closest("li").addClass("chosen");
          var url = $(this).attr("href");
          $(document.body).trigger(
            "gadgetexpress_catelog_filter_ajax",
            url,
            $(this)
          );
        }

        if ($widget.hasClass("widget_product_tag_cloud")) {
          $(this).addClass("selected");
        }

        if ($widget.hasClass("product-sort-by")) {
          $(this).addClass("active");
        }
      });

    toffedassen.$body.on(
      "gadgetexpress_catelog_filter_ajax",
      function (e, url, element) {
        var $container = $("#gadgetexpress-shop-content"),
          $container_nav = $("#primary-sidebar"),
          $shopTopbar = $("#un-shop-topbar"),
          $shopToolbar = $("#gadgetexpress-shop-toolbar"),
          $ordering = $(".shop-toolbar .woocommerce-ordering"),
          $found = $(".shop-toolbar .product-found"),
          $pageHeader = $(".page-header-catalog"),
          $productHeader = $(".woocommerce-products-header");

        if ($shopToolbar.length > 0) {
          var position = $shopToolbar.offset().top - 200;
          $("html, body").stop().animate(
            {
              scrollTop: position,
            },
            1200
          );
        }

        $(".gadgetexpress-catalog-loading").addClass("show");

        if ("?" == url.slice(-1)) {
          url = url.slice(0, -1);
        }

        url = url.replace(/%2C/g, ",");

        history.pushState(null, null, url);

        $(document.body).trigger(
          "gadgetexpress_ajax_filter_before_send_request",
          [url, element]
        );

        if (toffedassen.ajaxXHR) {
          toffedassen.ajaxXHR.abort();
        }

        toffedassen.ajaxXHR = $.get(
          url,
          function (res) {
            var $sContent =
              $(res).find("#gadgetexpress-shop-content").length > 0
                ? $(res).find("#gadgetexpress-shop-content").html()
                : "";
            $container.html($sContent);
            $container_nav.html($(res).find("#primary-sidebar").html());
            $shopTopbar.html($(res).find("#un-shop-topbar").html());

            if ($(res).find(".shop-toolbar .woocommerce-ordering").length > 0) {
              $ordering.html(
                $(res).find(".shop-toolbar .woocommerce-ordering").html()
              );
            }

            $found.html($(res).find(".shop-toolbar .product-found").html());
            $pageHeader.html($(res).find("#page-header-catalog").html());
            $productHeader.html(
              $(res).find(".woocommerce-products-header").html()
            );

            toffedassen.priceSlider();
            toffedassen.filterScroll();
            $(".gadgetexpress-catalog-loading").removeClass("show");

            $(document.body).trigger(
              "gadgetexpress_ajax_filter_request_success",
              [res, url]
            );

            toffedassen.shopView();
          },
          "html"
        );
      }
    );
  };

  // Change product quantity

  toffedassen.productQuantity = function () {
    toffedassen.$body.on(
      "click",
      ".quantity .increase, .quantity .decrease",
      function (e) {
        e.preventDefault();

        var $this = $(this),
          $qty = $this.siblings(".qty"),
          step = parseInt($qty.attr("step"), 10),
          current = parseInt($qty.val(), 10),
          min = parseInt($qty.attr("min"), 10),
          max = parseInt($qty.attr("max"), 10);

        min = min ? min : 1;
        max = max ? max : current + 1;

        if ($this.hasClass("decrease") && current > min) {
          $qty.val(current - step);
          $qty.trigger("change");
        }
        if ($this.hasClass("increase") && current < max) {
          $qty.val(current + step);
          $qty.trigger("change");
        }
      }
    );
  };

  // Shop View

  toffedassen.shopView = function () {
    $("#gadgetexpress-shop-view").on("click", "a", function (e) {
      e.preventDefault();
      var $el = $(this),
        view = $el.data("view");

      if ($el.hasClass("current")) {
        return;
      }

      $el.addClass("current").siblings().removeClass("current");
      toffedassen.$body
        .removeClass("shop-view-grid shop-view-list")
        .addClass("shop-view-" + view);

      document.cookie =
        "shop_view=" + view + ";domain=" + window.location.host + ";path=/";
    });
  };

  // Loading Ajax
  toffedassen.shopLoadingAjax = function () {
    // Shop Page
    toffedassen.$body.on(
      "click",
      "#gadgetexpress-shop-infinite-loading a.next",
      function (e) {
        e.preventDefault();

        if ($(this).data("requestRunning")) {
          return;
        }

        $(this).data("requestRunning", true);

        var $products = $(this)
            .closest(".woocommerce-pagination")
            .prev(".products"),
          $pagination = $(this).closest(".woocommerce-pagination"),
          $parent = $(this).parents("#gadgetexpress-shop-infinite-loading");

        $parent.addClass("loading");

        $.get($(this).attr("href"), function (response) {
          var content = $(response).find("ul.products").children("li.product"),
            $pagination_html = $(response)
              .find(".woocommerce-pagination")
              .html();

          $pagination.html($pagination_html);

          for (var index = 0; index < content.length; index++) {
            $(content[index]).css("animation-delay", index * 100 + 100 + "ms");
          }

          content.addClass("toffedassenFadeInUp toffedassenAnimation");

          $products.append(content);
          $pagination.find(".page-numbers.next").data("requestRunning", false);
          $parent.removeClass("loading");
          toffedassen.$body.trigger("gadgetexpress_shop_ajax_loading_success");

          if (!$pagination.find("li .page-numbers").hasClass("next")) {
            $pagination.addClass("loaded");
          }

          toffedassen.tooltip();
        });
      }
    );
  };

  toffedassen.viewPort = function () {
    if ("infinite" !== toffedassenData.shop_nav_type) {
      return;
    }

    toffedassen.$window
      .on("scroll", function () {
        if (
          toffedassen.$body
            .find("#gadgetexpress-shop-infinite-loading")
            .is(":in-viewport")
        ) {
          toffedassen.$body
            .find("#gadgetexpress-shop-infinite-loading a.next")
            .click();
        }
      })
      .trigger("scroll");
  };

  /**
   * Show photoSwipe lightbox for product images
   */
  toffedassen.productImagesLightbox = function () {
    var $images = $(".woocommerce-product-gallery");

    if (!$images.length) {
      return;
    }

    if ("no" == toffedassenData.product.lightbox) {
      $images.on(
        "click",
        "a.gallery-item-icon, a.video-item-icon",
        function () {
          return false;
        }
      );
      return;
    }

    var $links = $images.find(".woocommerce-product-gallery__image");

    $images.on("click", "a.gallery-item-icon", function (e) {
      e.preventDefault();

      var items = [];

      $links.each(function () {
        var $a = $(this).find("a");
        items.push({
          src: $a.attr("href"),
          w: $a.find("img").attr("data-large_image_width"),
          h: $a.find("img").attr("data-large_image_height"),
        });
      });

      var index = $images.find(".flex-active-slide").index();
      lightBox(index, items);
    });

    $images.on("click", "a.video-item-icon", function (e) {
      e.preventDefault();

      var items = [],
        $el = $(this);

      items.push({
        html: $el.data("href"),
      });

      var index = 0;
      lightBox(index, items);
    });

    $images
      .find(".woocommerce-product-gallery__image")
      .on("click", "a", function (e) {
        e.preventDefault();

        if (
          toffedassen.$body.hasClass("single-product-layout-1") ||
          toffedassen.$body.hasClass("single-product-layout-2")
        ) {
          return false;
        }

        var items = [];

        $links.each(function () {
          var $a = $(this).find("a");
          items.push({
            src: $a.attr("href"),
            w: $a.find("img").attr("data-large_image_width"),
            h: $a.find("img").attr("data-large_image_height"),
          });
        });

        var index = $links.index(
          $(this).closest(".woocommerce-product-gallery__image")
        );
        lightBox(index, items);
      });

    function lightBox(index, items) {
      var options = {
        index: index,
        bgOpacity: 0.85,
        showHideOpacity: true,
        mainClass: "pswp--minimal-dark",
        barsSize: { top: 0, bottom: 0 },
        captionEl: false,
        fullscreenEl: false,
        shareEl: false,
        tapToClose: true,
        tapToToggleControls: false,
      };

      var lightBox = new PhotoSwipe(
        document.getElementById("pswp"),
        window.PhotoSwipeUI_Default,
        items,
        options
      );
      lightBox.init();

      lightBox.listen("close", function () {
        $("#pswp .video-wrapper")
          .find("iframe")
          .each(function () {
            $(this).attr("src", $(this).attr("src"));
          });

        $("#pswp .video-wrapper")
          .find("video")
          .each(function () {
            $(this)[0].pause();
          });
      });
    }
  };

  /**
   * Make product summary be sticky when use product layout 1
   */
  toffedassen.stickyProductSummary = function () {
    if ($.fn.stick_in_parent && toffedassen.$window.width() > 767) {
      var $el = $("div.product .sticky-summary");

      $el.stick_in_parent({
        parent: ".gadgetexpress-single-product-detail",
        offset_top: 30,
      });
    }
  };

  /**
   * Change product quantity
   */
  toffedassen.productThumbnail = function () {
    if (toffedassenData.product.thumb_slider != "1") {
      return;
    }

    var $gallery = $(".woocommerce-product-gallery");
    var $thumbnail = $gallery.find(".flex-control-thumbs");
    $gallery.imagesLoaded(function () {
      setTimeout(function () {
        if ($thumbnail.length < 1) {
          return;
        }
        var columns = $gallery.data("columns");
        var count = $thumbnail.find("li").length;
        if (count > columns) {
          if (toffedassenData.product.thumb_vertical == "1") {
            var vertical = true;

            if (toffedassen.$window.width() <= 480) {
              vertical = false;
            }

            $thumbnail.not(".slick-initialized").slick({
              rtl: toffedassenData.isRTL === "1",
              slidesToShow: columns,
              focusOnSelect: true,
              slidesToScroll: 1,
              vertical: vertical,
              infinite: false,
              prevArrow:
                '<span class="icon-chevron-up slick-prev-arrow"></span>',
              nextArrow:
                '<span class="icon-chevron-down slick-next-arrow"></span>',
              responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 4,
                  },
                },
                {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 3,
                  },
                },
              ],
            });

            $thumbnail.find("li.slick-current").trigger("click");
          } else {
            $thumbnail.not(".slick-initialized").slick({
              rtl: toffedassenData.isRTL === "1",
              slidesToShow: columns,
              focusOnSelect: true,
              slidesToScroll: 1,
              infinite: false,
              prevArrow:
                '<span class="icon-chevron-left slick-prev-arrow"></span>',
              nextArrow:
                '<span class="icon-chevron-right slick-next-arrow"></span>',
            });
          }
        } else {
          $thumbnail.addClass("no-slick");
        }
      }, 100);
    });
  };

  toffedassen.productGalleryCarousel = function () {
    if (toffedassenData.product.gallery_carousel != "1") {
      return;
    }

    var $product = $(".woocommerce div.product"),
      $gallery = $("div.images", $product),
      $sliders = $(".woocommerce-product-gallery__wrapper", $gallery);

    // Show it

    $gallery.imagesLoaded(function () {
      var slidersOptions = {
        rtl: toffedassenData.isRTL === "1",
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        prevArrow: '<span class="icon-arrow-left slick-prev-arrow"></span>',
        nextArrow: '<span class="icon-arrow-right slick-next-arrow"></span>',
      };

      if ($product.hasClass("gadgetexpress-product-layout-5")) {
        var start = $sliders.children().length > 2 ? 1 : 0;
        slidersOptions.centerMode = true;
        slidersOptions.initialSlide = start;
        slidersOptions.centerPadding = "26.8%";
        slidersOptions.appendArrows = $(".slick-arrow-wrapper");
        slidersOptions.responsive = [
          {
            breakpoint: 601,
            settings: {
              centerPadding: "0",
            },
          },
        ];
      }

      if ($product.hasClass("gadgetexpress-product-layout-6")) {
        slidersOptions.prevArrow =
          '<span class="arrow_carrot-left slick-prev-arrow"></span>';
        slidersOptions.nextArrow =
          '<span class="arrow_carrot-right slick-next-arrow"></span>';
        slidersOptions.variableWidth = true;
        slidersOptions.slidesToShow = 2;
        slidersOptions.responsive = [
          {
            breakpoint: 1200,
            settings: {
              variableWidth: false,
            },
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
            },
          },
        ];
      }

      $sliders.not(".slick-initialized").slick(slidersOptions);
    });
  };

  toffedassen.productvariation = function () {
    var $form = $(".variations_form");

    toffedassen.$body.on("tawcvs_initialized", function () {
      $form.unbind("tawcvs_no_matching_variations");
      $form.on("tawcvs_no_matching_variations", function (event, $el) {
        event.preventDefault();

        $form.find(".woocommerce-variation.single_variation").show();
        if (typeof wc_add_to_cart_variation_params !== "undefined") {
          $(".variations_form")
            .find(".single_variation")
            .slideDown(200)
            .html(
              "<p>" +
                wc_add_to_cart_variation_params.i18n_no_matching_variations_text +
                "</p>"
            );
        }
      });
    });

    $(".variations_form td.value").on("change", "select", function () {
      var value = $(this).find("option:selected").text();
      $(this)
        .closest("tr")
        .find("td.label .gadgetexpress-attr-value")
        .html(value);
    });

    $form.find("td.value").each(function () {
      if (!$(this).find(".variation-selector").hasClass("hidden")) {
        $(this).addClass("show-select");
      } else {
        $(this).prev().addClass("show-label");
      }
    });

    if (typeof wc_add_to_cart_variation_params !== "undefined") {
      $form.on(
        "found_variation.wc-variation-form",
        function (event, variation) {
          var $sku = $(".gadgetexpress-single-product-detail")
            .find(".sku_wrapper")
            .find(".sku");

          if (variation.sku) {
            $sku.wc_set_content(variation.sku);
          } else {
            $sku.wc_reset_content();
          }
        }
      );

      $form.on("reset_data", function (event, variation) {
        $(".gadgetexpress-single-product-detail")
          .find(".sku_wrapper")
          .find(".sku")
          .wc_reset_content();
      });
    }
  };

  // Get price js slider
  toffedassen.priceSlider = function () {
    // woocommerce_price_slider_params is required to continue, ensure the object exists
    if (typeof woocommerce_price_slider_params === "undefined") {
      return false;
    }

    if (
      $(".catalog-sidebar").find(".widget_price_filter").length <= 0 &&
      $("#un-shop-topbar").find(".widget_price_filter").length <= 0
    ) {
      return false;
    }

    // Get markup ready for slider
    $("input#min_price, input#max_price").hide();
    $(".price_slider, .price_label").show();

    // Price slider uses jquery ui
    var min_price = $(".price_slider_amount #min_price").data("min"),
      max_price = $(".price_slider_amount #max_price").data("max"),
      current_min_price = parseInt(min_price, 10),
      current_max_price = parseInt(max_price, 10);

    if ($(".price_slider_amount #min_price").val() != "") {
      current_min_price = parseInt(
        $(".price_slider_amount #min_price").val(),
        10
      );
    }
    if ($(".price_slider_amount #max_price").val() != "") {
      current_max_price = parseInt(
        $(".price_slider_amount #max_price").val(),
        10
      );
    }

    $(document.body).bind(
      "price_slider_create price_slider_slide",
      function (event, min, max) {
        if (woocommerce_price_slider_params.currency_pos === "left") {
          $(".price_slider_amount span.from").html(
            woocommerce_price_slider_params.currency_symbol + min
          );
          $(".price_slider_amount span.to").html(
            woocommerce_price_slider_params.currency_symbol + max
          );
        } else if (
          woocommerce_price_slider_params.currency_pos === "left_space"
        ) {
          $(".price_slider_amount span.from").html(
            woocommerce_price_slider_params.currency_symbol + " " + min
          );
          $(".price_slider_amount span.to").html(
            woocommerce_price_slider_params.currency_symbol + " " + max
          );
        } else if (woocommerce_price_slider_params.currency_pos === "right") {
          $(".price_slider_amount span.from").html(
            min + woocommerce_price_slider_params.currency_symbol
          );
          $(".price_slider_amount span.to").html(
            max + woocommerce_price_slider_params.currency_symbol
          );
        } else if (
          woocommerce_price_slider_params.currency_pos === "right_space"
        ) {
          $(".price_slider_amount span.from").html(
            min + " " + woocommerce_price_slider_params.currency_symbol
          );
          $(".price_slider_amount span.to").html(
            max + " " + woocommerce_price_slider_params.currency_symbol
          );
        }

        $(document.body).trigger("price_slider_updated", [min, max]);
      }
    );
    if (typeof $.fn.slider !== "undefined") {
      $(".price_slider").slider({
        range: true,
        animate: true,
        min: min_price,
        max: max_price,
        values: [current_min_price, current_max_price],
        create: function () {
          $(".price_slider_amount #min_price").val(current_min_price);
          $(".price_slider_amount #max_price").val(current_max_price);

          $(document.body).trigger("price_slider_create", [
            current_min_price,
            current_max_price,
          ]);
        },
        slide: function (event, ui) {
          $("input#min_price").val(ui.values[0]);
          $("input#max_price").val(ui.values[1]);

          $(document.body).trigger("price_slider_slide", [
            ui.values[0],
            ui.values[1],
          ]);
        },
        change: function (event, ui) {
          $(document.body).trigger("price_slider_change", [
            ui.values[0],
            ui.values[1],
          ]);
        },
      });
    }
  };

  //related & upsell slider

  toffedassen.singleProductCarousel = function () {
    if (!toffedassen.$body.hasClass("single-product")) {
      return;
    }
    var $upsells = toffedassen.$body.find(".up-sells"),
      $upsellsProduct = $upsells.find("ul.products"),
      upsellsProductColumns = $upsells.data("columns"),
      $related = toffedassen.$body.find(".related.products"),
      $relatedProduct = $related.find("ul.products"),
      relatedProductColumns = $related.data("columns");

    // Product thumnails and featured image slider
    $upsellsProduct.not(".slick-initialized").slick({
      rtl: toffedassenData.isRTL === "1",
      infinite: false,
      slidesToShow: upsellsProductColumns,
      slidesToScroll: 1,
      lazyLoad: "ondemand",
      arrows: false,
      dots: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow:
              parseInt(upsellsProductColumns) > 3
                ? 3
                : parseInt(upsellsProductColumns),
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow:
              parseInt(upsellsProductColumns) > 2
                ? 2
                : parseInt(upsellsProductColumns),
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 481,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });

    $relatedProduct.not(".slick-initialized").slick({
      rtl: toffedassenData.isRTL === "1",
      infinite: false,
      slidesToShow: relatedProductColumns,
      slidesToScroll: 1,
      arrows: false,
      lazyLoad: "ondemand",
      dots: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow:
              parseInt(relatedProductColumns) > 3
                ? 3
                : parseInt(relatedProductColumns),
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow:
              parseInt(relatedProductColumns) > 2
                ? 2
                : parseInt(relatedProductColumns),
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 481,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });
  };

  toffedassen.loginTab = function () {
    var $tabs = $(".gadgetexpress-tabs"),
      $el = $tabs.find(".tabs-nav a"),
      $panels = $tabs.find(".tabs-panel");
    $el.on("click", function (e) {
      e.preventDefault();

      var $tab = $(this),
        index = $tab.parent().index();

      if ($tab.hasClass("active")) {
        return;
      }

      $tabs.find(".tabs-nav a").removeClass("active");
      $tab.addClass("active");
      $panels.removeClass("active");
      $panels.filter(":eq(" + index + ")").addClass("active");
    });
  };

  /**
   * Toggle product quick view
   */
  toffedassen.productQuickView = function () {
    toffedassen.$body.on(
      "click",
      ".gadgetexpress-product-quick-view",
      function (e) {
        e.preventDefault();
        var $a = $(this);

        var url = $a.attr("href"),
          $modal = $("#quick-view-modal"),
          $product = $modal.find(".product"),
          $product_sumary = $modal.find(".product-summary"),
          $product_images = $modal.find(".product-images-wrapper"),
          $button = $modal.find(".modal-header .close-modal").first().clone();

        $product.removeClass().addClass("invisible");
        $product_sumary.html("");
        $product_images.html("");
        $modal.addClass("loading");
        toffedassen.openModal($modal);

        $.get(
          url,
          function (response) {
            var $html = $(response),
              $response_summary = $html.find("#content").find(".entry-summary"),
              $response_images = $html
                .find("#content")
                .find(".product-images-wrapper"),
              $product_thumbnails = $response_images.find(
                "#product-thumbnails"
              ),
              $variations = $response_summary.find(".variations_form"),
              productClasses = $html.find(".product").attr("class");

            // Remove unused elements
            $product_thumbnails.remove();
            $product.addClass(productClasses);
            $product_sumary.html($response_summary);
            $response_images
              .find(".woocommerce-product-gallery")
              .removeAttr("style");
            $product_images.html($response_images);

            if ($product.find(".close-modal").length < 1) {
              $product.show().prepend($button);
            }

            var $carousel = $product_images.find(
              ".woocommerce-product-gallery__wrapper"
            );

            // Force height for images
            $carousel.find("img").css("height", $product.outerHeight());

            $modal.removeClass("loading");
            $product.removeClass("invisible");

            $carousel.not(".slick-initialized").slick({
              rtl: toffedassenData.isRTL === "1",
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: false,
              prevArrow:
                '<span class="icon-chevron-left slick-prev-arrow"></span>',
              nextArrow:
                '<span class="icon-chevron-right slick-next-arrow"></span>',
            });

            $carousel.imagesLoaded(function () {
              //Force height for images
              $carousel.addClass("loaded");
            });

            $carousel
              .find(".woocommerce-product-gallery__image")
              .on("click", "a", function (e) {
                e.preventDefault();
              });

            if (typeof wc_add_to_cart_variation_params !== "undefined") {
              $variations.wc_variation_form();
              $variations.find(".variations select").change();
            }

            if (typeof $.fn.tawcvs_variation_swatches_form !== "undefined") {
              $variations.tawcvs_variation_swatches_form();
            }

            toffedassen.$body.on("tawcvs_initialized", function () {
              $(".variations_form").unbind("tawcvs_no_matching_variations");
              $(".variations_form").on(
                "tawcvs_no_matching_variations",
                function (event, $el) {
                  event.preventDefault();
                  $el.addClass("selected");

                  $(".variations_form")
                    .find(".woocommerce-variation.single_variation")
                    .show();
                  if (typeof wc_add_to_cart_variation_params !== "undefined") {
                    $(".variations_form")
                      .find(".single_variation")
                      .slideDown(200)
                      .html(
                        "<p>" +
                          wc_add_to_cart_variation_params.i18n_no_matching_variations_text +
                          "</p>"
                      );
                  }
                }
              );
            });

            toffedassen.productvariation();
          },
          "html"
        );
      }
    );

    $("#quick-view-modal").on("click", function (e) {
      var target = e.target;
      if ($(target).closest("div.product").length <= 0) {
        toffedassen.closeModal();
      }
    });
  };

  // add wishlist
  toffedassen.addWishlist = function () {
    $(".yith-wcwl-add-to-wishlist .yith-wcwl-add-button").on(
      "click",
      "a",
      function () {
        $(this).addClass("loading");
      }
    );

    toffedassen.$body.on("added_to_wishlist", function () {
      $(".yith-wcwl-add-to-wishlist .yith-wcwl-add-button a").removeClass(
        "loading"
      );
    });
  };

  toffedassen.showAddedToCartNotice = function () {
    $(document.body).on(
      "added_to_cart",
      function (event, fragments, cart_hash, $thisbutton) {
        var product_title =
            $thisbutton.attr("data-title") +
            " " +
            toffedassenData.l10n.notice_text,
          $message = "";

        if (toffedassenData.add_to_cart_action === "notice") {
          toffedassen.addedToCartNotice(
            $message,
            product_title,
            false,
            "success"
          );
        } else {
          $("#icon-cart-contents").trigger("click");
        }
      }
    );
  };

  toffedassen.addedToCartNotice = function (
    $message,
    $content,
    single,
    className
  ) {
    if (toffedassenData.l10n.added_to_cart_notice != "1" || !$.fn.notify) {
      return;
    }

    $message +=
      '<a href="' +
      toffedassenData.l10n.cart_link +
      '" class="btn-button">' +
      toffedassenData.l10n.cart_text +
      "</a>";

    if (single) {
      $message = '<div class="message-box">' + $message + "</div>";
    }

    $.notify.addStyle("gadget", {
      html:
        '<div><i class="icon-checkmark-circle message-icon"></i><span data-notify-text/>' +
        $message +
        '<span class="close icon-cross2"></span> </div>',
    });
    $.notify($content, {
      autoHideDelay: toffedassenData.l10n.cart_notice_auto_hide,
      className: className,
      style: "gadget",
      showAnimation: "fadeIn",
      hideAnimation: "fadeOut",
    });
  };

  // Add to cart ajax
  toffedassen.addToCartAjax = function () {
    if (toffedassenData.add_to_cart_ajax == "0") {
      return;
    }

    var found = false;
    toffedassen.$body.on("click", ".single_add_to_cart_button", function (e) {
      var $el = $(this),
        $cartForm = $el.closest("form.cart"),
        $productWrapper = $el.closest("div.product");

      if ($productWrapper.hasClass("product-type-external")) {
        return;
      }

      if ($cartForm.length > 0) {
        e.preventDefault();
      } else {
        return;
      }

      if ($el.hasClass("disabled")) {
        return;
      }

      $el.addClass("loading");
      if (found) {
        return;
      }
      found = true;

      var formdata = $cartForm.serializeArray(),
        currentURL = window.location.href;

      if ($el.val() != "") {
        formdata.push({ name: $el.attr("name"), value: $el.val() });
      }
      $.ajax({
        url: window.location.href,
        method: "post",
        data: formdata,
        error: function () {
          window.location = currentURL;
        },
        success: function (response) {
          if (!response) {
            window.location = currentURL;
          }

          if (typeof wc_add_to_cart_params !== "undefined") {
            if (wc_add_to_cart_params.cart_redirect_after_add === "yes") {
              window.location = wc_add_to_cart_params.cart_url;
              return;
            }
          }

          $(document.body).trigger("updated_wc_div");

          var $message = "",
            className = "success";
          if ($(response).find(".woocommerce-message").length > 0) {
            $message = $(response).find(".woocommerce-message").html();
          }

          if ($(response).find(".woocommerce-error").length > 0) {
            $message = $(response).find(".woocommerce-error").html();
            className = "error";
          }

          if ($(response).find(".woocommerce-info").length > 0) {
            $message = $(response).find(".woocommerce-info").html();
          }

          $el.removeClass("loading");

          if (toffedassenData.add_to_cart_action === "notice") {
            if ($message) {
              toffedassen.addedToCartNotice($message, " ", true, className);
            }
          } else {
            $(document.body).on("wc_fragments_refreshed", function () {
              $("#icon-cart-contents").trigger("click");
            });
          }

          found = false;
        },
      });
    });
  };

  // Product Attribute
  toffedassen.productAttribute = function () {
    toffedassen.$body.on("click", ".un-swatch-variation-image", function (e) {
      e.preventDefault();
      $(this).siblings(".un-swatch-variation-image").removeClass("selected");
      $(this).addClass("selected");
      var imgSrc = $(this).data("src"),
        imgSrcSet = $(this).data("src-set"),
        $mainImages = $(this)
          .parents("li.product")
          .find(".un-product-thumbnail > a"),
        $image = $mainImages.find("img").first(),
        imgWidth = $image.first().width(),
        imgHeight = $image.first().height();

      $mainImages.addClass("image-loading");
      $mainImages.css({
        width: imgWidth,
        height: imgHeight,
        display: "block",
      });

      $image.attr("src", imgSrc);

      if (imgSrcSet) {
        $image.attr("srcset", imgSrcSet);
      }

      $image.load(function () {
        $mainImages.removeClass("image-loading");
        $mainImages.removeAttr("style");
      });
    });
  };

  /**
   * Portfolio Masonry
   */
  toffedassen.portfolioMasonry = function () {
    if (!toffedassen.$body.hasClass("portfolio-masonry")) {
      return;
    }

    toffedassen.$body.imagesLoaded(function () {
      toffedassen.$body.find(".list-portfolio").isotope({
        itemSelector: ".portfolio-wrapper",
        layoutMode: "masonry",
      });
    });
  };

  /**
   * Portfolio Ajax
   */
  toffedassen.portfolioLoadingAjax = function () {
    if (!toffedassen.$body.hasClass("gadgetexpress-portfolio-page")) {
      return;
    }

    if (toffedassen.$body.hasClass("portfolio-carousel")) {
      return;
    }

    $(".gadgetexpress-portfolio-page")
      .find(".numeric-navigation")
      .on("click", ".page-numbers.next", function (e) {
        e.preventDefault();

        if ($(this).data("requestRunning")) {
          return;
        }

        $(this).data("requestRunning", true);

        $(this).addClass("loading");

        var $project = $(this)
            .parents(".numeric-navigation")
            .prev(".list-portfolio"),
          $pagination = $(this).parents(".numeric-navigation");

        $.get($(this).attr("href"), function (response) {
          var content = $(response).find(".list-portfolio").html(),
            $pagination_html = $(response).find(".numeric-navigation").html();
          var $content = $(content);

          for (var index = 0; index < $content.length; index++) {
            $($content[index]).css("animation-delay", index * 100 + 100 + "ms");
          }

          $content.addClass("toffedassenFadeIn toffedassenAnimation");

          $pagination.html($pagination_html);

          if (toffedassen.$body.hasClass("portfolio-masonry")) {
            $content.imagesLoaded(function () {
              $project.append($content).isotope("insert", $content);

              $pagination.find(".page-numbers.next").removeClass("loading");
              $pagination
                .find(".page-numbers.next")
                .data("requestRunning", false);
            });
          } else {
            $project.append($content);

            $pagination.find(".page-numbers.next").removeClass("loading");
            $pagination
              .find(".page-numbers.next")
              .data("requestRunning", false);
          }

          if (!$pagination.find(".page-numbers").hasClass("next")) {
            $pagination.addClass("loaded");
          }
        });
      });
  };

  /**
   * Portfolio Carousel
   */
  toffedassen.portfolioCarousel = function () {
    if (!toffedassen.$body.hasClass("gadgetexpress-portfolio-page")) {
      return;
    }

    if (!toffedassen.$body.hasClass("portfolio-carousel")) {
      return;
    }

    var $container = $(".swiper-container");

    if (!$container.length) {
      return;
    }

    var num = $(".list-portfolio .swiper-slide").length;

    var options = {
      loop: false,
      speed: 500,
      initialSlide: num > 0 ? 1 : 0,
      centeredSlides: true,
      spaceBetween: 100,
      scrollbar: {
        el: ".swiper-scrollbar",
        hide: false,
        draggable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        init: function () {
          $container.css("opacity", 1);
        },
      },
      breakpoints: {
        // when window width is <= 480px
        767: {
          spaceBetween: 30,
        },
        // when window width is <= 1199
        1199: {
          spaceBetween: 60,
        },
      },
    };

    var carousel = new Swiper($container, options);

    var xhr;

    carousel.on("reachEnd", function () {
      var $nav = $(".portfolio-carousel .paging-navigation");

      if (!$nav.length) {
        return;
      }

      if (xhr) {
        return;
      }

      var loadingHolder = document.createElement("div");

      $(loadingHolder)
        .addClass("swiper-slide loading-placeholder")
        .css({ height: carousel.height - 121 })
        .append(
          '<span class="spinner icon_loading gadgetexpress-spin su-icon"></span>'
        );

      carousel.appendSlide(loadingHolder);
      carousel.update();

      xhr = $.get($nav.find("a").attr("href"), function (response) {
        var $content = $(".list-portfolio", response),
          $portfolio = $content.children(),
          $newNav = $(".portfolio-carousel .paging-navigation", $content);

        if ($newNav.length) {
          $nav.find("a").replaceWith($("a", $newNav));
        } else {
          $nav.fadeOut(function () {
            $nav.remove();
          });
        }

        $(loadingHolder).remove();
        $portfolio.css({ opacity: 0 });

        carousel.appendSlide($portfolio.addClass("swiper-slide").get());
        carousel.update();

        $portfolio.animate({ opacity: 1 });
        xhr = false;

        $(document.body).trigger("gadgetexpress_portfolio_loaded", [
          $portfolio,
          true,
        ]);
      });
    });
  };

  toffedassen.filterScroll = function () {
    var $sidebar = $(".catalog-sidebar"),
      $categories = $(
        ".gadgetexpress_widget_product_categories > ul",
        $sidebar
      ),
      $filter = $(".gadgetexpress_attributes_filter > ul", $sidebar);

    toffedassen.filterElement($categories);
    toffedassen.filterElement($filter);
  };

  toffedassen.filterElement = function ($element) {
    var h = $element.outerHeight(true),
      dataHeight = $element.data("height");

    if (h > dataHeight) {
      $element.addClass("scroll-enable");
      $element.css("height", dataHeight);
    }
  };

  /**
   * Init tooltip
   */
  toffedassen.tooltip = function () {
    $("[data-rel=tooltip]").tooltip({ offsetTop: -15 });
  };

  /**
   * Ajax login before refresh page
   */
  toffedassen.loginModalAuthenticate = function () {
    $("#login-modal").on("submit", ".woocommerce-form-login", function (e) {
      var username = $("input[name=username]", this).val(),
        password = $("input[name=password]", this).val(),
        remember = $("input[name=rememberme]", this).is(":checked"),
        $button = $("[type=submit]", this),
        $form = $(this),
        $box = $form.next(".woocommerce-error");

      if (!username || !password) {
        return true;
      }

      e.preventDefault();
      $button.addClass("loading");

      if ($box.length) {
        $box.fadeOut();
      }

      $.post(
        toffedassenData.ajax_url,
        {
          action: "gadgetexpress_login_authenticate",
          creds: {
            user_login: username,
            user_password: password,
            remember: remember,
          },
        },
        function (response) {
          if (!response.success) {
            if (!$box.length) {
              $box = $(
                '<div class="woocommerce-error gadgetexpress-message-box danger"/>'
              );

              $box
                .append('<div class="box-content"></div>')
                .append(
                  '<a class="close" href="#"><span aria-hidden="true" class="icon-cross2"></span></a>'
                );

              $box.hide().insertAfter($form);
            }

            $box.find(".box-content").html(response.data);
            $box.fadeIn();
            $button.removeClass("loading");
          } else {
            window.location.reload();
          }
        }
      );
    });
  };

  /**
   * Close message box
   */
  $(document.body).on(
    "click",
    ".gadgetexpress-message-box .close",
    function (e) {
      e.preventDefault();

      $(this).closest(".gadgetexpress-message-box").fadeOut("slow");
    }
  );

  /**
   * Document ready
   */
  $(function () {
    toffedassen.init();
  });
})(jQuery);

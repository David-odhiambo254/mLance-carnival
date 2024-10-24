(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

    // ============== Header Hide Click On Body Js Start ========
    $('.header-button').on('click', function () {
      $('.body-overlay').toggleClass('show');
      $('.header').toggleClass('add-bg');
    });

    $('.body-overlay').on('click', function () {
      $('.header-button').trigger('click')
      $(this).removeClass('show');
      $('.header').removeClass('add-bg');
    });
    // =============== Header Hide Click On Body Js End =========

    // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function () {
      $('body').toggleClass('scroll-hide-sm')
    });
    $('.body-overlay').on('click', function () {
      $('body').removeClass('scroll-hide-sm')
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css('background', function () {
      var bg = ('url(' + $(this).data("background-image") + ')');
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      let FileName = window.location.pathname.split("/").reverse()[0];

      selector.find("li").each(function () {
        let anchor = $(this).find("a");
        if ($(anchor).attr("href") == FileName) {
          $(this).addClass("active");
        }
      });
      // if any li has active element add class
      selector.children("li").each(function () {
        if ($(this).find(".active").length) {
          $(this).addClass("active");
        }
      });
      // if no file name return
      if ("" == FileName) {
        selector.find("li").eq(0).addClass("active");
      }
    }
    if ($('ul').length) {
      dynamicActiveMenuClass($('ul'));
    }
    // ========================== add active class to ul>li top Active current page Js End =====================


    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {
      var input = $(this).siblings('input');
      $(this).toggleClass("fa fa-eye");
      if (input.attr("type") == "password") {
        $(input).attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

    // ========================= Banner Js Start ===============

    $('.banner-slider').slick({
      dots: false,
      infinite: true,
      speed: 1500,
      fade: true,
      cssEase: 'linear',
      autoplay: true,
      slidesToShow: 1,
      autoplaySpeed: 2000,
      slidesToScroll: 1,
      pauseOnHover: false,
      arrows: false,
    });
    // ========================= Banner Js End ===============

    // ========================= Brand Slider Js Start ================
    $('.brand__slider').slick({
      slidesToShow: 6,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 1000,
      pauseOnHover: true,
      speed: 2000,
      dots: false,
      arrows: false,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
      responsive: [{
        breakpoint: 1199,
        settings: {
          slidesToShow: 5,
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 4
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 400,
        settings: {
          slidesToShow: 2
        }
      }
      ]
    });
    // ========================= Brand Slider Js End ===================


    // ========================= Brand Slider Js Start ================
    $('.brand__slider-two').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 1000,
      pauseOnHover: true,
      speed: 2000,
      dots: false,
      arrows: false,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
      responsive: [{
        breakpoint: 1199,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 3
        }
      }
      ]
    });
    // ========================= Brand Slider Js End ===================
    // ============ Proposition Section Thumb Lightbox Start ===========
    $('.video-play-btn').magnificPopup({
      type: 'iframe',
    });
    // ============ Proposition Section Thumb Lightbox End =============

    // ================ Popular Services Slider Start ==================
    $('.service-slider').slick({
      slidesToShow: 5,
      slidesToScroll: 5,
      infinite: true,
      arrows: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-angle-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-angle-right"></i></button>',
      responsive: [
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 424,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
          }
        }
      ]
    });
    // ================ Popular Services Slider End ====================

    // =============== Testimonial Slider Js Start ====================
    $('.testimonial-slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 2000,
      speed: 1500,
      dots: true,
      pauseOnHover: true,
      arrows: false,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
      responsive: [{
        breakpoint: 1199,
        settings: {
          arrows: false,
          slidesToShow: 2,
          dots: true,
        }
      },
      {
        breakpoint: 991,
        settings: {
          arrows: false,
          slidesToShow: 2
        }
      },
      {
        breakpoint: 767,
        settings: {
          arrows: false,
          slidesToShow: 1
        }
      }
      ]
    });

    // ============== Testimonial Slider Js End ========================


    $('.category-slider').slick({
      slidesToShow: 4,
      slidesToScroll: 4,
      autoplay: false,
      autoplaySpeed: 2000,
      speed: 1500,
      dots: false,
      pauseOnHover: true,
      arrows: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-angle-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-angle-right"></i></button>',
      responsive: [{
        breakpoint: 1199,
        settings: {
          arrows: false,
          slidesToShow: 4,
          dots: true,
        }
      },
      {
        breakpoint: 992,
        settings: {
          arrows: false,
          slidesToShow: 3,
          dots: true
        }
      },
      {
        breakpoint: 767,
        settings: {
          arrows: false,
          slidesToShow: 2,
          dots: true,
        }
      },
      {
        breakpoint: 460,
        settings: {
          arrows: false,
          slidesToShow: 1,
          dots: true,
        }
      }
      ]
    });


    // ================ Gig Thumb Slider Start =========================
    $('.gig-card__thumbs').slick({
      infinite: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-angle-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fas fa-angle-right"></i></button>',
    });
    // ================ Gig Thumb Slider End ===========================

    // ================ Gig Details Slider Start ===========================
    var gigDetailsSlider2 = new Swiper('.gig-details__slider2', {
      loop: true,
      freeMode: true,
      watchSlidesProgress: true,
      // Responsive breakpoints
      breakpoints: {
        // when window width is >= 320px
        320: {
          slidesPerView: 2,
          spaceBetween: 10,
        },
        // when window width is >= 480px
        424: {
          slidesPerView: 3,
          spaceBetween: 10
        },
        // when window width is >= 768px
        768: {
          slidesPerView: 4,
          spaceBetween: 10
        },
        // when window width is >= 1200px
        1200: {
          slidesPerView: 5,
          spaceBetween: 10
        }
      }
    });

    var gigDetailsSlider1 = new Swiper('.gig-details__slider1', {
      loop: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      thumbs: {
        swiper: gigDetailsSlider2,
      },
    });
    // ================ Gig Details Slider End ===========================

    // ================ Gig Details Discover Slider Start ==================
    var gigDiscoverSlider = new Swiper('.gig-details__discover-slider', {
      slidesPerView: 1,
      loop: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        // when window width is >= 768px
        768: {
          slidesPerView: 2,
          spaceBetween: 20
        },
      }
    })
    // ================ Gig Details Discover Slider End ====================

    // ================ Gig Details Content Collapse JS Start ==================
    $('.gig-details__collapse-btn').on('click', function () {
      $('.gig-details__content-wrapper').toggleClass('show');

      if ($('.gig-details__content-wrapper').hasClass('show')) {

        $(this).html(`
          <i class="fas fa-minus"></i>
          <span>show less</span>
        `);

      } else {

        $(this).html(`
          <i class="fas fa-plus"></i>
          <span>show more</span>
        `);
      }

    });
    // ================ Gig Details Content Collapse JS End ====================


    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").on("click", function () {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
          .parent()
          .hasClass("active")
      ) {
        $(".has-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });
    // Sidebar Dropdown Menu End
    // Sidebar Icon & Overlay js 
    $(".dashboard-body__bar-icon").on("click", function () {
      $(".sidebar-menu").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });
    // Sidebar Icon & Overlay js 
    // ===================== Sidebar Menu Js End =================

    // ==================== Dashboard User Profile Dropdown Start ==================
    $('.user-info__button').on('click', function () {
      $('.user-info-dropdown').toggleClass('show');
    });
    $('.user-info__button').attr('tabindex', -1).focus();

    $('.user-info__button').on('focusout', function () {
      $('.user-info-dropdown').removeClass('show');
    });
    // ==================== Dashboard User Profile Dropdown End ==================

    // ========================= Odometer Counter Up Js End ==========
    $(".statistics-counter-item").each(function () {
      $(this).isInViewport(function (status) {
        if (status === "entered") {
          for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
            var el = document.querySelectorAll('.odometer')[i];
            el.innerHTML = el.getAttribute("data-odometer-final");
          }
        }
      });
    });
    // ========================= Odometer Up Counter Js End =====================
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $('.preloader').fadeOut();
  })
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 450) {
      $('.header').addClass('fixed-header');
    } else {
      $('.header').removeClass('fixed-header');
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $('.scroll-top');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({
      scrollTop: 0
    }, '300');
  });
  //========================= Scroll To Top Icon Js End ======================

  //Start-User--dashboard--js//
  $(document).ready(function () {
    // Sidebar Icon & Overlay js 
    $(".menu-bar-icon").on("click", function () {
      $(".sidebar-menu").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });

    $('.user-profile-icon').on("click", function () {
      $('.user-info-dropdown').toggleClass('d-none');
    });

  });


  // filter js

  $(".filter-task").on("click", (function () {
    $(".sidebar-overlay").addClass("active show");
    $(".filter-sidebar").addClass("active")
  }));

  $(".side-sidebar-close-btn, .sidebar-overlay").on("click", (function () {
    $(".sidebar-overlay").removeClass("active show");
    $(".filter-sidebar").removeClass("active")
  }));




  //copy-for-share//
  $('#copyBtn').click(async function () {
    var link = $(this).data('link');
    await navigator.clipboard.writeText(link);
    $(this).parent().find('i.fa-copy').addClass('copied');
    setTimeout(() => {
      $(this).parent().find('i.fa-copy').removeClass('copied');
    }, 2000);
  });
  $('#copyBtn').on("click", function (e) {
    e.stopPropagation(); // Prevent the click event from propagating to parent elements
  });


  $(document).on('change', ".image-upload", function () {
    proPicURL(this);
  });

  function proPicURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var preview = $(document).find(input).closest('.box').find('.mainFile');
        $(document).find(preview).css('background-image', 'url(' + e.target.result + ')');
        $(document).find(input).closest('.box').find('.mainFile').addClass('js--no-default');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  //End-User--dashboard--js//

})(jQuery);
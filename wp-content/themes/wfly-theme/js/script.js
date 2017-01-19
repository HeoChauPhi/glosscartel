(function($) {
  function headerFixed() {
    var headertop = $('#header');
    var offset = headertop.position();
    $(window).scroll(function () {
      if ($(this).scrollTop() > offset.top) {
        headertop.addClass('fixed');
      } else {
        headertop.removeClass('fixed');
      }
    });
  }

  function navigation() {
    $('.menu-icon').click(function() {
      $('.main-nav').slideToggle('fast active');
    });
  }

  function featureSlider() {
    $('.feature .block-slider').slick({
      arrows: false,
      dots: false,
      infinite: true,
      speed: 500,
      fade: true,
      cssEase: 'linear',
      adaptiveHeight: true,
      autoplay: true,
      autoplaySpeed: 3000,
    });
  }

  function shadowFeature() {
    var height_text = $('.box-book-now .text_big').outerHeight(true);
    $('.box-feature').parent().next('.box-book-now').css({'margin-top': - height_text + 'px'});
  }

  function verticalSlick() {
    $('.slickvertical > .block-content').slick({
      vertical: true,
      slidesToScroll: 1,
      autoplay: true,
      speed: 300,
      infinite: true,
      slidesToShow: 2
    });
  }

  function colorbox() {
    if($( window ).width() < 480) {
      $(".popup-login a, .box-cta-book a").colorbox({inline:true, width:"100%"});
    } else {
      $(".popup-login a, .box-cta-book a").colorbox({inline:true, width:"590px"});
    }
  }

  function popuplogin() {
    $('.popup-login').click(function() {
      $('#popup-regiter').addClass('active');
    });
    $('button#cboxClose').click(function() {
      $('#popup-regiter').removeClass('active');
    });
  }

  function loginForm() {
    var form_group = $('.login-form .form-group');
    form_group.each(function() {
      if($(this).html().replace(/\s|&nbsp;/g, '').length == 0) {
        $(this).remove();
      }

      var form_class = $(this).find('input').attr('name');
      $(this).addClass(form_class);
    })
  }

  function matchHeight() {
    //$(this).find('.post-cars .car-title').matchHeight();
  }

 function footerjs() {
    var height_window = $(window).height();
    var height_body = $('html > body').height();
    var height_header = $('.header.header-wrapper').height();
    if (height_window > height_body) {
      $('.page-content-wrappre').css('min-height', height_window - height_header - 54 + 'px');
    }
  }

  function siteMessage() {
    var site_message = $('#site-message');
    site_message.find('.icon-close').click(function() {
      $(this).parent().fadeOut( "slow" );
    });
  }

  function scrolldown() {
    var adminbar_height = $('#wpadminbar').outerHeight(true);

    $('.scroll-down').click(function() {
      var this_parent = $(this).parent();
      var next_box = this_parent.next();

      if (next_box.length) {
        $('html, body').animate({
          scrollTop: next_box.offset().top - adminbar_height
        }, 1000);
        return false;
       }
    });
  }

  function backToTop() {
    if ($('#back-to-top').length) {
      var scrollTrigger = 100, // px
      backToTop = function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > scrollTrigger) {
          $('#back-to-top').addClass('show');
        } else {
          $('#back-to-top').removeClass('show');
        }
      };
      backToTop();
      $(window).on('scroll', function () {
        backToTop();
      });
      $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
          scrollTop: 0
        }, 700);
      });
    }
  }

  function accordionElement($classname) {
    $($classname).accordion({
      heightStyle: "content"
    });
  }

  $(document).ready(function() {
    // Call to function
    siteMessage();
    headerFixed();
    navigation();
    featureSlider();
    shadowFeature();
    verticalSlick();
    // popuplogin();
    // colorbox();
    footerjs();
    loginForm();
    matchHeight();
    scrolldown();
    backToTop();
    //accordionElement('.box-faq-list');


    var hunghtfeature = $('.box-feature').height();
    var hunghtfeature1 = $('.box-form-login').outerHeight();
    $('.form-schedule-feature').css('top', hunghtfeature - 258 + 'px');
    if( hunghtfeature1 > 530 ) {
      $('.box-feature__iamge').css('height', 825 + 'px');
    }
  });

  $(window).load(function() {
    // Call to function
  });

  $(window).resize(function() {
    // Call to function
    matchHeight();
    footerjs();
  });
})(jQuery);

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
    $('.box-feature').slick({
      arrows: false,
      dots: false,
      infinite: true,
      speed: 500,
      fade: true,
      cssEase: 'linear',
      adaptiveHeight: true,
      autoplay: false,
      autoplaySpeed: 3000,
    });
  }

  function shadowFeature() {
    var height_text = $('.box-book-now').outerHeight(true);
    $('.box-feature').parent().next('.box-book-now').css({'margin-top': - height_text + 'px'});
  }

  function colorbox() {
    /*if($( window ).width() < 480) {
      $(".popup-login a, .box-cta-book a").colorbox({inline:true, width:"100%"});
    } else {
      $(".popup-login a, .box-cta-book a").colorbox({inline:true, width:"590px"});
    }*/
    $('#site-message a').colorbox({inline:true});

    if( !!$.cookie("subs_submited") ){
      $('.site-popup .site-popup-text, .site-popup .popup-form').remove();
      $('.site-popup .site-popup-submited').show();
      $('#site-message a').trigger("click");
    }

    if( !!$.cookie("subs_false") ){
      $('.site-popup .site-popup-submited').remove();
      $('#site-message a').trigger("click");
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
    $('.box-product-sale__list').each(function() {
      $(this).find('.box-product-sale_item').matchHeight();
    });
    $('.box-dry__list').each(function() {
      $(this).find('.box-dry__item').matchHeight();
    });
  }

  function verticalSlick() {
    $('.box-product-sale__list').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      dots: false,
      infinite: true,
      speed: 500,
      cssEase: 'linear',
      adaptiveHeight: true,
      autoplay: false,
      autoplaySpeed: 3000,
    });

    $('.box-dry__list').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      dots: false,
      infinite: true,
      speed: 500,
      cssEase: 'linear',
      adaptiveHeight: true,
      autoplay: false,
      autoplaySpeed: 3000,
    });
  }

 function footerjs() {
    var adminbar_height = $('#wpadminbar').outerHeight(true);
    var height_window = $(window).outerHeight(true);
    var height_html = $('html').outerHeight(true);
    var footer = $('.footer.footer-wrapper');

    if (adminbar_height == null) {
      var adminbar_height = 0;
    }

    if (height_window >= height_html) {
      footer.addClass('footer-fix');
      $('.page-wrapper').css({'min-height': height_window - adminbar_height, 'padding-bottom': footer.height()});
      //$('.main-content').css({'margin-bottom': footer.height()});
    } else if (height_window < height_html) {
      footer.removeClass('footer-fix');
      $('.page-wrapper').css({'min-height': 'inherit', 'padding-bottom': 0});
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

  function accordionElement() {
    $('.box-faq-list').each(function(){
      var parent_element = $(this);
      var accordion_header = $(this).find('.box-faq-question');
      var accordion_content = $(this).find('.box-faq-answer');
      accordion_content.each(function() {
        var accordion_content_height = $(this).height();
        $(this).css({'margin-top': - (accordion_content_height + 32)})
      });

      accordion_header.click(function(){
        var header_data = $(this).attr('data-question');
        var content_get = $(this).parent().find('.box-faq-answer[data-answer-for*="'+header_data+'"]');

        $('.box-faq-list').find('.box-faq-answer').removeClass('content-show');
        content_get.toggleClass('content-show');
      });
    });

    $('.sidebar-faq-list').each(function(){
      var adminbar_height = $('#wpadminbar').outerHeight(true);
      var data_accordion = $(this).parent().attr('data-accordion');
      var accordion_header = $(this).find('.sidebar-faq-question');

      accordion_header.click(function(){
        var header_data = $(this).attr('data-question');
        var header_get = $('.box-faq').find('.' + data_accordion + ' .box-faq-question[data-question*="'+header_data+'"]');
        console.log(header_get);
        if (header_get.length) {
          $('html, body').animate({
            scrollTop: header_get.offset().top - adminbar_height
          }, 200);
        }
      });
    });
  }

  function OtherArea() {
    if ($(this).val() == "Other") {
      $('.block-colorbox').show();
    }

    $('.block-colorbox .box-form-otherarea .close').click(function() {
      $('.form-schedule-feature .client_area').prop('selectedIndex',0);
      $('.form-schedule-feature .client_area').select2();
      $('.block-colorbox').hide();
      return false;
    });
  }

  function InterestLink() {
    $('.block-colorbox').show();

    $('.block-colorbox .box-form-otherarea .close').click(function() {
      $('.form-schedule-feature .client_area').prop('selectedIndex',0);
      $('.form-schedule-feature .client_area').select2();
      $('.block-colorbox').hide();
      return false;
    });

    return false;
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
    //colorbox();
    footerjs();
    loginForm();
    matchHeight();
    scrolldown();
    backToTop();
    accordionElement();


    var hunghtfeature = $('.box-feature').height();
    var hunghtfeature1 = $('.box-form-login').outerHeight();
    $('.form-schedule-feature').css('top', hunghtfeature - 258 + 'px');
    if( hunghtfeature1 > 530 ) {
      $('.box-feature__iamge').css('height', 825 + 'px');
    }

    $('.form-schedule-feature .client_area').on('change', OtherArea);
    $('#site-message a').on('click', InterestLink);
    $('.asc-box-client-choose .client-choose-look .group-content .file-upload #confirm_image').on('change', function(){
      var file_url = $(this).val();
      $(this).next('.confirm_image_value').val(file_url);
    });
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

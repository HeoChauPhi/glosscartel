/*jslint browser: true*/
/*global $, jQuery, Modernizr, enquire, audiojs*/

(function($) {
  var chosenSelect = function() {
    $('.client-date').val('');
    $('.datetime-chose').html('<span>Find A Time</span>');
  }

  var chosenDatepicker = function () {
    var date_value = $(this).val();
    var selected = $('.client-service').find('option:selected');
    var select_id = selected.data('id');

    $.ajax({
      type : "post",
      dataType : "json",
      url : ascAjax.ajaxurl,
      data : {action: "datetime", datetime: date_value, app_id: select_id },
      beforeSend: function() {
        $('.datetime-chose').html('');
        $('.datetime-chose').before('<div class="ajax-load-icon">Loading Items</div>');
      },
      success: function(response) {
        $('.ajax-load-icon').remove();
        $('.datetime-chose').html(response.markup);
        $('.select-two').select2();
      },
      error: function(response) {

      }
    });

  };

  function setCookie(key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value +';path=/'+ ';expires=' + expires.toUTCString();
  }

  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
  }

  var removeCookie = function () {
    if( !!$.cookie("Client[Area]") ){
      var ClientArea    = $.cookie("Client[Area]").length;
    }
    if( !!$.cookie("Client[Service]") ){
      var ClientService = $.cookie("Client[Service]").length;
    }
    if( !!$.cookie("Client[Date]") ){
      var ClientDate    = $.cookie("Client[Date]").length;
    }
    if( !!$.cookie("Client[Time]") ){
      var ClientTime    = $.cookie("Client[Time]").length;
    }

    if( !!$.cookie("confirm[Catimg]") ){
      var confirmCatimg   = $.cookie("confirm[Catimg]").length;
    }
    if( !!$.cookie("confirm[Category]") ){
      var confirmCategory = $.cookie("confirm[Category]").length;
    }
    if( !!$.cookie("confirm[Name]") ){
      var confirmName     = $.cookie("confirm[Name]").length;
    }
    if( !!$.cookie("confirm[Price]") ){
      var confirmPrice    = $.cookie("confirm[Price]").length;
    }
    if( !!$.cookie("confirm[Image]") ){
      var confirmImage    = $.cookie("confirm[Image]").length;
    }

    if( !!$.cookie("signin[email]") ){
      var signinemail        = $.cookie("signin[email]");
      var signinemail_length = $.cookie("signin[email]").length;
    }
    if( !!$.cookie("signin[emailid]") ){
      var signinemailid        = $.cookie("signin[emailid]");
      var signinemailid_length = $.cookie("signin[emailid]").length;
    }

    console.log(signinemail);
    console.log(signinemail_length);

    if( (signinemail_length > 2) && (signinemail != 'null') && (signinemail != '') ) {
      setCookie('signin[email]', '');

      location.reload();
    }

    return false;
  };

  var backLink = function () {
    parent.history.back();
    return false;
  }

  $(document).ready(function() {
    if( !!$.cookie("returnchoose") ){
      var returnchoose    = $.cookie("returnchoose");
      alert(returnchoose);
    }
    $('.sign-out').on('click', removeCookie);
    $('.back-link').on('click', backLink);

    $('input[name*="client_date"]').datepicker({
      showButtonPanel: true,
      showOtherMonths: true,
      selectOtherMonths: true,
      dateFormat: 'yy-mm-dd',
      beforeShow: function( input ) {
        setTimeout(function() {
          var buttonPane = $( input )
          .datepicker( "widget" )
          .find( ".ui-datepicker-buttonpane .ui-datepicker-current" );

          var btn = $('<button type="button" class="ui-datepicker-clear ui-state-default ui-priority-third ui-corner-all">Clear</button>');
          btn
          .unbind("click")
          .bind("click", function () {
            $.datepicker._clearDate( input );
          });

          //btn.appendTo( buttonPane );
          buttonPane.after(btn);

        }, 1 );
      },
      onChangeMonthYear: function( input ) {
        setTimeout(function() {
          var buttonPane = $( input )
          .datepicker( "widget" )
          .find( ".ui-datepicker-buttonpane .ui-datepicker-current" );

          var btn = $('<button type="button" class="ui-datepicker-clear ui-state-default ui-priority-third ui-corner-all">Clear</button>');
          btn
          .unbind("click")
          .bind("click", function () {
            $.datepicker._clearDate( input );
          });

          //btn.appendTo( buttonPane );
          buttonPane.after(btn);

        }, 1 );
      }
    });

    $('.select-two').select2();

    $('.client-date').on('change', chosenDatepicker);
    $('.client-service').on('change', chosenSelect);
  });

  $(window).load(function() {
    // Call to function
  });

  $(window).resize(function() {
    // Call to function
  });
})(jQuery);

(function ($) {
$(document).ready(function() {
  scriptContent();
})
$(document).ajaxSuccess(function(){
  scriptContent();
})

function scriptContent(){
  applicationTab();

  // home page promo slider
  if ($("#promo-slider").length > 0) {
    $("#promo-slider").onepage_scroll({
       sectionContainer: ".slide", // sectionContainer accepts any kind of selector in case you don't want to use section
       easing: "ease", // Easing options accepts the CSS3 easing animation such "ease", "linear", "ease-in", "ease-out", "ease-in-out", or even cubic bezier value such as "cubic-bezier(0.175, 0.885, 0.420, 1.310)"
       animationTime: 900, // AnimationTime let you define how long each section takes to animate
       pagination: true, // You can either show or hide the pagination. Toggle true for show, false for hide.
       updateURL: false, // Toggle this true if you want the URL to be updated automatically when the user scroll to each page.
       // responsiveFallback: 768
    });
  }
  // end

  // style form select element
  if ($('.selecter').length > 0) {
    // $('.selecter').selecter({
    //   // customClass: 'date'
    // });    

    // $('.form-item-payout-method').selecter({
    //   customClass: 'date-day'
    // });

    // $('.date-month').selecter({
    //   customClass: 'date-month'
    // });

    // $('.date-year').selecter({
    //   customClass: 'date-year'
    // });

    // $('.form-item-country-list').selecter({
    //   customClass: 'form-item-country-list'
    // });

    $('.selecter.month').selecter({
      customClass: 'month'
    });

    $('.selecter.year').selecter({
      customClass: 'year'
    });

    $('.selecter.country').selecter({
      customClass: 'country'
    });

    $('.selecter.page').selecter();
    $('.selecter.filter').selecter();

    $('.convert-money .selecter').selecter();

    $('.profile-withdraw .form-select').selecter();

    $('.settings-page .form-select').selecter();
  }
  // end

  // style form radion button element
  if ($('.form-radio').length > 0) {
    $('.form-radio').uniform();
  }
  // end

  // style form checkbox element
  if ($('.form-checkbox').length > 0) {
    $('.form-checkbox').uniform();
  }
  // end

  // style form checkbox element
  if ($('.form-file').length > 0) {
    $('.register-page .form-file').jfilestyle({
      input: false,
      buttonText: 'Прикрепить файлы'
    });

    $('.new-message-page .form-file, .message-page .form-file, .change-bank-settings .form-file').jfilestyle({
      input: false,
      buttonText: 'Прикрепить файл'
    });

    $('.update-avatar .form-file').jfilestyle({
      input: false,
      buttonText: 'Выбрать файл'
    });

    $('.add-loan-page .form-file').jfilestyle({
      input: false,
      buttonText: 'Прикрепить файл'
    });
  }
  // end

  // tabs content
  tabs();
  sub_tabs();
  // end

  // lang navigation
  navigationLanguage('#footer');
  // end

  // registration steps form
  registrationSteps();
  // end

  // show password
  // showPassword();
  // end

  // add datepicker for date form input type
  if ($('.datepicker').length > 0) {
    $('.datepicker').datepicker({
      regional: 'ru',
      changeMonth: true,
      changeYear: true
    });
  }
  // end

  // add datepicker for profile balance period
  rangeDate('.range-date');
  rangeDate('.tab-pane.history .period');  
  // end

  // // right sidebar slider
  // ammountSlider('#right-sidebar');
  // // end

  // // add loan slider
  // ammountSlider('.add-loan-page .form-item-slider-size');
  ammountSlideri('.add-loan-page .form-item-slider-time');
  // // end

  // custom function - change right sidebar content
  custom_sidebar_nav();
  // end

  // show profile panel title tooltips
  // profileTooltip();
  // end

  // profile applications image viewer
  imageViewer('#photo-viewer');
  // end

  // toggle profile history block
  toggleHistoryBlock();
  // end

  // profile popups
  popup();
  // end

  // profile popups
  popover();
  // end

  // toggle table row description
  toggleTableRow('tr.toggle');
  // end

  // toggle faq items in profile pages
  toggleProfileFaq();
  // end

  // toogle profile edit withdraw content
  toggleWithdrawDescription();
  // end

  // show reply message form
  showReplyMessageForm('#reply-message', '.form-toreply');
  // end

  // toggle quote
  toggleQuote('.show-quote');
  // end

  // register tabs
  registerTabs();
  // end

  // nano scroller
  // $('.nano').nanoScroller({
  //   iOSNativeScrolling: true,
  //   alwaysVisible: true
  // });
  // end

 

  // toggle loan full content info
  $('.loan-info').on('click', '.toggle-content', function(event) {
    event.preventDefault();

    var $this = $(this);

    $this.parents('.loan-info').find('.other-content').toggle(0, function(){
      if ($this.hasClass('show')) {
        $this.hide();
        $this.next().show();
      } else {
        $this.hide();
        $this.prev().show();
        $this.parents('.loan-info').find('.toggle-content.show').show();
      }
    });
  });
  // end
}

function tabs() {
  var $tab_nav = $('.panel .nav-tabs, .widget .nav-tabs'),
      $tab_content = $('.panel .tab-content, .widget .tab-content');

  if ($tab_nav.parents('.my-entries')) {
    var $tab_sub_content = $('#right-sidebar .tab-content');
  }

  // $tab_nav.find('> li:first-child').addClass('active');
  // $tab_content.find('> .tab-pane:first-child').addClass('active');

  if ($tab_nav.parents('.my-entries')) {
    $tab_sub_content.find('> .tab-pane:first-child').addClass('active');
  }

  $tab_nav.on('click', 'li > a', function(event) {
    event.preventDefault();

    var index = $tab_nav.find('> li').index($(this).parent());

    $tab_nav.find('> li').removeClass('active');
    $(this).parent().addClass('active');

    $tab_content.find('> .tab-pane').removeClass('active');
    $tab_content.find('> .tab-pane').eq(index).addClass('active');

    if ($tab_nav.parents('.my-entries')) {
      $tab_sub_content.find('> .tab-pane').removeClass('active');
      $tab_sub_content.find('> .tab-pane').eq(index).addClass('active');
    }
  });
}

function sub_tabs() {
  var $tab_nav = $('.panel .sub-nav-tabs'),
      $tab_content = $('.panel .sub-tab-content');

  // $tab_nav.find('> li').eq(0).addClass('active');
  // $tab_content.find('> .sub-tab-pane').eq(0).addClass('active');

  $tab_nav.on('click', 'li > a', function(event) {
    event.preventDefault();

    var index = $tab_nav.find('> li').index($(this).parent());

    $tab_nav.find('> li').removeClass('active');
    $(this).parent().addClass('active');

    $tab_content.find('> .sub-tab-pane').removeClass('active');
    $tab_content.find('> .sub-tab-pane').eq(index).addClass('active');
  });
}

function registerTabs() {
  var $tab_nav = $('.registration .nav-tabs'),
      $tab_content = $('.registration .tab-content');

  $tab_nav.find('li:first-child').addClass('active');
  $tab_content.find('.tab-pane:first-child').addClass('active');

  $tab_nav.on('click', 'li > a', function(event) {
    event.preventDefault();

    var index = $tab_nav.find('li').index($(this).parent());

    $tab_nav.find('li').removeClass('active');
    $(this).parent().addClass('active');

    $tab_content.find('.tab-pane').removeClass('active');
    $tab_content.find('.tab-pane').eq(index).addClass('active');
  });
}

function navigationLanguage(parent) {
  var $parent = $(parent),
      $nav = $parent.find('nav.lang'),
      active_text = $nav.find('li.active a').text(),
      active_data_text = $nav.find('li.active a').attr('data-lang'),
      active_class = $nav.find('li.active a').attr('class');

  if (active_data_text) {
    active_text = active_data_text;
  }

  if ($nav.find('b').length > 0) {
    $nav.find('b').addClass(active_class).text(active_text);
  } else {
    $nav.prepend('<b class="' + active_class + '">' + active_text + '</b>');
  }

  $(document).on('click', function(event) {
    if (!$(event.target).closest('.lang').length) {
      $nav.removeClass('active').find('ul').hide();
    }
  });

  $nav.on('click', 'b', function(event) {
    $nav.toggleClass('active').find('ul').toggle();
  });

  $nav.on('click', 'li a', function(event) {
    // event.preventDefault();

    var $this = $(this),
        active_text = $this.text(),
        active_data_text = $this.attr('data-lang'),
        active_class = $this.attr('class');

    if (active_data_text) {
      active_text = active_data_text;
    }

    $nav.find('b').removeClass().addClass(active_class).text(active_text);

    $nav.find('li').removeClass('active')
    $this.parent().addClass('active');

    $nav.removeClass('active').find('ul').hide();
  });
}

function registrationSteps() {
  var $tab_nav = $('.registration .steps ul'),
      $tab_content = $('.registration .form-elements');

  $tab_nav.on('click', 'li > a', function(event) {
    event.preventDefault();

    var index = $tab_nav.find('li').index($(this).parent());

    $tab_nav.find('li').removeClass('active');
    $tab_nav.find('li').removeClass('verify');
    $(this).parent().addClass('active');
    $(this).parent().prevAll().addClass('verify');

    $tab_content.find('.form-step').hide();
    $tab_content.find('.form-step').eq(index).show();
  });
}

// function profileTooltip() {
//   $('.tooltip').on('click', function(event) {
//     event.preventDefault();
    
//     $(this).toggleClass('active').parent().parent().find('.tooltip-description').slideToggle(200);
//   });
// }

// function showPassword() {
//   $('.show-password').on('click', function(event) {
//     event.preventDefault();

//     if (!$(this).hasClass('active')) {
//       $(this).addClass('active').prev().prop('type', 'text');
//     } else {
//       $(this).removeClass('active').prev().prop('type', 'password');      
//     }
//   });
// }

function rangeDate(element) {
  var $element = $(element);

  if ($element.length > 0) {
    $.datepicker._defaults.onAfterUpdate = null;

    var datepicker__updateDatepicker = $.datepicker._updateDatepicker;
    $.datepicker._updateDatepicker = function( inst ) {
      datepicker__updateDatepicker.call( this, inst );

      var onAfterUpdate = this._get(inst, 'onAfterUpdate');
      if (onAfterUpdate) {
        onAfterUpdate.apply((inst.input ? inst.input[0] : null),
           [(inst.input ? inst.input.val() : ''), inst]);
      }
    }

    $(function() {
      var cur = -1, prv = -1;

      $element.find('div')
        .datepicker({
          numberOfMonths: 3,
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          dateFormat: 'M d, yy',

          beforeShowDay: function ( date ) {
            return [true, ( (date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ? 'date-range-selected' : '')];
            $element.find('div').hide();
          },

          onSelect: function ( dateText, inst ) {
            var d1, d2;

            prv = cur;
            cur = (new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay)).getTime();
            if ( prv == -1 || prv == cur ) {
              prv = cur;
              $element.find('input').val( dateText );
            } else {
              d1 = $.datepicker.formatDate( 'M d, yy', new Date(Math.min(prv,cur)), {} );
              d2 = $.datepicker.formatDate( 'M d, yy', new Date(Math.max(prv,cur)), {} );
              $element.find('input').val( d1+' - '+d2 );
            }
          },

          onChangeMonthYear: function ( year, month, inst ) {
            //prv = cur = -1;
          },

          onAfterUpdate: function ( inst ) {
            $('<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">ОК</button>')
              .appendTo($element.find('.ui-datepicker-buttonpane'))
              .on('click', function () {
                $element.find('div').hide();
              });
          }
        })
        .position({
            my: 'left top',
            at: 'left bottom',
            of: $element.find('input')
         });

      $element.find('input').on('focus', function (e) {
        var v = this.value,
            d;

        try {
          if ( v.indexOf(' - ') > -1 ) {
            d = v.split(' - ');

            prv = $.datepicker.parseDate( 'M d, yy', d[0] ).getTime();
            cur = $.datepicker.parseDate( 'M d, yy', d[1] ).getTime();

          } else if ( v.length > 0 ) {
            prv = cur = $.datepicker.parseDate( 'M d, yy', v ).getTime();
          }
        } catch ( e ) {
          cur = prv = -1;
        }

        if ( cur > -1 )
          $element.find('div').datepicker('setDate', new Date(cur));

        $element.find('div').datepicker('refresh').show();
      });
    });
  }
}

function ammountSlideri(el) {
  var $element = $(el),
      slider_value = $element.find('input.amount').val(),
      slider_step = parseInt($element.find('input.step').val()),
      slider_percent = parseInt($element.find('input.percent').val()),
      slider_currency = $element.find('input.currency').val(),
      slider_min = parseInt($element.find('input.min-value').val()),
      slider_max = parseInt($element.find('input.max-value').val());

  if($element.find('input.amount').length) {

    // update invest value
    $element.find('span.invest-value').text(parseInt(slider_value.replace(/,/gi, "")));

    // update percent value
    var percent_value = humanizeNumber(Math.floor((parseInt(slider_value) / slider_percent) * 100));
    $element.find('span.percent-value').text(percent_value);

    // update total value
    var total_value = humanizeNumber(parseInt(slider_value.replace(/,/gi, "")) + parseInt(percent_value.replace(/,/gi, "")));
    $element.find('span.total-value').text(total_value);

    var slider = $element.find('.slider').slider({
      range: "min",
      value: $('input.term-value').val(),
      min: slider_min,
      max: slider_max,
      step: slider_step,
      create: function(event, ui ) {
        // $element.find('.ui-slider-handle').html('<span></span>');
      },
      slide: function(event, ui) {
        var value = ui.value.toString(),
            $this = $(this);
        value = parseInt(value);
        $(this).find('.amount').val(value);
        $('input.term-value').val(value);
// + ' ' + slider_currency

      }
    });
    $('.form-item-slider-time').on('slidestop', '.slider', function(event, ui) {
      var value = ui.value.toString(),
            $this = $(this);
        value = parseInt(value);
        $('input.term-value').val(value);

    });

    
    // $element.find('.amount').on('blur', function() {

    //   var slider_val = parseInt($(this).val().replace(/,/gi, ""));
    //   var rangePercent = (slider_val * 100) / (slider_max - slider_min);
      
    //   $('#second_slider .ui-slider-range-min').css('width', rangePercent + "%");
    //   $('#second_slider .ui-slider-handle').css('left', rangePercent + "%");
    //   // slider.slider('value', humanizeNumber($(this).val()));
    //   $element.find('.slider').slider({
    //     range: "min",
    //     value: slider_val,
    //     min: slider_min,
    //     max: slider_max,
    //     step: slider_step,
    //     create: function(event, ui ) {
    //       // $element.find('.ui-slider-handle').html('<span></span>');
    //     },
    //     slide: function(event, ui) {
    //       var value = ui.value.toString(),
    //           $this = $(this);

    //       value = humanizeNumber(value);

    //       // if ($element.find('input.amount').val() < slider_min || $element.find('input.amount').val() > slider_max){
    //       // $element.find('input.amount').css('color', 'red');
    //       // $element.find('input[name="invest_submit"]').attr("disabled", "disabled");
    //       // }
    //       // else{
    //       //   $element.find('input.amount').css('color', '#363b48');
    //       //   $element.find('input[name="invest_submit"]').removeAttr("disabled");
    //       // }

    //       // $(this).find('.amount').val(value + ' ' + slider_currency);
    //       $(this).find('.amount').val(value);
    //       // show update calculation link

    //       // // update invest value
    //       // $element.find('span.invest-value').text(value);

    //       // // update percent value
    //       // var percent_value = humanizeNumber(Math.floor((parseInt(value) / slider_percent) * 100));
    //       // $element.find('span.percent-value').text(percent_value);

    //       // // update total value
    //       // var total_value = humanizeNumber(parseInt(value.replace(/,/gi, "")) + parseInt(percent_value.replace(/,/gi, "")));
    //       // $element.find('span.total-value').text(total_value);
    //     }

    //   });

    //   //update all values after keyboard input sum //
    //   var value = $('#right-sidebar input.amount').val(),
    //       element = $('#right-sidebar');
    //   // update invest value
    //       $element.find('span.invest-value').text(value);

    //   // update percent value
    //   var percent_value = humanizeNumber(Math.floor((parseInt(value) / slider_percent) * 100));
    //   $element.find('span.percent-value').text(percent_value);

      
    // });
  }
}

function humanizeNumber(n) {
      n = n.toString();
      while (true) {
        var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3');
        if (n == n2) break;
        n = n2;
      }
      return n;
}

function custom_sidebar_nav() {
  var $nav = $('#pagination-links'),
      $nav_content = $('.my-application #right-sidebar .nav-content');

  $nav.find('li').eq(0).addClass('active');
  $nav_content.find('.tab-pane').eq(0).show();

  $nav.on('click', 'li > a', function(event) {
    event.preventDefault();

    var index = $nav.find('li').index($(this).parent());

    $nav.find('li').removeClass('active');
    $(this).parent().addClass('active');

    $nav_content.find('.tab-pane').hide();
    $nav_content.find('.tab-pane').eq(index).show();
  });
}

function imageViewer(element) {
  var $element = $(element),
      $large_image = $element.find('.big-image img'),
      $thumbs_item = $element.find('.thumbs li');

  // default configuration
  var $thumbs_item_default = $element.find('.thumbs li').first(),
      $large_img_default = $thumbs_item_default.find('img').attr('data-image-large'),
      $img_active_default = $thumbs_item_default.find('img').attr('data-image-active');

  $large_image.attr('src', $large_img_default);
  $thumbs_item_default.addClass('active').find('img').attr('src', $img_active_default);

  $thumbs_item.on('click', 'a', function(event) {
    event.preventDefault();
    
    var $this = $(this),
        $large_img = $this.find('img').attr('data-image-large'),
        $default_img = $this.find('img').attr('data-image'),
        $active_img = $this.find('img').attr('data-image-active');

    $large_image.attr('src', $large_img);

    // toggle active class
    $thumbs_item.removeClass('active');
    $this.parent('li').addClass('active');

    // change img src
    $thumbs_item.each(function(index, el) {
      var default_src = $(el).find('img').attr('data-image');
      
      $(el).find('img').attr('src', default_src);
    });

    $this.find('img').attr('src', $active_img);
  });
}

function toggleHistoryBlock() {
  $('.my-money .history').on('click', 'tr.info', function(event) {
    event.preventDefault();
    event.stopPropagation();

    var is_active = $(this).hasClass('active');

    $('.history tr.description').hide();
    $('.history tr.info').removeClass('active');

    if (is_active) {
      $(this).removeClass('active').next('tr.description').hide();
    } else {
      $(this).addClass('active').next('tr.description').show();
    }
  });
}

function popup() {
  $('a[data-popup]').on('click', function() {
    event.preventDefault();

    var popup_id = $(this).data('popup');
        $popup = $('.popup#' + popup_id);

    $('body').addClass('popup-open');
    $('.popup-overlay').fadeIn('100', function() {
      $popup.fadeIn('100', function(){
        if ($popup.find('.flexslider')) {
          $popup.find('.flexslider').flexslider({
            animation: 'fade',
            controlNav: 'thumbnails',
            slideshow: false,
            start: function(slider) {
              $popup.find('.counter').html((slider.currentSlide + 1) + '/' + slider.count);
            },
            after: function(slider) {
              $popup.find('.counter').html((slider.currentSlide + 1) + '/' + slider.count);
            }
          });
        }
      });      
    });
  });

  $('.popup .close').on('click', function() {
    event.preventDefault();

    $('.popup').fadeOut('100', function(){
      $('.popup-overlay').fadeOut(100);
      $('body').removeClass('popup-open');
    });
  });
}

function popover() {
  $('a[data-popover]').on('click', function() {
    event.preventDefault();

    var popover_id = $(this).data('popover');
        $popover = $('.popover#' + popover_id);

    $('.popover-overlay').fadeIn(100, function() {
      $popover.fadeIn(50);
    });
  });

  $('.popover .close').on('click', function() {
    event.preventDefault();

    $('.popover').fadeOut(50, function(){
      $('.popover-overlay').fadeOut(100);
    });
  });
}

function toggleTableRow(element) {
  var $element = $(element);

  $element.on('click', function(event) {
    event.preventDefault();
    
    $(this).toggleClass('active').next('tr.description').toggleClass('active');
  });  
}

/*left menu show|hide elemets */
function applicationTab() { 
  var $nav = $('.profile-application-nav'),
      $nav_content = $('.profile-application-nav-content');

  // $nav.find('li:first-child').addClass('active');
  $nav_content.each(function( index, element ) {
    $(element).find('> .profile-tab-pane:first-child').show();
  });

  $nav.on('click', 'li > a', function(event) {
    // event.preventDefault();

    var $this = $(this),
        _index = $this.parents('nav').find('li').index($(this).parent());

    if ($this.parents('nav').attr('id')) {
      var currentTabs = $('.' + $this.parents('nav').attr('id'));

      $this.parents('nav').find('li').removeClass('active');
      $(this).parent().addClass('active');

      currentTabs.each(function( index, element ) {
        // $(element).find('> .profile-tab-pane').hide();
        // $(element).find('> .profile-tab-pane').eq(_index).show();
      });
    } else {
      $this.parents('nav').find('li').removeClass('active');
      $(this).parent().addClass('active');

      $nav_content.each(function( index, element ) {
        // $(element).find('> .profile-tab-pane').hide();
        // $(element).find('> .profile-tab-pane').eq(_index).show();
      });
    }

    // $('.nano').nanoScroller({
    //   iOSNativeScrolling: true,
    //   alwaysVisible: true
    // });
  });
}

function toggleProfileFaq() {
  var $parent = $('.profile-page .faq-list');
  $parent.on('click', 'dt', function(event) {
    event.preventDefault();

    $(this).toggleClass('active').next('dd').toggle();
  });
}

function toggleWithdrawDescription() {
  var $parent = $('.profile-withdraw .order');
  $parent.on('click', 'a.edit', function(event) {
    event.preventDefault();

    $(this).toggleClass('active').parents('tr').next('tr').toggle();
  });
}

function showReplyMessageForm(el, form) {
  var $el = $(el);
  var $form = $(form);

  $el.on('click', function(event) {
    event.preventDefault();

    $el.hide();
    $form.show();    
  });
}

function toggleQuote(el) {
  var $el = $(el);

  $el.on('click', function(event) {
    event.preventDefault();

    $(this).parent().find('> .quote').toggle();
  });
}
})(jQuery);
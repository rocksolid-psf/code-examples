(function ($) {

	Drupal.behaviors.autoUpload = {
    attach: function (context, settings) {

      $('.allrise-car-params-loan-wrapper .amount').on('blur change', function(event) {
        if (parseInt($('.allrise-car-params-loan-wrapper .amount').val()) > parseInt($('.allrise-car-params-loan-wrapper .max-value').val())){
          console.log('1 '+$('.allrise-car-params-loan-wrapper .amount').val()+' 2 '+$('.allrise-car-params-loan-wrapper .max-value').val());
          $('.allrise-car-params-loan-wrapper input[name="loan_size"]').val($('.allrise-car-params-loan-wrapper .max-value').val());
          $('.allrise-car-params-loan-wrapper .amount').removeClass('error');
          $('input[name="calculation_btn_submit"]').removeAttr("disabled");
        }
      });


      var isDisabled = $('input[name="market_price"]').is(':disabled');
      if (isDisabled) {
        // $(element).prop('disabled', false);
        $('input[name="loan_size"]').on('click', function(){
          if ($(this).val() >= 0) {
            console.log('ok');
          }
          else{
            $(this).val(0);
          };
        });
        console.log('disabled');
      }
      else{
        console.log('enabled');
      };

      if ($('.pledge_type').length > 0 && $('.pledge_type').val() == 2) {
        ammountSlider('.add-loan-page .form-item-slider-size');
      }

      $('#allrise-create-loan-wrapper', context).delegate('input[name="files[allfiles]"]', 'change', function() {
        $('.allrise_autoupload_img').mousedown();
      });

      $('#allrise-create-loan-wrapper', context).delegate('input[name="files[second_section]"]', 'change', function() {
        $('.next_autoupload_img').mousedown();
      });

      $('#allrise-create-loan-wrapper', context).delegate('.ui-slider-range', 'resize', function() {

      });

         // toggle shedule content
      $('.add-loan-page').on('click', '.toggle-shedule', function(event) {
        event.preventDefault();

        $('.add-loan-page .shedule').fadeIn();
      });

      $('.add-loan-page').on('click', '.close-shedule', function(event) {
        event.preventDefault();

        $('.add-loan-page .shedule').fadeOut();
      });

      $('.add-loan-page .form-content').on('keydown', 'input[type="text"]', function() {
        $('.add-loan-page .shedule').fadeOut();
      });

      $('.add-loan-page .form-content').on('change', 'select', function() {
        $('.add-loan-page .shedule').fadeOut();
      });
      $('you-get').trigger('change', function() {
        $('.add-loan-page .shedule').fadeOut();
      });
      $('.add-loan-page .form-content').on('slidestop', '.slider', function(event, ui) {
        $('.add-loan-page .shedule').fadeOut();
      });
      // end
      //set focus on loan product market price
      $("input[name='market_price']").focus();

      function humanizeNumber(n) {
        n = n.toString();
        while (true) {
          var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3');
          if (n == n2) break;
          n = n2;
        }
        return n;
      }


      function getLoanParams(){

        $('select[name="payout_method"]').on('change', function(){
          var   size_loan = $('input[name="loan_size"]').val().replace(/\,/g, ''),
                term = parseInt($('input[name="loan_term"]').val()),
                percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
                method = $('select[name="payout_method"]').val();

          $.ajax({
            type: 'POST',
            url: '/loan-create-get-parameters',
            data: { size_loan : size_loan, term : term, method : method, percent : percent },
            dataType: 'json',
            async: false,
            success: function(data){

                if(data[0] > 0){
                  $('td.returned > span').first().text(numberWithCommas(data[0]));
                  console.log(data);
                }
                else {
                  $('td.returned > span').first().text('error');
                }
            }
          });
        });

        $('#allrise-create-loan-wrapper .form-content').on('slidestop', '#first_slider', function(event, ui) {
          $('input[name="loan_size"]').removeClass('error');
          $('input[name="calculation_btn_submit"]').removeAttr("disabled");
  	      var product_code = $('input.product_code').val(),
  	      loan_size = parseInt($('input[name="loan_size"]').val().replace(/,/gi, ""));

  	      //get commision and laon percent for chosen sum
  				$.ajax({
  			    type: 'POST',
  			    url: '/loan-create-get-parameters',
  			    data: { product_code : product_code, loan_size : loan_size },
  			    dataType: 'json',
  			    async: false,
  			    success: function(data){

  			        if(data[0] > 0 && data[1] > 0 && data[2] > 0){
  			        	$('td.percent-loan > b').first().text(data[0]);
                  $('input[name="commision_percent"]').val(data[0]);
  			        	$('input.commision_percent').val(data[1]);
    							$('input.commision_fixed').val(data[2]);
  			        }
  			        else {
  			        	$('td.percent-loan > b').first().text('error');
  			        }
  			    }
  			  });


  			  $('.shedule-graph-calculation').click(function( event ) {
  					$(this).parent().parent().find('span.error').hide();

  		      if($('input[name="loan_term"]').val() && $('input[name="loan_size"]').val()) {
  		      	var credit_percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
  		      			credit_sum = $('input[name="loan_size"]').val().replace(/\,/g, ''),
  		      			credit_term = parseInt($('input[name="loan_term"]').val()),
                  credit_type = $('select[name="payout_method"]').val(), //if disabled
  		      			_href = $(".shedule-link-page a").attr("href");
  		      	$("a.shedule-graph-calculation").text('График платежей');
  		      	$("a.shedule-graph-calculation").attr("href", _href + '/' +$('.form-item-payout-method').find('.selecter-options .selecter-item.selected').attr('data-value') + '/' +credit_sum + '/' +credit_term + '/' +credit_percent);
  		      }
  		      else{
  		      	event.preventDefault();
  		      	$(this).parent().parent().find('span.error').show();
  		      }
  			  });

          var   size_loan = $('input[name="loan_size"]').val().replace(/\,/g, ''),
                term = parseInt($('input[name="loan_term"]').val()),
                percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
                method = $('select[name="payout_method"]').val();

          $.ajax({
            type: 'POST',
            url: '/loan-create-get-parameters',
            data: { size_loan : size_loan, term : term, method : method, percent : percent },
            dataType: 'json',
            async: false,
            success: function(data){

                if(data[0] > 0){
                  $('td.returned > span').first().text(numberWithCommas(data[0]));
                }
                else {
                  $('td.returned > span').first().text('error');
                }
            }
          });

  				loanCalculations();
  		  });


        $('input[name="loan_size"]').on('change', function(){
          var product_code = $('input.product_code').val(),
            loan_size = parseInt($('input[name="loan_size"]').val().replace(/,/gi, ""));

            //get commision and laon percent for chosen sum
            $.ajax({
              type: 'POST',
              url: '/loan-create-get-parameters',
              data: { product_code : product_code, loan_size : loan_size },
              dataType: 'json',
              async: false,
              success: function(data){

                  if(data[0] > 0 && data[1] > 0 && data[2] > 0){
                    $('td.percent-loan > b').first().text(data[0]);
                    $('input[name="commision_percent"]').val(data[0]);
                    $('input.commision_percent').val(data[1]);
                    $('input.commision_fixed').val(data[2]);
                  }
                  else {
                    $('td.percent-loan > b').first().text('error');
                  }
              }
            });


            $('.shedule-graph-calculation').click(function( event ) {
              $(this).parent().parent().find('span.error').hide();

              if($('input[name="loan_term"]').val() && $('input[name="loan_size"]').val()) {
                var credit_percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
                    credit_sum = $('input[name="loan_size"]').val().replace(/\,/g, ''),
                    credit_term = parseInt($('input[name="loan_term"]').val()),
                    credit_type = $('select[name="payout_method"]').val(), //if disabled
                    _href = $(".shedule-link-page a").attr("href");
                $("a.shedule-graph-calculation").text('График платежей');
                $("a.shedule-graph-calculation").attr("href", _href + '/' +$('.form-item-payout-method').find('.selecter-options .selecter-item.selected').attr('data-value') + '/' +credit_sum + '/' +credit_term + '/' +credit_percent);
              }
              else{
                event.preventDefault();
                $(this).parent().parent().find('span.error').show();
              }
            });

            var   size_loan = $('input[name="loan_size"]').val().replace(/\,/g, ''),
                  term = parseInt($('input[name="loan_term"]').val()),
                  percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
                  method = $('select[name="payout_method"]').val();

            $.ajax({
              type: 'POST',
              url: '/loan-create-get-parameters',
              data: { size_loan : size_loan, term : term, method : method, percent : percent },
              dataType: 'json',
              async: false,
              success: function(data){

                  if(data[0] > 0){
                    $('td.returned > span').first().text(numberWithCommas(data[0]));
                  }
                  else {
                    $('td.returned > span').first().text('error');
                  }
              }
            });

            loanCalculations();
        });

        $('#allrise-create-loan-wrapper .form-content').on('slidestop', '#second_slider', function(event, ui) {
          var   size_loan = $('input[name="loan_size"]').val().replace(/\,/g, ''),
                term = parseInt($('input[name="loan_term"]').val()),
                percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
                method = $('select[name="payout_method"]').val();

          $.ajax({
            type: 'POST',
            url: '/loan-create-get-parameters',
            data: { size_loan : size_loan, term : term, method : method, percent : percent },
            dataType: 'json',
            async: false,
            success: function(data){

                if(data[0] > 0){
                  $('td.returned > span').first().text(numberWithCommas(data[0]));
                }
                else {
                  $('td.returned > span').first().text('error');
                }
            }
          });
        });

      }

      function ShowHidePass(){
        $('span.ico.show-password').click(function (){
          $(this).toggleClass('active');
          if ($(this).parent().find('span').hasClass('active')) {
            $($(this).parent().find('input')).prop('type', 'text');
          }
          else{
            $($(this).parent().find('input')).prop('type', 'password');
          }
        });
      };

			$("#loan-calculation-wrapper").once(function () {
        getLoanParams();
        ShowHidePass();
      });

      $(".allrise-car-params-loan-wrapper").once(function () {
        TooltipHover();
      });


    }
	};

  function TooltipHover(){
    $(".tooltip-hover").hover(function(){
      $(this).parent().find(".tooltip-description-hover").fadeIn();
      }, function(){
      $(this).parent().find(".tooltip-description-hover").fadeOut();
    });
  };

  function ShowHidePass(){
          $('span.ico.show-password').click(function (){
            $(this).toggleClass('active');
            if ($(this).parent().find('span').hasClass('active')) {
              $($(this).parent().find('input')).prop('type', 'text');
            }
            else{
              $($(this).parent().find('input')).prop('type', 'password');
            }
          });
      };

	$(document).ready(function() {
    $(document).on('click', '.chat-btn-call', function(event) {
      event.preventDefault();
      $("#sh_button").click();
    });

	  // add loan slider
	  ammountSlider('.add-loan-page .form-item-slider-size');

	  $('select[name="model_auto"]').on('change', function(el){

	  });

	})
	$(document).ajaxSuccess(function(){

    $('.shedule .close-shedule').on('click', function(event){
      event.preventDefault();
      $(this).parents('#shedule-graph-wrapper').find('.shedule').slideUp();
    });

    ShowHidePass();
		$('.shedule-graph-calculation').click(function( event ) {
			$(this).parent().parent().find('span.error').hide();

      if($('input[name="loan_term"]').val() && $('input[name="loan_size"]').val()) {
      	var credit_percent = $('td.percent-loan > b').first().text().replace(/\,/g, '.'),
      			credit_sum = $('input[name="loan_size"]').val().replace(/\,/g, ''),
      			credit_term = parseInt($('input[name="loan_term"]').val());
      			credit_type = $('select[name="payout_method"]').val(),
      			_href = $(".shedule-link-page a").attr("href");
      	$("a.shedule-graph-calculation").text('График платежей');
      	$("a.shedule-graph-calculation").attr("href", _href + '/' +credit_type + '/' +credit_sum + '/' +credit_term + '/' +credit_percent);
      }
      else{
      	event.preventDefault();

      	$(this).parent().parent().find('span.error').show();
      }

	  });

	  $('.allrise-car-data-loan-wrapper').delegate('input[name="files[allfiles]"]', 'change', function() {
	    $('.allrise_autoupload_img').mousedown();
	  });

	  $('.allrise-car-data-loan-wrapper').delegate('input[name="files[second_section]"]', 'change', function() {
	    $('.next_autoupload_img').mousedown();
	  });
    if ($('.pledge_type').length > 0 && $('.pledge_type').val() != 2) {
	  // scriptContent();
  	  $('input[name="market_price"]').on('keyup', function(){

        // loanCalculations();
        var max_sum = Math.round($('input.LTVMax').val() / 100 * $('input[name="market_price"]').val().replace(/,/gi, "")),
        		product_max = $('input.max_sum_value').val();

        $('input[name="loan_size"]').val(0);
        $('input#edit-loan-size').attr('max', 25000);
        if (max_sum < product_max) {
        	var sliderSum = max_sum;
        }
        else{
        	var sliderSum = product_max;
        }
        //update max_sum value. add change event to hidden input
        $('#first_slider .max-value').val(sliderSum).trigger('change');
        ammountSlider('.add-loan-page .form-item-slider-size');

  	  });
      $('input[name="market_price"]').on('change', function(){
        $('input[name="market_price"]').val(humanizeNumber($('input[name="market_price"]').val().replace(/,/g, "")));
      });
    }
	})

	function loanCalculations(){
		var $element = $('#loan-calculation-wrapper');


	  if($element.find('input[name="market_price"]').val() && $element.find('input[name="loan_size"]').val()) {
	  	var loan_size = parseInt($element.find('input[name="loan_size"]').val().replace(/,/gi, ""));
	  			market_price = (parseInt($element.find('input[name="market_price"]').val().replace(/,/gi, "")));
	  			res = (loan_size / market_price * 100).toFixed(0);
	  			//update LTV
			$element.find('td.LTV > b').first().text(numberWithCommas(res));
		}

		if($element.find('input[name="loan_size"]').val()) {

			var sum = parseInt($('input[name="loan_size"]').val().replace(/,/gi, "")),
  			commision_percent = $('input.commision_percent').val(),
  			commision_fixed = $('input.commision_fixed').val(),
  			commission_result = parseInt(sum * commision_percent / 100) + parseInt(commision_fixed);

  			//update commission
  			$element.find('td.commission > span').first().text(numberWithCommas(commission_result));

  		var loan_size = parseInt($element.find('input[name="loan_size"]').val().replace(/,/gi, ""));
  			commission = parseInt($element.find('td.commission > span').first().text().replace(/,/gi, "")),
  			result = parseInt(loan_size) - parseInt(commission);
  			//update you get
  			$element.find('td.you-get > span').first().text(numberWithCommas(result));
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

  function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
  }

	function numberWithCommas(x) {
  	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	function ammountSlider(el) {
  var $element = $(el),
      slider_value = $element.find('input.amount').val(),
      slider_step = parseInt($element.find('input.step').val()),
      slider_percent = parseInt($element.find('input.percent').val()),
      slider_currency = $element.find('input.currency').val(),
      slider_min = parseInt($element.find('input.min-value').val()),
      slider_max = parseInt($element.find('input.max-value').val());

  if (slider_max == 0 || slider_max < slider_min) {
    $('input[name="invest_submit"]').attr("disabled", "disabled");
  };

  if($element.find('input.amount').length) {

    // update invest value
    $element.find('span.invest-value').text(slider_value);

    // update percent value
    var percent_value = humanizeNumber(Math.floor((parseInt(slider_value) / slider_percent) * 100));
    $element.find('span.percent-value').text(percent_value);

    // update total value
    var total_value = humanizeNumber(parseInt(slider_value.replace(/,/gi, "")) + parseInt(percent_value.replace(/,/gi, "")));
    $element.find('span.total-value').text(total_value);

    var slider = $element.find('#first_slider.slider').slider({
      range: "min",
      value: 1000,
      min: slider_min,
      max: slider_max,
      step: slider_step,
      create: function(event, ui ) {
        // $element.find('.ui-slider-handle').html('<span></span>');
      },
      slide: function(event, ui) {
        var value = ui.value.toString(),
            $this = $(this);
        value = humanizeNumber(value);
        // $(this).find('.amount').val(value + ' ' + slider_currency);
        $(this).find('.amount').val(value);

        // show update calculation link
        if ($(this).hasClass('focus')) {
          $element.find('.update-calculation').slideDown('100');

        }

        // update invest value
        $element.find('span.invest-value').text(value);

        // update percent value
        var percent_value = humanizeNumber(Math.floor((parseInt(value) / slider_percent) * 100));
        $element.find('span.percent-value').text(percent_value);

        // update total value
        var total_value = humanizeNumber(parseInt(value.replace(/,/gi, "")) + parseInt(percent_value.replace(/,/gi, "")));
        $element.find('span.total-value').text(total_value);
      }
    });

    $element.find('.amount').on('focus', function() {
      $(this).val(parseInt($(this).val().replace(/,/gi, "")));

      slider.slider('value', parseInt($(this).val().replace(/,/gi, "")));
    }).on('blur', function() {
      // $(this).val(humanizeNumber($(this).val()) + ' ' + slider_currency);
      $(this).val(humanizeNumber($(this).val()));

      slider.slider('value', humanizeNumber($(this).val()));
    });

    $element.find('.amount').on('blur', function() {

      var slider_val = parseInt($(this).val().replace(/,/gi, ""));
      var rangePercent = (slider_val * 100) / (slider_max - slider_min);

      $('#first_slider .ui-slider-range-min').css('width', rangePercent + "%");
      $('#first_slider .ui-slider-handle').css('left', rangePercent + "%");
      $element.find('#first_slider.slider').slider({
        range: "min",
        value: slider_val,
        min: slider_min,
        max: slider_max,
        step: slider_step,
        create: function(event, ui ) {
        },
        slide: function(event, ui) {
          var value = ui.value.toString(),
              $this = $(this);

          value = humanizeNumber(value);

          if ($element.find('input.amount').val() < slider_min || $element.find('input.amount').val() > slider_max){
          $element.find('input.amount').css('color', 'red');
          $element.find('input[name="invest_submit"]').attr("disabled", "disabled");
          }
          else{
            $element.find('input.amount').css('color', '#363b48');
            $element.find('input[name="invest_submit"]').removeAttr("disabled");
          }

          // $(this).find('.amount').val(value + ' ' + slider_currency);
          $(this).find('.amount').val(value);
          // show update calculation link
          if ($(this).hasClass('focus')) {
            $element.find('.update-calculation').slideDown('100');

          }

          // update invest value
          $element.find('span.invest-value').text(value);

          // update percent value
          var percent_value = humanizeNumber(Math.floor((parseInt(value) / slider_percent) * 100));
          $element.find('span.percent-value').text(percent_value);

          // update total value
          var total_value = humanizeNumber(parseInt(value.replace(/,/gi, "")) + parseInt(percent_value.replace(/,/gi, "")));
          $element.find('span.total-value').text(total_value);
        }

      });

      //update all values after keyboard input sum //
      var value = $('#right-sidebar input.amount').val(),
          element = $('#right-sidebar');
      // update invest value
          $element.find('span.invest-value').text(value);

      // update percent value
      var percent_value = humanizeNumber(Math.floor((parseInt(value) / slider_percent) * 100));
      $element.find('span.percent-value').text(percent_value);

    });

    $element.find('.amount').on('keyup', function(){

        if ($(this).val() < slider_min || $(this).val() > slider_max){
          console.log('out_of_range '+$(this).val());
          $(this).addClass('error');
          $('input[name="calculation_btn_submit"]').prop('disabled', true);
        }
        else{
          $(this).removeClass('error');
          $('input[name="calculation_btn_submit"]').removeAttr("disabled");
        }
    });

    // Disallow non-numbers from being typed in box
    $element.find('.amount').on('keydown', function(event) {
      var isNotBackspaceOrDelete = event.keyCode != 46 && event.keyCode != 8;
      var isNotInteger = (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105);
      if(isNotBackspaceOrDelete && isNotInteger) {
          event.preventDefault();
      }
    });

    $('.calculate-payment').on('click', function(event) {
      event.preventDefault();

      $element.find('.amount').focus(); // focus input element for change values
      $element.find('.slider').addClass('focus'); // add custom class 'focus' for change style slider element
    });

    $('.update-calculation').on('click', '.link', function(event) {
      event.preventDefault();

      $element.find('.slider').removeClass('focus');
      $element.find('.update-calculation').slideUp('100');
    });

    // if payment emty - set focus for payment input slider
    $('.set-payment').on('click', '.btn', function(event) {
      event.preventDefault();

      $element.find('.amount').focus(); // focus input element for change values
      $element.find('.slider').addClass('focus'); // add custom class 'focus' for change style slider element
    });

  }
}
})(jQuery);

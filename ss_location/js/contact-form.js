(function ($, Drupal) {
  Drupal.behaviors.contactForm = {
    attach: function (context, settings) {
      $.datepicker.setDefaults({
        closeText: 'Sluiten',
        currentText: 'Vandaag',
        monthNames: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
        monthNamesShort: ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
        dayNames: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
        dayNamesShort: ['Zon', 'Maa', 'Din', 'Woe', 'Don', 'Vri', 'Zat'],
        dayNamesMin: ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
        weekHeader: 'Wk',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
      });

      $('input[name="ChildBirthDate"]').datepicker({
        changeMonth: true,
        changeYear: true
      });

      ssFillAddress('#question-form input[name="AddressStreet"]', '#question-form input[name="AddressCity"]', '#question-form input[name="AddressPostcode"]', '#question-form input[name="AddressHouseNumber"]');
      ssFillAddress('#complaints-form input[name="AddressStreet"]', '#complaints-form input[name="AddressCity"]', '#complaints-form input[name="AddressPostcode"]', '#complaints-form input[name="AddressHouseNumber"]');

      function ssFillAddress(streetSelector, citySelector, postcodeSelector, houseNumberSelector) {
        $(postcodeSelector + ', ' + houseNumberSelector).blur(function () {
          var Postcode = $(postcodeSelector).val();
          var HouseNumber = $(houseNumberSelector).val();

          Postcode = Postcode.replace(/\s/g, '');
          HouseNumber = parseInt(HouseNumber);

          if (Postcode.length == 6 && HouseNumber) {
            $.post({
              url: "/address-generate",
              dataType: "json",
              async: false,
              data: {'postcode': Postcode, 'house_number': HouseNumber},
              success: function (data) {
                $(streetSelector).val(data.street);
                $(citySelector).val(data.city);
                $(streetSelector + ', ' + citySelector).removeClass('hidden');
              },
              error: function () {
                $(streetSelector + ', ' + citySelector).val('');
                $(streetSelector + ', ' + citySelector).addClass('hidden');
              }
            })
          } else {
            $(streetSelector + ', ' + citySelector).val('');
            $(streetSelector + ', ' + citySelector).addClass('hidden');
          }
        });
      };

      // Open close form
      $("#question .read-more-link, #complaints .read-more-link", context).click(function(e) {
        e.preventDefault();
        var $this       = $(this, context),
            $thisTarget = $this.attr('href');
        $($thisTarget).show();

        var $getOffset       = $($thisTarget).offset().top,
            $heightHeader = $("header",context).height();
        if ($(".toolbar-fixed #toolbar-bar").length) {
          var $heighAdminBar = $("#toolbar-bar",context).height();
          $heightHeader += $heighAdminBar;
        }
        $('html, body').animate({
          scrollTop: $getOffset - $heightHeader
        }, 500);
      });
      $('.general-close-btn', context).click(function(e) {
        e.preventDefault();
        var $this       = $(this, context),
            $thisTarget = $this.attr('href');
        $($thisTarget).hide();
      });

      // Additional radio QuestionSubject
      $('.ss-location-questions-suggestions-form .row .fieldgroup .js-form-item [id^=edit-questionsubject-]').click(function() {
        var $this = $(this, context);
        if ($('.radio-error').length) {
          $('.radio-error').remove();
        }
        if ($this.val() == 'Mijn overeenkomst') {
          $('#edit-questionsubjectadditional').show();
        } else {
          $('#edit-questionsubjectadditional').hide();
        }
      });

      // Chose Question or Question
      if ($('#edit-contactreason-suggestion:checked').length) {
        $('.questions div.Question-section').hide();
        $('.questions div.Question-section #edit-questionsubject--wrapper').prop('required', false);
      }
      $('#edit-contactreason input[type="radio"]', context).click(function(e) {
        var $this       = $(this, context),
            $thisTarget = $this.attr('value'),
            $textarea   = $this.closest('form').find('textarea'),
            $elToShow   = $this.closest('form').find('p.' + $thisTarget + '-section');
        $elToShow.show();
        $elToShow.prev().hide();
        $elToShow.next().hide();

        if ($thisTarget == 'Suggestion') {
          $textarea.attr('placeholder', Drupal.t('Vul hier je suggestie in*'));
          $('.questions div.Question-section').hide();
          $('#question-form.questions h4.Suggestion-section').show();
          $('#question-form.questions h4.Question-section').hide();
          $('.questions div.Question-section #edit-questionsubject--wrapper').prop('required', false);
        } else {
          $textarea.attr('placeholder', Drupal.t('Vul hier je vraag in *'));
          $('.questions div.Question-section').show();
          $('#question-form.questions h4.Question-section').show();
          $('#question-form.questions h4.Suggestion-section').hide();
          $('.questions div.Question-section #edit-questionsubject--wrapper').attr('required', 'required');
        }
      });

      // Custom radio validation
      $('#ss-location-questions-suggestions-form #edit-submit--2').click(function(e) {
        if ($('#edit-contactreason-question:checked').length && !$('#edit-questionsubject input:checked').length) {
          e.preventDefault();
          var $getOffset = $('#contact-questions-suggestions').offset().top;
          $(window).scrollTop($getOffset);
          $('#edit-questionsubject .radio-error').remove();
          $('#edit-questionsubject').append('<label class="radio-error error">Wat is het onderwerp van je vraag?</label>')
          return false;
        }
      });

      // Show/hide location selectlist
      if ($('.ss-location-questions-suggestions-form .form-item-sendto #edit-sendto-customerservice:checked').length) {
        $('.ss-location-questions-suggestions-form .form-item-locationid').hide();
      }
      $('.ss-location-questions-suggestions-form .form-item-sendto input', context).click(function(e) {
        var $this       = $(this, context),
            $thisTarget = $this.attr('value');
        if ($thisTarget == 'CustomerService') {
          $('.ss-location-questions-suggestions-form .form-item-locationid').hide();
        } else {
          $('.ss-location-questions-suggestions-form .form-item-locationid').show();
        }
      });

        $.validator.addMethod("validPostCodeQuestion", function (value, element) {
        var AddressStreet = $('#question-form input[name="AddressStreet"]').val();
        var AddressCity = $('#question-form input[name="AddressCity"]').val();
        var Postcode = $('#question-form input[name="AddressPostcode"]').val();
        var HouseNumber = $('#question-form input[name="AddressHouseNumber"]').val();

        if ((AddressStreet == '' || AddressCity == '') && Postcode && HouseNumber) {
          return false;
        }
        else {
          return true;
        }
      }, Drupal.t("De postcode of het huisnummer is niet goed!"));

        $.validator.addMethod("validPostCodeComplaints", function (value, element) {
        var AddressStreet = $('#complaints-form input[name="AddressStreet"]').val();
        var AddressCity = $('#complaints-form input[name="AddressCity"]').val();
        var Postcode = $('#complaints-form input[name="AddressPostcode"]').val();
        var HouseNumber = $('#complaints-form input[name="AddressHouseNumber"]').val();

        if ((AddressStreet == '' || AddressCity == '') && Postcode && HouseNumber) {
          return false;
        }
        else {
          return true;
        }
      }, Drupal.t("De postcode of het huisnummer is niet goed!"));

      // Validation
      var $firstFormSettings = {
          rules:{
             AddressStreet: {
              required: false
            },
             AddressCity: {
              required: false
            },
            AddressPostcode: {validPostCodeQuestion: true},
            ContactEmail: {
              required: true,
            },
            ContactPhone: {
              required: true,
              minlength: 10,
            },
          },
        messages: {
          LocationId: {required: 'Waar moet je vraag naartoe?'},
          Remarks: {required: 'Wat is je vraag?'},
          NameTitle: {required: 'Dit veld is verplicht.'},
          NameFirst: {required: 'Voornaam is niet correct.'},
          NameLast: {required: Drupal.t('Achternaam is niet correct.')},
          AddressPostcode: {required: Drupal.t('Postcode is niet correct. Voorbeeld: 1234 AB.')},
          AddressHouseNumber: {required: Drupal.t('Het huisnummer is niet correct.')},
          ContactEmail: {
            required: 'Het e-mailadres is niet correct.',
            email: 'Het e-mailadres is niet correct.',
          },
          ContactPhone: {
            required: "Het telefoonnummer is niet correct",
            minlength: "Nummer moet uit 10 cijfers bestaan.",
          }
        }
      };

      $('#question-form form#ss-location-questions-suggestions-form').validate($firstFormSettings);

      $firstFormSettings.rules.AddressPostcode = {validPostCodeComplaints: true};
      $firstFormSettings.messages.LocationId.required = 'Waar moet je klacht naartoe?';
      $firstFormSettings.messages.Remarks.required = 'Wat is je klacht?';

      $('#complaints-form form.ss-location-questions-suggestions-form').validate($firstFormSettings);


      $("#contact-questions-suggestions textarea#edit-remarks").after('<span class="my-awesome-error error">' + Drupal.t('Wat is je suggestie?') + '</span>')
      $("#contact-questions-suggestions select#edit-locationid").after('<span class="my-awesome-error2 error">' + Drupal.t('Waar moet je suggestie naartoe?') + '</span>')


      $('#edit-contactreason input', context).click(function( ) {
        var $this   = $(this, context);
            $thisId = $this.attr('value'),
            $form   = $this.closest('form');
        $form.removeClass('Suggestion-new Question-new');
        $form.addClass($thisId+'-new');
      });


      //check for label.error question-form if = 0 show load amm
      $('div#complaints-form .form-submit, div#question-form .form-submit').click(function() {
        var $clickElm = $(this);
        var $formWrapId = $clickElm.closest("div.questions").attr('id');
        var $formWrap = $clickElm.closest("div.questions");
          setTimeout(function(){
            var $errorLabelTour = $formWrap.find("label.error:visible");
            if ( $errorLabelTour.length) {
              $('.apend-error').remove();
              $('#' + $formWrapId + " .actions", context).prepend("<div class='apend-error'> "+
                "<span class='apend-error-span'>Er is iets niet - of onvolledig - ingevuld. </span> </div>");
            } else {
              $('.apend-error').remove();
              $('#' + $formWrapId + " .actions", context).append("<div class='apend-load'> "+
                "<span class='apend-text'><img src='/themes/smallsteps/images/load.gif'> Een ogenbik geduld...</span> </div>");
            }
        }, 100);
      });
      //end check for label.error

    }
  };




})(jQuery, Drupal);

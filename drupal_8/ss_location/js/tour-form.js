(function ($, Drupal) {
  Drupal.behaviors.tourForm = {
    attach: function (context, settings) {
      var servicesCheckboxes = '#services-wrapper input[type="checkbox"]';
      var servicesSubmit = '#services-wrapper input[type="submit"]';
      var preferencesDayContainer = '#preferences-wrapper';
      var preferencesDayCheckboxes = '#preferences-wrapper #edit-preferreddays input[type="checkbox"]';
      var noPreferencesDayCheckbox = '#preferences-wrapper #edit-no-preferences-day';
      var preferencesCheckboxes = '#preferences-wrapper #edit-preferreddays input[type="checkbox"], #preferences-wrapper #edit-no-preferences-day';
      var preferencesDayHours = '#preferences-wrapper .preferences-day-hours';
      var preferencesSubmit = '#preferences-wrapper input[type="submit"]';
      var addressDayContainer = '#address-wrapper';
      var preferencesDatepicker = '#preferences-wrapper #location-tour-schedule';
      var schedulesDates = settings.ss_location.tour_form.schedules_dates;
      var schedulesDateHours = settings.ss_location.tour_form.schedules_date_hours;

      //Method for validate plugin
      $.validator.addMethod("validPostCode", function (value, element) {
        var AddressStreet = $('input[name="AddressStreet"]').val();
        var AddressCity = $('input[name="AddressCity"]').val();
        var Postcode = $('input[name="AddressPostcode"]').val();
        var HouseNumber = $('input[name="AddressHouseNumber"]').val();

        if ((AddressStreet == '' || AddressCity == '') && Postcode && HouseNumber) {
          return false;
        }
        else {
          return true;
        }
      }, Drupal.t("De postcode of het huisnummer is niet goed!"));


      $.validator.addMethod("Locationservices", function(value, element) {
        return $('#edit-locationservices input[type=checkbox]:checked', context).length > 0;
      }, Drupal.t("Kies een dienst."));


      $.validator.addMethod("Preferreddays", function(value, element) {
        if ($('#edit-no-preferences-day:checked', context).length > 0) {
          return true;
        }
        else if ($('#edit-preferreddays input[type=checkbox]:checked', context).length > 0 ) {
          return true;
        }
        else {
          return false;
        }
      }, Drupal.t("Geef je voorkeur aan."));
      //End Method for validate plugin

      $('form.ss-location-tour-form', context).validate({
        ignore: [],
        rules: {
           AddressStreet: {
            required: false
          },
          'LocationServices[KDV]': { Locationservices: true },
          'PreferredDays[Monday]': { Preferreddays: true},
           AddressCity: {
            required: false
          },
          AddressPostcode: {validPostCode: true},
          NameTitle: {
            required: true
          },
          ContactPhone: {minlength: 10},
        },
        messages: {
          NameTitle: {required: Drupal.t('Kies je aanhef.')},
          NameFirst: {required: Drupal.t('Voornaam is niet correct.')},
          NameLast: {required: Drupal.t('Achternaam is niet correct.')},
          AddressPostcode: {required: Drupal.t('Postcode is niet correct. Voorbeeld: 1234 AB.')},
          AddressHouseNumber: {required: Drupal.t('Het huisnummer is niet correct.')},
          ContactEmail: {
            required: Drupal.t('Het e-mailadres is niet correct.'),
            email: Drupal.t('Het e-mailadres is niet correct.'),
        },
          ContactPhone: {
            required: Drupal.t("Het telefoonnummer is niet correct"),
            minlength: Drupal.t("Nummer moet uit 10 cijfers bestaan."),
          }
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "LocationServices[KDV]" ) {
            error.insertAfter($("#edit-locationservices"));
          }
          else if (element.attr("name") == "PreferredDays[Monday]" ) {
            error.insertAfter($("#edit-preferreddays"));
          }
          else
          {
            error.insertAfter(element);
          }
        }
      });

      //check for label.error if = 0 show load amm
      $('#address-wrapper #edit-submit', context).click(function(event) {
          setTimeout(function(){
            var $errorLabelTour = $(".ss-location-tour-form").find("label.error:visible");
            if ( $errorLabelTour.length ) {
              $('.apend-error').remove();
              $("#edit-submit", context).before("<div class='apend-error'> "+
                "<span class='apend-error-span'>Er is iets niet - of onvolledig - ingevuld. </span> </div>");
               event.preventDefault();
              return false;
            }
            else {
              $('.apend-error').remove();
              $('.apend-load').remove();
              $(".ss-location-tour-form #edit-submit", context).after("<div class='apend-load'> "+
                "<span class='apend-text'><img src='/themes/smallsteps/images/load.gif'> Een ogenbik geduld...</span> </div>");
            }
        }, 100);
      });
      //end check for label.error

      //remove error if select
      $('#edit-no-preferences-day', context).click(function () {
        if ($('#edit-no-preferences-day:checked', context).length > 0 ){
        $("#preferences-wrapper .error", context).addClass( "preferences-error" );
        }
        else {
          $("#preferences-wrapper .error", context).removeClass( "preferences-error" );
        }
      });

      $('#edit-locationservices input[type="checkbox"]').change(function () {
        $("#edit-locationservices + label.error", context).hide();
      });

      $('#edit-preferreddays input[type="checkbox"]').change(function () {
        $("#edit-preferreddays + label.error", context).hide();
      });

      //end remove error if select

      $('#address-wrapper #edit-submit').click(function(e) {
        if ($('#address-wrapper input[name="NameTitle"]:checked').length == 0) {
          var scrollOffset = $('#address-wrapper').offset().top - 150;
          $(window).scrollTop(scrollOffset);
        }

      });
      $('#address-wrapper input[name="NameTitle"]').click(function(event) {
        $('#address-wrapper input[name="NameTitle"]').closest('.form-radios').find('label.error').remove();
      });
      // choose services, checkboxes are blocked if first step is completed
      $(servicesCheckboxes).click(function (event) {
        if ($(this).hasClass('disabled')) {
          event.preventDefault();
          return;
        }

        var servicesChecked = 0;
        $(servicesCheckboxes).each(function () {
          if (this.checked != false) {
            servicesChecked++;
          }
        });
        if (servicesChecked > 0) {
          $(servicesSubmit).removeClass('hidden');
        }
        else {
          $(servicesSubmit).addClass('hidden');
        }
      });

      // complete first step, show preferences section
      $(servicesSubmit).click(function (event) {
        $(servicesCheckboxes).addClass('disabled');
        $(preferencesDayContainer).removeClass('hidden');
        event.preventDefault();
        $(this).remove();
      });

      // choose preferences day
      $(preferencesDayCheckboxes).change(function () {
        var val = $(this).val();
        if ($(this).is(':checked')) {
          $(noPreferencesDayCheckbox).prop('checked', false);
          $(preferencesDayHours + '.day-' + val + '-hours').removeClass('hidden');
          $(preferencesDayHours + '.day-' + val + '-hours input[value="0"]').prop('checked', true);
        }
        else {
          $(preferencesDayHours + '.day-' + val + '-hours').addClass('hidden');
          $(preferencesDayHours + '.day-' + val + '-hours input').prop('checked', false);
        }
      });

      // if no preferences day chosen un-check all days and hours
      $(noPreferencesDayCheckbox).change(function () {
        if ($(this).is(':checked')) {
          $(preferencesDayCheckboxes).prop('checked', false);
          $(preferencesDayHours + ' input').prop('checked', false);
          $(preferencesDayHours).addClass('hidden');
        }
      });

      // show next button if we complete second step
      $(preferencesCheckboxes).change(function () {
        var preferencesChecked = 0;
        $(noPreferencesDayCheckbox).each(function () {
          if (this.checked != false) {
            preferencesChecked++;
          }
        });
        $(preferencesDayCheckboxes).each(function () {
          if (this.checked != false) {
            preferencesChecked++;
          }
        });
        if (preferencesChecked > 0) {
          $(preferencesSubmit).removeClass('hidden');
        }
        else {
          $(preferencesSubmit).addClass('hidden');
        }
      });

      // complete second step, show address section
      $(preferencesSubmit).click(function (event) {
        $(preferencesDayContainer).addClass('disabled');
        $(addressDayContainer).removeClass('hidden');
        event.preventDefault();
        $(this).remove();
      });

      // preferences checkboxes are blocked if second step is completed
      $(preferencesDayContainer).click(function (event) {
        if ($(this).hasClass('disabled')) {
          event.preventDefault();
        }
      });

      schedulesDates = $.parseJSON(schedulesDates);
      schedulesDateHours = $.parseJSON(schedulesDateHours);

      if (schedulesDates) {
        $.datepicker.setDefaults({
          closeText: 'Sluiten',
          currentText: 'Vandaag',
          monthNames: ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'],
          monthNamesShort: ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
          dayNames: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
          dayNamesShort: ['Zon', 'Maa', 'Din', 'Woe', 'Don', 'Vri', 'Zat'],
          dayNamesMin: ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
          weekHeader: 'Wk',
          dateFormat: 'yy-mm-dd',
          firstDay: 1,
          isRTL: false,
          showMonthAfterYear: false,
          yearSuffix: ''
        });

        $(preferencesDatepicker).datepicker({
          beforeShowDay: function (Date) {
            if ($.inArray($.datepicker.formatDate('yy-mm-dd', Date), schedulesDates) != -1) {
              return [true];
            }

            return [false];
          },
          minDate: 0,
          maxDate: '+3M',
          onSelect: function (DateText, Instance) {
            $('input[name="TourDate"]').val(DateText);
            $('input[name="TourTime"]').val(schedulesDateHours[DateText][$('.schedules-hours-' + DateText + ' input[type="radio"]').val()]);
            $('input[name="TourId"]').val($('.schedules-hours-' + DateText + ' input[type="radio"]:checked').val());
            $('.schedules-hours').addClass('hidden');
            $('.schedules-hours-' + DateText).removeClass('hidden');
          }
        });

        $('.ui-datepicker-today').click();
      }

      $('.schedules-hours input[type="radio"]').change(function () {
        $('input[name="TourTime"]').val(schedulesDateHours[$('input[name="TourDate"]').val()][$(this).val()]);
        $('input[name="TourId"]').val($(this).val());
      });

      $('input[name="AddressPostcode"], input[name="AddressHouseNumber"]').blur(function () {
        var Postcode = $('input[name="AddressPostcode"]').val();
        var HouseNumber = $('input[name="AddressHouseNumber"]').val();

        Postcode = Postcode.replace(/\s/g, '');
        HouseNumber = parseInt(HouseNumber);

        if (Postcode.length == 6 && HouseNumber) {
          $.post({
            url: "/address-generate",
            dataType: "json",
            async: false,
            data: {'postcode': Postcode, 'house_number': HouseNumber},
            success: function (data) {
              $('input[name="AddressStreet"]').val(data.street);
              $('input[name="AddressCity"]').val(data.city);
              $('input[name="AddressStreet"], input[name="AddressCity"]').removeClass('hidden');
            },
            error: function () {
              $('input[name="AddressStreet"], input[name="AddressCity"]').val('');
              $('input[name="AddressStreet"], input[name="AddressCity"]').addClass('hidden');
            }
          })
        } else {
          $('input[name="AddressStreet"], input[name="AddressCity"]').val('');
          $('input[name="AddressStreet"], input[name="AddressCity"]').addClass('hidden');
        }
      });


      $('input[name="TourScheduleOptout"]').change(function () {
        if ($(this).prop('checked')) {
          $(preferencesDatepicker).datepicker('option', 'disabled', true);
        }
        else {
          $(preferencesDatepicker).datepicker('option', 'disabled', false);
        }
      });
    }
  };



})(jQuery, Drupal);

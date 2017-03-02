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
        $("#edit-locationservices", context).next().remove();
      });

      $('#edit-preferreddays input[type="checkbox"]').change(function () {
        $("#edit-preferreddays", context).next().remove();
      });

      $('#edit-preferreddays #edit-addresspostcode').change(function () {
        $("#edit-addresspostcode", context).next().remove();
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

      $('input[name="AddressPostcode"], input[name="AddressHouseNumber"]').blur(function () {
        var Postcode = $('input[name="AddressPostcode"]').val();
        var HouseNumber = $('input[name="AddressHouseNumber"]').val();
        var Campaigin = $('input[name="CampaignId"]').val();

        Postcode = Postcode.replace(/\s/g, '');
        HouseNumber = parseInt(HouseNumber);

        if (Postcode.length == 6 && HouseNumber) {
          $.post({
            url: "/nearest-locations-generate",
            dataType: "json",
            async: false,
            data: {
              'postcode': Postcode,
              'house_number': HouseNumber,
              'campaign': Campaigin
            },
            success: function (data) {
              $('.page-node-type-generic-page .ss-tour-form .form-select').empty();
              for (i = 0; i < data.length; i++) {
                $('.page-node-type-generic-page .ss-tour-form .form-select').append( " <option> " + data[i].label + "</option>" );
              }
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

  Drupal.behaviors.TourFormOnGeneric = {
    attach: function(context, settings) {
      $('.ss-tour-form', context).validate({
        rules: {
          ContactPhone: {minlength: 10},
              field: {
            required: true
          },
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "LocationId" ) {
            error.insertAfter(".select2-container");
          } else {
            error.insertAfter(element);
          }
        },
        messages: {
          NameFirst: {required: Drupal.t('Voornaam is niet correct.')},
          LocationId: {required: Drupal.t('Kies een locatie.')},
          NameLast: {required: Drupal.t('Achternaam is niet correct.')},
          ContactEmail: {
            required: Drupal.t('Het e-mailadres is niet correct.'),
            email: Drupal.t('Het e-mailadres is niet correct.'),
        },
          ContactPhone: {
            required: Drupal.t("Het telefoonnummer is niet correct"),
            minlength: Drupal.t("Nummer moet uit 10 cijfers bestaan."),
          }
        },
      });

      //check for input.error if = 0 show load amm
      $('.ss-tour-form .form-submit', context).click(function() {
          setTimeout(function(){
            var $errorClasTour = $(".ss-tour-form", context).find("input.error:visible, select.error:visible ");
            if ( $errorClasTour.length ) {
              $('.apend-error').remove();
              $(".ss-tour-form .form-submit", context).before("<div class='apend-error'> "+
                "<span class='apend-error-span'>Er is iets niet - of onvolledig - ingevuld. </span> </div>");
            }
            else {
              $('.apend-error').remove();
              $('.apend-load').remove();
              $(".ss-tour-form .form-submit", context).after("<div class='apend-load'> "+
                "<span class='apend-text'><img src='/themes/smallsteps/images/load.gif'> Een ogenbik geduld...</span> </div>");
            }
        }, 100);
      });
      //end check for input.error

    }
  };




})(jQuery, Drupal);

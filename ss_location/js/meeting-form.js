(function ($, Drupal) {
  Drupal.behaviors.ocbijeenkomstForm = {
    attach: function (context, settings) {

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
      //end Method for validate plugin

      //validate plugin
      $('form.ss-location-questions-suggestions-form', context).validate({
        ignore: [],
        rules: {
           AddressStreet: {
            required: false
          },
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
            required: Drupal.t("Het telefoonnummer is niet correct."),
            minlength: Drupal.t("Nummer moet uit 10 cijfers bestaan."),
          },
          LocationId: Drupal.t("Kies een locatie."),
          Remarks: Drupal.t("Geef aan waarover je wilt praten."),
        },
      });
      // end validate plugin

      //check for label.error if = 0 show load amm
      $('#edit-submit', context).click(function(event) {
          setTimeout(function(){
            var $errorLabelTour = $(".ss-location-questions-suggestions-form").find("label.error:visible");
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
              $(".ss-location-questions-suggestions-form #edit-submit", context).after("<div class='apend-load'> "+
                "<span class='apend-text'><img src='/themes/smallsteps/images/load.gif'> Een ogenbik geduld...</span> </div>");
            }
        }, 100);
      });
      //end check for label.error

      //remove error if select
      // $('#edit-locationid').change(function () {
      //   $("#edit-locationid", context).next().remove();
      // });

      $('#address-wrapper input[name="NameTitle"]').click(function(event) {
        $('#address-wrapper input[name="NameTitle"]').closest('.form-radios').find('label.error').remove();
      });
      //end remove error if select

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
    }
  };


})(jQuery, Drupal);

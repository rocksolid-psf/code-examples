(function ($, Drupal) {
  Drupal.behaviors.registrationForm = {
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

      // Date
      var calendarSettings = {
        changeMonth: true,
        changeYear: true,
        minDate: 0
      }
      if ($('input[name="ChildBirthDate"]').length && settings.ss_location && settings.ss_location.registration.service == "PSZ"
        || $('.ss-location-registration-form #edit-locationservices-psz').length) {
        // Only 1st and 15th days are allowed (nearest 10 years)
        var enableDays  = [],
            currentYear = new Date().getFullYear(),
            maxYear     = currentYear + 10;
        for (var i = 1; i <= 12 && currentYear <= maxYear; i++) {
          enableDays.push(1  + '-' + i + '-' + currentYear);
          enableDays.push(15 + '-' + i + '-' + currentYear);
          if (i == 12) {
            i = 0;
            currentYear++;
          }
        }
        function enableAllTheseDays(date) {
          var sdate = $.datepicker.formatDate( 'd-m-yy', date)
          if($.inArray(sdate, enableDays) != -1) {
            return [true];
          }
          return [false];
        }
      }
      // Datepicker init
      $('input[name="PlacementDate"]').datepicker(calendarSettings);
      // If PSZ checked by default
      if ($('.ss-location-registration-form #edit-locationservices-psz:checked').length ||
         settings.ss_location && settings.ss_location.registration.service == "PSZ") {
        $('input[name="PlacementDate"]').datepicker('destroy');
        calendarSettings.beforeShowDay = enableAllTheseDays;
        $('input[name="PlacementDate"]').datepicker(calendarSettings);
      }
      // Reinit on click
      if ($('.ss-location-registration-form #edit-locationservices-psz').length) {
        $('.ss-location-registration-form .form-item-locationservices input').click(function() {
          if ($('.ss-location-registration-form #edit-locationservices-psz:checked').length) {
            $('input[name="PlacementDate"]').datepicker('setDate', null);
            $('input[name="PlacementDate"]').datepicker('destroy');
            calendarSettings.beforeShowDay = enableAllTheseDays;
            $('input[name="PlacementDate"]').datepicker(calendarSettings);
          } else {
            $('input[name="PlacementDate"]').datepicker('setDate', null);
            $('input[name="PlacementDate"]').datepicker('destroy');
            delete calendarSettings.beforeShowDay;
            $('input[name="PlacementDate"]').datepicker(calendarSettings);
          }
        });
      }

      var AddressStreet = $('input[name="AddressStreet"]').val();
      var AddressCity = $('input[name="AddressCity"]').val();

      if (AddressStreet.length > 0 && AddressCity.length > 0) {
        $('input[name="AddressStreet"], input[name="AddressCity"]').removeClass('hidden');
      }

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
              $('span.AddressStreet').html(data.street);
              $('span.AddressCity').html(data.city);
              $('input[name="AddressStreet"], input[name="AddressCity"]').removeClass('hidden');
              document.getElementById("edit-addresspostcode").setCustomValidity('');
              document.getElementById("edit-addresshousenumber").setCustomValidity('');
            },
            error: function () {
              $('input[name="AddressStreet"], input[name="AddressCity"]').val('');
              $('input[name="AddressStreet"], input[name="AddressCity"]').addClass('hidden');
              $('span.AddressStreet').html('');
              $('span.AddressCity').html('');
              document.getElementById("edit-addresspostcode").setCustomValidity(Drupal.t("De postcode of het huisnummer is niet goed"));
              document.getElementById("edit-addresshousenumber").setCustomValidity(Drupal.t("De postcode of het huisnummer is niet goed"));
            }
          })
        } else {
          $('input[name="AddressStreet"], input[name="AddressCity"]').val('');
          $('input[name="AddressStreet"], input[name="AddressCity"]').addClass('hidden');
          $('span.AddressStreet').html('');
          $('span.AddressCity').html('');
          document.getElementById("edit-addresspostcode").setCustomValidity(Drupal.t("De postcode of het huisnummer is niet goedhe Postcode or House Number is not correct"));
          document.getElementById("edit-addresshousenumber").setCustomValidity(Drupal.t("De postcode of het huisnummer is niet goed"));
        }
      });

      $('input[name="NameFirst"], ' +
        'input[name="NameLast"], ' +
        'input[name="AddressHouseNumber"], ' +
        'input[name="AddressAdditional"], ' +
        'input[name="AddressPostcode"], ' +
        'input[name="ContactPhone"], ' +
        'input[name="ContactEmail"], ' +
        'input[name="ChildNameFirst"], ' +
        'input[name="ChildNameLast"], ' +
        'input[name="ChildBirthDate"], ' +
        'input[name="PlacementDate"]').change(function () {
        var spanClass = $(this).attr('name');
        var value = $(this).val();
        $('span.' + spanClass).html(value);
        $(this).removeClass('error');
        $(this).next('label.error').remove();
      });

      $('input[name="ChildGender"]').change(function () {
        $('input[name="ChildGender"]:checked').each(function() {
          var idVal = $(this).attr("id");
          $('span.ChildGender').html($("label[for='"+idVal+"']").text());
        });
      });

      $('input[name="ChildGender"]:checked').each(function() {
        var idVal = $(this).attr("id");
        $('span.ChildGender').html($("label[for='"+idVal+"']").text());
      });

      $('input[name="LocationServices"]').change(function () {
        $('input[name="LocationServices"]:checked').each(function() {
          var idVal = $(this).attr("id");
          $('span.LocationServices').html($("label[for='"+idVal+"']").text());
        });
      });

      $('input[name="LocationServices"]:checked').each(function() {
        var idVal = $(this).attr("id");
        $('span.LocationServices').html($("label[for='"+idVal+"']").text());
      });

      $('#edit-placementdays input').change(function () {
        var html = '';
        $('#edit-placementdays input:checked').each(function() {
          var idVal = $(this).attr("id");
          html = html + ' ' + $("label[for='"+idVal+"']").text()
          $('span.PlacementDays').html(html);
        });
      });

      var html = '';
      $('#edit-placementdays input:checked').each(function() {
        var idVal = $(this).attr("id");
        html = html + ' ' + $("label[for='"+idVal+"']").text()
        $('span.PlacementDays').html(html);
      });
    }
  };

  Drupal.behaviors.disableIfChecked = {
    attach: function (context, settings) {
      this.init('#edit-childnameoptout', '#edit-childnamefirst', 'span.ChildNameFirst', context);
    },
    check: function(checkbox, disabledInput, span, context) {
      if ($(checkbox, context).is(':checked')) {
        var $onbekendVal = $(checkbox, context).next().text();
        $(disabledInput, context).attr('disabled', 'disabled');
        $(disabledInput, context).val($onbekendVal);
        $(span, context).html($onbekendVal);
      } else {
        $(disabledInput, context).val('');
        $(disabledInput, context).removeAttr('disabled');
        $(disabledInput, context).focus();
      }
    },
    init: function(checkbox, disabledInput, span, context){
      var $thisObj = this;
      $(checkbox, context).click(function() {
        $thisObj.check(checkbox, disabledInput, span, context);
      });
    }
  };

  Drupal.behaviors.showIfSelected = {
    attach: function (context, settings) {
      this.init(true, '#edit-locationservices', '#edit-locationservices-bso', '.bso-services .subheader:first-child, #edit-locationservicebso, #ss-location-registration-form-step-3 .school-question', '#edit-locationservicebso input', context);
      this.additionalCondition('#edit-locationservicebso', '#edit-locationservicebso-tso', '#edit-locationservicebso-nso:checked, #edit-locationservicebso-vso:checked', '#ss-location-registration-form-step-3 .form-item-placementdays-wednesday', false, context);
    },
    checkShow: function(conditionIfChecked, checkedEl, shownEl, disabledEl, context) {
      if ($(checkedEl, context).prop('checked') == conditionIfChecked) {
        $(shownEl, context).removeClass('hidden');
        $(disabledEl, context).removeAttr('disabled');
      } else {
        $(shownEl, context).addClass('hidden');
        $(disabledEl, context).attr('checked', false);
        $(disabledEl, context).attr('disabled', 'disabled');
      }
    },
    additionalCondition: function(clickedEl, el, checkedEl, showEl, conditionIfChecked, context) {
      var $thisObj = this;
      $(clickedEl, context).click(function() {
        if ($(el,context).length == 0 || $(checkedEl, context).length >= 1 && $(el, context).prop('checked') == true) {
          $(showEl, context).removeClass('hidden');
          $(showEl, context).removeAttr('disabled');
        } else {
          $thisObj.checkShow(conditionIfChecked, el, showEl, showEl, context);
        }
      });
    },
    init: function(conditionIfChecked, clickedEl, checkedEl, shownEl, disabledEl, context){
      var $thisObj = this;
      $thisObj.checkShow(conditionIfChecked, checkedEl, shownEl, disabledEl, context);
      $(clickedEl, context).click(function() {
        $thisObj.checkShow(conditionIfChecked, checkedEl, shownEl, disabledEl, context);
      });
    }
  };

  Drupal.behaviors.postCode = {
    attach: function (context, settings) {
      $('#edit-addresspostcode, #edit-addresshousenumber').on('input', function () {
        $('.error-postcode, .new-required-fields label.error').remove();
        $('.additional-required-fields input, .new-required-fields input').removeClass('error');
      });
    },
  };

    Drupal.behaviors.hideIfCheced = {
    attach: function(context, settings) {
      this.init('#ss-location-registration-form-step-5 .yes-no',
                '#ss-location-registration-form-step-5 #edit-childadd input:checked',
                '#ss-location-registration-form-step-5 > .actions #edit-submit', context);
    },
    ifCheced: function(el, elToHide, context) {
      if($(el, context).length == 1) {
        $(elToHide, context).show();
      } else {
        $(elToHide, context).hide();
      }
    },
    init: function(elClick, el, elToHide, context) {
      var thisObj = this;
      $(elClick, context).click(function() {
        thisObj.ifCheced(el, elToHide, context);
      });
    }
  };

  Drupal.behaviors.AddRequiredForCheckBoxSteps2 = {
    attach: function(context, settings) {
      $("#ss-location-registration-form-step-2 .read-more-link", context).click(function(event) {
        if($('#edit-childgender input[type="radio"]:checked', context).length==0)
        {
          $("#edit-childgender", context).next().remove();
          $("#edit-childgender", context).after('<label class="error disabled er-cust1">' + Drupal.t('Dit veld is verplicht.') + '</label>');

        }else {
          $("#edit-childgender", context).next().remove();
        }
        if($('#edit-childadditional input[type="radio"]:checked', context).length==0)
        {
          $("#edit-childadditional", context).next().remove();
          $("#edit-childadditional", context).after('<label class="error disabled er-cust2">' + Drupal.t('Dit veld is verplicht.') + '</label>');
        }else {
          $("#edit-childadditional", context).next().remove();
        }
      });
      $('#edit-childgender input[type="radio"]').change(function () {
        $("#edit-childgender", context).next().remove();
      });
      $('#edit-childadditional input[type="radio"]').change(function () {
        $("#edit-childadditional", context).next().remove();
      });
    }
  };

    Drupal.behaviors.AddRequiredForCheckBoxSteps3 = {
    attach: function(context, settings) {
      $("#ss-location-registration-form-step-3 .read-more-link", context).click(function(event) {
        if($('#edit-placementdays input[type="checkbox"]:checked', context).length==0)
        {
          $("#edit-placementdays", context).next().remove();
          $("#edit-placementdays", context).after('<label class="error disabled er-cust1">' + Drupal.t('Dit veld is verplicht.') + '</label>');

        }else {
          $("#edit-placementdays", context).next().remove();
        }
      });
      $('#edit-placementdays input[type="checkbox"]').change(function () {
        $("#edit-placementdays", context).next().remove();
      });
    }
  };

  Drupal.behaviors.registerTabs = {
    attach: function (context, settings) {
      this.skipStep('#ss-location-registration-form-step-1', '.registration-steps span', '.registration-steps .step-' )
      this.disabledElements('.registration-steps span.active', 'disabled', context);
      this.formStepSubmit('.registration-form .read-more-cl', '.step-', '.registration-steps span', context);
      this.tabSwitch('.registration-steps span', '#ss-location-registration-form-step-', context);
      this.backButton('#ss-location-registration-form-step-5 .subheader a', '#ss-location-registration-form-step-', '.registration-steps .step-', context);
    },
    tabSwitch: function(el, regionToShow, context) {
      $(el, context).click(function(event) {
        var $this = $(this, context);
        if ($this.hasClass('active') || $this.hasClass('disabled')) {
          event.preventDefault();
          return false;
        } else {
          $(el, context).removeClass('active');
          $this.addClass('active');
          $this.addClass('disabled');
          $this.nextAll().addClass('disabled');

          var currentRegionId = $this.attr('data-tab');
          $('*[id^="ss-location-registration-form-step-"]').not('.hidden').addClass('hidden');
          $(regionToShow+currentRegionId).removeClass('hidden');
        }
      });
    },
    disabledElements: function(el, classDis, context) {
      $(el, context).nextAll().addClass(classDis);
    },
    skipStep: function(el, allTabs, activeTab, context) {
      $el = $(el, context);
      var elDataStep = $el.attr('data-step');
      elDataStep++;
      if ($el.hasClass('skip')) {
        $el.addClass('hidden');
        $el.next().removeClass('hidden');

        $(allTabs, context).removeClass('active');
        $(activeTab+elDataStep, context).prevAll().removeClass('disabled');
        $(activeTab+elDataStep, context).addClass('disabled active');
      }
    },
    formStepSubmit: function(el, tab, allTabs, context) {
      var $thisObj = this;
      $(el, context).click(function(event) {
        var $this = $(this);
        var parentFormStep = $this.closest('*[id^="ss-location-registration-form-step-"]');
        var requiredEmptyFields = parentFormStep.find('input:required');

        var emailField = parentFormStep.find('input[type="email"]');
        var radioReq = parentFormStep.find('input[type="radio"]');
        var i = false;

        $(requiredEmptyFields).each(function() {
          $this = $(this, context);
          var emptyFieldLenght = $(this).val();
          i += !emptyFieldLenght;
          var a = !emptyFieldLenght;
          if (a==0) {
            $this.removeClass('error');
            $this.next().remove();
          } else {
            $this.addClass('error');
            $this.next().remove();
            $('.error-postcode').remove();
            $this.after('<label class="error">' + Drupal.t('Dit veld is verplicht.') + '</label>');
            if ($('#edit-addressstreet').hasClass('error') || $('#edit-addresscity').hasClass('error')) {
              $('.error-postcode').remove();
              $('#edit-addresspostcode, #edit-addresshousenumber').addClass('error');
              $('.additional-required-fields').after('<label class="error error-postcode">' + Drupal.t('Postcode is niet correct. Voorbeeld: 1234 AB.') + '</label>');
            }
          }
        });

        //check for label.error
        var $errorLabel = $("[id*='ss-location-registration-form-step-']").not(".hidden" ).find("label.error:visible");
        if ( $errorLabel.length ) {
          $('.apend-error').remove();
          $(".actions", context).prepend("<div class='apend-error'> "+
            "<span class='apend-error-span'>Er is iets niet - of onvolledig - ingevuld. </span> </div>");
            event.preventDefault();
            return false;
        } else {
          $('.apend-error').remove();
        }
        //end check for label.error

        // email validate
        if (emailField.length) {
          var x = emailField.val();
          var atpos = x.indexOf("@");
          var dotpos = x.lastIndexOf(".");
          if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
            emailField.removeClass('error');
            emailField.next('label').remove();
            emailField.addClass('error');
            emailField.after('<label class="error error-mail">' + Drupal.t('Het e-mailadres is niet correct.') + '</label>');
            return false;
          }
        }
        if (requiredEmptyFields.length >= 1 && i != 0) {
          return false;
        } else {
          var formStepId = parentFormStep.attr('data-step');
          emailField.next('label').remove();
          $('.error-postcode').remove();
          requiredEmptyFields.removeClass('error');
          formStepId++;
          $(allTabs, context).removeClass('active');
          $(tab+formStepId, context).prevAll().removeClass('disabled');
          $(tab+formStepId, context).addClass('disabled active');

          parentFormStep.addClass('hidden');
          parentFormStep.next().removeClass('hidden');

          $thisObj.scrollTop(80);
        }
      });
    //remove errore when click on tabs
     $('.registration-steps span').click(function() {
        $('.apend-error').remove();
     });

    },
    backButton: function(el, region, tab, context) {
      var $thisObj = this;
      $(el, context).click(function(event) {
        var $this = $(this, context);
        var stepId = $this.attr('data-step');
        $(tab+stepId, context).addClass('disabled active');
        $(tab+stepId, context).nextAll().addClass('disabled');
        $(tab+stepId, context).nextAll().removeClass('active');

        $this.closest('*[id^="ss-location-registration-form-step-"]').addClass('hidden');
        $(region+stepId, context).removeClass('hidden');
        $thisObj.scrollTop(80);
      });
    },
    scrollTop: function(val) {
      var $root = $('html, body');
      $root.animate({
        scrollTop: val
      }, 400);
    }
  };


  Drupal.behaviors.OpenSecondTab= {
    attach: function(context, settings) {
      var additional = "#additional",
          hashAdditional = location.hash;
      if (hashAdditional == additional ) {
        $('#ss-location-registration-form-step-1 .read-more-link ').click();
      }
    }
  };
  //   Drupal.behaviors.NoSubmit = {
  //   attach: function(context, settings) {
  //     $("#ss-location-registration-form-step-2 .read-more-link", context).click(function(event) {
  //     if($('#edit-childgender input[type="radio"]:checked, #edit-childadditional input[type="radio"]:checked ', context).length <=1 )
  //     {
  //        cancelButton[0].onclick = null;
  //     }
  //     });
  //   }
  // };



})(jQuery, Drupal);

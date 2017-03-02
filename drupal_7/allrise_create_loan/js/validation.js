(function ($) {

  function CheckErrorUserData(){
  
    // handle visible state
    var required = $('input.ufield').length;
    var validated = $('input.ufield.error').length;
    var require = $('input.uffield').length;
    var validate = $('input.uffield.error').length;
    var checkboxes = $('input.form-checkbox').length;
    var errors = 0;
    var checkError = 0;

    if (required > 0) {
      $.each($('input.ufield'), function(i, element){
         var text_value=$(element).val();
         if(text_value == '') {
            errors++;
         }
      });
    }
    if (validated > 0) {
      $.each($('input.ufield.error'), function(i, element){
        var text_value=$(element).val();

          errors++;
       });
    }

    console.log('err = ' + errors);
    if (errors > 0) {
      $('input[name="btn_submit_user_data"]').prop("disabled", true);
      console.log('disabled');
    }
    else {      
      console.log('not disabled');
      $('input[name="btn_submit_user_data"]').prop("disabled", false);
      errors =0;
    
    }
  };

  Drupal.behaviors.createLoanValidation = {
    attach: function (context, settings) {

      $('input.move-up-btn').mousedown(function(){
        $('html, body').animate({scrollTop : 0},400);
        return false;
      });

      jQuery.fn.addErrorView =
      function(){
        return this.each(function()
        {
          $(this).parent().parent().find('.ico').removeClass('verify');
          $(this).parent().parent().find('.ico').addClass('error');
          $(this).addClass('error');
        });
      };

      jQuery.fn.removeErrorView =
      function(){
        return this.each(function()
        {
          $(this).parent().parent().find('.ico').addClass('verify');
          $(this).parent().parent().find('.ico').removeClass('error');
          $(this).removeClass('error');
        });
      };

      jQuery.fn.ForceNumericOnly =
      function(){
        return this.each(function()
        {
          $(this).keydown(function(e)
          {
            var key = e.charCode || e.keyCode || 0;
            // Разрешаем backspace, tab, delete, стрелки, обычные цифры и цифры на дополнительной клавиатуре
            return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
                (key >= 37 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
          });
        });
      };
    
      function isDate(txtDate, validElem){
        var currVal = txtDate,
            validEl = validElem,
            birthEl = $('.allrise-borrower_register-step1-wrapper .form-item-birthdate'),
            issueEl = $('.allrise-borrower_register-step1-wrapper .form-item-issue-date');

        if(currVal == '' || validEl == '')
            return false;
        
        var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
        var dtArray = currVal.match(rxDatePattern); // is format OK?
        
        if (dtArray == null) 
            return false;
        
        //Checks for dd/mm/yyyy format.
        dtDay = dtArray[1];
        dtMonth = dtArray[3];
        dtYear = dtArray[5];        
        console.log('dtDay '+dtDay+' dtMonth '+dtMonth+' dtYear '+dtYear);
        if (dtMonth < 1 || dtMonth > 12) {
            return false;
        }
        else if (dtDay < 1 || dtDay> 31) {
            return false;
        }
        else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31){
          if (validEl == 1) {
            $(birthEl).find('select[name="birthdate[day]"]').val('30');
            return true;
          }
          else if (validEl == 2) {
            $(issueEl).find('select[name="issue_date[day]"]').val('30');
            return true;
          }
          else{
            return false;
          };
            
        }
        else if (dtMonth == 2) 
        {
          
          var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
          if (dtDay > 29 || dtDay ==29){
            if(!isleap){
              if (validEl == 1) {
                $(birthEl).find('select[name="birthdate[day]"]').val('28');
                return true;
              }
              else if (validEl == 2) {
                $(issueEl).find('select[name="issue_date[day]"]').val('28');
                return true;
              }
              else{
                return false;
              };
            }
            else{
              if (validEl == 1) {
                $(birthEl).find('select[name="birthdate[day]"]').val('29');
              }
              else if (validEl == 2){
                $(issueEl).find('select[name="issue_date[day]"]').val('29');  
              }
              else{
                return false;
              }
              
              return true;
            }
            
          }
          
        }
        return true;
      };

      function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
      };

      function CheckErrors(){
        var required = $('input.rfield').length;
        var validated = $('input.rfield.error').length;
        var checkboxes = $('input.form-checkbox').length;
        var errors = 0;
        var checkError = 0;

        if (required > 0) {
          $.each($('input.rfield'), function(i, element){
             var text_value=$(element).val();
             if(text_value == '') {
                errors++;
             }
          });
        }
        if (validated > 0) {
          $.each($('input.rfield.error'), function(i, element){
            var text_value=$(element).val();
              errors++;
           });
        }

        console.log('err = ' + errors);
        if (errors > 0) {
          $('input[name="create-user-and-loan"]').prop("disabled", true);
        }
        else {
          var firstCheck = $('input[name="confirmation1"]:checkbox:checked'),
              secondCheck = $('input[name="confirmation2"]:checkbox:checked'),
              thirdCheck = $('input[name="confirmation3"]:checkbox:checked');
          if (firstCheck.val() == 1 && secondCheck.val() == 1 && thirdCheck.val() == 1) {
            $('input[name="create-user-and-loan"]').prop("disabled", false);
            errors =0;
          }
        }
      };


      function CheckErrorPledgeData(){
  
        // handle visible state
        var required = $('.pfield').length;
        var validated = $('.pfield.error').length;
        var errors = 0;
        var checkError = 0;

        if (required > 0) {
          $.each($('.pfield'), function(i, element){
             var text_value=$(element).val();
             if(text_value == '') {
                errors++;
             }
          });
        }
        if (validated > 0) {
          $.each($('.pfield.error'), function(i, element){
            var text_value=$(element).val();

              errors++;
           });
        }

        console.log('err = ' + errors);
        if (errors > 0) {
          $('input[name="create_and_register"]').add('input[name="create_borrower_loan"]').prop("disabled", true);
          console.log('disabled');
        }
        else {      
          console.log('not disabled');
          $('input[name="create_and_register"]').add('input[name="create_borrower_loan"]').prop("disabled", false);
          errors =0;
        
        }
      };
      CheckErrors();
     

      // function CheckValidation() {

      //   $('input[name="phone_code"]').add('input[name="phone_num"]').ForceNumericOnly();
      //   $('input[name="email"]').on('keyup', function(){
          
      //     console.log($('input[name="phone_code"]').val() + $('input[name="phone_num"]').val());
      //     var mail = $('input[name="email"]').val(); // переменная с имеилом

      //     $.ajax({
      //       type: 'POST',
      //       url: '/usermail_page_post', // ссылка на какую страницу передавать POST
      //       data: { mail : mail },
      //       dataType: 'html',
      //       async: false,
      //       success: function(data){
      //         var data = data;
      //         console.log(data);
      //         // event.preventDefault(); // чтоб отключить сабмит на время проверки.
      //           if(data > 0){
      //             $('input[name="email"]').parent().parent().find('.ico').removeClass('verify');
      //             $('input[name="email"]').parent().parent().find('.ico').addClass('error');
      //             $('input[name="email"]').addClass('error');
      //           }
      //           else {
      //             $('input[name="email"]').parent().parent().find('.ico').addClass('verify');
      //             $('input[name="email"]').parent().parent().find('.ico').removeClass('error');
      //             $('input[name="email"]').removeClass('error');
      //           }

      //         // return true; // запускаем сабмит дальше.
      //       }
      //     });

      //   });
        
      // };

      function CheckUserAggr(){
        // if($("#isAgeSelected").is(':checked'))
        //     $("#txtAge").show();  // checked
        // else
        //     $("#txtAge").hide();
        var firstCheck = $('input[name="confirmation1"]:checkbox:checked'),
            secondCheck = $('input[name="confirmation2"]:checkbox:checked'),
            thirdCheck = $('input[name="confirmation3"]:checkbox:checked');
        if (firstCheck.val() == 1 && secondCheck.val() == 1 && thirdCheck.val() == 1) {
          // $('input[name="create-user-and-loan"]').prop("disabled", false);
        }
        else{
          // $('input[name="create-user-and-loan"]').prop("disabled", true);
        };
      };


      // CheckValidation();
      var formPhone = $('.allrise-borrower_register-step1-wrapper .form-item-phone-code');
      var formCountryCode = $(".allrise-borrower_register-step1-wrapper .form-item-country-code");
      $(formPhone).find(".ru.form-text").mask("7 (999) 999-9999");
      $(formPhone).find(".ua.form-text").mask("38 (999) 999-99-99");

      $(formCountryCode).find('select[name="country_code"]').on('change', function(){
        var selectCode = $(this).val(),
            inputEl = $(formPhone).find('input[name="phone_code"]');
            console.log('selCode '+selectCode);
        if (!$(inputEl).hasClass(selectCode)){
          $(formCountryCode).parents('.form-item.mob-phone').find('.country-ico').removeClass('ua').removeClass('ru').addClass(selectCode);
          $(inputEl).removeClass('ua').removeClass('ru').addClass(selectCode);
          $(formPhone).find(".ru.form-text").mask("7 (999) 999-9999");
          $(formPhone).find(".ua.form-text").mask("38 (999) 999-99-99");
        }
      });
      var errors = 0;
      var jVal = {
        'uCode' : function() {

          var PhoneCode = $('input[name="phone_code"]').val(),
              // PhoneNum = $('input[name="phone_num"]').val(),
              field = $('input[name="phone_code"]');

          // var patt = /[0-9_-]{5}$/i,
          //     patt1 = /[0-9_-]{12}$/i;
          // // $(field).keyup(function() {
          // if (field.val().length > 12){
          //   this.value = this.value.substr(0, 12);
          // };
          // if (!patt1.test(PhoneCode)) {
          //   field.addErrorView();
          // }
          
            console.log('pattern');
            var u_phone = $('input[name="phone_code"]').val();
          if($('input[name="phone_code"]').val()){
            $.ajax({
              type: 'POST',
              url: '/userphone_page_post', // ссылка на какую страницу передавать POST
              data: { u_phone : u_phone },
              dataType: 'html',
              async: false,
              success: function(data){
                var data = data;
                  if(data > 0){
                    field.addErrorView();
                    $('.mob-phone .field-answer').show('slow');
                  }
                  else {
                    field.removeErrorView();
                    $('.mob-phone .field-answer').hide('slow');
                  }
              }
            });
          }
          else{
            field.addErrorView();
          };
          CheckErrors();
        },

        // 'uPhone' : function() {

        //   var PhoneCode = $('input[name="phone_code"]').val(),
        //       PhoneNum = $('input[name="phone_num"]').val(),
        //       field = $('input[name="phone_num"]');

        //   var patt = /[0-9_-]{5}$/i,
        //       patt1 = /[0-9_-]{3}$/i;

        //   if(!patt.test(PhoneNum) && !patt1.test(PhoneCode)) {
        //     $('input[name="phone_num"]').parent().parent().find('.ico').removeClass('verify');
        //     $('input[name="phone_num"]').parent().parent().find('.ico').addClass('error');
        //     $('input[name="phone_num"]').addClass('error');
        //     console.log('r1');
        //   }
        //   else {
        //     $('input[name="phone_num"]').parent().parent().find('.ico').addClass('verify');
        //     $('input[name="phone_num"]').parent().parent().find('.ico').removeClass('error');
        //     $('input[name="phone_num"]').removeClass('error');
        //     console.log('no error');
        //   };
        //   if (!patt.test(PhoneNum) && !patt1.test(PhoneCode)) {
        //     field.addErrorView();
        //   }
        //   else{

        //     var u_phone = $('input[name="phone_code"]').val() + $('input[name="phone_num"]').val();

        //     $.ajax({
        //       type: 'POST',
        //       url: '/userphone_page_post', // ссылка на какую страницу передавать POST
        //       data: { u_phone : u_phone },
        //       dataType: 'html',
        //       async: false,
        //       success: function(data){
        //         var data = data;

        //           if(data > 0){
        //             field.addErrorView();
        //           }
        //           else {
        //             field.removeErrorView();
        //           }
        //       }
        //     });
        //   }
          
        //   CheckErrors();
        // },
      };

      /*form validation events.*/
      $("#allrise-create-loan-wrapper >form").once(function () {

          $('input[name="corp_mfo"]').on('change', function(){
          if ($(this).val().length) {
            var bankDetails = $.getBankDetailsData($(this).val()); //helper func getBankDetailsData[theme/additional_pages_functions]
            if (bankDetails[0] != 'error') {
              $('input[name="bank"]').val(bankDetails[0]);
              $('input[name="bank_account"]').val(bankDetails[1]);
            }
            else{
              $('input[name="bank"]').val("");
              $('input[name="bank_account"]').val("");
            };
            
          };    
        });

        /*check checkbox onload*/
        if ($('input[name="reg_place_check"]:checkbox:checked').val() == 1) {
            $('.residence-address-wrapper').slideUp();
            $('.residence-address-wrapper input').removeClass('ufield');
            CheckErrorUserData();
        }

        /*checkbox click*/
        $('input[name="reg_place_check"]').on('click', function(event){
          if ($('input[name="reg_place_check"]:checkbox:checked').val() == 1) {
            $('.residence-address-wrapper').slideUp();
            $('.residence-address-wrapper input').removeClass('ufield');
            CheckErrorUserData();
          }
          else{
            $('.residence-address-wrapper').slideDown();
            $('.residence-address-wrapper input').show();
            $('.residence-address-wrapper input').addClass('ufield');
            CheckErrorUserData();
          };
        });
        /*phonecheck*/
        $('input[name="phone_code"]').add('input[name="phone_num"]').add('input.numeric').ForceNumericOnly();
        $('input[name="phone_code"]').change(jVal.uCode);
        // $('input[name="phone_num"]').blur(jVal.uPhone);
        /* email*/
        $('input[name="email"]').on('input', function() {
          var mail = $('input[name="email"]').val(),
            field = $('input[name="email"]');
          if( !validateEmail(mail)) { 
            field.addErrorView();
          }
          else{
            field.removeErrorView();
            var mail = $('input[name="email"]').val(); // переменная с имеилом

            $.ajax({
              type: 'POST',
              url: '/usermail_page_post', // ссылка на какую страницу передавать POST
              data: { mail : mail },
              dataType: 'html',
              async: false,
              success: function(data){
                var data = data;
                  if(data > 0){
                    field.addErrorView();
                    $('.e-mail .field-answer').show('slow');
                  }
                  else {
                    field.removeErrorView();
                    $('.e-mail .field-answer').hide('slow');
                  }
              }
            });
          }  
          CheckErrors();
        });
        //all inputs. val check
        $('.form-item .rfield.nfield').on('keyup', function() {
          
          if (!this.value) {
            $(this).addErrorView();  
          }
          else{
            $(this).removeErrorView();
          };
          CheckErrors();
        });
        //pass
        $('input[name="password"]').on('keyup', function() {

          var field = $('input[name="password"]');

          if(field.val().length < 6){
            field.addErrorView();
          } 
          else{
            field.removeErrorView();
          }
          CheckErrors();
        });
        //pass confirm checkout
        $('input[name="password_confirm"]').on('keyup', function() {
          
          var pass1 = $('input[name="password_confirm"]').val();
          var pass2 = $('input[name="password"]').val(),
              field = $('input[name="password_confirm"]');

          if(pass1!=pass2){
            field.addErrorView();
          } 
          else{
            field.removeErrorView();
          }
          CheckErrors();
        });
        //chekboxes 
        $('input.form-checkbox').click(function(){
          $(this).toggleClass('active');
          CheckErrors();
        });

        /*step1 validation*/
        $('.form-type-textfield input.ufield').on('keyup', function() {  
          
          if( $(this).val().length < 1 ){
            $(this).addErrorView();
          }
          else  {
            $(this).removeErrorView();
          };
          CheckErrorUserData();
        }); 

        // $('.form-type-textfield input.uffield').on('keyup', function() {  
          
        //   if( $(this).val().length < 1 ){
        //     $(this).addErrorView();
        //   }
        //   else  {
        //     $(this).removeErrorView();
        //   };
        //   CheckErrorUserData();
        // }); 

        /*step3 validation*/
        $('.form-type-textfield input.pfield').add('.form-type-textarea textarea.pfield').on('keyup', function() {  
          
          if( $(this).val().length < 1 ){
            $(this).addErrorView();
          }
          else  {
            $(this).removeErrorView();
          };
          CheckErrorPledgeData();
        }); 

        CheckErrors();
        CheckErrorUserData();
        CheckErrorCalculations();
        CheckErrorPledgeData();
        $('#allrise-create-loan-wrapper .form-content').on('slidestop', '.slider', function(event, ui) {
          CheckErrorCalculations();
        });
        $('.allrise-car-params-loan-wrapper .form-type-textfield input').on('keyup', function() {
          CheckErrorCalculations();
        });
        $('#first_slider .max-value').on('change', function(){
          console.log('max_changed');
          CheckErrorCalculations();
        });

        $('.tooltip').on('click', function(event) {
          event.preventDefault();
          
          $(this).toggleClass('active').parent().parent().find('.tooltip-description').slideToggle(200);
        });

        /*validate date selectors*/
        /*birthdate*/
        $('.allrise-borrower_register-step1-wrapper .form-item-birthdate select.selecter').on('change', function(){
          var elem =  $('.allrise-borrower_register-step1-wrapper .form-item-birthdate'),
              bDay = $(elem).find('select[name="birthdate[day]"]').val(),
              bMonth = $(elem).find('select[name="birthdate[month]"]').val(),
              bYear = $(elem).find('select[name="birthdate[year]"]').val();
          if (bDay > 0 && bMonth > 0 && bYear > 0) {
            console.log('formatted data: '+bDay+'/'+bMonth+'/'+bYear);
            var txtVal =  bDay+'/'+bMonth+'/'+bYear;
            if(isDate(txtVal, 1))
              console.log('Valid Date');
            else
              console.log('Invalid Date');
          }
        });

        /*issuedate*/
        $('.allrise-borrower_register-step1-wrapper .form-item-issue-date select.selecter').on('change', function(){
          var elem =  $('.allrise-borrower_register-step1-wrapper .form-item-issue-date'),
              bDay = $(elem).find('select[name="issue_date[day]"]').val(),
              bMonth = $(elem).find('select[name="issue_date[month]"]').val(),
              bYear = $(elem).find('select[name="issue_date[year]"]').val();
          if (bDay > 0 && bMonth > 0 && bYear > 0) {
            console.log('formatted data: '+bDay+'/'+bMonth+'/'+bYear);
            var txtVal =  bDay+'/'+bMonth+'/'+bYear;
            if(isDate(txtVal, 2))
              console.log('Valid Date');
            else
              console.log('Invalid Date');
          }
        });

      });
      /*ending*/
      function CheckErrorCalculations(){
  
        // handle visible state
        var required = $('input.cfield').length;
        var validated = $('input.cfield.error').length; 
        var errors = 0;
        var checkError = 0;

        //check max-val 
        // if (parseInt($('#first_slider .min-value').val()) > parseInt($('#first_slider .max-value').val())) {
        //   $('input[name="calculation_btn_submit"]').prop("disabled", true);
        //   errors++;
        // }


        if (required > 0) {

          $.each($('input.cfield'), function(i, element){
            var text_value=$(element).val();
            if(text_value == '') {
              errors++;
            }
          });

          // if ($('input[name="loan_size"]').length > 0 && $('input[name="loan_size"]').val() != 0) {
          //   if ($('#first_slider .min-value').val() < $('#first_slider .max-value').val()) {
          //     var loanSize = $('input[name="loan_size"]').val().replace(/,/g, "");
          //     console.log('loanSize' + loanSize);
          //     console.log('min'+$('#first_slider .min-value').val());
          //     if (loanSize > $('#first_slider .min-value').val() && loanSize < $('#first_slider .max-value').val()) {
          //       console.log('in');
          //     }
          //     if (loanSize < $('#first_slider .min-value').val() || loanSize > $('#first_slider .max-value').val()) {
          //       console.log('out');
          //       errors++;
          //     }
          //   }
          //   if ($('#first_slider .min-value').val() > $('#first_slider .max-value').val()) {
          //     errors++;
          //   }
          // }
          // if ($('input[name="loan_size"]').length > 0 && $('input[name="loan_size"]').val() == 0) {
          //     errors++;
          // }
        }
        if (validated > 0) {
          $.each($('input.cfield.error'), function(i, element){
            var text_value=$(element).val();

              errors++;
           });
        }

        console.log('err = ' + errors);
        if (errors > 0) {
          $('input[name="calculation_btn_submit"]').prop("disabled", true);
          console.log('disabled');
        }
        else {      
          console.log('not disabled');
          $('input[name="calculation_btn_submit"]').prop("disabled", false);
          errors =0;
        
        }


      };
    }
  };

  $(document).ajaxSuccess(function() {
    console.log('sliderapp');
    /*step1 validation*/
    $('.form-type-textfield input.ufield').on('change', function() {  
      
      if( $(this).val().length < 1 ){
        $(this).addErrorView();
      }
      else  {
        $(this).removeErrorView();
      };
      CheckErrorUserData();
    }); 
  
  });
})(jQuery);
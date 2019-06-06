
(function ($) {
    "use strict";


     /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
  
  
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');
    var submission = true;
    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }
          if (!submission) {
            return false;
          }
          submission = false
            var user_name = $(".user_name").val();
            var user_password = $(".user_password").val();
          if (user_name&&user_password) {
            var _Dialog = Dialog;
            $.ajax({
                type : 'POST',
                url : 'https://bbs.310game.com/forum-api/game/user-login',
                data : {"user_name": user_name, "user_password": user_password},
                dataType : 'json',
                success : function(data){
                    submission = true;
                    $.cookie('x-token', data.data.token, { expires: 30 });
                    window.location.href="./index.html";
                },
                error : function(data){
                    _Dialog.init(data.responseJSON.message);
                    submission = true;
                }
            })


          }else{
            submission = true
            return false;
          }
          return false;
    });

/*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');
    var submission = true;
    $('.register-validate-form').on('submit',function(){
        var check = true
        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }
          if (!submission) {
            return false;
          }
           submission = false;
            var nickname = $(".nickname").val();
            var user_name = $(".user_name").val();
            var user_password = $(".user_password").val();
          if (user_name&&user_password) {
            var _Dialog = Dialog;
            $.ajax({
                type : 'POST',
                url : 'https://bbs.310game.com/forum-api/game/user-register',
                data : {"user_name": user_name, "user_password": user_password, "nickname": nickname},
                dataType : 'json',
                success : function(data){
                    $.cookie('x-token', data.data.token, { expires: 30 });
                    submission = true;
                    window.location.href="./index.html";
                },
                error : function(data){
                    _Dialog.init(data.responseJSON.message);
                    submission = true;
                }
            })


          }else{
            submission = true
            return false;
          }
          return false;
    });

    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    

})(jQuery);
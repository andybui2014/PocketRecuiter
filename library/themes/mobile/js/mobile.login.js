function mobileLogin(){ }
mobileLogin.prototype = { 
    init: function(){
        $("#cmd-login").unbind('click').bind('click',this.login);
        $('#show-login').unbind('click').bind('click',this.reset);
    },
    reset: function(){
        $('#login-message').html('');
        $('#form-login').find('.form-group1').removeClass('has-error');
        $('#form-login :input[name="username"]').val('');
        $('#form-login :input[name="password"]').val('');
        $('#form-login #username_message').html('');
        $('#form-login #password_message').html('');
        $('#cmd-login').button('reset');
    },
    login: function(){
        var $this = $(this);
        $this.button('loading');

        var fields = {
            username: { notEmpty: { message: 'The username is required'}} ,
            password: { notEmpty: {message: 'The password is required'}}
        }

        var uid = $('#form-login :input[name="username"]');
        var pwd = $('#form-login :input[name="password"]');
        var uid_message = $('#form-login #username_message');
        var pwd_message = $('#form-login #password_message');
        if(
            typeof uid !=='undefined' && typeof uid !==undefined &&  uid.length > 0 &&
            typeof pwd !=='undefined' && typeof pwd !==undefined &&  pwd.length > 0
           ){
            //check value email
            var error = false;
            if(uid.val() =='' || uid.val().length <= 1){
                error = true;
                uid.parent().addClass('has-error');
                uid_message.html(fields.username.notEmpty.message).fadeOut().fadeIn();
            }else{
                uid_message.parent().removeClass('has-error').addClass('has-success');
                uid_message.html('');
            }
            if(pwd.val() =='' || pwd.val().length <= 1){
                error = true;
                pwd_message.parent().addClass('has-error');
                pwd_message.html(fields.password.notEmpty.message).fadeOut().fadeIn();
            }else{
                pwd_message.parent().removeClass('has-error').addClass('has-success');
                pwd_message.html('');
            }
            if(error==false){
                $.ajax({
                    url: 'login/do-login',
                    data: $('#form-login').serializeArray(),
                    type: 'POST',
                    success: function(xhr){
                        if(xhr.success){
                            window.location = 'client/start-profile';
                            $this.button('reset');
                        }else{
                            $this.button('reset');
                            $('#login-message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                     '<strong>'+xhr.error+'</strong>'+
                                '</div>'
                            );
                            pwd_message.parent().removeClass('has-success').addClass('has-error');
                            uid.parent().removeClass('has-success').addClass('has-error');
                        }
                    }
                });
            }else{

                $this.button('reset');
            }


        }
        //$(this).button('reset');
    }
}
$(function() {
    var mobile= new mobileLogin();
    mobile.init();
});
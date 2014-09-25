function pocketMain(){ }

pocketMain.NAME          = 'pocket recruiter';
pocketMain.VERSION       = '0.1';
pocketMain.DESCRIPTION   = 'Class Pocket';

pocketMain.prototype.constructor = pocketMain;
pocketMain.prototype = {
    /**
     *  Function init main
     */
    init: function(){
       // $("#cmd-logout").unbind('click').bind('click',this.logOut);
    },
    /**
     *  logOut
     *  Function lgout client
     *  @param Object|null options
     */
    logOut: function(url){
        var url = url || '';
        var $this = $(this);
        var urlSubmit = $this.attr('data-url');
        $this.append($('<div></div>').append('<form id="form-logout" method="post" action="'+urlSubmit+'"></form>'));
        $this.find('#form-logout').submit();
        return;
    },
    logIn: function(){
        var $this = $(this);
        $this.button('loading');

        var fields = {
            username: { notEmpty: { message: 'The email is required'}} ,
            password: { notEmpty: {message: 'The password is required'}}
        }

        var uid = $('#form-login :input[name="email"]');
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
                        //alert("usertype:"+xhr.usertype);
                        if(xhr.success){
                            if(xhr.usertype==1)
                            {
                                window.location = 'client/start-profile';
                                $this.button('reset');
                            }
                             if(xhr.usertype==2)
                            {
                                
                                window.location = 'candidate/start-profile';
                                $this.button('reset');
                            }
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
    }

}



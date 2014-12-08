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
        $("#btn-login").unbind('click').bind('click',this.logIn);
        $('#show-login').unbind('click').bind('click',this.reset);
		$('#btn-logout').unbind('click').bind('click',this.logOut);
		$('#resetpass').unbind('click').bind('click',this.resetPass);
		$('#newpass').unbind('click').bind('click',this.setnewPass);
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
    reset: function(){
        $('#login-message').html('');
        $('#form-login').find('.form-group').removeClass('has-error');     
        $('#form-login :input[name="email"]').val('');        
        $('#form-login :input[name="password"]').val('');         
        $('#form-login #email').html('');       
        $('#form-login #password').html('');
       
        $('#btn-login').button('reset');
    },
	resetPass:function(){
        var btn = $(this);
        btn.button('loading');
        var fields = {
             emailaddress: { notEmpty: {message: 'email address is required.'}},
        }
        var emailaddress = $('#form-forgotpass').find('[name="emailaddress"]');
        var emailaddress_message = $('#form-forgotpass #emailaddress_message');
          if( typeof emailaddress !=='undefined' && typeof emailaddress !==undefined &&  emailaddress.length > 0 ){
            var error = false;
               if(emailaddress.val() ==''){
                error = true;
               emailaddress_message.parent().addClass('has-error');
               emailaddress_message.html(fields.emailaddress.notEmpty.message).fadeOut().fadeIn();
            }else{
               emailaddress_message.parent().removeClass('has-error').addClass('has-success');
               emailaddress_message.html('');
           }
          }
          if(error == false)
          {
                $.ajax({
                url: 'login/reset-pass',
                data: $('#form-forgotpass').serializeArray(),
                    type: 'POST',
                
             //  success: function(data, status, xhr){
                success: function(data,xhr){
                    //alert(data. error)
                   // console.log(data);
                    if(data.success){
							$('#openModalFogotpass').modal('hide')
                          $(".mail").append(emailaddress.val());
                          $('#openModalcheckmailFogotpass').modal('show')
                       

                    }else{
                        btn.button('reset');
                        alert(data. error);
                    }
                }
            });
          }
          else{
              btn.button('reset');
          }
    },
	setnewPass:function(){
        var btn = $(this);
        btn.button('loading');
        var fields = {
             password: { notEmpty: {message: 'Password is required.'}},
			 
        }
        var password = $('#form-newpass').find('[name="password"]');
		var newpassword = $('#form-newpass').find('[name="newpassword"]');
        var password_message = $('#form-newpass #password_message');
          if( typeof password !=='undefined' && typeof password !==undefined &&  password.length > 0 ){
            var error = false;
               if(password.val() ==''){
                error = true;
               password_message.parent().addClass('has-error');
               password_message.html(fields.password.notEmpty.message).fadeOut().fadeIn();
            }else{
               password_message.parent().removeClass('has-error').addClass('has-success');
               password_message.html('');
           }
		   var newpassword_message = $('#form-newpass #newpassword_message');
          
           
               if(password.val() !=newpassword.val()){
                error = true;
			   newpassword_message.parent().addClass('has-error');
               newpassword_message.html("New Password and confirm new password different");
            }else{
               newpassword_message.parent().removeClass('has-error').addClass('has-success');
               newpassword_message.html('');
           }
		   
          
		  
		   
          }
          if(error == false)
          {
                $.ajax({
                url: 'resetpass/do-reset-pass',
                data: $('#form-newpass').serializeArray(),
                    type: 'POST',
                
             //  success: function(data, status, xhr){
                success: function(data,xhr){
                    //alert(data. error)
                   // console.log(data);
				   window.location = 'login';
                   
                }
            });
          }
          else{
              btn.button('reset');
          }
    },
    logIn: function(){
        var $this = $(this);
        $this.button('loading');

        var fields = {
            email: { notEmpty: { message: 'Email is required.'}} ,
            password: { notEmpty: {message: 'Password is required.'}}
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
               // alert("testt");
                error = true;
                uid.parent().addClass('has-error');
                uid_message.html(fields.email.notEmpty.message).fadeOut().fadeIn();
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
                               // window.location = 'client/start-profile';
							   window.location = 'dashboard/index';
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
                           // uid_message.html(fields.email.notEmpty.message).fadeOut().fadeIn();
                        }
                    }
                });
            }else{
                $this.button('reset');
            }
        }
    }

}
$(function() {
    var pocket= new pocketMain();
    pocket.init();
});



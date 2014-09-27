function register(){ }

register.prototype = {
    init: function(){
        $("#cmd-register").unbind('click').bind('click',this.profile);
       // $("#cmd-updateprofile").unbind('click').bind('click',this.isValidContactPhone);
        $('#show-profile').unbind('click').bind('click',this.reset);
    },
    reset: function(){
        $('#Register-message').html('');
        //$('#Register').find('.form-group).removeClass('has-error');
        $('#Register').find('.form-group').removeClass('has-error');
        $('#Register :input[name="emailaddress"]').val('');
        $('#Register :input[name="firstname"]').val('');
        $('#Register :input[name="LastName"]').val('');
        $('#Register :input[name="password"]').val('');
         $('#Register :input[name="RetypePassword"]').val('');
        $('#Register :input[name="usertype"]').val('');
       $('#Register :input[name="loginname"]').val('');
        $('#Register').find('[name="About_us"]').value='';
        $('#Register :input[name="accept"]').val('');
        $('#Register #emailaddress').html('');
        $('#Register #firstname').html('');
        $('#Register #LastName').html('');
        $('#Register #RetypePassword').html('');
        $('#Register #password').html('');
        $('#Register #usertype').html('');
        $('#Register #About_us').html('');
        $('#Register #accept').html('');
        $('#Register #loginname').html('');
       
        $('#cmd-register').button('reset');
    },
   
    
    profile: function(){
   var $this = $(this);
        //$this.button('loading');

        var fields = {
             emailaddress: { notEmpty: {message: 'Email address is required.'},emailAddress: {message: 'Email address is not a valid email address'}},
            firstname: { notEmpty: { message: 'First Name is required.'}} ,
            LastName: { notEmpty: {message: 'Last Name is required.'}},
            password: { notEmpty: {message: 'Password is required.'}},
            accept: { notEmpty: {message: 'You need to accept the terms of Service.'}},
            About_us: { notEmpty: {message: 'Source is required.'}},
            RetypePassword: { identical: {message: 'Password and Retype Password are not the same.'}},
            loginname: { notEmpty: {message: 'User Name is required.'}}
            
           
           
         
        }

        var fn = $('#Register :input[name="firstname"]');
      
        var ln = $('#Register :input[name="LastName"]');
      
        var pw = $('#Register :input[name="password"]');
        var rpw = $('#Register :input[name="RetypePassword"]');
       var act = $('#Register :input[name="accept"]');
        var pmrm = $('#Register :input[name="emailaddress"]');
       var lgn = $('#Register :input[name="loginname"]');
        var abu = $('#Register').find('[name="About_us"]');
        var fn_message = $('#Register #FirstName_message');
        var ln_message = $('#Register #LastName_message');
        var pw_message = $('#Register #Password_message');
        var act_message = $('#Register #accept_message');
        var abu_message = $('#Register #About_us_message');
      var rpw_message = $('#Register #RetypePassword_message');
        var pmrm_message = $('#Register #Email1_message');
      var lgn_message = $('#Register #loginname_message');
        if(
           // typeof fn !=='undefined' && typeof fn !==undefined &&  fn.length > 0 && 
            typeof lgn !=='undefined' && typeof lgn !==undefined &&  lgn.length > 0 &&
           typeof pw !=='undefined' && typeof pw !==undefined &&  pw.length > 0 &&
            typeof act !=='undefined' && typeof act !==undefined &&  act.length > 0 &&
            typeof pmrm !=='undefined' && typeof pmrm !==undefined &&  pmrm.length > 0 &&           
            typeof abu !=='undefined' && typeof abu !==undefined &&  abu.length > 0 
           ){
            //check value email
            var error = false;
         /*   if(fn.val() =='' || fn.val().length <= 1){
                error = true;
                fn.parent().addClass('has-error');
                fn_message.html(fields.FirstName.notEmpty.message).fadeOut().fadeIn();
            }else{
                fn_message.parent().removeClass('has-error').addClass('has-success');
                fn_message.html('');
            }*/
         
            if(lgn.val() =='' || lgn.val().length <= 1){
                error = true;
                lgn_message.parent().addClass('has-error');
                lgn_message.html(fields.loginname.notEmpty.message).fadeOut().fadeIn();
            }else{
                lgn_message.parent().removeClass('has-error').addClass('has-success');
                lgn_message.html('');
            }
          
             if(pw.val() =='' || pw.val().length <= 1){
               error = true;
               pw_message.parent().addClass('has-error');
               pw_message.html(fields.password.notEmpty.message).fadeOut().fadeIn();
           }else{
               pw_message.parent().removeClass('has-error').addClass('has-success');
               pw_message.html('');
           }
          // alert(rpw.val());
           if( rpw.val() !=pw.val()&& pw.val()!="" ){
               error = true;
               rpw_message.parent().addClass('has-error');
               rpw_message.html(fields.RetypePassword.identical.message).fadeOut().fadeIn();
           }else{
               rpw_message.parent().removeClass('has-error').addClass('has-success');
               rpw_message.html('');
           }
         //alert(abu.val());
           if(abu.val() ==''){
               error = true;
               abu_message.parent().addClass('has-error');
               abu_message.html(fields.About_us.notEmpty.message).fadeOut().fadeIn();
           }else{
               abu_message.parent().removeClass('has-error').addClass('has-success');
               abu_message.html('');
           }
      //alert(("#accept").val());
           // var accept= $("#accept").
           //if(act.addClass('accept').checked)
//{
//alert('Thank you. Form is ready for approval.');

//}
//alert($('#accept:checked').length);

          if($('#accept:checked').length==0){
              //alert(test);
              error = true;
               act_message.parent().addClass('has-error');
               act_message.html(fields.accept.notEmpty.message).fadeOut().fadeIn();
           }else{
               act_message.parent().removeClass('has-error').addClass('has-success');
               act_message.html('');
           }
           var x = pmrm.val();
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");
            
            if(pmrm.val() =='' || pmrm.val().length <= 1){
                error = true;
                pmrm_message.parent().addClass('has-error');
                pmrm_message.html(fields.emailaddress.notEmpty.message).fadeOut().fadeIn();
                }
                else if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
                error = true;
                pmrm_message.parent().addClass('has-error');
                pmrm_message.html(fields.emailaddress.emailAddress.message).fadeOut().fadeIn();
                }
                else{
                pmrm_message.parent().removeClass('has-error').addClass('has-success');
                pmrm_message.html('');
            }
            if(error==false){
                $.ajax({
                    url:  'register/do-register' ,
                    data: $('#Register').serializeArray(),
                    type: 'POST',
                    success: function(xhr){
                       
                      // alert("usertype:"+xhr.usertype);
                       //console.log(c);
                     //alert(xhr.success);
                     // alert("erro:"+xhr.error);
                        if(xhr.success){
                           // alert("usertype:"+xhr.usertype);
                            if(xhr.usertype==1)
                                        {
                                            window.location = 'client/start-profile';
                                            
                                        }
                                         if(xhr.usertype==2)
                                        {
                                            
                                            window.location = 'candidate/start-profile';
                                           
                                        }
                           
                          //  window.location = 'update-profle';
                          
                            $this.button('reset');
                           // alert("success");
                        }else{
                            $this.button('reset');
                            $('#profile-message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                     '<strong>'+xhr.error+'</strong>'+
                                '</div>'
                            );
                           alert("erro:"+xhr.error);
                           if(xhr.error=="email exists")
                           {
                            pmrm_message.parent().removeClass('has-success').addClass('has-error');
                            pmrm_message.html(fields.emailaddress.notEmpty.message).fadeOut().fadeIn();
                           }
                           if(xhr.error=="User exists")
                           {
                            lgn_message.parent().removeClass('has-success').addClass('has-error');
                            lgn_message.html(fields.loginname.notEmpty.message).fadeOut().fadeIn();
                           }
                           
                            //uid.parent().removeClass('has-success').addClass('has-error');
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
    var regis= new register();
    regis.init();
});
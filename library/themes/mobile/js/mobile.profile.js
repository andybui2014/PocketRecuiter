function mobileProfile(){ }

mobileProfile.prototype = {
    init: function(){
        $("#cmd-updateprofile").unbind('click').bind('click',this.profile);
       // $("#cmd-updateprofile").unbind('click').bind('click',this.isValidContactPhone);
        $('#show-profile').unbind('click').bind('click',this.reset);
    },
    reset: function(){
        $('#profile-message').html('');
        $('#form-profile').find('.form-group1').removeClass('has-error');
        $('#form-profile :input[name="CompanyName"]').val('');
        //$('#form-profile :input[name="Password"]').val('');
        $('#form-profile :input[name="ContactName"]').val('');
       // $('#form-profile :input[name="UserName"]').val('');
        $('#form-profile :input[name="ContactPhone1"]').val('');
        $('#form-profile :input[name="ContactPhone2"]').val('');
        $('#form-profile :input[name="Email1"]').val('');
        $('#form-profile :input[name="Email2"]').val('');
        $('#form-profile #companyname_message').html('');
        $('#form-profile #Password_message').html('');
        $('#form-profile #ContactName_message').html('');
        $('#form-profile #UserName_message').html('');
        $('#form-profile #ContactPhone1_message').html('');
        $('#form-profile #ContactPhone2_message').html('');
         $('#form-profile #Email1_message').html('');
         $('#form-profile #Email2_message').html('');
        $('#cmd-updateprofile').button('reset');
    },
   
    
    profile: function(){
        var $this = $(this);
        //$this.button('loading');

        var fields = {
            CompanyName: { notEmpty: { message: 'The company name is required.'}} ,
           // Password: { notEmpty: {message: 'The password is required.'}},
            ContactName: { notEmpty: {message: 'The contact name is required.'}},
           // UserName: { notEmpty: {message: 'The user name is required.'}},
            ContactPhone1: { notEmpty: {message: 'The primary phone is required.'},digits: {message: 'The primary phone must be a valid 10 digit number with optional extension similar to XXX XXX XXXX '}},
           // ContactPhone1: { digits: {message: 'The primary phone must be a valid 10 digit number with optional extension similar to XXX XXX XXXX '}},
              ContactPhone2: { digits: {message: 'The Secondary phone must be a valid 10 digit number with optional extension similar to XXX XXX XXXX '}},
            Email1: { notEmpty: {message: 'The primary mail is required.'},emailAddress: {message: 'The primary mail is not a valid email address'}},
           // Email1: { emailAddress: {message: 'The primary mail is not a valid email address'}},
            Email2: { emailAddress: {message: 'The Secondary mail is not a valid email address'}}
        }

        var uid = $('#form-profile :input[name="CompanyName"]');
      //  var pwd = $('#form-profile :input[name="Password"]');
        var ctn = $('#form-profile :input[name="ContactName"]');
       // var usn = $('#form-profile :input[name="UserName"]');
        var pmrp = $('#form-profile :input[name="ContactPhone1"]');
        var scp = $('#form-profile :input[name="ContactPhone2"]');
        var pmrm = $('#form-profile :input[name="Email1"]');
        var scm = $('#form-profile :input[name="Email2"]');
        var uid_message = $('#form-profile #companyname_message');
        var pwd_message = $('#form-profile #Password_message');
        var ctn_message = $('#form-profile #ContactName_message');
        var usn_message = $('#form-profile #UserName_message');
        var pmrp_message = $('#form-profile #ContactPhone1_message');
        var scp_message = $('#form-profile #ContactPhone2_message');
        var pmrm_message = $('#form-profile #Email1_message');
        var scm_message = $('#form-profile #Email2_message');
        if(
            typeof uid !=='undefined' && typeof uid !==undefined &&  uid.length > 0 && 
          //  typeof pwd !=='undefined' && typeof pwd !==undefined &&  pwd.length > 0 &&
            typeof ctn !=='undefined' && typeof ctn !==undefined &&  ctn.length > 0 &&
           // typeof usn !=='undefined' && typeof usn !==undefined &&  usn.length > 0 &&
            typeof pmrp !=='undefined' && typeof pmrp !==undefined &&  pmrp.length > 0 &&
            typeof pmrm !=='undefined' && typeof pmrm !==undefined &&  pmrm.length > 0 &&
            typeof scp !=='undefined' && typeof scp !==undefined &&  scp.length > 0 &&
            typeof scm !=='undefined' && typeof scm !==undefined &&  scm.length > 0
           ){
            //check value email
            var error = false;
            if(uid.val() =='' || uid.val().length <= 1){
                error = true;
                uid.parent().addClass('has-error');
                uid_message.html(fields.CompanyName.notEmpty.message).fadeOut().fadeIn();
            }else{
                uid_message.parent().removeClass('has-error').addClass('has-success');
                uid_message.html('');
            }
         //   if(pwd.val() =='' || pwd.val().length <= 1){
          //      error = true;
          //      pwd_message.parent().addClass('has-error');
          //      pwd_message.html(fields.Password.notEmpty.message).fadeOut().fadeIn();
         //   }else{
           //     pwd_message.parent().removeClass('has-error').addClass('has-success');
           //     pwd_message.html('');
          //  }
            if(ctn.val() =='' || ctn.val().length <= 1){
                error = true;
                ctn_message.parent().addClass('has-error');
                ctn_message.html(fields.ContactName.notEmpty.message).fadeOut().fadeIn();
            }else{
                ctn_message.parent().removeClass('has-error').addClass('has-success');
                ctn_message.html('');
            }
           // if(usn.val() =='' || usn.val().length <= 1){
           //     error = true;
           //     usn_message.parent().addClass('has-error');
           //     usn_message.html(fields.UserName.notEmpty.message).fadeOut().fadeIn();
          //  }else{
            ////    usn_message.parent().removeClass('has-error').addClass('has-success');
           //     usn_message.html('');
            //}
            var phone=pmrp.val();
            var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/; 
            if(pmrp.val() =='' || pmrp.val().length <= 1){
                error = true;
                pmrp_message.parent().addClass('has-error');
                pmrp_message.html(fields.ContactPhone1.notEmpty.message).fadeOut().fadeIn();
            }
           // else{
             //   pmrp_message.parent().removeClass('has-error').addClass('has-success');
              //  pmrp_message.html('');
          //  }
            
           else if (!phone.match(phoneno) && phone.length >1) {
                error = true;
                pmrp_message.parent().addClass('has-error');
                pmrp_message.html(fields.ContactPhone1.digits.message).fadeOut().fadeIn();
                
            }else{
                
                pmrp_message.parent().removeClass('has-error').addClass('has-success');
                pmrp_message.html('');
            }
            var phone2=scp.val();
             if (!phone2.match(phoneno) && phone2.length >1) {
                 error = true;
                scp_message.parent().addClass('has-error');
                scp_message.html(fields.ContactPhone2.digits.message).fadeOut().fadeIn();
                
                
            }else{
                scp_message.parent().removeClass('has-error').addClass('has-success');
                scp_message.html('');
            }
            var filter = "/^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/";
           var x = pmrm.val();
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");
            
            if(pmrm.val() =='' || pmrm.val().length <= 1){
                error = true;
                pmrm_message.parent().addClass('has-error');
                pmrm_message.html(fields.Email1.notEmpty.message).fadeOut().fadeIn();
                }
                else if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
                error = true;
                pmrm_message.parent().addClass('has-error');
                pmrm_message.html(fields.Email1.emailAddress.message).fadeOut().fadeIn();
                }
                else{
                pmrm_message.parent().removeClass('has-error').addClass('has-success');
                pmrm_message.html('');
            }
           // var filter = "/^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/";
          /* var x = pmrm.val();
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");
            
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
                error = true;
                pmrm_message.parent().addClass('has-error');
                pmrm_message.html(fields.Email1.emailAddress.message).fadeOut().fadeIn();
                
            }else{
                pmrm_message.parent().removeClass('has-error').addClass('has-success');
                pmrm_message.html('');
            }*/
            var y = scm.val();
            var atpos2 = y.indexOf("@");
            var dotpos2 = y.lastIndexOf(".");
            
          /*  if (atpos2<1 || dotpos2<atpos2+2 || dotpos2+2>=y.length) {
                error = true;
                scm_message.parent().addClass('has-error');
                scm_message.html(fields.Email2.emailAddress.message).fadeOut().fadeIn();
                
            }else{
                scm_message.parent().removeClass('has-error').addClass('has-success');
                scm_message.html('');
            }*/
            if(error==false){
                $.ajax({
                    url: 'update-profle',
                    data: $('#form-profile').serializeArray(),
                    type: 'POST',
                    success: function(xhr){
                        if(xhr.success){
                            window.location = 'start-profile';
                          //  window.location = 'update-profle';
                            $this.button('reset');
                            alert("success");
                        }else{
                            $this.button('reset');
                            $('#profile-message').html(
                                '<div class="alert alert-danger alert-dismissible" role="alert">'+
                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+
                                     '<strong>'+xhr.error+'</strong>'+
                                '</div>'
                            );
                           // alert("erro");
                            //pwd_message.parent().removeClass('has-success').addClass('has-error');
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
    var mobile= new mobileProfile();
    mobile.init();
});
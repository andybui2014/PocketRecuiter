function companyprofile(){ }

companyprofile.prototype = {
    init: function(){
        $("#submit").unbind('click').bind('click',this.profile);
        $('#show-profile').unbind('click').bind('click',this.reset);
    },
    reset: function(){
        $('#profile-message').html('');
        $('#profile').find('.form-group1').removeClass('has-error');
       
      //  $('#form-profile :input[name="emailaddress"]').val('');
       
        // $('#form-profile #emailaddress').html('');

        $('#cmd-updateprofile').button('reset');
    },
   
    
    profile: function(){
        var $this = $(this);
        //$this.button('loading');

        var fields = {

            emailaddress: { notEmpty: {message: 'The email address is required.'},emailAddress: {message: 'The email address is not a valid email '}},
           
        }


      //  var pmrm = $('#form-profile :input[name="emailaddress"]');
      
       // var pmrm_message = $('#form-profile #Email1_message');
      var error = false;
       /* if(
           
            typeof pmrm !=='undefined' && typeof pmrm !==undefined &&  pmrm.length > 0 )
{
            //check value email
            
         
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
            }*/
           
            if(error==false){
                
                $.ajax({
                   // url: 'update-profle',
                   
                    url: 'do-edit-profile',
                    data: $('#profile').serializeArray(),
                    type: 'POST',
                    
                    success: function(xhr){
                        //alert(xhr.success);
                        alert("file:"+$('#images').val());
                        if(xhr.success){
                           // $('#photo1').val()=$('#images').val();
                          // console.log($('#form-profile').serializeArray());
                            
                            //window.location = 'start-profile';
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
                            alert("erro:"+xhr.error);
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
//}
$(function() {
    var mobile= new companyprofile();
    mobile.init();
});
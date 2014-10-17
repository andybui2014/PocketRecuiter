function Overview(){ }
Overview.prototype = {
    init: function(){
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#saveSkills').unbind('click').bind('click',this.saveSkills);
         $('#Saveprofile').unbind('click').bind('click',this.Saveprofile);  


        //skill will be removed when clicked for first time page on load
        $(".removeSkillOnload").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilID");
            $("#selectSkill").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();
        });
        //
         if($("#ckAll").is(":checked")){
            $(".checkIs").prop('checked','checked');

            $(".ischecktr input:checked").each(function(){
                disabled = $(this).attr('disabled');
                disabled = typeof(disabled);
                if(disabled != "undefined"){
                    $(this).prop('checked',false);
                }
               
            });

        } else {
            $(".checkIs").removeAttr('checked');
        }

        //
  
        $('#minimumsalary').blur(function () {
            if($('#minimumsalary').val()==''){
            $("#minimumsalary_message").addClass('has-error');
            $("#minimumsalary_message").html("Minimumsalary name is required");
            }else{
            $("#minimumsalary_message").removeClass('has-error').addClass('has-success');
            $("#minimumsalary_message").html('');
            }  
         });
        $('#maximumsalary').blur(function () {
            if($('#maximumsalary').val()==''){
            $("#maximumsalary_message").addClass('has-error');
            $("#maximumsalary_message").html("Maximumsalary name is required");
            }else{
            $("#maximumsalary_message").removeClass('has-error').addClass('has-success');
            $("#maximumsalary_message").html('');
            }  
         });
        $('#Overview').blur(function () {
            if($('#Overview').val()==''){
            $("#Overview_message").addClass('has-error');
            $("#Overview_message").html("Overview name is required");
            }else{
            $("#Overview_message").removeClass('has-error').addClass('has-success');
            $("#Overview_message").html('');
            }  
         });
        $('#tagline').blur(function () {
            if($('#tagline').val()==''){
            $("#tagline_message").addClass('has-error');
            $("#tagline_message").html("Tagline is required");
            }else{
            $("#tagline_message").removeClass('has-error').addClass('has-success');
            $("#tagline_message").html('');
            }  
         });
        $('#lastname').blur(function () {
            if($('#lastname').val()==''){
            $("#LastName_message").addClass('has-error');
            $("#LastName_message").html("Last name is required");
            }else{
            $("#LastName_message").removeClass('has-error').addClass('has-success');
            $("#LastName_message").html('');
            }  
         });
         $('#firstname').blur(function () {
            if($('#firstname').val()==''){
            $("#FirstName_message").addClass('has-error');
            $("#FirstName_message").html("First name is required");
            }else{
            $("#FirstName_message").removeClass('has-error').addClass('has-success');
            $("#FirstName_message").html('');
            }  
         });

    },
    reset: function(){
        $('#profile-message').html('');
        $('#profile').find('.form-group').removeClass('has-error');
        $('#profile :input[name="firstname"]').val('');
        $('#profile :input[name="lastname"]').val('');
        $('#profile').find('[name="tagline"]').value='';
        $('#profile').find('[name="overview"]').value=''; 
        $('#profile').find('[name="maximumsalary"]').value=''; 
        $('#profile').find('[name="minimumsalary"]').value=''; 
           
        $('#profile #firstname').html('');
        $('#profile #lastname').html('');
        $('#profile #tagline').html('');
        $('#profile #overview').html('');
        $('#profile #maximumsalary').html('');
        $('#profile #minimumsalary').html('');
               
       
    },

    checkAllIs: function(){
        if($("#ckAll").is(":checked")){
            $(".checkIs").prop('checked','checked');

            $(".ischecktr input:checked").each(function(){
                disabled = $(this).attr('disabled');
                disabled = typeof(disabled);
                if(disabled != "undefined"){
                    $(this).prop('checked',false);
                }
            });

        } else {
            $(".checkIs").removeAttr('checked');
        }



},
    checkIs: function(){
        var lengthAllCheckbox = $('.ischecktr input:checkbox').length;

        if($('table#notificationCk .checkIs').is(":checked")) {
            if ($(".ischecktr input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        } else{
            if ($(".ischecktr input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        }
    },
 saveSkills:function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'add-skills',
            data: $('#form-skills').serializeArray(),
            type: 'POST',
            error : function (xhr,error) {
                btn.button('reset');
            },
            success: function(xhr){
               
                var skill=xhr.SkillName;
                var skillid=xhr.SkillID;  
                
              
                $.each( skill, function( key, value ) {
                   
                        var skillname=value;       
                          
                       // alert(skillname);  
                   if( skillname !=='undefined' &&  skillname !==undefined &&  skillname.length > 0 )
                   {
                        
                  
                   
                    $("div#requiredTest").append("<span style='background:#ededed;' class='label-tag pull-left getSkillText'>" + skillname +
             "&nbsp;&nbsp;<imge class='removeskill  glyphicon glyphicon-remove' height='15px'  style='cursor:pointer; color:#ccc;' > " +
             "<input type='hidden'  name='SkillName[]' value='"+skillname+"' ></span>");
             $(".removeskill").unbind("click").bind("click",function(){
          
            $(this).parent().remove();
        });
            }    
                                 
});
                if(skill){
                    btn.button('reset');
                    $("#openModalSkill").modal('hide');
                    var str = " <span style='' class='label-tag pull-left getSkillText'> ";
                   
                  // $.each(data.skills,function( index, element ) 
                    $.each(skill,function( index, element ) { 
                      
                      
   
  });
                  
                }else{
                    btn.button('reset');
                }
            }
        });
    },


    Saveprofile:function(){
        var $this = $(this);
    
        var fields = {
        firstname: { notEmpty: {message: 'First name is required.'}},
        lastname: { notEmpty: { message: 'Last name is required.'}} ,
        tagline: { notEmpty: {message: 'Tagline is required.'}},
        overview: { notEmpty: {message: 'overview is required.'}},
        maximumsalary: { notEmpty: {message: 'Maximumsalary is required.'}},
        minimumsalary: { notEmpty: {message: 'Minimumsalary is required.'}}
        
        }

        var fn = $('#profile :input[name="firstname"]'); 
        var ln = $('#profile :input[name="lastname"]');       
        var taln = $('#profile').find('[name="tagline"]');
        var ovw = $('#profile').find('[name="overview"]'); 
        var maxslr = $('#profile').find('[name="maximumsalary"]'); 
        var minslr = $('#profile').find('[name="minimumsalary"]'); 
      
        var fn_message = $('#profile #FirstName_message');
        var ln_message = $('#profile #LastName_message');
        var taln_message = $('#profile #tagline_message');
        var ovw_message = $('#profile #Overview_message');
        var maxslr_message = $('#profile #maximumsalary_message');
        var minslr_message = $('#profile #minimumsalary_message');
        // alert(fn.val()+ln.val()); 
      
            var error = false;
            
           if(fn.val() =='' || fn.val().length <= 1){
                error = true;
                fn.parent().addClass('has-error');
                fn_message.html(fields.firstname.notEmpty.message).fadeOut().fadeIn();
            }else{
                fn_message.parent().removeClass('has-error').addClass('has-success');
                fn_message.html('');
            }
            
             if(ln.val() =='' || ln.val().length <= 1){
                error = true;
                ln.parent().addClass('has-error');
                ln_message.html(fields.lastname.notEmpty.message).fadeOut().fadeIn();
            }else{
                ln_message.parent().removeClass('has-error').addClass('has-success');
                ln_message.html('');
            }
            
            if(taln.val() =='' || taln.val().length <= 1){
                error = true;
                taln.parent().addClass('has-error');
                taln_message.html(fields.tagline.notEmpty.message).fadeOut().fadeIn();
            }else{
                taln_message.parent().removeClass('has-error').addClass('has-success');
                taln_message.html('');
            }
            
            if(ovw.val() =='' || ovw.val().length <= 1){
                error = true;
                ovw.parent().addClass('has-error');
                ovw_message.html(fields.overview.notEmpty.message).fadeOut().fadeIn();
            }else{
                ovw_message.parent().removeClass('has-error').addClass('has-success');
                ovw_message.html('');
            }
            
            if(maxslr.val() ==''){
                error = true;
                maxslr.parent().addClass('has-error');
                maxslr_message.html(fields.maximumsalary.notEmpty.message).fadeOut().fadeIn();
            }else{
                maxslr_message.parent().removeClass('has-error').addClass('has-success');
                maxslr_message.html('');
            }
            
            if(minslr.val() ==''){
                error = true;
                minslr.parent().addClass('has-error');
                minslr_message.html(fields.minimumsalary.notEmpty.message).fadeOut().fadeIn();
            }else{
                minslr_message.parent().removeClass('has-error').addClass('has-success');
                minslr_message.html('');
            }
            
             if(error==false){
        
                  $.ajax({
                url: 'do-editoverview',
                data: $('#profile').serializeArray(),
                type: 'POST',
                error : function (xhr,error) {
                    btn.button('reset');
                },
                success: function(data, status, xhr){
                   if(xhr.success){                            
                       window.location = 'profile'; 
                       alert("success");
                     }else{
                                
                               alert("erro:"+xhr.error);
                             
                             
                            }
                        }
                
            });
             }
           //}
    },
    addrequiredtest:function(){
        var testID = $("#selectRequiredTest option:selected").val();
        var testName = $("#selectRequiredTest option:selected").text();
        $("div#requiredTest").append("<span class='label-tag pull-left getTest'>" +testName +
            "&nbsp;&nbsp;<imge class='removeTest  glyphicon glyphicon-remove'  height='15px' testID='"+testID+"' style='cursor:pointer; color:#ccc'>" +
            "<input type='hidden'  name='testid[]' value='"+testID+"' ></span> ");

        $(".removeTest").unbind("click").bind("click",function(){
            var testID = $(this).attr("testID");
            $("#selectRequiredTest").find("option[value='" + testID + "']").css("display", "");
            $(this).parent().remove();
        });

        $("#selectRequiredTest option:selected").css("display", "none");
        $("#selectRequiredTest option[value='']").prop("selected", "selected");
    },
   
  
    

   
    
}


$(function() {
    var mbNot= new Overview();
    mbNot.init();
});

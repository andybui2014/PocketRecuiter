function Education(){ }
Education.prototype = {
    init: function(){
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#saveSkills').unbind('click').bind('click',this.saveSkills);
         $('#SaveEducation').unbind('click').bind('click',this.SaveEducation);  


    },
   
    SaveEducation:function(){
        var $this = $(this);
    
        
                  $.ajax({
                url: 'do-edieducation',
                data: $('#education-form').serializeArray(),
                type: 'POST',
                error : function (xhr,error) {
                    btn.button('reset');
                },
                success: function(data, status, xhr){
                   if(xhr.success){                            
                      // window.location = 'vieweducation'; 
                       alert("success");
                     }else{
                                
                               alert("erro:"+xhr.error);
                             
                             
                            }
                        }
                
            });
            // }
           //}
    },
    
  
    

   
    
}


$(function() {
    var mbNot= new Education();
    mbNot.init();
});

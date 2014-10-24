function Education(){ }
Education.prototype = {
    init: function(){
       // $('#Cancle').unbind('click').bind('click',this.Cancle);
         $('#SaveAndAnothorEducation').unbind('click').bind('click',this.SaveAndAnothorEducation);
         $('#SaveEducation').unbind('click').bind('click',this.SaveEducation);  
         $('#AddEducation').unbind('click').bind('click',this.AddEducation); 
         $('#AddAndAnothorEducation').unbind('click').bind('click',this.AddAndAnothorEducation); 
  },
     
    AddEducation:function(){
        var $this = $(this);
    
        
                  $.ajax({
                url: 'do-addnew-education',
                data: $('#education-form').serializeArray(),
                type: 'POST',
                error : function (xhr,error) {
                    btn.button('reset');
                },
                success: function(data, status, xhr){
                   if(xhr.success){                            
                      window.location = 'vieweducation'; 
                       alert("success");
                     }else{
                                
                               alert("erro:"+xhr.error);
                             
                             
                            }
                        }
                
            });
           
    },
     AddAndAnothorEducation:function(){
        var $this = $(this);
    
        
                  $.ajax({
                url: 'do-addnew-education',
                data: $('#education-form').serializeArray(),
                type: 'POST',
                error : function (xhr,error) {
                    btn.button('reset');
                },
                success: function(data, status, xhr){
                   if(xhr.success){                            
                      window.location = 'add-education'; 
                      // alert("success");
                     }else{
                                
                               alert("erro:"+xhr.error);
                             
                             
                            }
                        }
                
            });
           
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
                      window.location = 'vieweducation'; 
                       alert("success");
                     }else{
                                
                               alert("erro:"+xhr.error);
                             
                             
                            }
                        }
                
            });
           
    },
    
   SaveAndAnothorEducation:function(){
         var $this = $(this);$.ajax({
                url: 'do-edieducation',
                data: $('#education-form').serializeArray(),
                type: 'POST',
                success: function(data, status, xhr){
                   if(xhr.success){                            
                      window.location = 'add-education'; 
                      $('#institution_name').focus();  
                      
                      // alert("success");
                      
                     }else{
                                
                               alert("erro:"+xhr.error);
                            }
                        }                
            });
            
   }
    

   
    
}


$(function() {
    var mbNot= new Education();
    mbNot.init();
});

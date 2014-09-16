 function jCustomUploadStudentEdit(){    
    var btnBrowse = jQuery('.jQUploadStudentEdit');    
    if(btnBrowse.length > 0){
        btnBrowse.each(function(pos, btn){            
            if(jQuery(btn).parent().find('.divUpload').length > 0){
                var divInput = jQuery(btn).parent().find('.divUpload');
            }else{
                jQuery(btn).parent().append('<div class="divUpload"><input type="file" name="fileUpload"/></div>');            
                var divInput = jQuery(btn).parent().find('.divUpload');
            }            
            var coords = jQuery(btn).position();        
            divInput.css({
                'overflow': 'hidden',                
                'width': jQuery(btn).outerWidth(),
                'height': jQuery(btn).outerHeight(),
                'position': 'absolute',
                'top': coords.top,
                'left': coords.left                
            });
            divInput.find(':first').css({
                'font-size': '50px',
                'position': 'absolute',                    
                'height': jQuery(btn).outerHeight(),
                'opacity': 0.01,
                'top': 0,
                'right': 0,
                'margin': 0
            }).bind('change', function(){
                if(jQuery(btn).parent().find('input[type="text"]').length > 0){
                    jQuery(btn).parent().find('input[type="text"]').val(jQuery(this).val());
                }
                if(jQuery(btn).attr('id') == 'btnChangeLogo'){
                    jQuery('#hidSaveOrUpload').val(1);
                    jQuery('#frmStudentEdit')[0].submit();
                }
            });            
            jQuery(btn).data('divInput',divInput);            
            jQuery(btn).after(divInput);
            jQuery(btn).css("display","block");
        });
    }
}
gProject.initElements = function()
{
    gProject.initFormTeacher.initFormFilter();  
    gProject.initFormTeacher.initInformation(); 
    gProject.initFormTeacher.initSecstionRight();
    gProject.initFormTeacher.initInformationRight();
    Project.initFormTeacher.EditTeacher(); 
    gProject.initFormTeacher.initFormEditTeacher(); 
    gProject.onReady();
}
gProject.onReady = function()
{
    
}
gProject.initFormTeacher = { 
    initFormFilter: function(){
        var strId = 'frmleftmenuTeacher';
        var elements = [];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                loadingBodyContentLeft(frm.attr('action'),{
                    data:data,
                    onSuccess:function(){
                        gProject.initElements();
                    }
                }) 
            }
        });
    }  ,
    initInformation : function()
    {
        jQuery(".cmdloadTeacherinfo").unbind('click');
        jQuery(".cmdloadTeacherinfo").click(function(){
            var el = this;
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    jQuery(el).parent().parent().parent().find('.cmdloadTeacherinfo').each(function(){
                        jQuery(this).css("color",'#2A4100');
                        jQuery(this).css("font-weight",'normal');
                    });
                    jQuery(el).css("color",'blue');
                    jQuery(el).css("font-weight",'bold');
                    gProject.initElements();
                }
            });
        });
    },
    initInformation : function()
    {
        jQuery(".cmdloadTeacherinfo").unbind('click');
        jQuery(".cmdloadTeacherinfo").click(function(){
            var el = this;
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    jQuery(el).parent().parent().parent().find('.cmdloadTeacherinfo').each(function(){
                        jQuery(this).css("color",'#2A4100');
                        jQuery(this).css("font-weight",'normal');
                    });
                    jQuery(el).css("color",'blue');
                    jQuery(el).css("font-weight",'bold');
                    gProject.initElements();
                }
            });
        });
    },
    initInformationRight : function()
    {
        jQuery(".cmdloadTeacherinfoRight").unbind('click');
        jQuery(".cmdloadTeacherinfoRight").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    initSecstionRight : function()
    {
        jQuery(".cmdloadSectionsRight").unbind('click');
        jQuery(".cmdloadSectionsRight").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    gProject.initElements();
                }
            });
        });
    }
};
    
                
$(document).ready(function(){	
    gProject.initElements();
});

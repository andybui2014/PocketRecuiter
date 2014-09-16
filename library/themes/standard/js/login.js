
var gProject = {};	
window.gProject = gProject;
$(document).ready(function(){	
    gProject.initForm.initFormLogin();
    gProject.initForm.initFormForgotpassword();
    gProject.initElements();
    gProject.onReady();
});

/******************************
 ***** Show error if have *****
 ******************************/
/******************************
 ***** Functions built in *****
 ******************************/
gProject.onReady = function(){
    // gProject.generateJsonData();
    jQuery('#cmdChangeLogo').unbind().bind('click',function(e){
        jShowLayer('#changeLogoBox');
    });
    //init calendar
    jQuery( ".datepicker" ).datepicker({dateFormat: 'dd/mm/yy'});
    // check message error or success
    //gProject.checkValidateError();
    //gProject.checkSuccessMessage();
    //gProject.initChart();
};


gProject.getNumber = function(){
	var n = Number(String(Math.random()*100).substring(0, 5));
	var n1 = Math.random()*1000;
	var n2 = Math.random()*1000;
	n = ((n2 > n1)?n:(-1)*n);
	n = (n < 5 && n > -5)?n:this.getNumber();
	return Number(n);
}

gProject.generateJsonData = function(){
	var start = new Date(2012,3,17,7,0,0).valueOf();	
	var max = new Date(2012,3,24,7,0,0).valueOf();
	
        var count = 0;
//	var str = '{"data":';
			var str = '[';
			for(var i=start; i<=max; i+=86400000){
				count += gProject.getNumber();
                                if(count < 0){
                                    count = 0 - count;
                                }
				if(i == max){
					str += '[' + i + ',' + String(count).substring(0, 5) + ']';					
				}else{	
					str += '[' + i + ',' + String(count).substring(0, 5) + '],';
				}
			}
		str +=	']';
	return str;
}

gProject.initForm = { 
    initFormLogin: function(){
        var strId = 'frmLogin';
        var elements = [{
            field: 'username',
            valid: 'require',
            messages: "Please Enter Username."
        },{
            field: 'password',
            valid: 'require',
            messages: "Please Enter Password."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                       if(resp.success)
                       {
                           alert('URL_BASE: '+URL_BASE);
                           //window.location = URL_BASE;
                           window.location = URL_BASE+'client/start-profile';
                       }    
                       else
                       {
                           frm[0].showHideError(null, resp.error);
                       }    
                       frm[0]._submitted = false;
                    }
                });
            }
        });
    },
    initFormForgotpassword: function(){
        var strId = 'frmForgotPassword';
        var elements = [{
            field: 'username',
            valid: function(){
                if(jQuery.trim(jQuery('#'+strId+' #username').val())==''
                    && jQuery.trim(jQuery('#'+strId+' #email').val())==''){
                    return 0;
                }
                return -1;
            },
            messages: "Please Enter Username."
        },{
            field: 'email',
            valid: function(){
                if(jQuery.trim(jQuery('#'+strId+' #username').val())==''
                    && jQuery.trim(jQuery('#'+strId+' #email').val())==''){
                    return 0;
                }
                return -1;
            },
            messages: "Please Enter Password."
        },{
            field: 'email',
            valid: 'email',
            messages: "Invalid email format"
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                       frm[0]._submitted = false;
                    }
                });
            }
        });
    }
};

gProject.initElements = function(){
        
};

gProject.callBackFunc = {
    changeLogo: function(url){
        jQuery('#changeLogoBox .logo-wrap img').attr('src', url);
    },
    keepFilename: function(filename){
        jQuery('#hidFileName').val(filename);
    }, 
    changeLogoGeneric: function(id,url){
        jQuery('#'+id+' .logo-wrap img').attr('src', url);
    },
    keepFilenameGeneric: function(id,filename){
        jQuery('#'+id+' #hidFileName').val(filename);
    }
};


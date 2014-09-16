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
            jQuery('#frmStudentEdit #btnChangeLogo').click(function(){
                jQuery("#frmStudentEdit input:file[name='fileUpload']").click(); 
            });
        });
    }
}

function jCustomUploadStudentAdd(){	
    var btnBrowse = jQuery('.jQUploadStudentAdd');	
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
                    jQuery('#frmStudentAdd')[0].submit();
                }
            });			
            jQuery(btn).data('divInput',divInput);			
            jQuery(btn).after(divInput);
            jQuery(btn).css("display","block");
            jQuery('#frmStudentAdd #btnChangeLogo').click(function(){
                jQuery("#frmStudentAdd input:file[name='fileUpload']").click(); 
            });
        });
    }
}

gProject.initElements = function()
{
    gProject.initFormStudent.initFormFilter();
    gProject.initFormStudent.initInformation();
    gProject.initFormStudent.initSecstionRight();
    gProject.initFormStudent.initInformationRight();
    gProject.initFormStudent.initGradesRight();
    gProject.initFormStudent.initTranscriptsRight();
    gProject.initFormStudent.EditStudent();
    gProject.initFormStudent.initFormEditStudent();
    gProject.initFormStudent.AddStudent();
    gProject.initFormStudent.DeleteStudent();
    gProject.initFormStudent.initFormAddStudent();
    gProject.initFormStudent.initFormEnrolsectionStudent();
    gProject.initFormStudent.AddSection();
    gProject.initFormStudent.initFormAddSection();
    gProject.initFormStudent.initFormAssigngradeStudent();
    gProject.initFormStudent.EditSection(); 
    gProject.initFormStudent.DeleteSection();
    gProject.initFormStudent.initFormEditSection();
    gProject.initFormStudent.AssigngradeStudent();
    gProject.initFormStudent.initGradesOrther();
    gProject.initFormStudent.EditGrades();
    gProject.initFormStudent.initFormEditGrades();
    gProject.initFormStudent.DeleteGrades();
    gProject.initFormStudent.OrthergradeStudent();
    gProject.initFormStudent.EditOrthergrade();
    gProject.initFormStudent.SendEmailInformation();
    gProject.initFormStudent.initFormOrtherGradeAdd();
    gProject.initFormStudent.initFormOrtherGradeEdit();
    gProject.initFormStudent.DeleteOtherGrades();
    gProject.onReady();
}
gProject.onReady = function()
{
    jQuery( ".datepicker" ).datepicker({
        dateFormat: 'mm/dd/yy'
    });
    buildClickCheckSelectOne("#content .editSection");
    buildClickCheckSelectOne("#content .editGrade");
   
    // load success for setion
    $('.showcurrentsection').click(
        function()
        {
            var totalrecord = 0;
        
            if ($(".showcurrentsection").is(":checked"))
            {
                $('.iscurrentsection').each(function(index){
                    totalrecord += 1;
                    if($(this).attr('iscurrentsection') == 0)
                    {
                        totalrecord -= 1;
                        $(this).css('display','none');
                    }
                });
                $('.totalrecord').html("This student currently has " + totalrecord + " section(s):");
                $('#totalrecords').html("Total records -  " + totalrecord + "");
                
            }
            else
            {
                $('.iscurrentsection').each(function(index){
                    totalrecord += 1;
                    if($(this).attr('iscurrentsection') == 0)
                        $(this).css('display','');
                });
                $('.totalrecord').html("This student has " + totalrecord + " section(s):");
                $('#totalrecords').html("Total records -  " + totalrecord + "");
            }
            
        });


}
gProject.initFormStudent = { 
    initFormFilter: function(){
        var strId = 'frmleftmenu';
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
    },
    initInformation : function()
    {
        jQuery(".cmdloadStudentinfo").unbind('click');
        jQuery(".cmdloadStudentinfo").click(function(){
            var el = this;
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    jQuery(el).parent().parent().parent().find('.cmdloadStudentinfo').each(function(){
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
        jQuery(".cmdloadStudentinfoRight").unbind('click');
        jQuery(".cmdloadStudentinfoRight").click(function(){
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
    },
    initGradesRight : function()
    {
        jQuery(".cmdloadGradesRight").unbind('click');
        jQuery(".cmdloadGradesRight").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    initTranscriptsRight : function()
    {
        jQuery(".cmdloadTranscriptsRight").unbind('click');
        jQuery(".cmdloadTranscriptsRight").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    EditStudent : function()
    {
        jQuery(".cmdEditStudent").unbind('click');
        jQuery(".cmdEditStudent").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    initFormEditStudent : function()
    {
        
        var strId = 'frmStudentEdit';
        var elements = [{
            field: 'lname',
            valid: 'require',
            messages: "Please enter Last Name."
        },{
            field: 'fname',
            valid: 'require',
            messages: "Please enter First Name."
        },{
            field: 'gender',
            valid: 'require',
            messages: "Please select Gender."
        },{
            field: 'email',
            valid: 'require',
            messages: "Please enter Email."
        },{
            field: 'email',
            valid: 'email',
            messages: "Invalid E-mail."
        },{
            field: 'address',
            valid: 'require',
            messages: "Please enter Address."
        },{
            field: 'city',
            valid: 'require',
            messages: "Please enter City."
        },{
            field: 'state',
            valid: 'require',
            messages: "Please enter State."
        },{
            field: 'country',
            valid: 'require',
            messages: "Please select Country."
        },{
            field: 'zip',
            valid: 'require',
            messages: "Please enter ZIP."
        },{
            field: 'zip',
            valid: 'zip',
            messages: "Invalid ZIP."
        },{
            field: 'phone',
            valid: 'require',
            messages: "Please enter Phone."
        },{
            field: 'phone',
            valid: 'phone',
            messages: "Invalid Phone."
        },{
            field: 'mobile',
            valid: 'phone',
            messages: "Invalid Mobile."
        },{
            field: 'ethnicity',
            valid: 'require',
            messages: "Please select Ethnicity."
        },{
            field: 'g1name',
            valid: 'require',
            messages: "Please enter Guardian1 Name."
        },{
            field: 'g1relation',
            valid: 'require',
            messages: "Please enter Guardian1 Relationship."
        },{
            field: 'g1phone',
            valid: 'require',
            messages: "Please enter Guardian1 work Phone."
        },{
            field: 'g1phone',
            valid: 'phone',
            messages: "Invalid Guardian1 Work Phone."
        },{
            field: 'g1mobile',
            valid: 'phone',
            messages: "Invalid Guardian1 Mobile."
        },{
            field: 'g1email',
            valid: 'email',
            messages: "Invalid Guardian1 E-mail."
        },{
            field: 'g2phone',
            valid: 'phone',
            messages: "Invalid Guardian2 Work Phone."
        },{
            field: 'g2mobile',
            valid: 'phone',
            messages: "Invalid Guardian2 Mobile."
        },{
            field: 'g2email',
            valid: 'email',
            messages: "Invalid Guardian2 E-mail."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery("#cmdloadStudentinfoRight").click();
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
        jCustomUploadStudentEdit();
    },
    DeleteStudent : function()
    {
        jQuery(".cmdDeletedStudent").unbind('click');
        jQuery(".cmdDeletedStudent").click(function(){
            var ref = jQuery(this).attr('ref');
            var msg = jQuery(this).attr('msg');
            jShowConfirm('#confirmLayer',{
                message: msg,
                header: "Confirm",
                onYes: function(){
                    jQuery.ajax({
                        url: ref,
                        type: 'POST',
                        success: function(resp){
                            loadingBodyContent(URL_BASE + "index/index?page=sections&ajax=1");
                        }
                    });
                }
            });
            
        });
    },
    initFormAddStudent : function()
    {
        
        var strId = 'frmStudentAdd';
        var elements = [{
            field: 'lname',
            valid: 'require',
            messages: "Please enter Last Name."
        },{
            field: 'fname',
            valid: 'require',
            messages: "Please enter First Name."
        },{
            field: 'gender',
            valid: 'require',
            messages: "Please select Gender."
        },{
            field: 'email',
            valid: 'require',
            messages: "Please enter Email."
        },{
            field: 'email',
            valid: 'email',
            messages: "Invalid E-mail."
        },{
            field: 'address',
            valid: 'require',
            messages: "Please enter Address."
        },{
            field: 'city',
            valid: 'require',
            messages: "Please enter City."
        },{
            field: 'state',
            valid: 'require',
            messages: "Please enter State."
        },{
            field: 'country',
            valid: 'require',
            messages: "Please select Country."
        },{
            field: 'zip',
            valid: 'require',
            messages: "Please enter ZIP."
        },{
            field: 'zip',
            valid: 'zip',
            messages: "Invalid ZIP."
        },{
            field: 'phone',
            valid: 'require',
            messages: "Please enter Phone."
        },{
            field: 'phone',
            valid: 'phone',
            messages: "Invalid Phone."
        },{
            field: 'mobile',
            valid: 'phone',
            messages: "Invalid Mobile."
        },{
            field: 'ethnicity',
            valid: 'require',
            messages: "Please select Ethnicity."
        },{
            field: 'g1name',
            valid: 'require',
            messages: "Please enter Guardian1 Name."
        },{
            field: 'g1relation',
            valid: 'require',
            messages: "Please enter Guardian1 Relationship."
        },{
            field: 'g1phone',
            valid: 'require',
            messages: "Please enter Guardian1 work Phone."
        },{
            field: 'g1phone',
            valid: 'phone',
            messages: "Invalid Guardian1 Work Phone."
        },{
            field: 'g1mobile',
            valid: 'phone',
            messages: "Invalid Guardian1 Mobile."
        },{
            field: 'g1email',
            valid: 'email',
            messages: "Invalid Guardian1 E-mail."
        },{
            field: 'g2phone',
            valid: 'phone',
            messages: "Invalid Guardian2 Work Phone."
        },{
            field: 'g2mobile',
            valid: 'phone',
            messages: "Invalid Guardian2 Mobile."
        },{
            field: 'g2email',
            valid: 'email',
            messages: "Invalid Guardian2 E-mail."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery("#cmdloadStudentinfoRight").click();
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
        jCustomUploadStudentAdd();
    },
    AddStudent : function()
    {
        jQuery(".cmdAddStudent").unbind('click');
        jQuery(".cmdAddStudent").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    initFormEnrolsectionStudent : function()
    {
        
        var strId = 'frmEnrol';
        var elements = [{
            field: 'department',
            valid: 'require',
            messages: "Please enter select department."
        },
        {
            field: 'course',
            valid: 'require',
            messages: "Please select course."
        },
        {
            field: 'teacher',
            valid: 'require',
            messages: "Please select teacher."
        },
        {
            field: 'section',
            valid: 'require',
            messages: "Please select section."
        },
        {
            field: 'start_date',
            valid: 'require',
            messages: "Please enter start date."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery("#cmdloadStudentinfoRight").click();
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
    
    initFormAddSection : function()
    {
        var strId = 'frmEnrol';
        var elements = [{
            field: 'department',
            valid: 'require',
            messages: "Please enter select department."
        },
        {
            field: 'course',
            valid: 'require',
            messages: "Please select course."
        },
        {
            field: 'teacher',
            valid: 'require',
            messages: "Please select teacher."
        },
        {
            field: 'section',
            valid: 'require',
            messages: "Please select section."
        },
        {
            field: 'start_date',
            valid: 'require',
            messages: "Please enter start date."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery("#cmdloadSectionsRight").click();
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
    AddSection : function()
    {
        jQuery(".cmdAddSection").unbind('click');
        jQuery(".cmdAddSection").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                    jQuery("#content #department").unbind("change",getCourceByDept)
                    .bind("change",getCourceByDept);
                    jQuery("#content #course").unbind("change",getTeacherByCour)
                    .bind("change",getTeacherByCour);
                    jQuery("#content #teacher").unbind("change",getSectionByTeacher)
                    .bind("change",getSectionByTeacher);
                }
            });
        });
    }, 
    
    
    //-----------------------------------EDIT SESION
    EditSection : function()
    {
        jQuery(".cmdEditSection").unbind('click');
        jQuery(".cmdEditSection").click(function(){
            var data = getCheckboxList("#listSections .editSection");
            var msg = "";
            if(data.length == 0)  msg = "Please select an enrollment to edit.";
            if(data.length > 1)  msg = "Please just select an enrollment to edit.";
            if(msg == "")
            {
                loadingBodyContentRight(jQuery(this).attr('ref'),{
                    onSuccess:function(){
                        // load success   
                        gProject.initElements();
                    },
                    data:'enrol_id='+ data[0]
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
        });
        
 
    },
    initFormEditSection : function()
    {
        
        var strId = 'frmEditSection';
        var elements = [{
            field: 'start_date',
            valid: 'require',
            messages: "Please enter start date."
        },{
            field: 'end_date',
            valid: 'require',
            messages: "Please enter end date."
        },
        {
            field: 's_status',
            valid: 'require',
            messages: "Please enter status."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery("#cmdloadSectionsRight").click();
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
        
    } ,
    DeleteSection : function()
    {
        jQuery(".cmdDeletedSections").unbind('click');
        jQuery(".cmdDeletedSections").click(function(){
            var data = getCheckboxList("#listSections .editSection");
            var msg = "";
            if(data.length == 0)  msg = "Please select an enrollment to delete.";
            if(msg == "")
            {
                var ref = jQuery(this).attr('ref');
                jShowConfirm('#confirmLayer',{
                    message: "Are you sure to delete the selected enrollment?",
                    header: "Warning",
                    onYes: function(){
                        jQuery.ajax({
                            url: ref,
                            data:"enrol_id="+data[0],
                            type: 'POST',
                            success: function(resp){
                                jQuery("#cmdloadSectionsRight").click();
                            }
                        });
                    }
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
                    
        });
                
    },
           
         
   
           
    
//---------------------------- grade------------------------------
initFormAssigngradeStudent : function()
    {
        
        var strId = 'frmassigngrade';
        var elements = [{
            field: 'department',
            valid: 'require',
            messages: "Please select department."
        },
        {
            field: 'course',
            valid: 'require',
            messages: "Please select course."
        },
        {
            field: 'start_date',
            valid: 'require',
            messages: "Please enter date."
        },
        {
            field: 'sem1_credits',
            valid: 'require',
            messages: "Please select Sem 1 Credit."
        },
        {
            field: 'sem2_credits',
            valid: 'require',
            messages: "Please select Sem 2 Credit."
        }
        ];
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
                            jQuery(".cmdloadGradesRight").click();
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
    AssigngradeStudent : function()
    {
        jQuery(".cmdAssignGradeStudent").unbind('click');
        jQuery(".cmdAssignGradeStudent").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                    jQuery("#content #department").unbind("change",getCourceByDept)
                    .bind("change",getCourceByDept);
                }
            });
        });
    } ,
    initGradesOrther : function()
    {
        jQuery(".cmdloadGradesOrther").unbind('click');
        jQuery(".cmdloadGradesOrther").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    },
    EditGrades : function()
    {
        jQuery(".cmdEditGrades").unbind('click');
        jQuery(".cmdEditGrades").click(function(){
            var data = getCheckboxList("#listGrade .editGrade");
            var msg = "";
            if(data.length == 0)  msg = "Please select a grade to edit.";
            if(data.length > 1)  msg = "Please just select a grade to edit.";
            if(msg == "")
            
            {
                loadingBodyContentRight(jQuery(this).attr('ref'),{
                    onSuccess:function(){
                        // load success   
                        gProject.initElements();
                    },
                    data:'grade_id='+ data[0]
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
        });
    } ,
    initFormEditGrades : function()
    {
        jQuery("#frmeditgrade #department").unbind("change",getCourceByDept).bind("change",getCourceByDept);
        var strId = 'frmeditgrade';
        var elements = [
            {
                field: 'department',
                valid: 'require',
                messages: "Please select department."
            },
            {
                field: 'course',
                valid: 'require',
                messages: "Please select course."
            }
            ,
            {
                field: 'grade_date',
                valid: 'require',
                messages: "Please enter date."
            },
            {
                field: 'sem1_credits',
                valid: 'require',
                messages: "Please select Sem 1 Credit."
            },
            {
                field: 'sem2_credits',
                valid: 'require',
                messages: "Please select Sem 2 Credit."
            }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery('#hidSaveOrUpload').val(0);
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery(".cmdloadGradesRight").click();
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
        
    }  ,
     DeleteGrades : function()
    {
        jQuery(".cmdDeletedGradeassi").unbind('click');
        jQuery(".cmdDeletedGradeassi").click(function(){
            var data = getCheckboxList("#listGrade .editGrade");
            var msg = "";
            if(data.length == 0)  msg = "Please select a  grade to delete.";
            if(msg == "")
            {
                var ref = jQuery(this).attr('ref');
                jShowConfirm('#confirmLayer',{
                    message: "Are you sure to delete the selected grade?",
                    header: "Warning",
                    onYes: function(){
                        jQuery.ajax({
                            url: ref,
                            data:"grade_id="+data[0],
                            type: 'POST',
                            success: function(resp){
                                jQuery(".cmdloadGradesRight").click();
                            }
                        });
                    }
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
                    
        });
                
    },
    //------------------ orther grader---------- 
     OrthergradeStudent : function()
    {
        jQuery(".cmdOrthergradesStudent").unbind('click');
        jQuery(".cmdOrthergradesStudent").click(function(){
            loadingBodyContentRight(jQuery(this).attr('ref'),{
                onSuccess:function(){
                    // load success
                    gProject.initElements();
                }
            });
        });
    } ,
    
     EditOrthergrade : function()
    {
        jQuery(".cmdEditOrthergrade").unbind('click');
        jQuery(".cmdEditOrthergrade").click(function(){
             var data = getCheckboxList("#listGradeOrther .editGrade");
            var msg = "";
            if(data.length == 0)  msg = "Please select a grade to edit.";
            if(data.length > 1)  msg = "Please just select a grade to edit.";
            if(msg == "")
            
            {
                loadingBodyContentRight(jQuery(this).attr('ref'),{
                    onSuccess:function(){
                        // load success   
                        gProject.initElements();
                    },
                    data:'grade_id='+ data[0]
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
        });
    }
  ,
  SendEmailInformation : function (){
      jQuery(".cmdSendEmailInformation").unbind('click');
        jQuery(".cmdSendEmailInformation").click(function(){
            var ref = jQuery(this).attr('ref');
            sendemail("#divSendEmail",ref);  
        });
  },
  initFormOrtherGradeAdd : function()
    {
        
        var strId = 'frmOrtherGradesAAd';
        var elements = [{
            field: 'department',
            valid: 'require',
            messages: "Please select department."
        },
        {
            field: 'course',
            valid: 'require',
            messages: "Please select course."
        },
        {
            field: 'dt',
            valid: 'require',
            messages: "Please enter date."
        },
            {
                field: 'sem1_credits',
                valid: 'require',
                messages: "Please select Sem 1 Credit."
            },
            {
                field: 'sem2_credits',
                valid: 'require',
                messages: "Please select Sem 2 Credit."
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
                            jQuery("#cmdloadGradesOrther").click();
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
  initFormOrtherGradeEdit : function()
    {
        
        var strId = 'frmOrtherGradesEdit';
        var elements = [{
            field: 'department',
            valid: 'require',
            messages: "Please select department."
        },
        {
            field: 'course',
            valid: 'require',
            messages: "Please select course."
        },
        {
            field: 'dt',
            valid: 'require',
            messages: "Please enter date."
        },
            {
                field: 'sem1_credits',
                valid: 'require',
                messages: "Please select Sem 1 Credit."
            },
            {
                field: 'sem2_credits',
                valid: 'require',
                messages: "Please select Sem 2 Credit."
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
                            jQuery("#cmdloadGradesOrther").click();
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
     DeleteOtherGrades : function()
    {
        jQuery(".cmdDeleteOrthergrade").unbind('click');
        jQuery(".cmdDeleteOrthergrade").click(function(){
            var data = getCheckboxList("#listGradeOrther .editGrade");
            var msg = "";
            if(data.length == 0)  msg = "Please select a grade to delete.";
            if(msg == "")
            {
                var ref = jQuery(this).attr('ref');
                jShowConfirm('#confirmLayer',{
                    message: "Are you sure to delete the selected grade?",
                    header: "Warning",
                    onYes: function(){
                        jQuery.ajax({
                            url: ref,
                            data:"grade_id="+data[0],
                            type: 'POST',
                            success: function(resp){
                                jQuery(".cmdloadGradesOrther").click();
                            }
                        });
                    }
                });
            }
            else
            {
                jShowConfirm('#msgLayer',{
                    message: msg,
                    header: ""
                });
            }
                    
        });
                
    }
};

$(document).ready(function(){    
    gProject.initElements();
});
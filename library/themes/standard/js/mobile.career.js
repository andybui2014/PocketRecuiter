function career(){ }
career.prototype = {
    init: function(){
        $(".checkIs").unbind('click').bind('click',this.checkIs);
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#addnotification').unbind('click').bind('click',this.addnotification);
        $('#EditNotification').unbind('click').bind('click',this.editnotification);
        $('#saveEditnotification').unbind('click').bind('click',this.saveEditnotification);
        $('#deleteNotification').unbind('click').bind('click',this.deleteNotification);

       // $('.edit-career-btn').unbind('click').bind('click',this.editcareerbtn);

        $('.calcareercr').unbind('click').bind('click',this.calcareercr);
        $('#careerlist').unbind('click').bind('click',this.careerlist);
        $('#addSkill').unbind('click').bind('click',this.addSkill);
        $('#addrequiredtest').unbind('click').bind('click',this.addrequiredtest);
        $('#postcareernew').unbind('click').bind('click',this.postcareernew);
        //required test will be removed when clicked for first time page on load
        $(".removeRequiredTestOnload").unbind("click").bind("click",function(){
            var testID = $(this).attr("testID");
            $("#selectRequiredTest").find("option[value='" + testID + "']").css("display", "");
            $(this).parent().remove();
        });

        //skill will be removed when clicked for first time page on load
        $(".removeSkillOnload").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilID");
            $("#selectSkill").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();
        });

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
    addnotification: function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'save-notifications',
            data: $('#form-addnotification').serializeArray(),
            type: 'POST',
            success: function(xhr){
                if(xhr.success){
                    window.location = 'list';
                   // btn.button('reset');
                }else{
                }
            }
        });
    },
    editnotification:function(){
        var length = 0;
        length = $(".ischecktr input:checked").length;
        var disabled = "";
        if (length ==1){
            var content = $(".ischecktr input:checked").parent().find('.notiText').text();
            content = $.trim(content);
            var notiID = $(".ischecktr input:checked").attr('NotiID');
            notiID = $.trim(notiID);
            $('#EditModalNotification').modal('show');
            $('form#form-editnotification #editNotification').val(content);
            $('form#form-editnotification #ModalEditNotiID').val(notiID);
        } else if(length == 0){
            alert("Select a Notification");
        }
        else {
            alert("Only select a Notification");
        }
    },
    saveEditnotification:function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'save-edit-notifications',
            data: $('#form-editnotification').serializeArray(),
            type: 'POST',
            success: function(xhr){
                if(xhr.success){
                    window.location = 'list';
                    btn.button('reset');
                }else{
                }
            }
        });
    },
    deleteNotification:function(){
        var length = 0;
        var listNotiID = [];
        length = length = $(".ischecktr input:checked").length;
        if(length > 0){
            $(".ischecktr input:checked").each(function(){
                var list = "";
                    list = $(this).attr('NotiID');
                    listNotiID.push(list);
            });

            $.ajax({
                url: 'delete-notifications',
                data:{listNotiID:listNotiID},
                type: 'POST',
                success: function(xhr){
                    if(xhr.success){
                        window.location = 'list';
                    }else{
                    }
                }
            });

        }else{
            alert("Please select a notifications");
            return;
        }
    },
    calcareercr:function(){
        window.location = 'careercreate';
    },
    careerlist:function(){
        window.location = 'careerlist';
    },
    addSkill:function(){
        var skilIDSe = $(this).parent().parent().find("#selectSkill option:selected").val();
        var skillText = $(this).parent().parent().find("#selectSkill option:selected").text();
        $("div#requiredSkillClass").append("<span style='padding-left:0px; width:100px' class='form-control'>" +
            "<imge class='removeskill glyphicon glyphicon-remove' height='15px' skilID='"+skilIDSe+"' style='cursor:pointer;' >"+skillText+" <input type='hidden'  name='SkillID[]' value='"+skilIDSe+"' ></span>");

        $(".removeskill").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilID");
            $("#selectSkill").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();
        });

        $(this).parent().parent().find("#selectSkill option:selected").css("display", "none");
        $(this).parent().parent().find("#selectSkill option[value='']").prop("selected", "selected");
    },
    addrequiredtest:function(){
        var testID = $(this).parent().parent().find("#selectRequiredTest option:selected").val();
        var testName = $(this).parent().parent().find("#selectRequiredTest option:selected").text();
        $("div#requiredTest").append("<div style='padding-left:5px'>" +
            "<imge class='removeTest' src='images/delete.png' height='15px' testID='"+testID+"' style='cursor:pointer;'>"+testName+"<input type='hidden'  name='testid[]' value='"+testID+"' ></div> ");

        $(".removeTest").unbind("click").bind("click",function(){
            var testID = $(this).attr("testID");
            $("#selectRequiredTest").find("option[value='" + testID + "']").css("display", "");
            $(this).parent().remove();
        });

        $(this).parent().parent().find("#selectRequiredTest option:selected").css("display", "none");
        $(this).parent().parent().find("#selectRequiredTest option[value='']").prop("selected", "selected");
    },
    previewPost: function(){
        var pp_career_name = $("#careername").val();
        $("#previewpost #pp_career_name").text(pp_career_name);
        var pp_company_name = $("#companyname").val();
        $("#previewpost #pp_company_name").text(pp_company_name);
        var pp_career_des = $("#careerdescription").val();
        $("#previewpost #pp_career_des").text(pp_career_des);
        var pp_industry = $("#industry").val();
        $("#previewpost #pp_industry").text(pp_industry);
        var pp_career_type = $("#careerType option:selected").val();
        $("#previewpost #pp_career_type").text(pp_career_type);

        var pp_career_location = $("#loacation").val();
        $("#previewpost #pp_career_location").text(pp_career_location);
        var pp_minimun_education = $("#minimuneducation").val();
        $("#previewpost #pp_minimun_education").text(pp_minimun_education);
        var pp_degree_title = $("#degreetitle").val();
        $("#previewpost #pp_degree_title").text(pp_degree_title);

        var pp_required_skills = "";
        $(".removeskill").each(function(){
            pp_required_skills  =  pp_required_skills  + $(this).text()+ ";";
        });

        $("#previewpost #pp_required_skills").text(pp_required_skills);

        var pp_required_experience = $("#requiredExperience option:selected").val();
        $("#previewpost #pp_required_experience").text(pp_required_experience);

        var PPSRFrom = $("#salaryRangeF option:selected").val();
        var PPSRTo = $("#salaryRangeT option:selected").val();
        var pp_salary_range = "$" + PPSRFrom + "-" + PPSRTo;
        $("#previewpost #pp_salary_range").text(pp_salary_range);

        var pp_required_test = "";
        $(".removeTest").each(function(){
            pp_required_test  =  pp_required_test  + $(this).text()+ ";";
        });

        $("#previewpost #pp_required_test").text(pp_required_test);
    },
    postcareernew:function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'save-career-new',
            data: $('#form-careerCr').serializeArray(),
            type: 'POST',
            success: function(xhr){
                if(xhr.success){
                    window.location = 'careerlist';
                    btn.button('reset');
                }else{
                    btn.button('reset');
                }
            }
        });
    },
    editcareerbtn:function(){
        var oppNumber =  $(this).children('.oppID').val();
        $.ajax({
            url: 'careeredit',
            data: {oppNumber:oppNumber},
            type: 'POST',
            success: function(data,xhr,status){

            }
        });
    }
}

$(function() {
    var mbNot= new career();
    mbNot.init();
});

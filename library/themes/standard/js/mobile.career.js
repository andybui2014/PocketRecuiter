function career(){ }
career.prototype = {
    init: function(){
        /*
        $(".checkIs").unbind('click').bind('click',this.checkIs);
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#addnotification').unbind('click').bind('click',this.addnotification);
        $('#EditNotification').unbind('click').bind('click',this.editnotification);
        $('#saveEditnotification').unbind('click').bind('click',this.saveEditnotification);
        $('#deleteNotification').unbind('click').bind('click',this.deleteNotification); */

        $('.calcareercr').unbind('click').bind('click',this.calcareercr);
        $('#careerlist').unbind('click').bind('click',this.careerlist);
        $('#selectSkill').unbind('change').bind('change',this.addSkill);
        $('#selectRequiredTest').unbind('change').bind('change',this.addrequiredtest);
        $('#postcareernew').unbind('click').bind('click',this.postcareernew);
        $('#CompanyID').unbind('change').bind('change',this.setTestComp);
        $('#postcareeredit').unbind('click').bind('click',this.postcareeredit);
        $('#saveCompanyProfile').unbind('click').bind('click',this.saveCompanyProfile);

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

    calcareercr:function(){
        window.location = 'careercreate';
    },
    careerlist:function(){
        window.location = 'careerlist';
    },
    addSkill:function(){
        //var skilIDSe = $(this).parent().parent().find("#selectSkill option:selected").val();
        //var skillText = $(this).parent().parent().find("#selectSkill option:selected").text();
        var skillText = $("#selectSkill option:selected").text();
        var skilIDSe = $("#selectSkill option:selected").val();
        $("div#requiredSkillClass").append("<span style='' class='label-tag pull-left getSkillText'>" + skillText +
             "&nbsp;&nbsp;<imge class='removeskill glyphicon glyphicon-remove' height='15px' skilID='"+skilIDSe+"' style='cursor:pointer; color:#ccc;' > " +
             "<input type='hidden'  name='SkillID[]' value='"+skilIDSe+"' ></span>");

        $(".removeskill").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilID");
            $("#selectSkill").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();
        });

        $("#selectSkill option:selected").css("display", "none");
        $("#selectSkill option[value='']").prop("selected", "selected");
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
        $(".getSkillText").each(function(){
            pp_required_skills  =  pp_required_skills  + $.trim($(this).text()) + ";";
        });

        $("#previewpost #pp_required_skills").text(pp_required_skills);

        var pp_required_experience = $("#requiredExperience option:selected").val();
        $("#previewpost #pp_required_experience").text(pp_required_experience);

        var PPSRFrom = $("#salaryRangeF option:selected").val();
        var PPSRTo = $("#salaryRangeT option:selected").val();
        var pp_salary_range = "$" + PPSRFrom + "-" + PPSRTo;
        $("#previewpost #pp_salary_range").text(pp_salary_range);

        var pp_required_test = "";
        $(".getTest").each(function(){
            pp_required_test  =  pp_required_test  + $.trim($(this).text())+ ";";
        });

        $("#previewpost #pp_required_test").text(pp_required_test);
    },
    postcareernew:function(){
        var btn = $(this);
        btn.button('loading');
        var Veridate_careername = $("#careername").val();
        var Veridate_companyname = $("#CompanyID option:selected").val();
        var Veridate_careerdescription = $("#careerdescription").val();
        var Veridate_industry = $("#industry").val();
        var Veridate_minimuneducation = $("#minimuneducation").val();
        var Veridate_degreetitle = $("#degreetitle").val();
        var Veridate_requiredExperience = $("#requiredExperience").val();
        var Veridate_salaryRangeF = $("#salaryRangeF").val();
        var Veridate_salaryRangeT = $("#salaryRangeT").val();
        var Veridate_requiredSkillClass = "";
        $(".getSkillText").each(function(){
            Veridate_requiredSkillClass  =  Veridate_requiredSkillClass  + $.trim($(this).text());
        });
        $("#careernameLable").text("Career Name *");
        $("#careernameLable").css("color","black");
            $("#companynameLable").text("Company Name *");
            $("#companynameLable").css("color","black");
        $("#careerdescriptionLable").text("Career Description *");
        $("#careerdescriptionLable").css("color","black");
            $("#industryLable").text("Career Industry *");
            $("#industryLable").css("color","black");
        $("#minimuneducationLable").text("Minimun Education *");
        $("#minimuneducationLable").css("color","black");
            $("#degreetitleLable").text("Degree Title *");
            $("#degreetitleLable").css("color","black");
        $("#requiredSkillLable").text("Required Skills *");
        $("#requiredSkillLable").css("color","black");
            $("#requiredExperienceLable").text("Required Experience *");
            $("#requiredExperienceLable").css("color","black");
        $("#salaryRangeLable").text("Salary Range *");
        $("#salaryRangeLable").css("color","black");

        if(Veridate_careername ==""){
            $("#careernameLable").text("Career Name must require");
            $("#careernameLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_companyname ==""){
            $("#companynameLable").text("Company must require");
            $("#companynameLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_careerdescription ==""){
            $("#careerdescriptionLable").text("Career Description must require");
            $("#careerdescriptionLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_industry ==""){
            $("#industryLable").text("Career Industry must require");
            $("#industryLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_minimuneducation ==""){
            $("#minimuneducationLable").text("Minimun Education must require");
            $("#minimuneducationLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_degreetitle ==""){
            $("#degreetitleLable").text("Degree Title must require");
            $("#degreetitleLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_requiredSkillClass ==""){
            $("#requiredSkillLable").text("Required Skills must require");
            $("#requiredSkillLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(requiredExperience ==""){
            $("#requiredExperienceLable").text("Required Experience must require");
            $("#requiredExperienceLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }else if(Veridate_salaryRangeF =="" || Veridate_salaryRangeT ==""){
            $("#salaryRangeLable").text("Salary Range must require");
            $("#salaryRangeLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }


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
    postcareeredit:function(){
        var btn = $(this);
        btn.button('loading');

        var Veridate_careername = $("#careername").val();
        var Veridate_companyname = $("#CompanyID option:selected").val();
        var Veridate_careerdescription = $("#careerdescription").val();
        var Veridate_industry = $("#industry").val();
        var Veridate_minimuneducation = $("#minimuneducation").val();
        var Veridate_degreetitle = $("#degreetitle").val();
        var Veridate_requiredExperience = $("#requiredExperience").val();
        var Veridate_salaryRangeF = $("#salaryRangeF").val();
        var Veridate_salaryRangeT = $("#salaryRangeT").val();
        var Veridate_requiredSkillClass = "";
        $(".getSkillText").each(function(){
            Veridate_requiredSkillClass  =  Veridate_requiredSkillClass  + $.trim($(this).text());
        });
        $("#careernameLable").text("Career Name *");
        $("#careernameLable").css("color","black");
        $("#companynameLable").text("Company Name *");
        $("#companynameLable").css("color","black");
        $("#careerdescriptionLable").text("Career Description *");
        $("#careerdescriptionLable").css("color","black");
        $("#industryLable").text("Career Industry *");
        $("#industryLable").css("color","black");
        $("#minimuneducationLable").text("Minimun Education *");
        $("#minimuneducationLable").css("color","black");
        $("#degreetitleLable").text("Degree Title *");
        $("#degreetitleLable").css("color","black");
        $("#requiredSkillLable").text("Required Skills *");
        $("#requiredSkillLable").css("color","black");
        $("#requiredExperienceLable").text("Required Experience *");
        $("#requiredExperienceLable").css("color","black");
        $("#salaryRangeLable").text("Salary Range *");
        $("#salaryRangeLable").css("color","black");

        if(Veridate_careername ==""){
            $("#careernameLable").text("Career Name must require");
            $("#careernameLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_companyname ==""){
            $("#companynameLable").text("Company must require");
            $("#companynameLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_careerdescription ==""){
            $("#careerdescriptionLable").text("Career Description must require");
            $("#careerdescriptionLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_industry ==""){
            $("#industryLable").text("Career Industry must require");
            $("#industryLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_minimuneducation ==""){
            $("#minimuneducationLable").text("Minimun Education must require");
            $("#minimuneducationLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_degreetitle ==""){
            $("#degreetitleLable").text("Degree Title must require");
            $("#degreetitleLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_requiredSkillClass ==""){
            $("#requiredSkillLable").text("Required Skills must require");
            $("#requiredSkillLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(requiredExperience ==""){
            $("#requiredExperienceLable").text("Required Experience must require");
            $("#requiredExperienceLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }else if(Veridate_salaryRangeF =="" || Veridate_salaryRangeT ==""){
            $("#salaryRangeLable").text("Salary Range must require");
            $("#salaryRangeLable").css("color","red");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }

        $.ajax({
            url: 'edit-career',
            data: $('#form-careerEdit').serializeArray(),
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
    setTestComp:function()
    {
        var IDComp = $("#CompanyID option:selected").val();
        var NameComp = $("#CompanyID option:selected").text();
       // if(IDComp !=""){
            $("#companyname").val(NameComp);
            var currentlist = [];
            $(".removeTest").each(function(){
               var vl = $(this).attr('testid');
                currentlist.push(vl);
            })

            $.ajax({
                url: 'get-test',
                dataType    : 'json',
                data: {CompanyID:IDComp},
                type: 'POST',
                success: function(data, status, xhr){
                    if(data){
                        var str = "<option value=''>Select Test</option>";
                        $.each(data,function(k,val){
                            var position = val["TestID"];
                            var retn ="";
                             retn = currentlist.indexOf(position);
                            if(retn == -1){
                                str +="<option value='"+val["TestID"]+"'>"+val["TestName"]+"</option>";
                            } else {
                                str +="<option style='display:none' value='"+val["TestID"]+"'>"+val["TestName"]+"</option>";
                           }


                        });
                        $('#selectRequiredTest').find('option').remove().end().append(str) ;
                    }
                }
            });
       // }
    },
    saveCompanyProfile:function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'do-company-profile',
            data: $('#form-companyProfile').serializeArray(),
            type: 'POST',
            error : function (xhr,error) {
                btn.button('reset');
            },
            success: function(data, status, xhr){
                if(data){
                    btn.button('reset');
                    $("#openModalCompany").modal('hide');
                    var str = "<option value=''>Select Company</option>";
                    var comID = data.comID;
                    delete data.comID;
                    $.each(data,function(k,val){
                        var CompanyID = val["CompanyID"];
                        var Companyname = val["Companyname"];

                       if(comID == CompanyID){
                            str +="<option value='"+CompanyID+"' selected='selected'>"+Companyname+"</option>";
                       } else {
                         str +="<option  value='"+CompanyID+"'>"+Companyname+"</option>";
                        }

                    });
                    $('#CompanyID').find('option').remove().end().append(str) ;
                    career.prototype.setTestComp();
                }else{
                    btn.button('reset');
                }
            }
        });
    }
}

$(function() {
    var mbNot= new career();
    mbNot.init();
});

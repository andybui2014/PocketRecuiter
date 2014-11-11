var skillSearch =1;
function career(){ }
career.prototype = {
    init: function(){
        /*
        $(".checkIs").unbind('click').bind('click',this.checkIs);
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#addnotification').unbind('click').bind('click',this.addnotification);
        $('#EditNotification').unbind('click').bind('click',this.editnotification);
        $('#saveEditnotification').unbind('click').bind('click',this.saveEditnotification); */


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

    deleteCareer:function(id){
        $.ajax({
            url: 'delete-career',
            data: {career_ID:id},
            type: 'POST',
            success: function(data, status, xhr){
                if(data){
                    //console.log(data);
                    window.location = 'careerlist';
                }else{
                    alert("This career is using");
                }
            }
        });
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
        $("#careernameLable").text("");
        $("#companynameLable").text("");
        $("#careerdescriptionLable").text("");
        $("#industryLable").text("");
        $("#minimuneducationLable").text("");
        $("#degreetitleLable").text("");
        $("#requiredSkillLable").text("");
        $("#requiredExperienceLable").text("");
        $("#salaryRangeLable").text("");

        if(Veridate_careername ==""){
            $("#careernameLable").text("(Career Name must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_companyname ==""){
            $("#companynameLable").text("(Company must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_careerdescription ==""){
            $("#careerdescriptionLable").text("(Career Description must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_industry ==""){
            $("#industryLable").text("Career Industry must require");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_minimuneducation ==""){
            $("#minimuneducationLable").text("(Minimun Education must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_degreetitle ==""){
            $("#degreetitleLable").text("(Degree Title must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_requiredSkillClass ==""){
            $("#requiredSkillLable").text("(Required Skills must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(requiredExperience ==""){
            $("#requiredExperienceLable").text("(Required Experience must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }else if(Veridate_salaryRangeF =="" || Veridate_salaryRangeT ==""){
            $("#salaryRangeLable").text("(Salary Range must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }

        var postOrContinue = $(this).attr("id");
        if(postOrContinue == 'postcareernew') {
            $("#career_status").val(1);
        } else if(postOrContinue == 'savePostLater'){
            $("#career_status").val(0);
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

        $("#careernameLable").text("");
        $("#companynameLable").text("");
        $("#careerdescriptionLable").text("");
        $("#industryLable").text("");
        $("#minimuneducationLable").text("");
        $("#degreetitleLable").text("");
        $("#requiredSkillLable").text("");
        $("#requiredExperienceLable").text("");
        $("#salaryRangeLable").text("");

        if(Veridate_careername ==""){
            $("#careernameLable").text("(Career Name must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_companyname ==""){
            $("#companynameLable").text("(Company must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_careerdescription ==""){
            $("#careerdescriptionLable").text("(Career Description must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_industry ==""){
            $("#industryLable").text("Career Industry must require");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_minimuneducation ==""){
            $("#minimuneducationLable").text("(Minimun Education must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_degreetitle ==""){
            $("#degreetitleLable").text("(Degree Title must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(Veridate_requiredSkillClass ==""){
            $("#requiredSkillLable").text("(Required Skills must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        } else if(requiredExperience ==""){
            $("#requiredExperienceLable").text("(Required Experience must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }else if(Veridate_salaryRangeF =="" || Veridate_salaryRangeT ==""){
            $("#salaryRangeLable").text("(Salary Range must require)");
            btn.button('reset');
            $("#calcareercrtab").click();
            return;
        }

        var postOrContinue = $(this).attr("id");
        if(postOrContinue == 'postcareeredit') {
            $("#career_status").val(1);
        } else if(postOrContinue == 'savePostLaterEdit'){
            $("#career_status").val(0);
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
    },

    selectSkillToSearch:function(){
        var skillText = $("#selectSkillToSearch option:selected").text();
        var skilIDSelected = $("#selectSkillToSearch option:selected").val();
        if(skillSearch >1){
            $("div#addSkillandKeyword").append("<span class='previous' style='float:left;margin-top: 5px; margin-right: 10px; font-weight: bold'>OR</span><span style='' class='label-tag pull-left'>" + skillText +
                "&nbsp;&nbsp;<imge class='resetSkill removeskillPr glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
            "<input type='hidden'  name='skilIDSear[]' value='"+skilIDSelected+"' ></span>");
        } else{
            $("div#addSkillandKeyword").append("<span style='' class='label-tag pull-left'>" + skillText +
                "&nbsp;&nbsp;<imge class='resetSkill removeskillNext glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
                "<input type='hidden'  name='skilIDSear[]' value='"+skilIDSelected+"' ></span>");
        }

        $(".removeskillNext").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $("#selectSkillToSearch").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().next('span').remove();
            $(this).parent().remove();

            skillSearch =skillSearch -1;
        });

        $(".removeskillPr").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $("#selectSkillToSearch").find("option[value='" + skilID + "']").css("display", "");
            var existspan = $(this).parent().prev('span').hasClass('previous');
            if(existspan){
                $(this).parent().prev('span').remove();
            } else {
                $(this).parent().next('span').remove();
            }

            $(this).parent().remove();

            skillSearch =skillSearch -1;
        });

        $("#selectSkillToSearch option:selected").css("display", "none");
        $("#selectSkillToSearch option[value='']").prop("selected", "selected");

        skillSearch =skillSearch+1;
    },

    careerMatchSearch:function(){
        var btn = $(this);
        btn.button('loading');
        $.ajax({
            url: 'do-search-career',
            data: $('#form-careermatch').serializeArray(),
            type: 'POST',
            error : function (xhr,error) {
                btn.button('reset');
            },
            success: function(data, status, xhr){
                var html = "";
                if(data){
                    btn.button('reset');
                    var count =0;
                    var flagUS =  urlImage+'images/USA_flag.jpg';
                   // alert(flagUS);
                    $.each(data,function(k,candidateInfo){
                        count ++;
                        btn.button('reset');
                            if($.isEmptyObject(candidateInfo.image)){
                               var images = urlImage+'images/avatar_nonex44.jpg';
                           } else {
                                var images = urlImage+'images/'+candidateInfo.image;
                           }

                            var skillname ="";
                            var i =1;
                            $.each(candidateInfo.skillName,function(kk,skname){
                                if(i==1){
                                    skillname = skname;
                                } else{
                                    skillname = skillname+ ', ' + skname;
                                }
                                i = i+1;
                                });
                            if(count >1){
                                html += "<div class='col-md-12' style='height:10px!important'></div>"
                            }
                            html +="<div class='col-md-12 borderbottom_Gray' style='margin-left: 15px; margin-right: 5px' >" +
                                "<div class='col-md-12'>" +
                                "<div class='col-md-1' style='margin-left: -25px'> " +
                                "<img src='"+images+"'>" +
                                "</div>" +
                                    "<div class='col-md-11' style='margin-left: -25px'>" +
                                            "<div class='col-md-12' style='color: #1a5187'><strong>" + candidateInfo.firstname +" &nbsp;"+ candidateInfo.lastname +" </strong></div>" +
                                            "<div class='col-md-12'><strong>Expected Salary :&nbsp;"
                                if(candidateInfo.minimumsalary !=null && candidateInfo.maximumsalary !=null){
                                    html +=  candidateInfo.minimumsalary +"K &nbsp; - "+ candidateInfo.maximumsalary + "K "
                                } else if (candidateInfo.minimumsalary !=null){
                                    html +=  candidateInfo.minimumsalary + "K "
                                } else if(candidateInfo.maximumsalary !=null){
                                    html +=  candidateInfo.maximumsalary + "K "
                                }


                                html +=" &nbsp; </strong></div>" +
                                    "</div>" +
                                "</div>  " +
                                "<div class='col-md-12 text-justify' style='margin-left:-10px'> " +
                                    "<div style='height:10px!important;'></div>  " +  candidateInfo.overview +
                                "</div>" +
                                "<div class='col-md-12' style='margin-left:-10px'> " +
                                    "<small><span style='color: #1a5187' class='glyphicon glyphicon-play'></span></small>" +
                                    "<span style='color: #1a5187; font-weight: bold'>Read more</span>" +
                                "</div>" +
                               "<div class='col-md-12' style='margin-left:-10px'>" +
                                "<div style='height:10px!important;'></div>" +
                                        "<span><strong>Skills:</strong>"+skillname+".</span> </div>" +
                              "<div class='col-md-12' style='margin-left:-10px'>" +
                                " <div style='height:10px!important;'></div>" +
                                "<span><img src='"+flagUS+"'> <strong>Unied States</strong></span><span>"

                                if(candidateInfo.Address1 !=null){
                                    html += candidateInfo.Address1 +"|&nbsp;"
                                }

                                html +="|&nbsp;<strong>Distance: "
                                if(candidateInfo.tralveldistanceinmiles!=null){
                                    html += candidateInfo.tralveldistanceinmiles
                                }

                                html +=  "</strong> </span>" +
                                "</div> " +
                                "<div class='col-md-12 text-right'> " +
                                "<button style='margin-top:15px; margin-right:0px;' class='btn btn-primary' type='button'><strong>Contact</strong></button>" +
                            "</div>" +
                            "<div class='col-md-12' style='margin-left:-30px'>" +
                            "<div style='height:10px!important;'></div>" +
                            "</div>" +
                        "</div>"
                    });

                    $(".containerData").html("");
                        $(".containerData").html(html);

                    $(".resetSkill").each(function(){
                        var skilID = $(this).attr("skilIDSelected");
                        $("#selectSkillToSearch").find("option[value='" + skilID + "']").css("display", "");
                    });
                    $("#addSkillandKeyword").html("");
                    skillSearch =1;
                } else {
                    btn.button('reset');
                }

            }
        });
    },

    AddSkillToSear:function(){
        var skillText = $(this).closest('.col-md-8').siblings().find('#Select-Skill option:selected').text();
        var skilIDSelected = $(this).closest('.col-md-8').siblings().find('#Select-Skill option:selected').val();

        $(this).siblings(".add-skills").append("<span style='' class='label-tag pull-left'>" + skillText +
            "&nbsp;&nbsp;<imge class='reset-Skill glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
            "<input type='hidden'  name='skilID_match_premium[]' value='"+skilIDSelected+"' ></span>");


        $(".reset-Skill").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $(this).closest('.col-md-8').siblings().find("#Select-Skill option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();


        });

        $(this).closest('.col-md-8').siblings().find('#Select-Skill option:selected').css("display", "none");
        $(this).closest('.col-md-8').siblings().find("#Select-Skill option[value='']").prop("selected", "selected");
    },

    AddKeywordToSear:function(){
        var skillText = $(this).closest('.col-md-7').siblings().find('.Select-keyword option:selected').text();
        var skilIDSelected = $(this).closest('.col-md-7').siblings().find('.Select-keyword option:selected').val();

        $(this).siblings(".add-key-word").append("<span style='' class='label-tag pull-left'>" + skillText +
            "&nbsp;&nbsp;<imge class='reset-Skill glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
            "<input type='hidden'  name='keyword_match_premium[]' value='"+skilIDSelected+"' ></span>");


        $(".reset-Skill").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $(this).closest('.col-md-7').siblings().find(".Select-keyword option[value='" + skilID + "']").css("display", "");
            $(this).parent().remove();


        });

        $(this).closest('.col-md-7').siblings().find('.Select-keyword option:selected').css("display", "none");
        $(this).closest('.col-md-7').siblings().find(".Select-keyword option[value='']").prop("selected", "selected");
    },

    careerMatchPremiun:function(){
        var skill_match_premium =[];

        var keyword_match_premium_arr =[];
        var i = 0;
        $(".search-search .add-key-word").each(function(){
            var keyword_match_premium =[];
            $(this).find("input").each(function(){
                var kmpValue="";
                kmpValue = $(this).attr("value")
                keyword_match_premium.push(kmpValue);
            })

            keyword_match_premium_arr[i] = keyword_match_premium;
            i++;
        });

        $(".search-search .add-skills").each(function(){
            $(this).find("input").each(function(){
                var skValue="";
                skValue = $(this).attr("value")
                skill_match_premium.push(skValue);
            })
        });

        $.ajax({
            url: 'careermatch-premium-account',
            dataType: 'html',
            data: {keyword_match_premium:keyword_match_premium_arr, skill_match_premium:skill_match_premium},
            cache: false,
            type: 'POST',
            context: this,
            error : function (status,xhr,error) {

            },
            success: function(data,status,xhr){
                if(data){
                    $(".containerData").html("");
                    $(".containerData").html(data);

                }

            }
        });
    }
}


$(function() {
    var mbNot= new career();
    mbNot.init();
});

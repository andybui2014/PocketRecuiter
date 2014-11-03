
function pocketCandidate(){}
pocketCandidate.NAME          = 'pocket Candidate';
pocketCandidate.VERSION       = '0.1';
pocketCandidate.DESCRIPTION   = 'Class  Candidate';

pocketCandidate.prototype.constructor = pocketCandidate;
pocketCandidate.prototype = {
    init: function(){
        $('#cmd-next').unbind('click').bind('click',this.next);
        $('#cmd-back').unbind('click').bind('click',this.back);
        $('#add-another').unbind('click').bind('click',this.addAnother);
    },
    editEmployment: function(){
        var $this = $(this);
        if($this.attr('data-id') > 0){
            $.post('./detail-employment',{id: $this.attr('data-id') },function(xhr){

                if(xhr.success){
                    $('#employment-form :input[name="companyName"]').val(xhr.info.CompanyName);
                    $('#employment-form :input[name="posotionHeld"]').val(xhr.info.PostionHeld);
                    $('#employment-form :input[name="startDate"]').val(xhr.info.StartDate);
                    $('#employment-form :input[name="endDate"]').val(xhr.info.EndDate);
                    $('#employment-form #empDesc').val(xhr.info.Description);
                    $('#employment-form #add-another').attr('data-status','update').html('<strong> + Update Employment</strong>');
                    $('#employment-form #empId').val(xhr.info.CandidateEmploymentID);

                }
            })
        }
    },
    deleteEmployment: function(){
        var $this= $(this);
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Employment</span>');
            $modal.find('#modal-content').html(
                '<p>Are you sure delete <span style="color: #b81900"></span> employment : <strong>'+$qstName+'</strong></p>' +
                '<p>' +
                '<div class="">'+
                '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                '</div>' +
                '</p>'
            ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                    $.post('./do-remove-employment',{id: $this.attr('data-id')},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }
                    })
                })
            });
        }
    },
    deleteEducation:function(){
        var $this= $(this);
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Education</span>');
                $modal.find('#modal-content').html(
                        '<p>Are you sure delete <span style="color: #b81900"></span> education : <strong>'+$qstName+'</strong></p>' +
                            '<p>' +
                            '<div class="">'+
                            '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                            '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                            '</div>' +
                            '</p>'
                    ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                        $.post('./do-remove-education',{id: $this.attr('data-id')},function(xhr){
                            if(xhr.success){
                                location.reload();
                            }
                        })
                    })
            });
        }
    },
    editPortfolio: function(){

    },
    portfolioCheckAll: function(){
        if($(this).is(':checked'))
            $('#portfolioList :input[type="checkbox"]').prop('checked', true);
        else
            $('#portfolioList :input[type="checkbox"]').prop('checked', false);
    },
    deletePortfolio: function(){
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#portfolioList :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){

                var $id = $(this).attr('data-id');
                if($id > 0){
                    $idx = $idx + 1;
                    stest += '<p>- <a>'+$(this).attr('data-text')+'</a></p>';
                    arrTests.push($id);
                }
            }
        })

        var dataTIds = arrTests;
        var $qstName = stest;
        var $modal = $('#modal-dialog');
        if(arrTests.length > 0){
            $modal.modal("show").on("shown.bs.modal", function () {
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Portfolio</span>');
                $modal.find('#modal-content').html('' +
                        '<p>Are you sure delete <span style="color: #b81900"><strong>'+$idx+'</strong></span> portfolios : '+$qstName+'</p>' +
                        '<p>' +
                        '<div class="">'+
                        '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                        '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                        '</div>' +
                        '</p>').find('#qst-cfRemove').bind('click',function(){
                        if(dataTIds.length > 0){
                            $.post('./do-delete-portfolio',{data: dataTIds},function(xhr){
                                if(xhr.success){
                                    location.reload();
                                }
                            })
                        }
                    });
            });
        }


    },
    editEducation: function(){
        var $this = $(this);
        if($this.attr('data-id') > 0){
            $.post('./detail-education',{id: $this.attr('data-id')},function(xhr){
                if(xhr.success){
                    $('#education-form #eduId').val(xhr.info.CredentialExperienceID);
                    $('#education-form :input[name="inst-name"]').val(xhr.info.institution_name);
                    $('#education-form :input[name="degree-name"]').val(xhr.info.title);
                    $('#education-form :input[name="start-year"]').val(xhr.info.startdate);
                    $('#education-form :input[name="end-year"]').val(xhr.info.enddate);
                    $('#education-form #add-another').attr('data-status','update').html('<strong> + Update Education</strong>');
                }
            })
        }
    },
    addAnother: function(){
        var $this = $(this);
        $this.button('loading');
        //Step Education
        if($this.attr('data-step')=='education'){

            $('#education-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });

            if($this.attr('data-status')=='add'){
            $.post('./do-add-education',{data: $('#education-form').serializeArray()},function(xhr){
                if(xhr.success){
                    location.reload();
                    }else{
                        //errors
                        $.each(xhr.info,function(key,item){
                            $('#education-form :input[name="'+key+'"]').parent().addClass('has-error');
                        })
                        $this.button('reset');

                    }
                })
            }else{
                $.post('./do-update-education',{data: $('#education-form').serializeArray()},function(xhr){
                    if(xhr.success){
                        location.reload();
                    }else{
                        //errors
                        $.each(xhr.info,function(key,item){
                            $('#education-form :input[name="'+key+'"]').parent().addClass('has-error');
                        })
                        $this.button('reset');
                    }
                })
            }


            //console.log(data);
        }else if($this.attr('data-step')=='employment'){
            $('#employment-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });

            var compVal = $('#employment-form :input[name="companyName"]');
            if(compVal.val() !='' || compVal.val().length > 0){
                if($this.attr('data-status')=='add'){
                    $.post('./do-add-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }else{
                            //errors
                            $.each(xhr.info,function(key,item){
                                $('#employment-form :input[name="'+key+'"]').parent().addClass('has-error');
                            })
                            $this.button('reset');
                        }
                    })
                }else if($this.attr('data-status')=='update'){
                    $.post('./do-update-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }else{
                            //errors
                            $.each(xhr.info,function(key,item){
                                $('#employment-form :input[name="'+key+'"]').parent().addClass('has-error');
                            })
                            $this.button('reset');
                        }
                    })
                }

            }else{
                compVal.focus();
                compVal.parent('.form-group').addClass('has-error');
                $this.button('reset');
            }
        }else if($this.attr('data-step')=='portfolio'){
            $('#portfolio-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });
            if($this.attr('data-status')=='add'){
                var portForm = $('#portfolio-form :input[name="title"]');
                if(portForm.val() !='' || portForm.val().length > 0){
                    $.post('./do-add-portfolio',{data: $('#portfolio-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }else{
                            var urlVal = $('#portfolio-form :input[name="url"]');
                            urlVal.focus();
                            urlVal.parent('.form-group').addClass('has-error');
                            $this.button('reset');
                        }
                    })
                }else{
                    portForm.focus();
                    portForm.parent('.form-group').addClass('has-error');
                    $this.button('reset');
                }

            }
        }
        //$this.button('reset');
    },
    back: function(){
        var $this = $(this);
        $this.button('loading');
        if($this.attr('data-back').length > 0){
            location.href = './profile-builder?utm_source=' + $this.attr('data-back');
        }else{
            location.href = './profile-builder?utm_source=contact';
        }
    },
    next: function(){
        var $this = $(this);
        $this.button('loading');
        var dataId = $this.attr('data-id');
        if(dataId=='contact'){
            $.post( './step-next-contact',{data: $('#form-contact').serializeArray()}).done(function( xhr ) {
                if(xhr.success){
                    location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                }else{
                    //errors
                    $.each(xhr.info,function(key,item){
                       $('#form-contact :input[name="'+key+'"]').parent().addClass('has-error');
                    })
                    $this.button('reset');
                }
            });
        }else if(dataId=='skills'){
            var data = [];
            $('#tree-lst').find('img').each(function(idx,item){
                if($(this).attr('class') !=='img-toggle' && $(this).attr('data-status')=='select'){
                    data.push($(this).attr('data-id'));
                }
            });
            $.post('./do-update-skills',{data:data},function(xhr){
                if(xhr.success){
                    location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                }
            })
        }else{
            location.href = './profile-builder?utm_source=' + $this.attr('data-next');
        }
    },
    watchListCheckedAll:function(){
        if($("#ckAll").is(":checked")){
            $(".isck").prop('checked','checked');

        } else {
            $(".isck").removeAttr('checked');
        }
    },

    watchListIsChecked:function(){
        var lengthAllCheckbox = $('.isck').length;
        if($('.isck').is(":checked")) {
            if ($(".trIsck input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        } else{
            if ($(".trIsck input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        }
    },
    skillViewNext:function(){
        var data = [];
        $('#tree-lst').find('img').each(function(idx,item){
            if($(this).attr('class') !=='img-toggle' && $(this).attr('data-status')=='select'){
                data.push($(this).attr('data-id'));
            }
        });
        $.post('./do-update-skills',{data:data},function(xhr){
            if(xhr.success){
                //location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                location.reload();
            }
        })
    },
    skillViewBack:function(){
        location.reload();
    },
    editSkills: function(url){
        $(window).load(function(){
$(function  () {
                $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
                $('.tree li.parent_li > span').on('click', function (e) {
                    var children = $(this).parent('li.parent_li').find(' > ul > li');
                    if (children.is(":visible")) {
                        children.hide('fast');
                        $(this).attr('title', 'Expand this branch')
                            .find(' > i')
                            .addClass('icon-plus-sign')
                            .removeClass('icon-minus-sign');
                        $(this).find('.img-toggle').attr('src',url+'images/trees/ico_add_sm.png');
                    } else {
                        children.show('fast');
                        $(this).attr('title', 'Collapse this branch')
                            .find(' > i')
                            .addClass('icon-minus-sign')
                            .removeClass('icon-plus-sign');
                        $(this).find('.img-toggle').attr('src',url+'images/trees/ico_sub_sm.png');
                    }
                    e.stopPropagation();
                });
                //window ready loaded
                $('.img-parent').unbind('click').bind('click',function(){
                    if($(this).attr('data-status')=='select'){
                        $(this)
                            .attr('src',url+'images/trees/ico_expand.png')
                            .attr('data-status','deselect');

                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_expand_sm.png')
                            .attr('data-status',$(this).attr('data-status'));

                    }else if($(this).attr('data-status')=='deselect'){
                        $(this).attr('src',url+'images/trees/ico_colapse.png').attr('data-status','select');
                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_colapse_sm.png')
                            .attr('data-status',$(this).attr('data-status'));
                    }
                })

                $('.img-item').unbind('click').bind('click',function(){
                    var parentLi = $(this).closest('.parent_li');
                    if($(this).attr('data-status')=='select'){
                        $(this)
                            .attr('src',url+'images/trees/ico_expand_sm.png')
                            .attr('data-status','deselect');
                    }else{
                        $(this)
                            .attr('src',url+'images/trees/ico_colapse_sm.png')
                            .attr('data-status','select');
                        }
                    if($(this).nextAll('span:first').attr('title')=='Collapse this branch'){
                        $(this).nextAll('ul')
                            .find('.img-item')
                            .attr('data-status',$(this).attr('data-status'))
                            .attr('src',$(this).attr('src'));
                    }
                });
            });
        });//]]>
    }, 
	
    editContactInfo:function(){
      //  $(this).button('loading');
        var arrValidate = [
            'firstname','lastname','email','country',
            'addResLine','addResLine2','city','stateProvince','zipcode'
        ];
        var form = $('#form-contact');
        var isError = false;
        form.find('input[type="text"]').each(function(){
            var item = $(this);
            if($.inArray(item.attr('name'),arrValidate) >= 0){
              //  console.log(item.val());
                if(item.val()==''){
                    isError = true;
                    item.parent().addClass('has-error');
                }else{
                    item.parent().removeClass('has-error');
                }
            }
        });
        if(isError==false){
            $.post('./update-contact-info',{data:form.serializeArray()},function(xhr){
               console.log(xhr);
                $(this).button('reset');
            })
        }
    },
	
	deleteWatchList:function(id){
        $.ajax({
            url: 'delete-watch-list',
            data: {OpportunityID:id},
            type: 'POST',
            success: function(data, status, xhr){
                if(data){
                    window.location = 'watch-list';
                }
            }
        });
    },
    addNewCandidateEmploy:function(){
        var addemployment = $(this).attr('addemployment');

        $('#form-add-edit-candidate-employment :input[type="text"]').change(function(){
            if($(this).val() !='' && $(this).val().length > 0){
                $(this).parent().removeClass('has-error');
            }
        });
        $.ajax({
            url: 'do-candidate-employment',
            data: $('#form-add-edit-candidate-employment').serializeArray(),
            type: 'POST',
            success: function(xhr){
                if(xhr.success){
                    if(addemployment=='reloadYes'){
                        location.reload();
                    } else {
                        $('.position-held').val("");
                        $('.Candidate-employmentID').val("");
                        $('.client-name').val("");
                        $('.des').val("");
                        $('.start-date').val("");
                        $('.end-date').val("");
                    }

                }else{
                   $.each(xhr.info,function(key,item){
                       $('#form-add-edit-candidate-employment :input[name="'+key+'"]').parent().addClass('has-error');
                  })
                }
            }
        });
    },
	
	addOppSkillsToSearch:function(){
        var skillText = $("#add-opp-skills option:selected").text();
        var skilIDSelected = $("#add-opp-skills option:selected").val();

            $("div#match-opp-skills").append("<span style='' class='label-tag pull-left'>" + skillText +
                "&nbsp;&nbsp;<imge class='resetSkill delete-skills glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
                "<input type='hidden'  name='matchopportunitySear[]' value='"+skilIDSelected+"' ></span>");


        $(".delete-skills").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $("#add-opp-skills").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().next('span').remove();
            $(this).parent().remove();


        });


        $("#add-opp-skills option:selected").css("display", "none");
        $("#add-opp-skills option[value='']").prop("selected", "selected");


    },

    matchOpportunities:function(CandidateProfileID){
       // var CandidateProfileID =$(this).attr('CandidateProfileID');
        var dataform = $('#form-match-opportunity-sear').serializeArray();
        $.ajax({
            url: 'do-search-opportunities',
            dataType: 'json',
            data: dataform,
            type: 'POST',
            error : function (status, xhr,error) {
             console.log(status);  console.log(xhr); console.log(error);
            },
            success: function(data, status, xhr){
                var html = "";
                if(data){

                    var flagUS =  urlImage+'images/USA_flag.jpg';
                    // alert(flagUS);
                    $.each(data,function(k,oppList){
                         var disabedYesNo = (oppList.hadApplied)?"disabled":'';
                        //if($.isEmptyObject($oppList.image)){
                            var images = urlImage+'images/avatar_nonex44.jpg';
                       // } else {
                       //     var images = urlImage+'images/'+$oppList.image;
                       // }

                        var skillname ="";
                        var i =1;
                        $.each(oppList.Skills,function(kk,skname){
                            if(i==1){
                                skillname = skname.SkillName;
                            } else{
                                skillname = skillname+ ', ' + skname.SkillName;
                            }
                            i = i+1;
                        });

                        html +="<div class='col-md-12 borderbottom_Gray' style='margin-left: 15px' >" +
                            "<div class='col-md-12'>" +
                            "<div class='col-md-1' style='margin-left: -45px'> " +
                            "<img src='"+images+"'>" +
                            "</div>" +
                            "<div class='col-md-11' style='margin-left: -25px'>" +
                            "<div class='col-md-12' style='color: #1a5187'><strong>" + oppList.title +"</strong></div>" +
                            "<div class='col-md-12'><strong>"+ oppList.Companyname +" </strong></div>" +
                            "</div>" +
                            "</div>  " +
                            "<div class='col-md-12' style='margin-left:-30px'> " +
                            "<div style='height:10px!important;'></div>  " +  oppList.careerdescription +
                            "</div>" +
                            "<div class='col-md-12' style='margin-left:-30px'> " +
                            "<span style='color: #1a5187' class='glyphicon glyphicon-play'></span>" +
                            "<span style='color: #1a5187'>Read more</span>" +
                            "</div>" +
                            "<div class='col-md-12' style='margin-left:-30px'>" +
                            "<div style='height:10px!important;'></div>" +
                            "<span><strong>Skills:</strong>"+skillname+".</span> </div>" +
                            "<div class='col-md-12' style='margin-left:-30px'>" +
                            " <div style='height:10px!important;'></div>" +
                            "<span><img src='"+flagUS+"'> <strong>United States</strong></span>"
                            if(oppList.location !=""){
                                html += "<span>&nbsp;|&nbsp;"+oppList.location +"&nbsp;|&nbsp;<strong>Distance: </strong> </span>"
                            }else{
                                html += "<span>&nbsp;|&nbsp;<strong>Distance: </strong> </span>"
                            }

                            if(oppList.salaryrangefrom !=null && oppList.salaryrangeto !=null){
                                html += "<span>|&nbsp;<strong>Salary:&nbsp;</strong>" + oppList.salaryrangefrom + "K &nbsp;to&nbsp;"+ oppList.salaryrangeto +"K</span></div>"
                            } else if(oppList.salaryrangefrom !=null){
                                html += "<span>|&nbsp;<strong>Salary:&nbsp;</strong>" + oppList.salaryrangefrom + "K</span></div> "
                            } else if(oppList.salaryrangeto!=null){
                                html += "<span>|&nbsp;<strong>Salary:&nbsp;</strong>" + oppList.oppList.salaryrangeto + "K</span></div> "
                            } else{
                                html += "</div> "
                            }




                            if(oppList.hadApplied){
                                html +=   "<div class='col-md-12 text-right'> " +
                                    "<button style='margin-top:15px; margin-right:0px;'" +
                                    " class='btn btn-primary disabled btn-for-apply' type='button'  OpportunityID='"+oppList.OpportunityID+"'  CandidateProfileID='"+CandidateProfileID+"' ><strong>Apply</strong></button>" +
                                    "</div>" +
                                    "<div class='col-md-12' style='margin-left:-30px'>" +
                                    "<div style='height:10px!important;'></div>" +
                                    "</div>" +
                                    "</div>"
                            } else {
                                html +=   "<div class='col-md-12 text-right'> " +
                                    "<button style='margin-top:15px; margin-right:0px;' class='btn btn-primary btn-for-apply' OpportunityID='"+oppList.OpportunityID+"'  CandidateProfileID='"+CandidateProfileID+"'  type='button' ><strong>Apply</strong></button>" +
                                    "</div>" +
                                    "<div class='col-md-12' style='margin-left:-30px'>" +
                                    "<div style='height:10px!important;'></div>" +
                                    "</div>" +
                                "</div>"
                            }

                    });

                    $(".containerData").html("");
                    $(".containerData").html(html);
                    $(".btn-for-apply").unbind("click").bind("click",pocketCandidate.prototype.candidateApply)
                }

            }
        });
    },

    candidateApply:function(){
        $(this).addClass('disabled');
        var  $me =  $(this)
        var OpportunityID = $(this).attr('OpportunityID')
        var CandidateProfileID = $(this).attr('CandidateProfileID')
        $.ajax({
            url: 'candidate-apply',
            data: {CandidateProfileID:CandidateProfileID, OpportunityID:OpportunityID},
            type: 'POST',
            success: function(xhr){
                if(xhr.success ==1){
                    $('#this-job-was-applied').modal('show');

                    $('#modal-this-job-was-applied').css("color","green");
                    $('#modal-this-job-was-applied').text(xhr.error);
                }else{
                    $('#this-job-was-applied').modal('show');
                    $('#modal-this-job-was-applied').css("color","red");
                    $('#modal-this-job-was-applied').text(xhr.error);
                }
                $me.addClass('disabled');
            }
        });

    },

    activitiesCheckedAll:function(){
        if($("#Active-Check-All").is(":checked")){
            $(".Active-Is-Check").prop('checked','checked');

        } else {
            $(".Active-Is-Check").removeAttr('checked');
        }
    },

    activitiesChecked:function(){
        var lengthAllCheckbox = $('.Active-Is-Check').length;
        if($('.Active-Is-Check').is(":checked")) {
            if ($(".activitiesIsChecked input:checked").length === lengthAllCheckbox) {
                $('#Active-Check-All').prop('checked', true);
            } else {
                $('#Active-Check-All').prop('checked',false);
            }
        } else{
            if ($(".activitiesIsChecked input:checked").length === lengthAllCheckbox) {
                $('#Active-Check-All').prop('checked', true);
            } else {
                $('#Active-Check-All').prop('checked',false);
            }
        }
    },

    deleteActivities:function(id){
        $.ajax({
            url: 'delete-activities',
            data: {OpportunityID:id},
            type: 'POST',
            success: function(data, status, xhr){
                if(data){
                    window.location = 'activities';
                }
            }
        });
    }
}


$(function  () {
    var prCandidate= new pocketCandidate();
    prCandidate.init();
});




function pocketDashboard(){}
pocketDashboard.NAME          = 'pocket recruiter';
pocketDashboard.VERSION       = '0.1';
pocketDashboard.DESCRIPTION   = 'Class pocketDashboard';

pocketDashboard.prototype.constructor = pocketDashboard;
pocketDashboard.prototype = {
    init: function(){
    },

    deleteTest:function(testID){
        $.ajax({
            url: 'delete-test',
            data: {testID:testID},
            type: 'POST',
            error : function (data,xhr,error) {

            },
            success: function(data,status, xhr){
                var html = "";
                if(data.suc){
                    $.each(data.list,function(k,candidateInfo){
                        html +="<span class='label-tag pull-left'>"+candidateInfo['TestName']+" " +
                            "<i class='glyphicon glyphicon-remove text-right tag-remove' onclick='javascript:objdb.deleteTest("+candidateInfo['TestID']+")'></i></span>"
});

                    $("#technical-test").html("");
                    $("#technical-test").html(html);
                }else{
                    $("#this-test-is-using").modal('show');
                }
            }
        });
    },

    addnotificationIndex: function(){
        var btn = $(this);
        btn.button('loading');
        var iSreceiverid = 0;
        $('#toTheseEmail input').each(function(){
            iSreceiverid =1;
        });

        if(iSreceiverid !=0){
            $.ajax({
                url: 'new-notifications',
                data: $('#form-addnotification').serializeArray(),
                type: 'POST',
                error : function (data,xhr,error) {
                    btn.button('reset');
                },
                success: function(data,status,xhr){
                    if(data.suc){
                        btn.button('reset');
                        var html ="";
                        $.each(data.list,function(k,notiInfo){
                            html +="<tr class='notification-ischeck'><td><label><input type='checkbox' value='"+notiInfo['NotificationID']+"' class='Index-Is-Check' /> &nbsp;"+notiInfo['cbContactNameR']+" &nbsp; "+notiInfo['cbContactLNameR']+" </label>"+notiInfo['cbDateTime']+"</td></tr>";
                        });

                        $("#notification-list").html("");
                        $("#notification-list").html(html);

                        //Reload candidate
                       var html_can =""
                        $.each(data.listCandidate,function(kk,listCandidateInfo){
                            var salary = ''; var image='';
                            if(listCandidateInfo.minimumsalary ==null && listCandidateInfo.maximumsalary ==null){
                                   salary = '';
                                } else if(listCandidateInfo.minimumsalary ==null){
                                    salary = listCandidateInfo.maximumsalary +'K';
                                } else if(listCandidateInfo.maximumsalary =='null'){
                                    salary = listCandidateInfo.minimumsalary +'K';
                                } else{
                                    salary = listCandidateInfo.minimumsalary+'K'+'-'+listCandidateInfo.maximumsalary+'K';
                                }

                            if(listCandidateInfo.image==null){
                                 image = urlImage+'avatar_none.jpg';
                            } else{
                                image = urlImage+listCandidateInfo.image;
                            }

                            var companyName='';
                            var lengtharray =[];
                            lengtharray = listCandidateInfo.strTitle
                            if(listCandidateInfo.strTitle !='' && lengtharray.length>0){
                                var i= 1;
                                var companyName = '';

                                $.each(listCandidateInfo.strTitle,function(kkk,strTitleInfo){
                                    if(i==1){
                                        companyName = strTitleInfo;
                                    } else{
                                        companyName = companyName +' , '+strTitleInfo;
                                    }
                                    i++;
                                });
                            }

                            if(listCandidateInfo.tralveldistanceinmiles >1){
                                var travel = listCandidateInfo.tralveldistanceinmiles +'miles';
                            } else if(listCandidateInfo.tralveldistanceinmiles== 1){
                                var travel = listCandidateInfo.tralveldistanceinmiles+'mile';
                            }

                            html_can +="<div class='col-md-12' style='color: #777; margin-bottom: 10px;'>" +
                                " <div class='col-md-3' style='padding-left: 0'><img src='"+image+"' style='height: 55px; width:50px'></div>" +
                                    "<div class='col-md-9 text-bottom' style='vertical-align: text-bottom;padding-bottom: 0px; padding-left: 0'>" +
                                        "<div style='color: #2a6496; font-weight: bold;vertical-align: text-bottom; padding-top:0'>"+listCandidateInfo.firstname+"&nbsp;"+listCandidateInfo.lastname+"</div>"+
                                        "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Opportunity:&nbsp;"+companyName+"</div>"+
                                        "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Expected Salary:&nbsp;"+salary+"</div>"+
                                        "<div style='vertical-align: text-bottom; padding-bottom: 0px'> District from job:&nbsp;"+travel+"</div>"+
                                         "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Mobile:&nbsp;"+listCandidateInfo.PhoneNumber+"</div>"+
                                     "</div>"+
                                "</div>";

                        });

                        $(".Contain-candidate-reload").html("");
                        $(".Contain-candidate-reload").html(html_can);
                        //end
                    }else{
                        btn.button('reset');
                    }

                }
            });
        } else {
            alert("Seclect a reciever");
            btn.button('reset');
        }

        $("#form-addnotification #toTheseEmail").text("");
        $("#form-addnotification #subjectNotification").val("");
        $("#form-addnotification #contentNotification").val("");

    },

    addReceiveEmail: function(){
        var idReceiver = $(this).parent().parent().find("#receiverid option:selected").val();
        var nameReceiver = $(this).parent().parent().find("#receiverid option:selected").text();
        $("div#toTheseEmail").append("<span style='padding-left:5px'>" +
            "<imge class='removeidReceiver' src='images/delete.png' height='15px' idReceiver='"+idReceiver+"' style='cursor:pointer;'>"+nameReceiver+";<input type='hidden'  name='receiverid[]' value='"+idReceiver+"' ></span> ");

        $(".removeidReceiver").unbind("click").bind("click",function(){
            var idReceiver = $(this).attr("idReceiver");
            $("#receiverid").find("option[value='" + idReceiver + "']").css("display", "");
            $(this).parent().remove();
        });


        $(this).parent().parent().find("#receiverid option:selected").css("display", "none");
        $(this).parent().parent().find("#receiverid option[value='']").prop("selected", "selected");
    },

    deleletNoticcationCheck:function(){
        var length = 0;
        var listNotiID = [];
        var length = 0 ;
        $(".notification-ischeck").each(function(){
                var list = "";
                length ++;
                list= $(this).children().find('input:checked').val();
                var lNoti = list;
                list=typeof(list);
                if(list !='undefined'){
                    listNotiID.push(lNoti);
                }

        });

        if(length > 0){

            $.ajax({
                url: 'delete-notifications-checked',
                data:{listNotiID:listNotiID},
                type: 'POST',
                success: function(data,status,xhr){
                    if(data.suc){

                        var html ="";
                        $.each(data.list,function(k,notiInfo){
                            html +="<tr class='notification-ischeck'><td><label><input type='checkbox' value='"+notiInfo['NotificationID']+"' class='Index-Is-Check'  />&nbsp; "+notiInfo['cbContactNameR']+" &nbsp; "+notiInfo['cbContactLNameR']+" </label>"+notiInfo['cbDateTime']+"</td></tr>";
                        });

                        $("#notification-list").html("");
                        $("#notification-list").html(html);
                        //Reload candidate
                        var html_can =""
                        $.each(data.listCandidate,function(kk,listCandidateInfo){
                            var salary = ''; var image='';
                            if(listCandidateInfo.minimumsalary ==null && listCandidateInfo.maximumsalary ==null){
                                salary = '';
                            } else if(listCandidateInfo.minimumsalary ==null){
                                salary = listCandidateInfo.maximumsalary +'K';
                            } else if(listCandidateInfo.maximumsalary =='null'){
                                salary = listCandidateInfo.minimumsalary +'K';
                            } else{
                                salary = listCandidateInfo.minimumsalary+'K'+'-'+listCandidateInfo.maximumsalary+'K';
                            }

                            if(listCandidateInfo.image==null){
                                image = urlImage+'avatar_none.jpg';
                            } else{
                                image = urlImage+listCandidateInfo.image;
                            }

                            var companyName='';
                            var lengtharray =[];
                            lengtharray = listCandidateInfo.strTitle
                            if(listCandidateInfo.strTitle !='' && lengtharray.length>0){
                                var i= 1;
                                var companyName = '';

                                $.each(listCandidateInfo.strTitle,function(kkk,strTitleInfo){
                                    if(i==1){
                                        companyName = strTitleInfo;
                                    } else{
                                        companyName = companyName +' , '+strTitleInfo;
                                    }
                                    i++;
                                });
                            }

                            if(listCandidateInfo.tralveldistanceinmiles >1){
                                var travel = listCandidateInfo.tralveldistanceinmiles +'miles';
                            } else if(listCandidateInfo.tralveldistanceinmiles== 1){
                                var travel = listCandidateInfo.tralveldistanceinmiles+'mile';
                            }

                            html_can +="<div class='col-md-12' style='color: #777; margin-bottom: 10px;'>" +
                                " <div class='col-md-3' style='padding-left: 0'><img src='"+image+"' style='height: 55px; width:50px'></div>" +
                                "<div class='col-md-9 text-bottom' style='vertical-align: text-bottom;padding-bottom: 0px; padding-left: 0'>" +
                                "<div style='color: #2a6496; font-weight: bold;vertical-align: text-bottom; padding-top:0'>"+listCandidateInfo.firstname+"&nbsp;"+listCandidateInfo.lastname+"</div>"+
                                "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Opportunity:&nbsp;"+companyName+"</div>"+
                                "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Expected Salary:&nbsp;"+salary+"</div>"+
                                "<div style='vertical-align: text-bottom; padding-bottom: 0px'> District from job:&nbsp;"+travel+"</div>"+
                                "<div style='vertical-align: text-bottom; padding-bottom: 0px'>Mobile:&nbsp;"+listCandidateInfo.PhoneNumber+"</div>"+
                                "</div>"+
                                "</div>";

                        });

                        $(".Contain-candidate-reload").html("");
                        $(".Contain-candidate-reload").html(html_can);
                        //end
                    }else{

                    }

                }
            });

        }else{
            alert("Please select a notification");
            return;
        }

        $("#are-you-sure-you-want-to-delete").modal('hide');
},

    IndexCheckedAll:function(){
        if($("#Index-Check-All").is(":checked")){
            $(".Index-Is-Check").prop('checked','checked');

        } else {
            $(".Index-Is-Check").removeAttr('checked');
        }
    },

    IndexIsChecked:function(){
        var lengthAllCheckbox = $('.Index-Is-Check').length;
        if($('.Index-Is-Check').is(":checked")) {
            if ($(".notification-ischeck input:checked").length === lengthAllCheckbox) {
                $('#Index-Check-All').prop('checked', true);
            } else {
                $('#Index-Check-All').prop('checked',false);
            }
        } else{
            if ($(".notification-ischeck input:checked").length === lengthAllCheckbox) {
                $('#Index-Check-All').prop('checked', true);
            } else {
                $('#Index-Check-All').prop('checked',false);
            }
        }
    },

    publibOpportunity:function(){
        var status =1 ;
        var id= $(this).attr('opportunityattr');
        var active= $(this).closest("tr").find(".opportunity-active");
        $.ajax({
            url: 'publib-opportunity',
            data:{status:status, oppotunityID:id},
            type: 'POST',
            error : function (status,xhr,error) {
            },
            success: function(xhr,status){
                if(xhr.success){
                    active.text("Active");
                    active.css({"color":"#7aac34"});
                }

            }
        });
    }

}
$(function  () {
    var prDashboard= new pocketDashboard();
    prDashboard.init();
});




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
                    }else{
                        btn.button('reset');
                    }

                }
            });
        } else {
            alert("Seclect a reciever");
            btn.button('reset');
        }

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
                    }else{

                    }

                }
            });

        }else{
            alert("Please select a notification");
            return;
        }
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
    }

}
$(function  () {
    var prDashboard= new pocketDashboard();
    prDashboard.init();
});



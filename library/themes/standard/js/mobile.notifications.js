function notifications(){ }
notifications.prototype = {
    init: function(){
        $(".checkIs").unbind('click').bind('click',this.checkIs);
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#addnotification').unbind('click').bind('click',this.addnotification);
        $('#EditNotification').unbind('click').bind('click',this.editnotification);
        $('#saveEditnotification').unbind('click').bind('click',this.saveEditnotification);
        $('#deleteNotification').unbind('click').bind('click',this.deleteNotification);
        $('.glyphiconDelete').unbind('click').bind('click',this.deleteThisNotification);
        $('#mailMassage').unbind('click').bind('click',this.mailMassage);
        $('#mailSend').unbind('click').bind('click',this.mailSend);
        $('#addReceiveEmail').unbind('click').bind('click',this.addReceiveEmail);
    },
    checkAllIs: function(){
        if($("#ckAll").is(":checked")){
            //$(".checkIs").prop('checked','checked');
            $(".ischecktr").each(
                function(){
                    if($(this).css("display")!="none")
                    {
                        $(this).children().find('.checkIs').prop('checked','checked');
                    }
                }
            )

            $(".ischecktr input:checked").each(function(){
                disabled = $(this).attr('disabled');
                disabled = typeof(disabled);
                if(disabled != "undefined"){
                    $(this).prop('checked',false);
                }
            });
            $(this).parents('thead').toggleClass('active');
            $(".checkIs").parent().toggleClass('checked');
            $(".checkIs").val(1);

        } else {
            $(".checkIs").removeAttr('checked');
            $(this).parents('thead').toggleClass('active');
            $(".checkIs").parent().toggleClass('checked');
            $(".checkIs").val("");
        }



},
    checkIs: function(){
        //var lengthAllCheckbox = $('.ischecktr input:checkbox').length;
        var lengthAllCheckbox = 0 ;
            $(".ischecktr").each(function(){
                if($(this).css("display")!="none")
                {
                    lengthAllCheckbox ++;
                }
            });
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
        var iSreceiverid = 0;
        $('#toTheseEmail input').each(function(){
            iSreceiverid =1;
        });
        //var iSreceiverid = $('#form-addnotification #receiverid option:selected').val();
        if(iSreceiverid !=0){
            $.ajax({
                url: 'save-notifications',
                data: $('#form-addnotification').serializeArray(),
                type: 'POST',
                error : function (xhr,error) {
                    btn.button('reset');
                },
                success: function(xhr){
                    if(xhr.success){
                        window.location = 'list';
                        // btn.button('reset');
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
    editnotification:function(){
        var length = 0;
        length = $(".ischecktr input:checked").length;
        var disabled = "";
        if (length ==1){
            var subject = $(".ischecktr input:checked").parent().siblings().find('.notiText').text();
            subject = $.trim(subject);
            var content = $(".ischecktr input:checked").parent().siblings().find('.notiContent').text();
            content = $.trim(content);
            var notiID = $(".ischecktr input:checked").attr('NotiID');
            notiID = $.trim(notiID);
            var sendtoContactName = $(".ischecktr input:checked").attr('contactname');
            $('#EditModalNotification').modal('show');
            $('form#form-editnotification #editNotification').val(subject);
            $('form#form-editnotification #editcontentNotification').val(content);
            $('form#form-editnotification #ModalEditNotiID').val(notiID);
            $('form#form-editnotification #hadSentToUser').text(sendtoContactName);
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
                console.log(xhr);
                if(xhr.success){
                    window.location = 'list';
                    btn.button('reset');
                }else{
                }
            },error: function(xhr){
                btn.button('reset');
            }
        });
        btn.button('reset');
    },
    deleteNotification:function(){
        var length = 0;
        var idCurrentActive = $('.messageActive').attr('id');
        var listNotiID = [];
       // length = $(".ischecktr input:checked").length;
        var length = 0 ;
        $(".ischecktr").each(function(){
            if($(this).css("display")!="none")
            {
                var list = "";
                length ++;
                    list= $(this).children().find('input:checked').attr('NotiID');
                var lNoti = list;
                list=typeof(list);
                if(list !='undefined'){
                    listNotiID.push(lNoti);
                }

            }
        });

        if(length > 0){
           /* $(".ischecktr input:checked").each(function(){
                var list = "";
                    list = $(this).attr('NotiID');
                    listNotiID.push(list);
            }); */

            $.ajax({
                url: 'delete-notifications',
                data:{listNotiID:listNotiID,idCurrentActive:idCurrentActive},
                type: 'POST',
                success: function(xhr){
                    if(xhr.success){
                        window.location = 'list';
                    }else{
                    }
                }
            });

        }else{
            alert("Please select a notification");
            return;
        }
    },
    deleteThisNotification:function(){
        if($(this).hasClass('deleteNo')){
            return;
        } else{
            var idCurrentActive = $('.messageActive').attr('id');
            var listNotiID = [];
            var list ="";
            $me =  $(this).parent().parent().siblings().find('.checkIs');
            list =  $me.attr('NotiID');
            listNotiID.push(list);
               $.ajax({
             url: 'delete-notifications',
             data:{listNotiID:listNotiID,idCurrentActive:idCurrentActive},
             type: 'POST',
             success: function(xhr){
             if(xhr.success){
             window.location = 'list';
             }else{
             }
             }
             });
        }

    },
    mailMassage:function(){
        $(".senderSendTo").hide();
        $(".reveiverMailFrom").show();
        $("#mailSend").removeClass('messageActive');
        $("#mailSend").addClass('messageDeActive');
        $("#mailMassage").removeClass('messageDeActive');
        $("#mailMassage").addClass('messageActive');
    },
    mailSend:function(){
        $(".reveiverMailFrom").hide();
        $(".senderSendTo").show();
        $("#mailMassage").removeClass('messageActive');
        $("#mailMassage").addClass('messageDeActive');
        $("#mailSend").removeClass('messageDeActive');
        $("#mailSend").addClass('messageActive');
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
    }
}

$(function() {
    var mbNot= new notifications();
    mbNot.init();
});

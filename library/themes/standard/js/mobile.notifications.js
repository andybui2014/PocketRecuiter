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
        var loaddefault =0;
        var lengthAllCheckbox = $('table#notificationCk tr:gt(0) input:checkbox').length;
        if(loaddefault == 0){
            if ($('table#notificationCk tr.ischecktr input:checkbox[checked]').length === lengthAllCheckbox) {
                $('#projectCheckboxAll').attr('checked', true);
            } else {
                $('#projectCheckboxAll').attr('checked', false);
            }
            loaddefault = 1;
        }

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
        var iSreceiverid = $('#form-addnotification #receiverid option:selected').val();
        if(iSreceiverid !=""){
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
            var content = $(".ischecktr input:checked").parent().siblings().find('.notiText').text();
            content = $.trim(content);
            var notiID = $(".ischecktr input:checked").attr('NotiID');
            notiID = $.trim(notiID);
            var sendtoContactName = $(".ischecktr input:checked").attr('contactname');
            $('#EditModalNotification').modal('show');
            $('form#form-editnotification #editNotification').val(content);
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
                console.log(xhr);
            }
        });
        btn.button('reset');
    },
    deleteNotification:function(){
        var length = 0;
        var listNotiID = [];
        length = $(".ischecktr input:checked").length;
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
            alert("Please select a notification");
            return;
        }
    },
    deleteThisNotification:function(){
        if($(this).hasClass('deleteYes')){
            return;
        } else{
            var listNotiID = [];
            var list ="";
            $me =  $(this).parent().parent().siblings().find('.checkIs');
            list =  $me.attr('NotiID');
            listNotiID.push(list);
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
        }

    }
}

$(function() {
    var mbNot= new notifications();
    mbNot.init();
});

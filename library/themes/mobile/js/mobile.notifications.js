function notifications(){ }
notifications.prototype = {
    init: function(){
        $(".checkIs").unbind('click').bind('click',this.checkIs);
        $('#ckAll').unbind('click').bind('click',this.checkAllIs);
        $('#addnotification').unbind('click').bind('click',this.addnotification);
        $('#EditNotification').unbind('click').bind('click',this.editnotification);
        $('#saveEditnotification').unbind('click').bind('click',this.saveEditnotification);
        $('#deleteNotification').unbind('click').bind('click',this.deleteNotification);
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
    }
}

$(function() {
    var mobile= new notifications();
    mobile.init();
});

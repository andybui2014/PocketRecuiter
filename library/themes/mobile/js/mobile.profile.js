function mobileProfile(){ }
mobileProfile.prototype = {
    init: function(){
        $("#cmd-logout").unbind('click').bind('click',this.logout);
    },
    logout: function(){
        var $this = $(this);
        $this.append(
            $('<div style="display:none"></div>').append($('<form method="post" id="form-logout" action="do-logout"></form>')
        )).find('#form-logout').submit();

    }
}
$(function() {
    var mobile= new mobileProfile();
    mobile.init();
});
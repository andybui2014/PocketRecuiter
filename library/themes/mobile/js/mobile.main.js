function mobileMain(){ }

mobileMain.NAME          = 'mobileMain';
mobileMain.VERSION       = '0.1';
mobileMain.DESCRIPTION   = 'Class mobileMain';

mobileMain.prototype.constructor = mobileMain;
mobileMain.prototype = {
    /**
     *  Function init main
     */
    init: function(){
        $("#cmd-logout").unbind('click').bind('click',this.logOut);
    },
    /**
     *  logOut
     *  Function lgout client
     *  @param Object|null options
     */
    logOut: function(url){
        var url = url || '';
        var $this = $(this);
        var urlSubmit = $this.attr('data-url');
        $this.append($('<div></div>').append('<form id="form-logout" method="post" action="'+urlSubmit+'"></form>'));
        $this.find('#form-logout').submit();
        return;
    }
}
$(function() {
    var mbMain= new mobileMain();
    mbMain.init();
});


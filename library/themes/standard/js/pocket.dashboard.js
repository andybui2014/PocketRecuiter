
function pocketDashboard(){}
pocketDashboard.NAME          = 'pocket recruiter';
pocketDashboard.VERSION       = '0.1';
pocketDashboard.DESCRIPTION   = 'Class pocketDashboard';

pocketDashboard.prototype.constructor = pocketDashboard;
pocketDashboard.prototype = {
    init: function(){
    }

}
$(function  () {
    var prDashboard= new pocketDashboard();
    prDashboard.init();
});



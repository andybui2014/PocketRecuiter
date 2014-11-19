
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
    }

}
$(function  () {
    var prDashboard= new pocketDashboard();
    prDashboard.init();
});



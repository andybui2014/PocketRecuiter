function pocketTest(){ }

pocketTest.NAME          = 'pocket recruiter';
pocketTest.VERSION       = '0.1';
pocketTest.DESCRIPTION   = 'Class pocketTest';

pocketTest.prototype.constructor = pocketTest;
pocketTest.prototype = {
    /**
     *  Function init main
     */
    init: function(){
       $('.btn-edit').unbind('click').bind('click',this.info);
       $('#qstCheckall').unbind('change').bind('change',this.checkAll);
    },
    checkAll: function(){
        if($(this).is(':checked')){
            $('#qst-list :input[type="checkbox"]').prop('checked', true);
        }else{
            $('#qst-list :input[type="checkbox"]').prop('checked', false);
        }
    },
    info: function(){
        $this  = $(this);
        var id = $this.attr('data-id');
        if(id > 0){
            $.ajax({
                url: '/test/test-info',
                data: {tid:id},
                type: 'POST',
                success: function(xhr){
                    if(xhr.success){

                        $('#tab-edit').show();
                        $('.title-test label').html(xhr.info.TestName);
                        var qstItem = '';
                        if(!$.isEmptyObject(xhr.info.Questions)){
                            $.each(xhr.info.Questions,function(idx,item){
                                qstItem += '<li class="item-list"><label><input data-qid="'+item.TestQuestionID+'" data-tid="'+item.Test_TestID+'" type="checkbox"/> '+item.Question+'</label> <span class="pull-right"><i class="glyphicon glyphicon-remove"></i></span></li>';
                            })

                        }
                        $('#qst-list').html(qstItem);
                    }
                }
            });
        }
    }
}
$(function() {
    var prTest= new pocketTest();
    prTest.init();
});




function pocketTest(){}
pocketTest.NAME          = 'pocket recruiter';
pocketTest.VERSION       = '0.1';
pocketTest.DESCRIPTION   = 'Class pocketTest';

pocketTest.prototype.constructor = pocketTest;
pocketTest.prototype = {
    init: function(){
       $('.btn-edit').unbind('click').bind('click',this.testInfo);
       $('#test-create').unbind('click').bind('click',this.testCreate);
       $('#test-list').find('.qst-remove').unbind('click').bind('click',this.testRemove);
       $('#test-list').find('#all-check').unbind('click').bind('click',this.testCheckAll);
       $('#test-list').find('#test-act-remove').unbind('click').bind('click',this.testRemoveAct);
       $('#test-list').find('#test-act-assign').unbind('click').bind('click',this.testAssignAct);

    },
    testRemoveAct: function(){
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#test-list :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){

                var $id = $(this).attr('data-id');
                if($id > 0){
                    $idx = $idx + 1;
                    stest += '<p>- <a>'+$(this).attr('data-text')+'</a></p>';
                    arrTests.push($id);
                }
            }
        })

        var dataTIds = arrTests;
        var $qstName = stest;
        var $modal = $('#modal-dialog');
        if(arrTests.length > 0){
            $modal.modal("show").on("shown.bs.modal", function () {
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Tests</span>');
                $modal.find('#modal-content').html('' +
                        '<p>Are you sure delete <span style="color: #b81900"><strong>'+$idx+'</strong></span> tests : '+$qstName+'</p>' +
                        '<p>' +
                        '<div class="">'+
                        '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                        '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                        '</div>' +
                        '</p>').find('#qst-cfRemove').bind('click',function(){
                        if(dataTIds.length > 0){
                            $.post( '/test/remove-tests',{dataTIds: dataTIds}).done(function( data ) {
                                if(data.success){
                                    $modal.modal("hide");
                                    $modal.on('hidden.bs.modal', function () {
                                        location.reload();
                                    })
                                }
                            });
                        }
                    });
            });
        }
    },
    testAssignAct: function(){

    },
    testCheckAll: function(){
        if($(this).is(':checked'))
            $('#test-list :input[type="checkbox"]').prop('checked', true);
        else
            $('#test-list :input[type="checkbox"]').prop('checked', false);
    },
    testRemove: function(){
        var dataTId = $(this).attr('data-id');
        var $qstName = $(this).attr('data-text');
        var $modal = $('#modal-dialog');
        $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Test</span>');
            $modal.find('#modal-content').html('' +
                    '<p>Are you sure delete test: <strong style="color: #2a6496;font-size: 14px;font-weight: bold">'+$qstName+'</strong></p>' +
                    '<p>' +
                    '<div class="">'+
                    '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                    '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                    '</div>' +
                    '</p>').find('#qst-cfRemove').bind('click',function(){
                    if(dataTId > 0){
                        $.post( '/test/remove-test',{dataTId: dataTId}).done(function( data ) {
                            if(data.success){
                                $modal.modal("hide");
                                $modal.on('hidden.bs.modal', function () {
                                     location.reload();
                                })
                            }
                        });
                    }
                });
        });
    },
    testInfo: function(){
        //$('html,body').animate({scrollTop: $(this).offset().top}, 800);
        $('#v-testname').show();

        var $this  = $(this);
        var id = $this.attr('data-id');
        if(id > 0){
            var html =  '<div class="form-group">'+
                '<label><a>Question</a></label>'+
                '<input type="text" placeholder="Question" class="form-control" name="qstName">'+
                '</div>'+
                '<div class="form-group">'+
                '<label for="credential"><a>Answer Option</a></label>'+
                '<textarea placeholder="Answer Option" class="form-control" name="qstOpt[]"></textarea>'+
                '</div>'+
                '<div class="col-xs-12">'+
                '<p class="pull-right"><a href="javascript: void(0)" class="lbl-log" id="optMore">Add More Option</a></p>'+
                '</div>' +
                '<div style="padding-left: 0;margin-top: 50px;" class="col-xs-12">'+
                '<button type="button" class="btn btn-success" id="btn-saveQst" data-loading-text="Loading..." value="">Save Question</button>'+
                '</div>';
            $('#form-qst').html(html);
            $( "#form-qst :input").prop( "disabled", true );
            $.post( '/test/test-info',{tid:id}).done(function( xhr ) {

                $('#tab-edit .panel-title').find('strong').html('Questions Sheet - Total '+ xhr.info.Questions.length);

                var params = {
                    'TestID':xhr.info.TestID,
                    'TestQuestionID':xhr.info.TestQuestionID,
                    'Test_TestID': xhr.info.Test_TestID
                };
                //Others
                $('#tab-edit').show();
                $('.title-test label').html(xhr.info.TestName);
                var qstItem = '';
                if(!$.isEmptyObject(xhr.info.Questions)){
                    $.each(xhr.info.Questions,function(idx,item){
                        qstItem += '<li class="item-list"><label><input name="qstItem[]" data-qid="'+item.TestQuestionID+'" data-tid="'+item.Test_TestID+'" type="checkbox"/> '+item.Question+'</label> <span class="pull-right"><i class="glyphicon glyphicon-remove qst-remove"  data-qid="'+item.TestQuestionID+'" data-tid="'+item.Test_TestID+'"></i></span></li>';
                    })

                }
                $('#qst-list').html(qstItem);

                //#Lib Functions
                function qstRemove(){
                    var dataTId = $(this).attr('data-tid');
                    var dataQid = $(this).attr('data-qid');
                    var $itemList = $(this).parent().parent();
                    var $qstName = $itemList.find('label').text();
                    var $modal = $('#modal-dialog');
                    $modal.modal("show").on("shown.bs.modal", function () {
                        $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Question</span>');
                        $modal.find('#modal-content').html('' +
                            '<p>Are you sure delete question: <strong style="color: #2a6496;font-size: 14px;font-weight: bold">'+$qstName+'</strong></p>' +
                            '<p>' +
                                '<div class="">'+
                                '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                                '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                                '</div>' +
                            '</p>').find('#qst-cfRemove').bind('click',function(){
                                if(dataTId > 0 && dataQid > 0){
                                    $.post( '/test/remove-question',{dataTId: dataTId,dataQid: dataQid}).done(function( data ) {
                                        if(data.success){
                                            $modal.modal("hide");
                                            $modal.on('hidden.bs.modal', function () {
                                                $itemList.fadeOut('slow').fadeIn('slow').remove();
                                            })
                                        }
                                    });
                                }
                            });
                    });
                }
                function qstCheckAll(){
                    if($(this).is(':checked'))
                        $('#qst-list :input[type="checkbox"]').prop('checked', true);
                    else
                        $('#qst-list :input[type="checkbox"]').prop('checked', false);
                }
                function qstChangeName(){
                    var $this = $(this);
                    var $nameTest = $this.parent('.title-test').find('label').html();
                    var $modal = $('#modal-dialog');
                    $modal.modal("show").on("shown.bs.modal", function () {
                        $modal.find('#myModalLabel').html($nameTest);
                        $modal.find('#modal-content').html('' +
                                '<div class="form-group"><input placeholder="Change Name Test" type="text" class="form-control" id="name-edit-text" /></div>' +
                                '<button aria-hidden="true" data-dismiss="modal" data-loading-text="Loading..." class="btn btn-success" id="name-edit-change"   type="button">Change Name</button>' +
                                '').find('#name-edit-change').unbind('click').bind('click',function(e){
                                e.preventDefault();

                                var nameTest = $('#name-edit-text').val();
                                if(nameTest !=='' && nameTest.length > 0){
                                    $.post( '/test/edit-name',{name: nameTest,testId: params.TestID}).done(function( data ) {

                                    })
                                    $this.parent('.title-test').find('label').html(nameTest);
                                }
                            });

                    });
                }
                function optEdit(){
                    if($(this).is(':checked')){
                        $( "#form-qst :input" ).prop( "disabled", false );
                        var dataTId = $(this).attr('data-tid');
                        var dataQid = $(this).attr('data-qid');
                        if(dataTId > 0 && dataQid > 0){
                            $.post( '/test/get-question',{dataTId:dataTId,dataQid:dataQid}).done(function( data ) {
                                var html = '';
                                if($.isEmptyObject(data.info)){
                                    html =  '<div class="form-group">'+
                                        '<label><a>Question</a></label>'+
                                        '<textarea  placeholder="Question" class="form-control questions-name-class" name="qstName"></textarea>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                        '<label for="credential"><a>Answer Option</a></label>'+
                                        '<textarea placeholder="Answer Option" class="form-control questions-answer" name="qstOpt[]"></textarea>'+
                                        '<input type="hidden" value="">'+
                                        '</div>'+
                                        '<div class="col-xs-12">'+
                                        '<p class="pull-right"><a  class="lbl-log" href="javascript: void(0)" id="optMore">Add More Option</a></p>'+
                                        '</div>' +
                                        '<div style="padding-left: 0;margin-top: 50px;" class="col-xs-12">'+
                                        '<button type="button" class="btn btn-success" id="btn-saveQst" data-loading-text="Loading..." value="">Save Question</button>'+
                                        '</div>';
                                }else{
                                    html =  '<div class="form-group">'+
                                        '<label><a>Question</a></label>'+
                                        '<textarea  placeholder="Question" class="form-control questions-name-class" name="qstName">'+data.info.Question+'</textarea>'+
                                        '</div>';
                                    if(!$.isEmptyObject(data.info.QuestionAnswers)){
                                        $.each(data.info.QuestionAnswers,function(idx,item){
                                            var QASID = item.TestQuestionAnswerID;
                                            if(item.TestQuestionAnswerID ==null || item.TestQuestionAnswerID =='undefined'){
                                                QASID ="";
                                            }
                                            html += '<div class="form-group">'+
                                                '<label for="credential"><a>Answer Option</a></label>'+
                                                '<textarea placeholder="Answer Option" class="form-control questions-answer" name="qstOpt[]">'+item.AnswerText+'</textarea>'+
                                                '<input type="hidden" value="'+QASID+'">'+
                                                '</div>';
                                        })
                                    }else{
                                        html += '<div class="form-group">'+
                                            '<label for="credential"><a>Answer Option</a></label>'+
                                            '<textarea placeholder="Answer Option" class="form-control questions-answer" name="qstOpt[]"></textarea>'+
                                            '<input type="hidden" value="">'+
                                            '</div>';
                                    }
                                    html += '<div class="col-xs-12">'+
                                        '<p class="pull-right"><a href="javascript: void(0)" class="lbl-log" id="optMore">Add More Option</a></p>'+
                                        '</div>' +
                                        '<div style="padding-left: 0;margin-top: 50px;" class="col-xs-12">'+
                                        '<button type="button" class="btn btn-success" id="btn-saveQst" data-loading-text="Loading..." value="">Save Question</button>'+
                                        '</div>';
                                }
                                $('#form-qst').html(html);
                                $('#form-qst').find('#optMore').unbind('click').bind('click',function(e){
                                    e.preventDefault();
                                    $( "#form-qst").find(".form-group").first().after(
                                        '<div class="form-group">'+
                                            '<label for="credential"><a>Answer Option</a></label>'+
                                            '<textarea placeholder="Answer Option" class="form-control questions-answer" name="qstOpt[]"></textarea>'+
                                            '<input type="hidden" value="">'+
                                            '</div>'
                                    );
                                });
                                $('#form-qst').find('#btn-saveQst').unbind('click').bind('click',function(){
                                    var $this = $(this);
                                    $this.button('loading');
                                    var qstarr =[];
                                    var qstListID =[];
                                    $("#form-qst .questions-answer").each(function(){
                                        var qstIds ="";
                                        var qstOpts="";

                                        qstOpts  = $(this).val();
                                        qstIds  =$(this).siblings(':input').val();
                                        qstarr.push({answerText:qstOpts,answerID:qstIds});
                                        qstListID.push(qstIds);
                                    });

                                    var qstName = $("#form-qst .questions-name-class").val();
                                   /* var dataPost = $('#form-qst').serializeArray();
                                    dataPost.push({name: "dataTId", value: dataTId});
                                    dataPost.push({name: "dataQid", value: dataQid}); */

                                    $.ajax({
                                        url: '/test/do-add-question',
                                       // data: dataPost,
                                        data: {qstName: qstName, dataTId:dataTId,dataQid:dataQid, qasList:qstarr, qstListID:qstListID},
                                        type: 'POST',
                                        success: function(data){
                                            $this.button('reset');
                                            qstAdd();
                                            //location.reload();
                                        }
                                    });
                                });
                            })
                        }
                    }else{
                        var html =  '<div class="form-group">'+
                            '<label><a>Question</a></label>'+
                            '<input type="text" placeholder="Question" class="form-control" name="qstName">'+
                            '</div>'+
                            '<div class="form-group">'+
                            '<label for="credential"><a>Answer Option</a></label>'+
                            '<textarea placeholder="Answer Option" class="form-control" name="qstOpt[]"></textarea>'+
                            '</div>'+
                            '<div class="col-xs-12">'+
                            '<p class="pull-right"><a href="javascript: void(0)" class="lbl-log" id="optMore">Add More Option</a></p>'+
                            '</div>' +
                            '<div style="padding-left: 0;margin-top: 50px;" class="col-xs-12">'+
                            '<button type="button" class="btn btn-success" id="btn-saveQst" data-loading-text="Loading..." value="">Save Question</button>'+
                            '</div>';
                        $('#form-qst').html(html);
                        $( "#form-qst :input").prop( "disabled", true );
                    }
                }
                function qstAdd(){
                    var $this = $(this);
                    $this.button('loading');
                    if($this.attr('id')=='btn-addQst'){
                        var xhr =  $.post( '/test/add-question',{testId: params.TestID}).done(function( xhrQst ) {
                            var nQid = xhrQst.qstId;
                            $.post( '/test/test-info',{tid:id}).done(function( data ) {
                                if(data.success){
                                    $this.button('reset');
                                    var html = '';
                                    $.each(data.info.Questions,function(idx,item){
                                         html +=  '<li class="item-list"><label><input  name="qstItem[]" type="checkbox" data-tid="'+item.Test_TestID+'" data-qid="'+item.TestQuestionID+'"> '+item.Question+'</label>' +
                                            ' <span class="pull-right"><i data-qid="'+item.TestQuestionID+'" data-tid="'+item.Test_TestID+'" class="glyphicon glyphicon-remove qst-remove"></i></span></li>';
                                    })
                                    $('#qst-list').html(html);
                                    $('#qst-list :input[type="checkbox"]').unbind('click').bind('click',optEdit);
                                    $('#qst-list').find('.qst-remove').unbind('click').bind('click',qstRemove);
                                    $('#qst-list :input[data-qid="'+nQid+'"]').trigger('click');


                                }
                            });
                        });

                    }else{
                        $.post( '/test/test-info',{tid:id}).done(function( data ) {
                            if(data.success){
                                $this.button('reset');
                                var html = '';
                                $.each(data.info.Questions,function(idx,item){
                                    html +=  '<li class="item-list"><label><input  name="qstItem[]" type="checkbox" data-tid="'+item.Test_TestID+'" data-qid="'+item.TestQuestionID+'"> '+item.Question+'</label>' +
                                        ' <span class="pull-right"><i data-qid="'+item.TestQuestionID+'" data-tid="'+item.Test_TestID+'" class="glyphicon glyphicon-remove qst-remove"></i></span></li>';
                                })
                                $('#qst-list').html(html);
                                $('#qst-list :input[type="checkbox"]').unbind('click').bind('click',optEdit);
                                $('#qst-list').find('.qst-remove').unbind('click').bind('click',qstRemove);
                            }
                        });
                    }

                }
                function saveTest(){
                    var xhr =  $.post( '/test/save-test',{testId: params.TestID}).done(function( data ) {});
                    xhr.always(function(){

                    })
                }
                //#Binding events
                $('#qstCheckAll').unbind('change').bind('change',qstCheckAll);
                $('#name-edit').unbind('click').bind('click',qstChangeName);
                $('#btn-addQst').unbind('click').bind('click',qstAdd);
                $('#qst-list :input[type="checkbox"]').unbind('click').bind('click',optEdit);
                $('#btn-saveTest').unbind('click').bind('click',saveTest);
                $('#qst-list').find('.qst-remove').unbind('click').bind('click',qstRemove);
            });
        }
    },
    testCreate: function(){
        var $this = $(this);
        var $modal = $('#modal-dialog');
        $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('Create New Test');
            $modal.find('#modal-content')
                .html('' +
                    '<div class="form-group"><input placeholder="New Test" type="text" class="form-control" id="name-edit-text" /></div>' +
                    '<button aria-hidden="true" data-dismiss="modal" data-loading-text="Loading..." class="btn btn-success" id="name-edit-change"   type="button">Create Test</button>'
                ).find('#name-edit-change').unbind('click').bind('click',function(e){
                    e.preventDefault();
                    var nameTest = $('#name-edit-text').val();
                    if(nameTest !=='' && nameTest.length > 0){
                        $.post( '/test/new-test',{name: nameTest}).done(function( data ) {
                            location.reload();
                        })
                        //$this.parent('.title-test').find('label').html(nameTest);
                    }
                });

        });
    }
}
$(function  () {
    var prTest= new pocketTest();
    prTest.init();
});



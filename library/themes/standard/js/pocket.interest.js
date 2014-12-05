
function pocketInterest(){}
pocketInterest.NAME          = 'pocket recruiter';
pocketInterest.VERSION       = '0.1';
pocketInterest.DESCRIPTION   = 'Class pocketInterest';

pocketInterest.prototype.constructor = pocketInterest;
pocketInterest.prototype = {
    init: function(){
       $('#addInterest').unbind('click').bind('click',this.interestCreate);
	   $('#qst-remove').unbind('click').bind('click',this.intRemove);
	   $('#test-act-remove').unbind('click').bind('click',this.testRemoveInt);
      // $('#test-create').unbind('click').bind('click',this.testCreate);
       $('#test-list').find('.qst-remove').unbind('click').bind('click',this.intRemove);
      // $('#test-list').find('#all-check').unbind('click').bind('click',this.testCheckAll);
       $('#test-list').find('li#test-act-remove').unbind('click').bind('click',this.testRemoveInt);
       $('#test-list').find('#test-edit').unbind('click').bind('click',this.editInterest);

    },
   
  IndexCheckedAll:function(){
        if($("#Index-Check-All").is(":checked")){
            $(".Index-Is-Check").prop('checked','checked');

        } else {
            $(".Index-Is-Check").removeAttr('checked');
        }
    },
  interestCreate: function(){
  var $this = $(this);
  if($this.attr('data-status')=='add'){
   var dataPost = $('#Interest-form').serializeArray();
	
	$.ajax({
		url: 'add-interest',
		data: dataPost,
		type: 'POST',
		success: function(xhr){
			//alert(xhr.success);
			if(xhr.success)
			{
				//location.reload();
				window.location = 'interest';
			}
			else
			{
				window.location = 'interest';
				alert("erro:"+xhr.info);
				
			}
			//location.reload();
		}
	});
  }
  if($this.attr('data-status')=='update'){
    $.post('do-edit-interest',{data: $('#Interest-form').serializeArray()},function(xhr){
                    if(xhr.success){
                        window.location = 'interest';
                    }else{
                        //errors
						location.reload();
                        
                    }
                })
  }
  
},
testRemoveInt: function(){
	
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#test-list :input[type="checkbox"]').each(function(idx,item){
		//$('.Index-Is-Check :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){
				
                var $id = $(this).attr('data-id');
				//alert("testt"+id);
                if($id > 0){
                    $idx = $idx + 1;
                    stest += '<p>- <a>'+$(this).attr('data-text')+'</a></p>';
                    arrTests.push($id);
                }
            }
        })
//alert(arrTests);
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
                            $.post( 'remove-interests',{dataTIds: dataTIds}).done(function( data ) {
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
	intRemove: function(){
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
                        $.post( 'delete-interest',{dataTId: dataTId}).done(function( data ) {
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
	 editInterest: function(){
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#test-list :input[type="checkbox"]').each(function(idx,item){
		//$('.Index-Is-Check :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){
				
                var $id = $(this).attr('data-id');
				//alert("testt"+id);
                if($id > 0){
                    $idx = $idx + 1;
                    stest += '<p>- <a>'+$(this).attr('data-text')+'</a></p>';
                    arrTests.push($id);
                }
            }
        })

        if(arrTests.length == 1){
            $.post('./edit-interest',{id: arrTests},function(xhr){
                if(xhr.success){
				//alert(xhr.interestid);
					$('#Interest-form #interestid').focus();
                    $('#Interest-form #interestid').val(xhr.interestid);
                    $('#Interest-form #interest').val(xhr.interesttext);
                   
                    $('#Interest-form #addInterest').attr('data-status','update').html('<strong> + Update </strong>');
                }
            })
        }
            
        if(arrTests.length > 1){
            alert("Please select only one Portfolio being Edit!");
        }
        
        
    },
}
$(function  () {
    var prInterest= new pocketInterest();
    prInterest.init();
});



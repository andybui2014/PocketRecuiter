
function pocketCandidate(){}
pocketCandidate.NAME          = 'pocket Candidate';
pocketCandidate.VERSION       = '0.1';
pocketCandidate.DESCRIPTION   = 'Class  Candidate';

pocketCandidate.prototype.constructor = pocketCandidate;
pocketCandidate.prototype = {
    init: function(){
       // $('#cmd-next').unbind('click').bind('click',this.next);
       // $('#cmd-back').unbind('click').bind('click',this.back);
       // $('#AddPorfolio').unbind('click').bind('click',this.AddPorfolio);
    },
    
    portfolioCheckAll: function(){
        if($(this).is(':checked'))
            $('#portfolioList :input[type="checkbox"]').prop('checked', true);
        else
            $('#portfolioList :input[type="checkbox"]').prop('checked', false);
    },
    deletePortfolio: function(){
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#portfolioList :input[type="checkbox"]').each(function(idx,item){
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
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Portfolio</span>');
                $modal.find('#modal-content').html('' +
                        '<p>Are you sure delete <span style="color: #b81900"><strong>'+$idx+'</strong></span> portfolios : '+$qstName+'</p>' +
                        '<p>' +
                        '<div class="">'+
                        '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                        '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                        '</div>' +
                        '</p>').find('#qst-cfRemove').bind('click',function(){
                        if(dataTIds.length > 0){
                            $.post('./do-delete-portfolio',{data: dataTIds},function(xhr){
                                if(xhr.success){
                                    location.reload();
                                }
                            })
                        }
                    });
            });
        }


    },
   editPortfolio: function(){
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#portfolioList :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){

                var $id = $(this).attr('data-id');
                if($id > 0){
                    $idx = $idx + 1;
                    stest += '<p>- <a>'+$(this).attr('data-text')+'</a></p>';
                    arrTests.push($id);
                }
            }
        })


        if(arrTests.length == 1){
            window.location = 'editportfolio?id='+arrTests; 
        }
            
        if(arrTests.length > 1){
            alert("Please select only one Portfolio being Edit!");
        }
        
    },
    deleteimage: function(){
                               

                                    $( "a" ).click(function() {
                                     var id=$(this).attr('data-id');
                                    // arrTests.push(id);
                                     if(id.length > 0){
                          
                                    $.ajax({
                                    url: 'deleteimages?id='+id,
                                    data: id,
                                    type: 'json',
                                    success: function(xhr){
                                       if(xhr.success){                            
                                        location.reload();                                  
                                         }}
                                });
                                }
    
                            });
//alert("tetsttt:"+arrTests);
       

        


    },
    AddPorfolio: function(){
        var $this = $(this);
        $this.button('loading');
        $.ajax({
                url: 'do-addportfolio',
                data: $('#portfolio-form').serializeArray(),
                type: 'POST',
                success: function(data, status, xhr){
                   if(xhr.success){                            
                    //  window.location = 'add-education'; 
                    
                      
                      // alert("success");
                      
                     }else{
                                
                               alert("erro:"+xhr.error);
                            }
                        }                
            });
    },
   

    candidateCheckedAll:function(){
        if($("#ckAll").is(":checked")){
            $(".isck").prop('checked','checked');

        } else {
            $(".isck").removeAttr('checked');
        }
    },

    
    


}


$(function  () {
    var prCandidate= new pocketCandidate();
    prCandidate.init();
});



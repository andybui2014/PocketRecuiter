
function pocketCandidate(){}
pocketCandidate.NAME          = 'pocket Candidate';
pocketCandidate.VERSION       = '0.1';
pocketCandidate.DESCRIPTION   = 'Class  Candidate';

pocketCandidate.prototype.constructor = pocketCandidate;
pocketCandidate.prototype = {
    init: function(){
        $('#cmd-next').unbind('click').bind('click',this.next);
        $('#cmd-back').unbind('click').bind('click',this.back);
        $('#add-another').unbind('click').bind('click',this.addAnother);
    },
    editEmployment: function(){
        var $this = $(this);
        if($this.attr('data-id') > 0){
            $.post('./detail-employment',{id: $this.attr('data-id') },function(xhr){

                if(xhr.success){
                    $('#employment-form :input[name="companyName"]').val(xhr.info.CompanyName);
                    $('#employment-form :input[name="posotionHeld"]').val(xhr.info.PostionHeld);
                    $('#employment-form :input[name="startDate"]').val(xhr.info.StartDate);
                    $('#employment-form :input[name="endDate"]').val(xhr.info.EndDate);
                    $('#employment-form #empDesc').val(xhr.info.Description);
                    $('#employment-form #add-another').attr('data-status','update').html('<strong> + Update Employment</strong>');
                    $('#employment-form #empId').val(xhr.info.CandidateEmploymentID);

                }
            })
        }
    },
    deleteEmployment: function(){
        var $this= $(this);
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Employment</span>');
            $modal.find('#modal-content').html(
                '<p>Are you sure delete <span style="color: #b81900"></span> employment : <strong>'+$qstName+'</strong></p>' +
                '<p>' +
                '<div class="">'+
                '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                '</div>' +
                '</p>'
            ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                    $.post('./do-remove-employment',{id: $this.attr('data-id')},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }
                    })
                })
            });
        }
    },
    editPortfolio: function(){

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

    addAnother: function(){
        var $this = $(this);
        $this.button('loading');
        //Step Education
        if($this.attr('data-step')=='education'){
            $.post('./do-add-education',{data: $('#education-form').serializeArray()},function(xhr){
                if(xhr.success){
                    location.reload();
                }
            })
            //console.log(data);
        }else if($this.attr('data-step')=='employment'){
            var compVal = $('#employment-form :input[name="companyName"]');
            if(compVal.val() !='' || compVal.val().length > 0){
                if($this.attr('data-status')=='add'){
                    $.post('./do-add-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }
                    })
                }else if($this.attr('data-status')=='update'){
                    $.post('./do-update-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }
                    })
                }

            }else{
                compVal.focus();
                compVal.parent('.form-group').addClass('has-error');
                $this.button('reset');
            }
        }else if($this.attr('data-step')=='portfolio'){
            if($this.attr('data-status')=='add'){
                var portForm = $('#portfolio-form :input[name="title"]');
                if(portForm.val() !='' || portForm.val().length > 0){
                    $.post('./do-add-portfolio',{data: $('#portfolio-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }else{
                            var urlVal = $('#portfolio-form :input[name="url"]');
                            urlVal.focus();
                            urlVal.parent('.form-group').addClass('has-error');
                            $this.button('reset');
                        }
                    })
                }else{
                    portForm.focus();
                    portForm.parent('.form-group').addClass('has-error');
                    $this.button('reset');
                }

            }
        }
        //$this.button('reset');
    },
    back: function(){
        var $this = $(this);
        $this.button('loading');
        if($this.attr('data-back').length > 0){
            location.href = './profile-builder?utm_source=' + $this.attr('data-back');
        }else{
            location.href = './profile-builder?utm_source=contact';
        }
    },
    next: function(){
        var $this = $(this);
        $this.button('loading');
        var dataId = $this.attr('data-id');
        if(dataId=='contact'){
            $.post( './step-next-contact',{data: $('#form-contact').serializeArray()}).done(function( xhr ) {
                if(xhr.success){
                    location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                }
            });
        }else{
            location.href = './profile-builder?utm_source=' + $this.attr('data-next');
        }
    }
}
$(function  () {
    var prCandidate= new pocketCandidate();
    prCandidate.init();
});



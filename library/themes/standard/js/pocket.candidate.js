
function pocketCandidate(){}
pocketCandidate.NAME          = 'pocket Candidate';
pocketCandidate.VERSION       = '0.1';
pocketCandidate.DESCRIPTION   = 'Class  Candidate';

pocketCandidate.prototype.constructor = pocketCandidate;
pocketCandidate.prototype = {
    init: function(){
        $('#cmd-next').unbind('click').bind('click',this.next);
        $('#cmd-next1').unbind('click').bind('click',this.next);
        $('#cmd-back').unbind('click').bind('click',this.back);
        $('#cmd-back1').unbind('click').bind('click',this.back);
        $('#add-another').unbind('click').bind('click',this.addAnother);
		$('#add-anotherjob').unbind('click').bind('click',this.addAnotherJob);
        $('#save-anotherjob').unbind('click').bind('click',this.saveAnotherJob);
        $('#save-anotherjob1').unbind('click').bind('click',this.saveAddAnotherJob);
		$('#cancel').unbind('click').bind('click',this.cancelJobFunction);
        $('#save-anotherjobnew').unbind('click').bind('click',this.saveAnotherJobnew);
		//$('#btn-removejob1').unbind('click').bind('click',this.deleteJobFunction1);
    },
	
    editEmployment: function(){
        var $this = $(this);
		//$('#divemployment').show();
       // $('#divjobfunction').hide();
        if($this.attr('data-id') > 0){
            $.post('./detail-employment',{id: $this.attr('data-id') },function(xhr){

                if(xhr.success){
				//alert(xhr.info.CompanyName);
                   // location.reload();
                   
                    $('#employment-form :input[name="companyName"]').val(xhr.info.CompanyName);
                    $('#employment-form :input[name="posotionHeld"]').val(xhr.info.PostionHeld);
                    $('#employment-form :input[name="startDate"]').val(xhr.info.StartDate);
                    $('#employment-form :input[name="endDate"]').val(xhr.info.EndDate);
                    $('#employment-form #empDesc').val(xhr.info.Description);
                    $('#employment-form #add-another').attr('data-status','update').html('<strong> + Update Employment</strong>');
                    $('#employment-form #empId').val(xhr.info.CandidateEmploymentID);
                    location.href = './profile-builder?utm_source=employment&id=' + $this.attr('data-id');

                }
            })
        }
    },
    deleteJobFunction: function(){
        var $this= $(this);
        var empId=$('empId').val();
       // alert("testtt");
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Job Function</span>');
            $modal.find('#modal-content').html(
                '<p>Are you sure delete <span style="color: #b81900"></span> job function : <strong>'+$qstName+'</strong></p>' +
                '<p>' +
                '<div class="">'+
                '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                '</div>' +
                '</p>'
            ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                    $.post('./do-remove-job-function',{id: $this.attr('data-id'),empId:empId},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }
                    })
                })
            });
        }
    },
	cancelJobFunction: function(){
		//location.reload();
        $('#openModalJobFunction').modal('hide');
	},
	   editJobFuntion: function(){
        var $this = $(this);
		//$('#divemployment').hide();
        $('#openModalJobFunction').modal('show');

		$('#add-anotherjob').show();
		var empId=$('#empId').val();
        var JobID=$('#JobID').val();
        //alert(empId);
        if($this.attr('data-id') > 0){
            $.post('./detail-job-function',{id: $this.attr('data-id'),empId:empId,JobID:JobID },function(xhr){

                if(xhr.success){
				//alert(xhr.info);
                    $('#employment-form #JobFucntion').val(xhr.info.JobFunctionID);
                    $('#employment-form #JobID').val(xhr.info.id);
					$('#employment-form #Percentage').val(xhr.info.Percentage);
                   // $('#employment-form #save-anotherjob').attr('data-status','updateJob').html('<strong>Save</strong>');
                    $('#employment-form #JobFunctionID').val(xhr.info.JobFunctionID);

                }
            })
        }
    },
    edit_JobFuntionnew: function(){
        var $this = $(this);
        //$('#divemployment').hide();
        $('#openModalJobFunctionEditnew').modal('show');

        $('#add-anotherjob').show();
        var empId=$('#empId').val();
        var JobID=$('#JobID').val();
        //alert(empId);
        if($this.attr('data-id') > 0){
            $.post('./edit-job-function-new',{id: $this.attr('data-id')},function(xhr){

                if(xhr.success){
                //alert(xhr.info);
                var i=0;
                var j=0
                var aValue=[];
                var pecent=[];
                $('input[name="JobFucntion1[]"]').each(function()
                 { 
                     
                      aValue[i] = $(this).val(); 
                     i=i+1;
                    
                 }
                 );
                // alert(aValue);
                $('input[name="Percentage1[]"]').each(function()
                 { 
                     
                      pecent[j] = $(this).val(); 
                      j=j+1;
                   // alert(pecent);
                 }
                 );
                 var Percentage_Edit = "";
            for (var i = 0; i < aValue.length; i++) {
               
                if(aValue[i]==xhr.info.JobFunctionID)
                {
                   // alert("tetst");
                   for(var j=0;j<pecent.length;j++){
                      // alert("i:"+i+"j:"+j)
                       if(i==j ){
                           Percentage_Edit=pecent[j];
                       }
                   }
                }
            }
               // alert(Percentage_Edit);
                    $('#employment-form #JobFucntion_Edit').val(xhr.info.JobFunctionID);
                  //  $('#employment-form #JobID').val(xhr.info.id);
                    $('#employment-form #Percentage_Edit').val(Percentage_Edit);
                   $('#employment-form #pecent_edit').val(Percentage_Edit);
                    $('#employment-form #JobFunctionID_Edit').val(xhr.info.JobFunctionID);
                    

                }
            })
        }
    },
    saveAddAnotherJob: function(){
        var $this = $(this); 
       // var pecent=$('#Percentage1').val();
        //alert(pecent);
        $('#employment-form :input[type="text"]').change(function(){
        if($(this).val() !='' && $(this).val().length > 0){
        $(this).parent().removeClass('has-error');
        }
         });
         $.post('./do-add-job-function',{data: $('#employment-form').serializeArray()},function(xhr){
                            
                            if(xhr.info=='Total Percentage greater than 100%' || xhr.info=='Percentage not empty' || xhr.info=='Percentage must be integer')
                            {
                                alert(xhr.info);
                                $('#employment-form #Percentage_add').parent().addClass('has-error');
                            }else{
                                $('#employment-form #Percentageddd_add').parent().removeClass('has-error');
                            }
                            if(xhr.info=='Job Fucntion is exit.'){
                                alert(xhr.info);
                                $('#employment-form #JobFucntion_add').parent().addClass('has-error');
                            }else {
                                $('#employment-form #JobFucntion_add').parent().removeClass('has-error');
                            }
                            //alert(xhr.pecent);
                            if(xhr.info==null || xhr.info==""){
                                $('#openModalJobFunction1').modal('hide');
                                var JobFucntion=$('#JobFucntion_add option:selected').text();
                                var Percentage=$('#Percentage_add').val();
                                var dataid=$('#JobFucntion_add').val();
                                //alert(JobFucntion);
                               // alert(dataid);
                                var string="<span id='btn-editjob1' class='btn-editjob1' style='color:#4cae4c;' data-id="+dataid+"> <strong>Edit</strong></span>&nbsp;&nbsp;&nbsp;&nbsp;<span  id='btn-removejob1' class='btn-removejob1' data-id="+dataid+" data-text="+JobFucntion+"><i class='glyphicon glyphicon-remove qst-removejob1'></i></span>";
                                //
                                $('#jobfunction').find("tbody")
                        .append($("<tr id="+dataid+">")
                .append($("<td id='jobfun' style='width: 60%;'>").text(JobFucntion))
                .append($("<td id='pecent' style='width: 20%;'>").text(Percentage+'%'))
                .append($("<td id='tol' style='float:right; padding-right: 10px;'>").append(string))
                );
                
                 $('table#jobfunction tr#'+dataid).find('td#jobfun').append($("<input type='hidden'  name='JobFucntion1[]'  value='"+dataid+"' >"));
                 $('table#jobfunction tr#'+dataid).find('td#pecent').append($("<input type='hidden'  name='Percentage1[]' id='Percentage1[]' value='"+Percentage+"' >"));
                $('#jobfunction .btn-editjob1').unbind('click').bind('click',prCandidate.edit_JobFuntionnew);
                $('#jobfunction #btn-removejob1').unbind('click').bind('click',prCandidate.deleteJobFunction1);
                
                                //
                                
                                
                                
                                
                                
                            }
                            if(xhr.pecent==1){
                                $("#add-anotherjob").hide();
                            }
                            
                        })
    },
    deleteJobFunction1:function(){
        var $this= $(this);
        var empId=$('empId').val();
        
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
            $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Job Function</span>');
            $modal.find('#modal-content').html(
                '<p>Are you sure delete <span style="color: #b81900"></span> job function : <strong>'+$qstName+'</strong></p>' +
                '<p>' +
                '<div class="">'+
                '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                '</div>' +
                '</p>'
            ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                   // $.post('./do-remove-job-function',{id: $this.attr('data-id'),empId:empId},function(xhr){
                     //   if(xhr.success){
                          //  location.reload();
                       // }
                      var idtr= $this.attr('data-id')
                       $('table#jobfunction tr#'+idtr).remove();
                       $('#modal-dialog').modal('hide');
                       
                    });
                });
           // });
        }
    },
    saveAnotherJob: function(){
       var $this = $(this); 
       var status=$('#save-anotherjob').attr("data-status");
        
	$('#employment-form :input[type="text"]').change(function(){
		if($(this).val() !='' && $(this).val().length > 0){
		$(this).parent().removeClass('has-error');
		}
	});
	var JobVal = $('#employment-form #Percentage');
	var JobFucntion = $('#employment-form #JobFucntion');
	//alert(status);
				
                        var empId=$('#empId').val();
						
						$.post('./do-update-job-function',{data: $('#employment-form').serializeArray()},function(xhr){
							if(xhr.info=='Total Percentage greater than 100%' || xhr.info=='Percentage not empty')
							{
								alert(xhr.info);
								$('#employment-form #Percentage').parent().addClass('has-error');
							}else{
								$('#employment-form #Percentage').parent().removeClass('has-error');
							}
                            if(xhr.info=='Job Fucntion not empty'){
                                alert(xhr.info);
                                $('#employment-form #JobFucntion').parent().addClass('has-error');
							}else {
                                $('#employment-form #JobFucntion').parent().removeClass('has-error');
							}
                            
							if(xhr.success==1){   
                             var idtr=xhr.info.JobFunctionID_old;
                                var idjob=xhr.info.JobFucntion_ID;
                                var idpecnt=xhr.info.Percentage;
                                var pecent_old=xhr.info.Percentage_old;
                                var JobFucntion=$('#JobFucntion option:selected').text();
                               // alert(JobFucntion);
                                 $('#openModalJobFunction').modal('hide');
                                
                               // $('table#jobfunction tr#'+idtr).remove();
                               $('table#jobfunction tr#'+idtr).find('td#jobfun').remove();
                               $('table#jobfunction tr#'+idtr).find('td#pecent').remove();
                               $('table#jobfunction tr#'+idtr).find('td#tol').remove();
                                
                                 var string="<span id='btn-editjob1' class='btn-editjob1' style='color:#4cae4c;' data-id="+idjob+"> <strong>Edit</strong></span>&nbsp;&nbsp;&nbsp;&nbsp;<span  id='btn-removejob1' class='btn-removejob1' data-id="+idjob+" data-text="+JobFucntion+"><i class='glyphicon glyphicon-remove qst-removejob1'></i></span>";
                                //
                                $('table#jobfunction').find('tr#'+idtr).append(($("<td style='width: 60%;' id='jobfun'>").text(JobFucntion)).append("<input type='hidden'  name='JobFucntion1[]'  value='"+idjob+"' >"));
                                 $('table#jobfunction').find('tr#'+idtr).append(($("<td style='width: 20%;' id='pecent'>").text(idpecnt+'%')).append("<input type='hidden'  name='Percentage1[]' id='Percentage1[]' value='"+idpecnt+"' ><input type='hidden'  name='Percentage1_old[]' id='Percentage1_old[]' value='"+pecent_old+"' >"));
                                  $('table#jobfunction').find('tr#'+idtr).append($("<td style='float:right; padding-right: 10px;' id='tol'>").append(string));
                                 
                                
                                
                              /*  $('#jobfunction').find("tbody")
                        .append($("<tr id="+idjob+">")
                .append($("<td style='width: 60%;'>").text(JobFucntion)).append("<input type='hidden'  name='JobFucntion1[]'  value='"+idjob+"' ></span>")
                .append($("<td style='width: 20%;'>").text(idpecnt+'%')).append("<input type='hidden'  name='Percentage1[]' id='Percentage1[]' value='"+idpecnt+"' ></span>")
                .append($("<td style='float:right; padding-right: 10px;'>").append(string))
                );*/
                $('#jobfunction .btn-editjob1').unbind('click').bind('click',prCandidate.edit_JobFuntionnew);
                $('#jobfunction #btn-removejob1').unbind('click').bind('click',prCandidate.deleteJobFunction1);                        
                               
                               
                            }
                           
                        })
                   
    },
     saveAnotherJobnew: function(){
       var $this = $(this); 
      
        
    $('#employment-form :input[type="text"]').change(function(){
        if($(this).val() !='' && $(this).val().length > 0){
        $(this).parent().removeClass('has-error');
        }
    });
   
                        $.post('./do-update-job-function-new',{data: $('#employment-form').serializeArray()},function(xhr){
                            if(xhr.info=='Total Percentage greater than 100%' || xhr.info=='Percentage not empty')
                            {
                                alert(xhr.info);
                                $('#employment-form #Percentage_Edit').parent().addClass('has-error');
                            }else{
                                $('#employment-form #Percentage_Edit').parent().removeClass('has-error');
                            }
                            if(xhr.info=='Job Fucntion not empty'){
                                alert(xhr.info);
                                $('#employment-form #JobFucntion_Edit').parent().addClass('has-error');
                            }else {
                                $('#employment-form #JobFucntion_Edit').parent().removeClass('has-error');
                            }
                            
                            if(xhr.success==1){
                                var idtr=xhr.info.JobFunctionID_old;
                                var idjob=xhr.info.JobFucntion_ID;
                                var idpecnt=xhr.info.Percentage;
                                var JobFucntion=$('#JobFucntion_Edit option:selected').text();
                               // alert(JobFucntion);
                                $('#openModalJobFunctionEditnew').modal('hide');
                                
                               // $('table#jobfunction tr#'+idtr).remove();
                               $('table#jobfunction tr#'+idtr).find('td#jobfun').remove();
                               $('table#jobfunction tr#'+idtr).find('td#pecent').remove();
                               $('table#jobfunction tr#'+idtr).find('td#tol').remove();
                                
                                 var string="<span id='btn-editjob1' class='btn-editjob1' style='color:#4cae4c;' data-id="+idjob+"> <strong>Edit</strong></span>&nbsp;&nbsp;&nbsp;&nbsp;<span  id='btn-removejob1' class='btn-removejob1' data-id="+idjob+" data-text="+JobFucntion+"><i class='glyphicon glyphicon-remove qst-removejob1'></i></span>";
                                //
                                $('table#jobfunction').find('tr#'+idtr).append(($("<td style='width: 60%;' id='jobfun'>").text(JobFucntion)).append("<input type='hidden'  name='JobFucntion1[]'  value='"+idjob+"' >"));
                                
                                $('table#jobfunction').find('tr#'+idtr).append(($("<td style='width: 20%;' id='pecent'>").text(idpecnt+'%')).append("<input type='hidden'  name='Percentage1[]' id='Percentage1[]' value='"+idpecnt+"' >"));
                                
$('table#jobfunction').find('tr#'+idtr).append($("<td style='float:right; padding-right: 10px;' id='tol'>").append(string));
                              /*  $('#jobfunction').find("tbody")
                        .append($("<tr id="+idjob+">")
                .append($("<td style='width: 60%;' id='jobfun'>").text(JobFucntion))
                .append($("<td style='width: 20%;' id='pecent'>").text(idpecnt+'%'))
                .append($("<td style='float:right; padding-right: 10px;'>").append(string))
                );
                 $('table#jobfunction tr#'+idjob).find('td#jobfun').append($("<input type='hidden'  name='JobFucntion1[]'  value='"+idjob+"' >"));
                 $('table#jobfunction tr#'+idjob).find('td#pecent').append($("<input type='hidden'  name='Percentage1[]' id='Percentage1[]' value='"+idpecnt+"' >"));*/

                $('#jobfunction .btn-editjob1').unbind('click').bind('click',prCandidate.edit_JobFuntionnew);
                $('#jobfunction #btn-removejob1').unbind('click').bind('click',prCandidate.deleteJobFunction1);
                                
                            }
                        });
                                
                   
    },
	addAnotherJob: function(){
		
	

    $('#openModalJobFunction1').modal('show');
     $.post('./add-job-function',{data: $('#employment-form').serializeArray()},function(xhr){
         if(xhr.pecent<=100){
             $('#employment-form #Percentage_add').val(xhr.pecent);
         }
     })

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
	
    deleteEducation:function(){
        var $this= $(this);
        if($this.attr('data-id') > 0){
            var $modal = $('#modal-dialog');
            var $qstName = $this.attr('data-text');
            $modal.modal("show").on("shown.bs.modal", function () {
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Confirm Delete Education</span>');
                $modal.find('#modal-content').html(
                        '<p>Are you sure delete <span style="color: #b81900"></span> education : <strong>'+$qstName+'</strong></p>' +
                            '<p>' +
                            '<div class="">'+
                            '<button type="button" id="qst-cfRemove" class="btn btn-primary">Confirm delete</button>&nbsp;&nbsp;&nbsp;' +
                            '<button type="button" aria-hidden="true" data-dismiss="modal"  class="btn btn-default">Close</button>'+
                            '</div>' +
                            '</p>'
                    ).find('#qst-cfRemove').unbind('click').bind('click',function(){
                        $.post('./do-remove-education',{id: $this.attr('data-id')},function(xhr){
                            if(xhr.success){
                                location.reload();
                            }
                        })
                    })
            });
        }
    },
    editPortfolio: function(){
       //console.log('test');
        var arrTests = [];
        var stest = '';
        var $idx = 0;
        $('#portfolioList :input[type="checkbox"]').each(function(idx,item){
            if($(this).is(':checked')){
                var $id = $(this).attr('data-id');
                if($id > 0){
                    arrTests.push($id);
                }
            }
        })
        if(arrTests.length){
            $.post('./get-portfolio',{data: arrTests},function(xhr){
                if(xhr.success){
                    console.log(xhr.data.CandidatePortfolioID);
                    $('#portfolio-form input[name="title"]').val(xhr.data.Title);
                    $('#portfolio-form input[name="url"]').val(xhr.data.URL);
                    $('#portfolio-form #portDesc').val(xhr.data.Description);
                    $('#portfolio-form input[name="portId"]').val(xhr.data.CandidatePortfolioID);
                    $('#portfolio-form #add-another').attr('data-status','update').html('<strong>Update Portfolio</strong>');

                }
            })
        }else{
            $modal.modal("show").on("shown.bs.modal", function () {
                $modal.find('#myModalLabel').html('<span style="color: #b81900">Warning</span>');
                $modal.find('#modal-content').html('<p>Only one portfolio</p>');
            });
        }

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
    editEducation: function(){
        var $this = $(this);
        if($this.attr('data-id') > 0){
            $.post('./detail-education',{id: $this.attr('data-id')},function(xhr){
                if(xhr.success){
                    $('#education-form #eduId').val(xhr.info.CredentialExperienceID);
                    $('#education-form :input[name="inst-name"]').val(xhr.info.institution_name);
                    $('#education-form :input[name="degree-name"]').val(xhr.info.title);
                    $('#education-form :input[name="start-year"]').val(xhr.info.startdate);
                    $('#education-form :input[name="end-year"]').val(xhr.info.enddate);
                    $('#education-form #add-another').attr('data-status','update').html('<strong> + Update Education</strong>');
                }
            })
        }
    },
    addAnother: function(){
        var $this = $(this);
        $this.button('loading');
        //Step Education
        if($this.attr('data-step')=='education'){

            $('#education-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });

            if($this.attr('data-status')=='add'){
            $.post('./do-add-education',{data: $('#education-form').serializeArray()},function(xhr){
                if(xhr.success){
                    location.reload();
                    }else{
                        //errors
                        $.each(xhr.info,function(key,item){
                            $('#education-form :input[name="'+key+'"]').parent().addClass('has-error');
                        })
                        $this.button('reset');

                    }
                })
            }else{
                $.post('./do-update-education',{data: $('#education-form').serializeArray()},function(xhr){
                    if(xhr.success){
                        location.reload();
                    }else{
                        //errors
                        $.each(xhr.info,function(key,item){
                            $('#education-form :input[name="'+key+'"]').parent().addClass('has-error');
                        })
                        $this.button('reset');
                    }
                })
            }


            //console.log(data);
        }else if($this.attr('data-step')=='employment'){
            $('#employment-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });
			
            var compVal = $('#employment-form :input[name="companyName"]');
            if(compVal.val() !='' || compVal.val().length > 0){
                if($this.attr('data-status')=='add'){
                    $.post('./do-add-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.reload();
                        }else{
                            //errors
                            $.each(xhr.info,function(key,item){
                                $('#employment-form :input[name="'+key+'"]').parent().addClass('has-error');
                            })
                            $this.button('reset');
                        }
                    })
                }else if($this.attr('data-status')=='update'){
                    $.post('./do-update-employment',{data: $('#employment-form').serializeArray()},function(xhr){
                        if(xhr.success){
                            location.href = './profile-builder?utm_source=employment';
                        }else{
                            //errors
                            $.each(xhr.info,function(key,item){
                                $('#employment-form :input[name="'+key+'"]').parent().addClass('has-error');
                            })
                            $this.button('reset');
                        }
                    })
                }

            }else{
                compVal.focus();
                compVal.parent('.form-group').addClass('has-error');
                $this.button('reset');
            }
        }else if($this.attr('data-step')=='portfolio'){
            $('#portfolio-form :input[type="text"]').change(function(){
                if($(this).val() !='' && $(this).val().length > 0){
                    $(this).parent().removeClass('has-error');
                }
            });
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

            }else{
                //update
                $.post('./do-update-portfolio2',{data: $('#portfolio-form').serializeArray()},function(xhr){
                    if(xhr.success){
                        location.reload();
                    }
                })
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
                }else{
                    //errors
                    $.each(xhr.info,function(key,item){
                       $('#form-contact :input[name="'+key+'"]').parent().addClass('has-error');
                    })
                    $this.button('reset');
                }
            });
        }else if(dataId=='skills'){
            var data = []; //attribute-lst
            /* $('#tree-lst').find('img').each(function(idx,item){
                if($(this).attr('class') !=='img-toggle' && $(this).attr('data-status')=='select'){
                    data.push($(this).attr('data-id'));
                }
            }); */

            var data_attr =[];
            $('#attribute-lst .tree').each(function(idx,item){
                if($(this).find('img').attr('class') !=='img-toggle' && $(this).find('img').attr('data-status')=='select'){

                    $(this).find('.attr-attr').each(function(){
                         var attr_id = $(this).find('.attrid').attr('attr-id');
                         var attr_value = $(this).find('.attr-value option:selected').val();
                         var attr_YoE = $(this).find('.attr-YoE').val();
                         var attr_LevelofInterest = $(this) .find('.attr-LevelofInterest option:selected').val();

                         if(typeof(attr_value) === 'undefined'){
                             attr_value = '';
                         }

                        if(typeof(attr_YoE) === 'undefined'){
                            attr_YoE = '';
                        }

                        if(typeof(attr_LevelofInterest) === 'undefined'){
                            attr_LevelofInterest = '';
                        }

                        data_attr.push({attr_id:attr_id, attr_value:attr_value,attr_YoE:attr_YoE,attr_LevelofInterest:attr_LevelofInterest});

                    });
                }
            });
            //console.log(data_attr);

            /*$.post('./do-update-skills',{data:data},function(xhr){
                if(xhr.success){
                    location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                }
            })  */
            $.ajax({
                url: './do-update-skills',
                data: {data:data_attr},
                type: 'POST',
                success: function(xhr){
                    if(xhr.success){
                        location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                    }

                }
            });
        }else{
            location.href = './profile-builder?utm_source=' + $this.attr('data-next');
        }
    },
    watchListCheckedAll:function(){
        if($("#ckAll").is(":checked")){
            $(".isck").prop('checked','checked');

        } else {
            $(".isck").removeAttr('checked');
        }
    },

    watchListIsChecked:function(){
        var lengthAllCheckbox = $('.isck').length;
        if($('.isck').is(":checked")) {
            if ($(".trIsck input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        } else{
            if ($(".trIsck input:checked").length === lengthAllCheckbox) {
                $('#ckAll').prop('checked', true);
            } else {
                $('#ckAll').prop('checked',false);
            }
        }
    },
    skillViewNext:function(){
        var data = [];
        $('#tree-lst').find('img').each(function(idx,item){
            if($(this).attr('class') !=='img-toggle' && $(this).attr('data-status')=='select'){
                data.push($(this).attr('data-id'));
            }
            
        });
        
        $.post('./do-update-skillsnew',{data:data},function(xhr){
            if(xhr.success){
                //console.log(data);
            
                for(var i=0; i<data.length; i++){
                  var id=data[0];
                }
            //alert(id);
                location.href = 'skills-edit?id='+id;
                //location.reload();
            }
        })
    },
    skillViewBack:function(){
        location.reload();
    },
    updateskill:function(){
        $.post('./do-edit-skill',{data: $('#form-skill').serializeArray()},function(xhr){
                    if(xhr.success){
                        location.reload();
                    }
                    else{
                        location.reload();
                    }
                })
                
         
        
    },
    editSkills: function(url){
        $(window).load(function(){
$(function  () {
                $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
                $('.tree li.parent_li > span').on('click', function (e) {
                    var children = $(this).parent('li.parent_li').find(' > ul > li');
                    if (children.is(":visible")) {
                        children.hide('fast');
                        $(this).attr('title', 'Expand this branch')
                            .find(' > i')
                            .addClass('icon-plus-sign')
                            .removeClass('icon-minus-sign');
                        $(this).find('.img-toggle').attr('src',url+'images/trees/ico_add_sm.png');
                    } else {
                        children.show('fast');
                        $(this).attr('title', 'Collapse this branch')
                            .find(' > i')
                            .addClass('icon-minus-sign')
                            .removeClass('icon-plus-sign');
                        $(this).find('.img-toggle').attr('src',url+'images/trees/ico_sub_sm.png');
                    }
                    e.stopPropagation();
                });
                //window ready loaded
                $('.img-parent').unbind('click').bind('click',function(){
                    if($(this).attr('data-status')=='select'){
                        $(this)
                            .attr('src',url+'images/trees/ico_expand.png')
                            .attr('data-status','deselect');

                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_expand_sm.png')
                            .attr('data-status',$(this).attr('data-status'));

                    }else if($(this).attr('data-status')=='deselect'){
                        $(this).attr('src',url+'images/trees/ico_colapse.png').attr('data-status','select');
                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_colapse_sm.png')
                            .attr('data-status',$(this).attr('data-status'));
                    }
                })

               $('.img-parent-attr').unbind('click').bind('click',function(){
                    if($(this).attr('data-status')=='select'){
                        $(this)
                            .attr('src',url+'images/trees/ico_expand.png')
                            .attr('data-status','deselect');

                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_expand_sm.png')
                            .attr('data-status',$(this).attr('data-status'));


                        $(this).closest('.parent_li').find('.attr-attr').each(function(){
                            $(this).find('.attr-value').prop("disabled", true);
                            $(this).find('.attr-YoE').prop("disabled", true);
                            $(this) .find('.attr-LevelofInterest').prop("disabled", true);

                        });


                    }else if($(this).attr('data-status')=='deselect'){
                        $(this).attr('src',url+'images/trees/ico_colapse.png').attr('data-status','select');
                        $(this).parent()
                            .find('.img-item')
                            .attr('src',url+'images/trees/ico_colapse_sm.png')
                            .attr('data-status',$(this).attr('data-status'));
                        $(this).closest('.parent_li').find('.attr-attr').each(function(){
                            $(this).find('.attr-value').prop("disabled", false);
                            $(this).find('.attr-YoE').prop("disabled", false);
                            $(this) .find('.attr-LevelofInterest').prop("disabled", false);

                        });
                    }
                })

                $('.img-item').unbind('click').bind('click',function(){
                    var parentLi = $(this).closest('.parent_li');
                    if($(this).attr('data-status')=='select'){
                        $(this)
                            .attr('src',url+'images/trees/ico_expand_sm.png')
                            .attr('data-status','deselect');
                    }else{
                        $(this)
                            .attr('src',url+'images/trees/ico_colapse_sm.png')
                            .attr('data-status','select');
                        }
                    if($(this).nextAll('span:first').attr('title')=='Collapse this branch'){
                        $(this).nextAll('ul')
                            .find('.img-item')
                            .attr('data-status',$(this).attr('data-status'))
                            .attr('src',$(this).attr('src'));
                    }
                });
            });
        });//]]>
    }, 
	
    editContactInfo:function(){
       // $(this).button('loading');
        var arrValidate = [
            'firstname','lastname','email','country',
            'addResLine','addResLine2','city','stateProvince','zipcode'
        ];
        var form = $('#form-contact');
        var isError = false;
        form.find('input[type="text"]').each(function(){
            var item = $(this);
            if($.inArray(item.attr('name'),arrValidate) >= 0){
                if(item.val()==''){
                    isError = true;
                    item.parent().addClass('has-error');
                }else{
                    item.parent().removeClass('has-error');
                }
            }
        });
        if(isError==false){
            $.post('./update-contact-info',{data:form.serializeArray()},function(xhr){
               console.log(xhr);
                $(this).button('reset');
            })
        }
    },
	
	deleteWatchList:function(id){
        $.ajax({
            url: 'delete-watch-list',
            data: {OpportunityID:id},
            type: 'POST',
            success: function(data, status, xhr){
                if(data){
                    window.location = 'watch-list';
                }
            }
        });
    },
    addNewCandidateEmploy:function(){
        var addemployment = $(this).attr('addemployment');

        $('#form-add-edit-candidate-employment :input[type="text"]').change(function(){
            if($(this).val() !='' && $(this).val().length > 0){
                $(this).parent().removeClass('has-error');
            }
        });
        $.ajax({
            url: 'do-candidate-employment',
            data: $('#form-add-edit-candidate-employment').serializeArray(),
            type: 'POST',
            success: function(xhr){
                if(xhr.success){
                    if(addemployment=='reloadYes'){
                        location.reload();
                    } else {
                        $('.position-held').val("");
                        $('.Candidate-employmentID').val("");
                        $('.client-name').val("");
                        $('.des').val("");
                        $('.start-date').val("");
                        $('.end-date').val("");
                    }

                }else{
                   $.each(xhr.info,function(key,item){
                       $('#form-add-edit-candidate-employment :input[name="'+key+'"]').parent().addClass('has-error');
                  })
                }
            }
        });
    },
referencesCreate: function(){
  var $this = $(this);
  var addemployment = $(this).attr('addemployment');
   var dataPost = $('#form-references').serializeArray();
	
	$.ajax({
		url: 'do-add-references',
		data: dataPost,
		type: 'POST',
		success: function(xhr){
			//alert(xhr.success);
			if(xhr.success)
			{
				if(addemployment=='reloadYes'){
                        location.reload();
                    } else {
                        $('.referencename').val("");
                        $('.referenceemail').val("");                       
                        $('.referencecomment').val("");
                        
                    }
			}
			else
			{
				//location.reload();
				//alert("erro:"+xhr.info);
				var referencename_message = $('#form-references #referencename_message');
				var referenceemail_message = $('#form-references #referenceemail_message');
				if(xhr.info=='References name not empty')
				{
					 referencename_message.parent().addClass('has-error');
					 referencename_message.html("References name not empty.");
				}else{
					 referencename_message.parent().removeClass('has-error').addClass('has-success');
					 referencename_message.html('');
				}
				var x = $('#referenceemail').val();
                var atpos = x.indexOf("@");
                var dotpos = x.lastIndexOf(".");
                
                if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
                    error = true;
                    $("#referenceemail_message").addClass('has-error');
                    $("#referenceemail_message").html("The references mail is not a valid email address");
                    }else{
                    $("#referenceemail_message").removeClass('has-error').addClass('has-success');
                    $("#referenceemail_message").html('');
                }  
				
				
			}
			//location.reload();
		}
	});
  },
	addOppSkillsToSearch:function(){
        var skillText = $("#add-opp-skills option:selected").text();
        var skilIDSelected = $("#add-opp-skills option:selected").val();

            $("div#match-opp-skills").append("<span style='' class='label-tag pull-left'>" + skillText +
                "&nbsp;&nbsp;<imge class='resetSkill delete-skills glyphicon glyphicon-remove' height='15px' skilIDSelected='"+skilIDSelected+"' style='cursor:pointer; color:#ccc;' > " +
                "<input type='hidden'  name='matchopportunitySear[]' value='"+skilIDSelected+"' ></span>");


        $(".delete-skills").unbind("click").bind("click",function(){
            var skilID = $(this).attr("skilIDSelected");
            $("#add-opp-skills").find("option[value='" + skilID + "']").css("display", "");
            $(this).parent().next('span').remove();
            $(this).parent().remove();


        });


        $("#add-opp-skills option:selected").css("display", "none");
        $("#add-opp-skills option[value='']").prop("selected", "selected");


    },

    matchOpportunities:function(CandidateProfileID){
       // var CandidateProfileID =$(this).attr('CandidateProfileID');
        var dataform = $('#form-match-opportunity-sear').serializeArray();
        $.ajax({
            url: 'match-opportunity',
            dataType: 'html',
            data: dataform,
            cache: false,
            type: 'POST',
            context: this,
            error : function (status,xhr,error) {
             console.log(status);  console.log(xhr); console.log(error);
            },
            success: function(data,status,xhr){
                if(data){
                    $(".containerData").html("");
                    $(".containerData").html(data);
                    $(".btn-for-apply").unbind("click").bind("click",pocketCandidate.prototype.candidateApply)

                    $('.readmore').unbind('click').bind('click',function() {
                        var $this = $(this);
                        var $collapse = $this.closest('.collapse-group').find('.collapse');
                        $collapse.collapse('toggle');
                    });
                }

            }
        });
    },

    candidateApply:function(){
        $(this).addClass('disabled');
        var  $me =  $(this)
        var OpportunityID = $(this).attr('OpportunityID')
        var CandidateProfileID = $(this).attr('CandidateProfileID')
        $.ajax({
            url: 'candidate-apply',
            data: {CandidateProfileID:CandidateProfileID, OpportunityID:OpportunityID},
            type: 'POST',
            success: function(xhr){
                if(xhr.success ==1){
                    $('#this-job-was-applied').modal('show');

                    $('#modal-this-job-was-applied').css("color","green");
                    $('#modal-this-job-was-applied').text(xhr.error);
                }else{
                    $('#this-job-was-applied').modal('show');
                    $('#modal-this-job-was-applied').css("color","red");
                    $('#modal-this-job-was-applied').text(xhr.error);
                }
                $me.addClass('disabled');
                $me.closest('.borderbottom_Gray').find('img:first').attr('src',urlImage+candiateImage);
            }
        });

    },

    activitiesCheckedAll:function(){
        if($("#Active-Check-All").is(":checked")){
            $(".Active-Is-Check").prop('checked','checked');

        } else {
            $(".Active-Is-Check").removeAttr('checked');
        }
    },

    activitiesChecked:function(){
        var lengthAllCheckbox = $('.Active-Is-Check').length;
        if($('.Active-Is-Check').is(":checked")) {
            if ($(".activitiesIsChecked input:checked").length === lengthAllCheckbox) {
                $('#Active-Check-All').prop('checked', true);
            } else {
                $('#Active-Check-All').prop('checked',false);
            }
        } else{
            if ($(".activitiesIsChecked input:checked").length === lengthAllCheckbox) {
                $('#Active-Check-All').prop('checked', true);
            } else {
                $('#Active-Check-All').prop('checked',false);
            }
        }
    },

    deleteActivities:function(id){
        $.ajax({
            url: 'delete-activities',
            data: {OpportunityID:id},
            type: 'POST',
            success: function(data, status, xhr){
                if(data){
                    window.location = 'activities';
                }
            }
        });
    },

    skill_Test_View :function(testID, testName){
        var page = $(this).attr('page');
        var size = $("#pagesize").val();
        if (typeof(page) === 'undefined')
            page = 1;
        $.ajax({
            url: 'skill-test-view',
            dataType: 'html',
            data: {page:page, size:size, testID:testID, testName:testName},
            cache: false,
            type: 'POST',
            context: this,
            error : function (status,xhr,error) {
            },
            success: function(data,status,xhr){
                if(data){
                    $(".contain-right").html("");
                    $(".contain-right").html(data);
                    jQuery(".paginator").unbind("click").bind("click", pocketCandidate.prototype.STV_changepage);
                }

            }
        });
    },

    STV_changepage:function()
    {
        //--- page,size
        var testID = $(this).attr('testID');
        var page = $(this).attr('page');
        var totalpage = $(this).attr('totalpage');
        var SaveTestAnswer = $(this).attr('SaveTestAnswer');
        var TestQuestionAnswerID = $('.existingCheck').find('.img-check').attr('TestQuestionAnswerID');
        var finish =0;
        if(SaveTestAnswer==1){
            if(page>0 && page < totalpage){
                page ++ ;
            } else{
                page = totalpage;
                finish =1;

            }
        } else{
            if(page>0 && page < totalpage){
                page ++ ;
            } else{
                page =1;
            }
        }

        var size = $("#pagesize").val();
        if (typeof(page) === 'undefined')
            page = 1;
        $.ajax({
            type: "POST",
            url: "skill-test-view",
            data: {page:page, size:size,SaveTestAnswer:SaveTestAnswer,TestQuestionAnswerID:TestQuestionAnswerID,testID:testID },
            success:function(html) {
                jQuery('.contain-right').html(html);
                $('.start-test-before').hide();
                $('.start-test').show();
                jQuery(".paginator").unbind("click").bind("click", pocketCandidate.prototype.STV_changepage);
                if(finish==1){
                    //$("#finish-test").modal('show');
                    pocketCandidate.prototype.skill_Test_Result(testID);
                }
            }
        });
    },

    skill_Test_Result :function(testID){
        $.ajax({
            url: 'skill-test-result',
            dataType: 'html',
            data: {testID:testID},
            cache: false,
            type: 'POST',
            context: this,
            error : function (status,xhr,error) {
            },
            success: function(data,status,xhr){
                if(data){
                    $(".contain-right").html("");
                    $(".contain-right").html(data);
                }

            }
        });
    }
}


$(function  () {
    var prCandidate= new pocketCandidate();
    prCandidate.init();
});



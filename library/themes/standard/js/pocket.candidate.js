
function pocketCandidate(){}
pocketCandidate.NAME          = 'pocket Candidate';
pocketCandidate.VERSION       = '0.1';
pocketCandidate.DESCRIPTION   = 'Class  Candidate';

pocketCandidate.prototype.constructor = pocketCandidate;
pocketCandidate.prototype = {
    init: function(){
        $('#cmd-next').unbind('click').bind('click',this.next);
        $('#cmd-back').unbind('click').bind('click',this.back);
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
            $.post( '/candidate/step-next-contact',{data: $('#form-contact').serializeArray()}).done(function( xhr ) {
                if(xhr.success){
                    location.href = './profile-builder?utm_source=' + $this.attr('data-next');
                }
            });
        }
    }
}
$(function  () {
    var prCandidate= new pocketCandidate();
    prCandidate.init();
});



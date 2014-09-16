function SetHoverIn(item, name)
{
    var str = $('.' + item).children('a').attr('class');
    if(typeof(str) == 'undefined') return false;                                                
    if(str.indexOf('active'))
    {
        $('.' + item).children('a').children('.'+ name +'_left').attr('class', 'newmenu_left '+ name +'_left_hover');
        $('.' + item).children('a').children('.'+ name +'_bg').attr('class', ' newmenu_bg '+ name +'_bg_hover');
        $('.' + item).children('a').children('.'+ name +'_right').attr('class', 'newmenu_right '+ name +'_right_hover');
    }
};
function SetHoverOut(item, name)
{
    var str = $('.' + item).children('a').attr('class');
    if(str.indexOf('active')==-1)
    {
        $('.' + item).children('a').children('.'+ name +'_left_hover').attr('class', 'newmenu_left '+ name +'_left');
        $('.' + item).children('a').children('.'+ name +'_bg_hover').attr('class', ' newmenu_bg '+ name +'_bg');
        $('.' + item).children('a').children('.'+ name +'_right_hover').attr('class', 'newmenu_right '+ name +'_right');
    }
};
jQuery(window).ready(function() {
    // for menu hover
    $('#tabul li').hover(
        function()
        {
            var _class = jQuery(this).attr("class");
            SetHoverIn(_class, _class);
        },
        function()
        {
            var _class = jQuery(this).attr("class");
            SetHoverOut(_class, _class);
        });
    // for menu onclick GUI   
    var tabid = jQuery("#hidSelectedMenu").val();   
    function setSelectedMenu(tabid)
    {
        if(jQuery.trim(tabid) == "") tabid = "home";
        SetHoverIn(tabid, tabid);
        $('#tabul .'+tabid).unbind("hover");
        $('#linemenu').attr('class', tabid + "_line");
        $('#headerbg').attr('class', tabid + "_headerbg");
        $('#footerbg').attr('class', tabid + "_footerbg");
    }
    function setSelectedMenuLi()
    {
        $('#tabul li').hover(
            function()
            {
                var _class = jQuery(this).attr("class");
                SetHoverIn(_class, _class);
            },
            function()
            {
                var _class = jQuery(this).attr("class");
                SetHoverOut(_class, _class);
            });
        $('#tabul li').each(function() {
            var _class = jQuery(this).attr("class");
            SetHoverOut(_class, _class);
        });
        tabid = jQuery(this).attr("class");
        setSelectedMenu(tabid);
        var url = jQuery(this).attr("url");
        loadingBodyContent(url);
    }
    setSelectedMenu(tabid);
    $('#tabul li').unbind("click",setSelectedMenuLi).bind("click",setSelectedMenuLi);
     
    // for menu get Ajax
     
    var opened_one = null; 
    $(function()
    {
        // hide all panels first : bad need to do something about this
        $('div[id*="MyDiv"]').hide();
        // make the panels absolute positioned
        $('div[id*="MyDiv"]').css('position', 'absolute');
        // show the panel based on rel attribute of the current hovered link
                                                                                                                                                                                                                                                                    
        $('#imgarrow').hover(function()
        {
            // hide previously opened panel
            $(opened_one).hide();
            var link_rel = "MyDiv";
            //get the position of the hovered element
            var pos = $(this).offset();
            // get the panel near by hovered element now
            $('div#' + link_rel).css({
                'left': (pos.left - 100 ) + 'px',
                'top':  (pos.top  + $(this).height() + 10) + 'px',
                'zIndex': '5000'
            });
            // finaly show the relevant hidden panel
            $('div#' + link_rel).fadeIn('slow');
            // this is currently opened
            opened_one = $('div#' + link_rel);
            // hide it back when mouse arrow moves away
            $('div#' + link_rel).hover(function(){}, function(){
                $(this).fadeOut('slow');
            });
        }, function(){});
                                                                                                                                                                                                                                                                    
                
    });
});                                                                                                                                                                                                                                                                   




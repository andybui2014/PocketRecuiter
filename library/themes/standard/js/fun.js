/************************************************************************************/
/*********************************** Library ****************************************/
/************************************************************************************/

function jAutoComplete(id, frmId, objConfig){
    if(!jQuery('#divAuComLayer').length){
        var layer = jQuery('<div id="divAuComLayer" class="aucomplete"></div>').appendTo(jQuery(document.body));
    }else{
        var layer = jQuery('#divAuComLayer');
    }
    
    layer.css({
        'position': 'absolute',
        'border': '1px solid #454545',		
        'top': -15000,
        'background': '#ffffff'
    });
    var input = jQuery('#' + frmId + ' #' + id);
    if(input.length == 0)	return;
	
    var timer = null;
    
    input.unbind().bind('keyup', function(e){
        var _this = this;
        if(e.which != 38 && e.which != 40 && e.which != 37 && e.which != 39 && e.which != 13){
            timer = clearTimeout(timer);
            timer = setTimeout(function(){                
                objConfig.param.name = _this.value;
                var url = objConfig.url + '?' + jQuery.param(objConfig.param);
                jQuery.ajax({                    
                    url: urlSite + 'admin/location/getLatLong',
                    type:'POST',
                    data: {
                        'url': url
                    },
                    success: function(resp){
                        resp = eval(resp);                        
                        if(typeof(resp)!='object'){                            
                            layer.html('');
                            layer.css({
                                'top': -15000
                            });
                        }else if(resp.length != 0){                            
                            var coords = input.offset();
                            strHTML = '<ul>';
                            for(i=0; i<resp.length; i++){
                                strHTML += '<li id="' + resp[i].lat + ',' + resp[i].lng + '">' + resp[i].vicinity + '</li>';
                            }
                            strHTML += '</ul>';
                            layer.html(strHTML);
                            layer.css({
                                'top': coords.top + input.height(),
                                'left': coords.left
                            });
                            var ul = jQuery(layer.find('ul')[0]);
                            if(ul.find('li').length > 10){
                                layer.css({
                                    'overflow-y':'scroll',
                                    'overflow-x':'hidden'
                                });
                                layer.css({
                                    'height': ''
                                });
                            }else{
                                layer.css({
                                    'height': 'auto'
                                });
                            }
                            ul.find('li').each(function(index, li){
                                jQuery(li).unbind().bind({
                                    'click': function(e){
                                        var _this = jQuery(this);
                                        var locate = _this.attr('id').split(',');
                                        jQuery('#' + frmId + ' #txtAddress').val(_this.text());
                                        jQuery('#' + frmId + ' #txtLongitude').val(locate[1]);
                                        jQuery('#' + frmId + ' #txtLatitude').val(locate[0]);
                                        layer.css('top', -15000);
                                    },
                                    'mouseover':function(e){
                                        jQuery(li).addClass('current');
                                    },
                                    'mouseout': function(e){
                                        jQuery(li).removeClass('current');
                                    }
                                });
                            });
                            var nav = -1;
                            jQuery(document).unbind('keydown').bind({
                                'keydown': function(e){
                                    if(!ul.find('li.current').length){
                                        ul.children().first().addClass('current');
                                        nav = 0;
                                    }
                                    if(e.which == 38){  //up
                                        if(ul.find('li.current').prev().length > 0){
                                            ul.find('li.current').removeClass('current').prev().addClass('current');
                                            nav--;
                                        }
                                        if(nav >= 9){
                                            layer[0].scrollTop = (nav-9)*20;
                                        }else{
                                            layer[0].scrollTop = 0;
                                        }                                        
                                    }
                                    if(e.which == 40){  //down
                                        if(ul.find('li.current').next().length > 0){
                                            ul.find('li.current').removeClass('current').next().addClass('current');
                                            nav++;
                                        }
                                        if(nav >= 9){
                                            layer[0].scrollTop = (nav-9)*20;
                                        }else{
                                            layer[0].scrollTop = 0;
                                        }
                                    }                                                                            
                                    if(e.which == 13){
                                        ul.find('li.current').trigger('click');
                                    }
                                },
                                'click': function(e){
                                    if(!layer.find(e.target).length){
                                        layer.html('');
                                        layer.css({
                                            'top': -15000
                                        });
                                    }
                                }
                            });
                            
                        } else {
                            layer.html('');
                            layer.css({
                                'top': -15000
                            }); 
                        }
                    }				
                });
            }, 750);
        }
    });
}

function jTabs(tabs, contents){
    if(tabs.length == contents.length){
        tabs.each(function(ind, tab){
            jQuery(tab).click(function(e){
                e.preventDefault();
                contents.addClass('hidden');
                jQuery(contents[ind]).removeClass('hidden');
				
                tabs.removeClass('active');
                this.addClass('active');
            });
        });
    }
}

/**
 * Validate Form
 * @param {string} id
 * @param {object} object 
 */
function jValidateForm(id, elements, erConfig){
    var frmObj = jQuery('#' + id);	
    if(frmObj.length > 0){
        //initialize
        var errorConfig = {};
        if(typeof(erConfig) == 'undefined' || typeof(erConfig.errorConfig) == 'undefined'){
            errorConfig = {
                type: 'layer',	//3 type: layer or showhide or multierror
                customError: 'alertForm' + id
            }
            var erLayer = jQuery('<div id="'+ errorConfig.customError +'" class="validateBox"><p class="message"></p></div>').appendTo(jQuery(document.body));			
            //create layer
            erLayer.find('.message').css({
                'font-size': '16px',
                'font-weight': 'bold',
                'padding': 0
            });
            erLayer.css({
                'padding': '10px',
                'background': '#00A1C1',
                'color': '#ffffff',
                'height': '16px',
                'position': 'absolute',
                'top': -15000
            });
            frmObj.data('alertForm', erLayer);
        }
        if(erConfig && typeof(erConfig.errorConfig) != 'undefined'){
            if(typeof(erConfig.errorConfig.type)!='undefined'){
                errorConfig.type = erConfig.errorConfig.type;
            }
            if(typeof(erConfig.errorConfig.customError)!='undefined'){
                erLayer = jQuery(erConfig.errorConfig.customError);
            }
        }
        var showErrorTimeout = null;
		
        //init elements
        function initFormElements(els){
            for(var i=0; i<els.length; i++){												
                var elObj = frmObj.find('#' + els[i].field);
                if(elObj.length > 0){
                    elObj.data('initVal', els[i].init);				
                    if(typeof(elObj.data('initVal'))!='undefined' && elObj.data('initVal') != ''){
                        elObj.val(elObj.data('initVal'));                        
                        elObj.unbind('focus').bind('focus', function(e){
                            var _this = jQuery(this);
                            if(jQuery.trim(_this.val()) == _this.data('initVal')){
                                _this.val('');
                            }
                        });
                        elObj.unbind('blur').bind('blur', function(e){
                            var _this = jQuery(this);
                            if(jQuery.trim(_this.val()) == ''){
                                _this.val(_this.data('initVal'));
                            }
                        });						
                    }
                    if(elObj[0].tagName.toLowerCase() == 'input'){
                        elObj.unbind('keypress').bind('keypress', function(e){
                            if(e.which == 13){	//enter
                                frmObj.trigger('submit');
                            }
                        });
                    }                    
                }
            }
        }		
        
        initFormElements(elements);
		
        //add event
        frmObj[0]._submitted = false;
        frmObj.unbind('submit').bind('submit', function(e){
            //reset error
            jQuery(errorConfig.customError).addClass('hidden');
            //valid error
            var errorObj = validElements(elements);            
            if(errorObj.length > 0){				
                if(errorConfig.type == 'layer'){
                    showLayer(errorObj[0].element, errorObj[0].message);				
                }else if(errorConfig.type == 'showhide'){
                    showHideError(errorObj[0].element, errorObj[0].message);
                }else{
                    showElementError(errorObj[0].element, errorObj[0].message, errorObj[0].errEl);
                }
                return false;
            }else{                
                jQuery(errorConfig.customError).addClass('hidden');				
                if(erConfig && typeof(erConfig.onSubmit)!='undefined'){
                    e.preventDefault();					
                    e.stopPropagation();
                    if(!frmObj[0]._submitted){
                        erLayer.fadeOut(200);
                        erConfig.onSubmit();
                        frmObj[0]._submitted = true;
                    }
                }
            }
        });
        //add event submit
        if(frmObj.find('.btnSubmit, .submit').length > 0){
            frmObj.find('.btnSubmit, .submit').unbind('click').bind('click', function(e){
                e.preventDefault();				
                frmObj.trigger('submit');
            });
        }
		
        function validElements(els){
            var errorEl = [];
            for(var i=0; i<els.length; i++){				
                var msgpos = isValidElement(els[i]);
                var msg = els[i].messages.split('|');
                var err = frmObj.find(errorConfig.customError)[i];
				
                if(msgpos != -1){	//error					
                    if(errorConfig.type == 'multierror'){
                        errorEl.push({
                            'element': els[i].field, 
                            'message': msg[msgpos],
                            'errEl': jQuery(err)
                        });
                    }else{
                        errorEl.push({
                            'element': els[i].field, 
                            'message': msg[msgpos]
                        });
                    }
                    break;
                }
            }			
            return errorEl;
        }
        function isValidElement(el){				
            var pos_error = -1;
            var elObj = frmObj.find('#' + el.field);
            if(typeof(el.valid)=='function'){
                pos_error = el.valid();
            }else{
                var valid = el.valid.split('|');
                for(i=0; i<valid.length; i++){
                    if(valid[i] == 'require'){
                        if(elObj[0].tagName == 'SELECT'){
                            if(jQuery.trim(elObj.val()) == 0){							
                                pos_error = i;
                                break;
                            }
                        }else if(elObj[0].tagName == 'INPUT' || elObj[0].tagName == 'TEXTAREA'){
                            if(elObj.attr('type') == 'hidden'){
                                if(jQuery.trim(elObj.val()) == 0){							
                                    pos_error = i;
                                    break;
                                }
                            }else if(elObj.attr('type') == 'radio'){
                                var _er = true;
                                elObj.each(function(ind, elo){
                                    if(elo.checked){
                                        _er = false;
                                    }
                                });
                                if(_er){
                                    pos_error = i;
                                    break;
                                }
                            }else if(elObj.attr('type') == 'checkbox'){
                                if(!elObj[0].checked){
                                    pos_error = i;
                                    break;
                                }
                            }else{
                                if(jQuery.trim(elObj.val()) == '' || jQuery.trim(elObj.val()) == elObj.data('initVal')){							
                                    pos_error = i;
                                    break;
                                }					
                            }
                        }
                    }
                    if(valid[i] == 'email'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isEmail(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }                        
                    }
                    if(valid[i] == 'zip'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isZip(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }                        
                    }
                    if(valid[i] == 'phone'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isPhone(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }                        
                    }
                    if(valid[i] == 'number'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isNumeric(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }
                    }
                    if(valid[i] == 'digit'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isDigit(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }
                    }
                    if(valid[i] == 'url'){
                        if(jQuery.trim(elObj.val())!=''){
                            if(!isUrl(jQuery.trim(elObj.val()))){
                                pos_error = i;
                                break;
                            }
                        }
                    }
                    if(valid[i][0]=='='){
                        var _field = valid[i].substring(1, valid[i].length);
                        var value = frmObj.find('#' + _field).val();
                        if(value != jQuery.trim(elObj.val())){
                            pos_error = i;
                            break;
                        }
                    }
                    if(valid[i].indexOf('minlength') != -1){
                        var min = valid[i].substring(valid[i].indexOf('=') + 1, valid[i].length);
                        if(jQuery.trim(elObj.val()).length < min){
                            pos_error = i;
                            break;
                        }
                    }
                    if(valid[i].indexOf('maxlength') != -1){
                        var max = valid[i].substring(valid[i].indexOf('=') + 1, valid[i].length);
                        if(jQuery.trim(elObj.val()).length > max){
                            pos_error = i;
                            break;
                        }
                    }
                }
            }
            return pos_error;
        }
        function isEmail(value){
            var re = new RegExp("^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]{2,4}$");
            return (value.search(re) != -1);
        }
        function isZip(value){
            var re = /^[0-9 \(\)\-]*$/;
            return re.test(value);
        }
        function isPhone(value){
            var re = /^[0-9 \(\)\-\.\+]*$/;
            return re.test(value);
        }
        function isDigit(value){
            var re = /^[-+]?\d*\.?\d+(?:[eE][-+]?\d+)?$/;
            return re.test(value);			
        }
        function isNumeric(value){
            var re = /^[0-9]+$/;
            return re.test(value);			
        }
        function isAlphabet(value){
            var re = /^[a-zA-Z]+$/;
            return re.test(value);			
        }
        function isUrl(value){
            var re = /(((https?)|(ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i;
            return re.test(value);			
        }
        function showLayer(el, msg){			
            var elObj = frmObj.find('#' + el);
            if(elObj.attr('type') == 'hidden' || elObj.attr('type') == 'checkbox'){
                var coords = elObj.parent().offset();	
                var _height = elObj.parent().height();
            }else{
                var coords = elObj.offset();		
                var _height = elObj.height();
            }
            var layer = frmObj.data('alertForm').removeClass('hidden');
            layer.find('.message').text(msg);
            layer.css({
                'top': coords.top + _height + 2,
                'left': coords.left
            });
            showErrorTimeout = clearTimeout(showErrorTimeout);
            showErrorTimeout = setTimeout(function(){
                layer.css({
                    'top': -15000
                });					
            }, 3000);
            if(elObj.length > 0){
                if(elObj.attr('type') == 'hidden'){
                    elObj.parent()[0].focus();
                    var c = elObj.parent().offset();
                    window.scrollTo(c.left,c.top);
                }else{
                    elObj[0].focus();
                }
            }
        }
        function showHideError(el, msg){
            var erEl = erLayer;
            erEl.text(msg);
            erEl.fadeIn(250);		
            showErrorTimeout = clearTimeout(showErrorTimeout);
            showErrorTimeout = setTimeout(function(){
                erEl.fadeOut(250);	
            }, 5000);
            if(frmObj.find('#' + el).length > 0){
                frmObj.find('#' + el)[0].focus();			
            }
        }
        frmObj[0].showHideError = showHideError;
        function showElementError(el, msg, err){
            if(err.find('.message').length > 0){
                err.find('.message').text(msg);
            }
            err.removeClass('hidden');
            if(frmObj.find('#' + el).length > 0){
                frmObj.find('#' + el)[0].focus();			
            }
        }
        function showHideMessage(el, msg){
            var erEl = erLayer;
            erEl.addClass('no-error');
            erEl.text(msg);
            erEl.fadeIn(250);		
            showErrorTimeout = clearTimeout(showErrorTimeout);
            showErrorTimeout = setTimeout(function(){
                erEl.fadeOut(250, function(){
                    erEl.removeClass('no-error');
                });	                
            }, 5000);
            if(frmObj.find('#' + el).length > 0){
                frmObj.find('#' + el)[0].focus();			
            }
        }
        frmObj[0].showHideMessage = showHideMessage;		
    }
}
function jCustomSelect(cont) {
    var selectors = jQuery('.customSelect');
    if(cont){
        selectors = cont.find('.customSelect');
    }
    if (selectors.length > 0) {
        selectors.each(function(index,selector) {
            runCustomSelect(selector);
        });
    }
	
    function runCustomSelect(sel) {		
        var selectorObj = jQuery(sel);
        selectorObj[0].csValue = selectorObj.find('.csValue')[0];		
        selectorObj[0].csText = selectorObj.find('.csText')[0];		
        if(!selectorObj.find('select')[0]) return;
		
        var strLi = '';
        selectorObj.find('select option').each(function(index, opt){
            if(index==0){
                strLi = '<li class="first" id="' + jQuery(opt).val() + '">'+ jQuery(opt).html() +'</li>';				
            }else{
                strLi += '<li id="' + jQuery(opt).val() + '">'+ jQuery(opt).html() +'</li>';
            }
            if(jQuery(opt).attr('selected')){
                selectorObj[0].csValue.value = jQuery(opt).val();
                selectorObj[0].csText.value = jQuery(opt).html();	
            }
        });
        if(typeof(selectorObj[0].csText.csLayer)!='undefined'){
            selectorObj[0].csText.csLayer.remove();
        }
        selectorObj[0].csText.csLayer = jQuery('<div class="customOption"><div class="csLayer"><ul>' + strLi + '</ul></div></div>');		
        selectorObj[0].csText.csLayer.appendTo(document.body);
        selectorObj[0].csText.csLayer.data('opened',false);
        selectorObj.find('.csIcon').unbind().click(function(e){
            e.preventDefault();
            var liHeight = jQuery(selectorObj[0].csText.csLayer.find('li')[0]).outerHeight();
            if(selectorObj[0].csText.csLayer.find('li').length > 4 ){
                var _height = Math.min(liHeight * 4, ((selectorObj[0].csText.csLayer.find('li').length) * liHeight));				
            }else {
                var _height = (selectorObj[0].csText.csLayer.find('li').length * liHeight);
            }
            var _width = selectorObj.outerWidth();
            var coords = selectorObj.offset();
            
            if(!selectorObj[0].csText.csLayer.data('opened')){
                selectorObj[0].csText.csLayer.addClass('smScrollContent').css({
                    'z-index': 9999,
                    'overflow': 'hidden',
                    'height': _height,
                    'width': _width,
                    'position': 'absolute',
                    'top': coords.top + selectorObj.outerHeight(),
                    'left': coords.left
                });                
                selectorObj[0].csText.csLayer.data('opened',true);
            }else{
                close();
            }
            
            selectorObj[0].csText.csLayer.find(':first').css('height', _height);
            if(jQuery(selectorObj[0].csText.csLayer.find('li').length > 4)){
                selectorObj[0].csText.csLayer.find(':first').css({
                    'overflow':'hidden',
                    'overflow-y': 'auto'
                });
            }
        });
				
        selectorObj[0].csText.csLayer.find('li').each(function (ind, li) {
            jQuery(li).css('cursor', 'pointer');
            jQuery(li).unbind().bind({
                'click': function(e){
                    e.preventDefault();
                    selectorObj[0].csValue.value = jQuery(this).attr('id');
                    selectorObj[0].csText.value = jQuery(this).text();				
                    //callback
                    callback(selectorObj[0].csValue.value);
                    //end callback	
                    close();
                },
                'mouseenter': function(){
                    selectorObj[0].csText.csLayer.find('li').removeClass('current');
                    jQuery(this).addClass('current');					
                }
            });
        });
        function callback(value){
            if(selectorObj.attr('id') == 'cboStoreLocation'){
                jQuery.ajax({
                    url: urlSite + 'admin/ServiceItem/list',
                    data: {
                        'value': value
                    },
                    type:'POST',
                    success: function(resp){
                        if(jQuery('#listItemCont').length > 0){
                            jQuery('#listItemCont').html(resp);
                            jQuery('.cmdPagingItem').unbind('click',PagingItems)
                            .bind('click',PagingItems);
                        }
                    }
                });          
            }
            if(selectorObj.attr('id') == 'cboTaskInLayerAdd'){
                if(value == 4){
                    jQuery('#addCatalogueBox #itemStringScan').parent().removeClass('hidden');
                }else{
                    jQuery('#addCatalogueBox #itemStringScan').parent().addClass('hidden');
                }
            }
            if(selectorObj.attr('id') == 'cboTaskInLayerEdit'){
                if(value == 4){
                    jQuery('#editCatalogueBox #itemStringScan').parent().removeClass('hidden');
                }else{
                    jQuery('#editCatalogueBox #itemStringScan').parent().addClass('hidden');
                }
            }
            if(selectorObj.attr('id') == 'cboItemTypeInLayerAdd'){
                if(value=='tasks'){
                    jQuery('#addCatalogueBox #cboTaskInLayerAdd').parent().removeClass('hidden');
                    jQuery('#addCatalogueBox #inpDiscount').parent().addClass('hidden');
                    jQuery('#addCatalogueBox #inpPrice').parent().addClass('hidden');
                } else if(value=='discounts'){
                    jQuery('#addCatalogueBox #cboTaskInLayerAdd').parent().addClass('hidden');
                    jQuery('#addCatalogueBox #inpDiscount').parent().removeClass('hidden');
                    jQuery('#addCatalogueBox #inpPrice').parent().addClass('hidden');
                } else if(value=='listings'){
                    jQuery('#addCatalogueBox #cboTaskInLayerAdd').parent().addClass('hidden');
                    jQuery('#addCatalogueBox #inpDiscount').parent().addClass('hidden');
                    jQuery('#addCatalogueBox #inpPrice').parent().removeClass('hidden');
                }
            }
            if(selectorObj.attr('id') == 'cboItemTypeInLayerEdit'){
                if(value=='tasks'){
                    jQuery('#editCatalogueBox #cboTaskInLayerEdit').parent().removeClass('hidden');
                    jQuery('#editCatalogueBox #inpDiscount').parent().addClass('hidden');
                    jQuery('#editCatalogueBox #inpPrice').parent().addClass('hidden');
                } else if(value=='discounts'){
                    jQuery('#editCatalogueBox #cboTaskInLayerEdit').parent().addClass('hidden');
                    jQuery('#editCatalogueBox #inpDiscount').parent().removeClass('hidden');
                    jQuery('#editCatalogueBox #inpPrice').parent().addClass('hidden');
                } else if(value=='listings'){
                    jQuery('#editCatalogueBox #cboTaskInLayerEdit').parent().addClass('hidden');
                    jQuery('#editCatalogueBox #inpDiscount').parent().addClass('hidden');
                    jQuery('#editCatalogueBox #inpPrice').parent().removeClass('hidden');
                }
            }
        }
        function close(layer){
            if(typeof(layer)!='undefined'){
                layer.css('top', -15000);
                layer.data('opened',false);
            }else{
                selectorObj[0].csText.csLayer.css('top', -15000);
                selectorObj[0].csText.csLayer.data('opened',false);
            }
        }
    }
    jQuery(document).unbind('mousedown').mousedown(function(e){				
        if(!jQuery(e.target).hasClass('customOption') && jQuery(e.target).parents('.customOption').length == 0 /*&& !jQuery(e.target).hasClass('smScroller') && jQuery(e.target).parents('.smScroller').length == 0*/){
            jQuery('.customOption').css('top', -15000);
            jQuery('.customOption').each(function(index, sel){				
                jQuery(sel).data('opened',false);
            });
        }
    });
}

// Add of Tien Nguyen
function PagingItems()
{
    var value = jQuery(".csValue").val();
    var url = jQuery(this).attr("url");
    jQuery.ajax({
        url: url,
        data: {
            'value': value
        },
        type:'POST',
        success: function(resp){
            if(jQuery('#listItemCont').length > 0){
                jQuery('#listItemCont').html(resp);
                jQuery('.cmdPagingItem').unbind('click',PagingItems)
                .bind('click',PagingItems);
            }
        }
    }); 
}

function jCustomRadiobox(cont){
    var arrChk = jQuery('.rdunchecked');
    if(cont){
        arrChk = jQuery(cont).find('.rdunchecked');
    }
    var prev = null;
    if(arrChk.length > 0){
        arrChk.each(function(index, rad){			
            if(jQuery(rad).hasClass('rdchecked')){
                prev = jQuery(rad);
            }			
            jQuery(rad).unbind('click').bind('click', function(e){
                e.preventDefault();				
                if(prev == null){
                    prev = jQuery(this);
                    jQuery(this).addClass('rdchecked');
                    if(jQuery(this).find(':first').length > 0){
                        jQuery(this).find(':first').checked = true;
                    }
                }else{
                    prev.removeClass('rdchecked');
                    if(prev.find(':first').length > 0){
                        prev.find(':first').checked = false;
                    }
                    jQuery(this).addClass('rdchecked');
                    if(jQuery(this).find(':first').length > 0){
                        jQuery(this).find(':first').checked = true;
                    }
                    prev = jQuery(this);
                }
				
                if(jQuery(this).find(':first').attr('id') == 'rdrdNewSurvey'){
                    jQuery('#slSurveyTemplate').addClass('hidden');
                }else if(jQuery(this).find(':first').attr('id') == 'rdrdCopySurvey'){
                    jQuery('#slSurveyTemplate').removeClass('hidden');					
                }
            });
        });
    }
}

function jCustomCheckbox(cont){
    var arrChk = jQuery('.csCheckbox');
    if(cont){
        arrChk = jQuery(cont).find('.csCheckbox');
    }
    var count = 0;
    var chkAll;
    if(arrChk.length > 0){
        arrChk.each(function(index, chk){
            if(jQuery(chk).hasClass('checked'))
                count++;
            if(jQuery(chk).hasClass('chkAll'))
                chkAll = jQuery(chk);
            jQuery(chk).unbind('click').bind('click', function(e){
                e.preventDefault();
                if(jQuery(this).hasClass('chkAll')){
                    if(jQuery(this).hasClass('checked')){
                        arrChk.removeClass('checked');
                        if(jQuery(this).find(':first'))
                            jQuery(this).find(':first')[0].checked = false;
                    }else{
                        arrChk.addClass('checked');
                        if(jQuery(this).find(':first'))
                            jQuery(this).find(':first')[0].checked = true;
                    }
                }else{
                    if(jQuery(this).hasClass('checked')){
                        jQuery(this).removeClass('checked');
                        if(jQuery(this).find(':first'))
                            jQuery(this).find(':first')[0].checked = false;
						
                    }else{
                        jQuery(this).addClass('checked');
                        if(jQuery(this).find(':first'))
                            jQuery(this).find(':first')[0].checked = true;
						
                    }					
                }
            });
        });
        if(count == arrChk.length-1 && arrChk.length >= 2){
            if(chkAll && chkAll[0]){
                chkAll.addClass('checked');	
                if(chkAll.find(':first'))
                    chkAll.find(':first').checked = true;
            }			
        }
    }
}



function jShowLayer(id){
    var el = jQuery(id);
    if(el.length == 0 ) return;	
	
    if(window.openedLayer1 == null){
        window.openedLayer1 = id;
        jShowMask(true);
    }else{
        window.openedLayer2 = id;
    }	
	
    jQuery(el).css({
        'position': 'absolute',
        'zIndex': 9990,
        'display': 'block'
    });
	
    el.find('.close, .btnClose').unbind('click').bind('click', function(e){
        e.preventDefault();
        jHideLayer(id);
    });	
    	
    //set position
    jCenter(id);
}
/**
 * show message layer
 * @param {string} id	  
 */
function jShowMessage(id){
    var el = jQuery(id);
    if(el.length == 0 ) return;	
	
    if(window.openedLayer1 == null){
        window.openedLayer1 = id;
        jShowMask(true);
    }else{
        window.openedLayer2 = id;
    }
	
    jQuery(el).css({
        'position': 'absolute',
        'zIndex': 9999,
        'display': 'block'
    });
    //set close button
    el.find('.close, .btnClose').unbind('click').bind('click', function(e){
        e.preventDefault();
        jHideLayer(id);
    });	
    //set position
    jCenter(id);	
}
/**
 * show message layer
 * @param {string} id	  
 */
function jShowConfirm(id, options){
    var el = jQuery(id);
    if(el.length == 0 ) return;	
	
    if(window.openedLayer1 == null){
        window.openedLayer1 = id;
        jShowMask(true,options.background);
    }else{
        window.openedLayer2 = id;
    }	
	
    jQuery(el).css({
        'position': 'absolute',
        'zIndex': 9999,
        'display': 'block'
    });
    //set close button
    el.find('.btnNo').unbind('click').bind('click', function(e){
        e.preventDefault();
        jHideLayer(id);		
    });	
	
    if(typeof(options)!='undefined'){
        if(typeof(options.message) == 'string'){
            el.find('.message').text(options.message);
        }
        if(typeof(options.header) == 'string'){
            el.find('.header').text(options.header);
        }
        if(typeof(options.onYes) == 'function'){
            //add btnYes
            el.find('.btnYes').unbind().bind('click', function(e){
                e.preventDefault();			
                //call back button Yes
                options.onYes();
                jHideLayer(id);
            });			
        }
        if(typeof(options.onNo) == 'function'){
            el.find('.btnNo').unbind().bind('click', function(e){
                e.preventDefault();
                options.onNo();
                jHideLayer(id);
            });
        }
    } 
    //set position
    jCenter(id);
	
}

/**
 * show Hide Popup Layer
 * @param {string} id	  
 */
function jCenter(id){
    var el = jQuery(id);
    //set position
    var dimensions = windowDimensions();	
    var _top = (dimensions.height - jQuery(el).innerHeight()) / 2 + jQuery(window).scrollTop();
    var _left = (dimensions.width - jQuery(el).innerWidth()) / 2;
    if(_top < 10) _top = 10;
    jQuery(el).css({			
        'left': _left,
        'top': _top
    });		
    jQuery(window).unbind('resize').bind('resize', function(){
        dimensions = windowDimensions();
        _top = (dimensions.height - jQuery(el).innerHeight()) / 2 + jQuery(window).scrollTop();
        _left = (dimensions.width - jQuery(el).innerWidth()) / 2;	
        jQuery(el).css({			
            'left': _left,
            'top': _top
        });				
    });
}
/**
 * show Hide Popup Layer
 * @param {string} id	  
 */
function jHideLayer(id){    
    if(window.openedLayer1!=null && window.openedLayer2==null){
        jShowMask(false);
        window.openedLayer1 = null;
    }else{
        window.openedLayer2 = null;
    }	
    jQuery(id).css({
        'left': -15000
    });
    if(jQuery(id).find('form').length > 0){
        jQuery(id).find('form')[0].reset();
    }
    if(jQuery('.aucomplete').length > 0){
        jQuery('.aucomplete').css('top', -15000);
    }
    jQuery(window).unbind();
    jQuery(document).unbind();
}
/**
 * show/Hide maskLayer
 * @param {Boolean} flag 
 */
function jShowMask(flag,background){		
    if(jQuery('#maskLayer').length == 0) {
        jQuery(document.body).append('<div id="maskLayer"></div>');
        window.jMask = jQuery('#maskLayer').hide();
    }
    if(flag) {
        if(background == true || typeof(background) == 'undefined')
        {
            window.jMask.show().css({
                'position': 'fixed',
                'visibility': 'visible',
                //'backgroundColor': '#0b819d',
                'zIndex': 9000,
                'opacity': 0.5,
                'width': '100%',
                'height': '100%',
                'top': '0',
                'left': '0'
            }); 
        }  
        else
        {
            window.jMask.show().css({
                'position': 'fixed',
                'visibility': 'visible',
                
                'zIndex': 9000,
                'opacity': 0.5,
                'width': '100%',
                'height': '100%',
                'top': '0',
                'left': '0'
            });
        }
        
    } else {
        window.jMask.hide();
    }
}

function windowDimensions() {
    var dimensions = {
        width: 0, 
        height: 0
    };
    dimensions.width = jQuery(window).width();
    dimensions.height = jQuery(window).height();	
    return dimensions;
};

function passParamToString(str, params){
    var arr = str.split(/\{\d{1,}\}/);
    var newstr = '';
    for(item in arr){
        if(item < arr.length-1){
            newstr += arr[item] + params[item];
        }
    }
    newstr += arr[arr.length-1];
}

// Checks a string to see if it in a valid date format
// of (D)D/(M)M/(YY)YY and returns true/false
function isValidDate(s) {
    // format D(D)/M(M)/(YY)YY
    var dateFormat = /^\d{1,4}[\.|\/|-]\d{1,2}[\.|\/|-]\d{1,4}jQuery/;

    if (dateFormat.test(s)) {
        // remove any leading zeros from date values
        s = s.replace(/0*(\d*)/gi,"jQuery1");
        var dateArray = s.split(/[\.|\/|-]/);
      
        // correct month value
        dateArray[1] = dateArray[1]-1;

        // correct year value
        if (dateArray[2].length<4) {
            // correct year value
            dateArray[2] = (parseInt(dateArray[2]) < 50) ? 2000 + parseInt(dateArray[2]) : 1900 + parseInt(dateArray[2]);
        }

        var testDate = new Date(dateArray[2], dateArray[1], dateArray[0]);
        if (testDate.getDate()!=dateArray[0] || testDate.getMonth()!=dateArray[1] || testDate.getFullYear()!=dateArray[2]) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function LoadingShow()
{
    jShowConfirm('#LoadingLayer',{
        background:false
    });
}
function LoadingHide()
{
    jHideLayer("#LoadingLayer");
}

function loadingContent(id)
{
    jQuery(id).html('<div style="text-align: center;"><img style="" src="'+URL_THEMES+'img/loading.gif" /></div>');
}

function checkLogined(data)
{
    if(typeof(data) == 'object')
    {
        if(data.logined == 0)
        {
            window.location = URL_BASE+'login/index';
            return true;
        }    
    }   
    return false;
}
function loadingBodyContentRight(url,options)
{
    if(typeof(options) == 'undefined') options = {};
    var data = '';
    if(typeof(options.data) != 'undefined')
    {
        data = options.data;
    }
    LoadingShow();
    jQuery.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function(resp){
            if(checkLogined(resp)) return false;
            LoadingHide();
            jQuery("#content").html(resp);
            if(typeof(options.onSuccess) == 'function')
            {
                options.onSuccess();
            }   
        }
    });
}

function loadingBodyContentLeft(url,options)
{
    if(typeof(options) == 'undefined') options = {};
    LoadingShow();
    var data = '';
    if(typeof(options.data) != 'undefined')
    {
        data = options.data;
    }    
    jQuery.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function(resp){
            if(checkLogined(resp)) return false;
            jQuery("#leftmenu").html(resp);
            LoadingHide();
            if(typeof(options.onSuccess) == 'function')
            {
                options.onSuccess();
            }
        }
    });
}

function loadingBodyContent(url,options)
{
    if(typeof(options) == 'undefined') options = {};
    LoadingShow();
    
    jQuery.ajax({
        url: url,
        data: '',
        type: 'POST',
        success: function(resp){
            if(checkLogined(resp)) return false;
            LoadingHide();
            jQuery("#contentPage").html(resp);
            if(typeof(options.onSuccess) == 'function')
            {
                options.onSuccess();
            }
        }
    });
}
function getCheckboxList(id)
{
    var data = [];
    var i = 0;
    jQuery(id).each(function(){
        if(jQuery(this).is(":checked"))
        {
            data[i] = jQuery(this).attr("itemid"); 
            i++;
        }
             
    });
    return data;
}

function doAjax(url,options)
{
    if(typeof(options) == 'undefined') options = {};
    var data = '';
    if(typeof(options.data) != 'undefined')
    {
        data = options.data;
    } 
    jQuery.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function(resp){
            if(checkLogined(resp)) return false;
            if(typeof(options.onSuccess) == 'function')
            {
                options.onSuccess(resp);
            }
        }
    });
}

function buildClickCheckSelectOne(id)
{
    jQuery(id).unbind("click");
    jQuery(id).click(function() {
       jQuery(id).each(function(){
          jQuery(this).removeAttr("checked") ;
       }); 
       jQuery(this).attr("checked",true);
    });
}


function sendemail(id,url)
{
    doAjax(url,{onSuccess:function(resp){
         jQuery(id).html(resp);
         jShowLayer(id);   
         var strId = 'frmemail';
        var elements = [{
            field: 'emailsubject',
            valid: 'require',
            messages: "Please select Subject."
        },
        {
            field: 'emailbody',
            valid: 'require',
            messages: "Please select Message."
        },
        {
            field: 'toemail',
            valid: 'require',
            messages: "To Email is not empty."
        },
        {
            field: 'formemail',
            valid: 'require',
            messages: "Form Email is not empty."
        }];
        jValidateForm(strId, elements,{
            errorConfig:{
                type: 'showhide',
                customError: '.validate-error'
            },
            onSubmit: function(){
                var frm = jQuery('#' + strId);
                var data = frm.serializeArray();
                jQuery.ajax({
                    url: frm.attr('action'),
                    data: data,
                    type: 'POST',
                    success: function(resp){
                        if(resp.success)
                        {
                            jQuery(".btnClose").click();
                        }    
                        else
                        {
                            frm[0].showHideError(null, resp.error);
                        }    
                        frm[0]._submitted = false;
                    }
                });
            }
        });
    }});
}
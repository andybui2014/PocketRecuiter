/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function getCourceByDept()
{
    var depid = jQuery(this).val();
    if(depid == "") return false;
    var url = URL_BASE + "service/get-courses-option?ajax=1";
    doAjax(url,{
        onSuccess : function(data)
        {
            jQuery("#content #course").html(data);
            jQuery("#content #teacher").html("<option value=''>Select</option>");
            jQuery("#content #section").html("<option value=''>Select</option>");
        },
        data:"dept_id="+depid
    });
}
function getTeacherByCour()
{
    var cour_id = jQuery(this).val();
    if(cour_id == "") return false;
    var url = URL_BASE + "service/get-teachers-option?ajax=1";
    doAjax(url,{
        onSuccess : function(data)
        {
            jQuery("#content #teacher").html(data);  
            jQuery("#content #section").html("<option value=''>Select</option>");
        },
        data:"cour_id="+cour_id
    });
}
function getSectionByTeacher()
{
    var teacher_id = jQuery(this).val();
    if(teacher_id == "") return false;
    var url = URL_BASE + "service/get-sections-option?ajax=1";
    doAjax(url,{
        onSuccess : function(data)
        {
            jQuery("#content #section").html(data);
        },
        data:"teacher_id="+teacher_id
    });
}




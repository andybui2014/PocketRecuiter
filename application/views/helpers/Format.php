<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Zend_View_Helper_Format {

    function format($value,$type,$option = "") {
        return $this->$type($value,$option);
    }
    
    function phone($phone,$option) {
        $pos = strpos($phone, '-');
        if ($pos === false && strlen($phone) > 6) {
            $formatphone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
            return $formatphone;
        } else {
            return $phone;
        }
    }
    function datetime($date,$option = "m/d/Y")
    {
           if(empty($date)) return "";
           if(empty($option)) $option = "m/d/Y";
           return date_format(new Datetime($date),$option);
    }
}

?>

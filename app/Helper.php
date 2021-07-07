<?php

if (!function_exists('classActivePath')) {
    function classActivePath($segment, $value)
    {
        if(!is_array($value)) {
            return Request::segment($segment) == $value ? ' menu-open' : '';
        }
        foreach ($value as $v) {
            if(Request::segment($segment) == $v) return ' menu-open';
        }
        return '';
    }
}

if (!function_exists('classActiveSegment')) {
    function classActiveSegment($segment, $value)
    {
        if(!is_array($value)) {
            return Request::segment($segment) == $value ? 'active' : '';
        }
        foreach ($value as $v) {
            if(Request::segment($segment) == $v) return 'active';
        }
        return '';
    }
}

if (!function_exists('classActiveCustomMenu')) {
    function classActiveCustomMenu($menu_name, $index)
    {
        if( isset(explode( '/',Request::path())[$index]) && explode( '/',Request::path())[$index]==strtolower($menu_name)) return 'active';
        return '';
    }
}

if (!function_exists('classActiveURLPath')) {
    function classActiveURLPath($url)
    {
        if(Request::path() == $url){
            return 'active';
        } 
        return '';
    }
}

if (!function_exists('createTransactionNumber')) {
    function createTransactionNumber($code, $id){
        return strtoupper($code) . str_pad($id, 12, "0", STR_PAD_LEFT);
    }
}

if (!function_exists('p')) {
    function p($arr = array()){
        print "<pre>";
        print_r($arr);
        print "</pre>";
    }
}

if (!function_exists('pe')) {
    function pe($arr = array()){
        p($arr);
        exit;
    }
}


function get_week_start_end($week, $year) {

    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['end'] = $dto->format('Y-m-d');


    return $ret;

  }
  

  function p($arr = array()){
      print "<pre>";
      print_r($arr);
      print "</pre>";
  }

  function pe($arr = array()){
    print "<pre>";
    print_r($arr);
    print "</pre>";
    exit();
  }
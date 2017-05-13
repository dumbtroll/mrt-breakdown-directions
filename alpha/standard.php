<?php

// Filters unwanted information, classifies tweets into MRT lines and gets delay time for all MRT lines

$complete_table = array();

include 'data.php';

include 'entities.php';

$nslarray = array();

$ewlarray = array();

$cclarray = array();

$nelarray = array();

$dtlarray = array();

// Filter by keywords

$data = array_filter($data, function ($var) { return (stripos($var, 'trial') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'stationary') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'extend') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, ' end ') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'operating hours') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'operating') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, ' operate') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'maintenance') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'feedback') === false); });

$data = array_filter($data, function ($var) { return (stripos($var, 'suggestion') === false); });

// Categorise into arrays by MRT/LRT line

foreach($data as $data) {
    
    $data = str_replace("twds", "towards", $data);
    
    $data = str_replace("minutes", "mins", $data);
    
    $data = str_replace("min", "mins", $data);
    
    if (strpos($data, "NSL") !== false) {
            
        array_push($nslarray, $data);
            
    } elseif (strpos($data, "EWL") !== false) {
        
        array_push($ewlarray, $data);
        
    } elseif (strpos($data, "CCL") !== false) {
        
        array_push($cclarray, $data);
            
    } elseif (strpos($data, 'NE') !== false) {
        
        array_push($nelarray, $data);
        
    } elseif (strpos($data, 'DT') !== false) {
        
        array_push($dtlarray, $data);
        
    }
}


// Get rid of cleared MRT breakdowns

/* below is for testing purpoess */

function cleared( &$array ) {
    
    foreach($array as $tweet) {
    
     if (stripos($tweet, "resumed") == true || stripos($tweet, "cleared") == true || stripos($tweet, "open") == true) {
    
        $array = array("");
        
    } else {
         
         $array = $array;
         
     }
    
    }
}

cleared( $nslarray );

$nsl = implode("", $nslarray);

cleared( $ewlarray );

$ewl = implode("", $ewlarray);

cleared( $cclarray );

$ccl = implode("", $cclarray);

cleared( $nelarray );

$nel = implode("", $nelarray);

cleared( $dtlarray );

$dtl = implode("", $dtlarray);

/* behaviour below may change if SMRT changes phrasing from No train service to No service */

// Get delay time

function getDelayTime( &$line , &$linedelaytime , &$line_security_incident ) {
    
    $notrainservice = 'no train service';

if (stripos($line, $notrainservice) == true || stripos($line, 'no train svc') == true || stripos($line, 'svc is') == true) {
    
    $linedelaytime = $notrainservice;    
    
} elseif (stripos($line, 'mins ')) {

    $linedelaytime = substr($line, stripos($line,"mins ") - 2 , 2);
    
} elseif (stripos($line, ' mins ')){
    
    $linedelaytime = substr($line, stripos($line," mins ") - 2 , 2);
    
}  
    
    if (stripos($line, "security") == true || stripos($line, "terror") == true) {
    
    $line_security_incident = true;
        
   
    } else {
        
        $line_security_incident = false;
        
    }
    
}

getDelayTime( $nsl , $nsldelaytime , $nsl_security_incident );
getDelayTime( $ewl , $ewldelaytime , $ewl_security_incident );
getDelayTime( $ccl , $ccldelaytime , $ccl_security_incident );
getDelayTime( $nel , $neldelaytime , $nel_security_incident );
getDelayTime( $dtl , $dtldelaytime , $dtl_security_incident );

$nsl_affected_stations = array();

$ewl_affected_stations = array();

$ccl_affected_stations = array();

$nel_affected_stations = array();

$dtl_affected_stations = array();

$complete_table = array();


?>
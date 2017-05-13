<?php

include 'standard.php';

// Get affected lines

$nsldirection = '';
$ewldirection = '';
$ccldirection = '';

function getAffectedLines( &$line , &$linemap , &$line_affected_stations , &$linedelaytime , &$line_security_incident , &$linedirection ) {
    
    $linedirection = array();
    
    
foreach($linemap as $linemp) {

    
    $plsreplace = "fault at ".$linemp;

    $plsreplacetoo = "fault at #".$linemp;
    
    /* the 2 lines below are not working. ignore due to <=2 compensation */
        
    $line = str_replace($plsreplace,'',$line);
        
    $line = str_replace($plsreplacetoo,'',$line);
    



$test = "towards ".$linemp;
    
$anothertest = "towards #".$linemp;
        
if (stripos($line, $test) == true || stripos($line, $anothertest) == true) {
    
    
    if (empty($linedirection) !== false) {
        
        
        array_push($linedirection, $linemp);
        
        
        
    }
    
    $line = str_replace($test," ",$line);
    
    $line = str_replace($anothertest," ",$line);
    

    
}


        
if (count($line_affected_stations) <= 2) {
    
    if (stripos($line, $linemp) == true /*&& $line_security_incident == false*/) {
        
        
        /* currently no support for security incidents on SMRT lines because security incidents have never even happened on them before */
        
        
        array_push($line_affected_stations, $linemp);
    
        
    } 
    }
    
if (stripos($line, 'bypass') == true || stripos($line, 'skip') == true) {

    
    $newtest = 'bypass '.$linemp;



    if (stripos($line, $newtest) == true) {
        
        
        $line_affected_stations = array($linemp);
        
        $line_affected_stations = $line_affected_stations[0];
        
    }

    
}
    
}
    

    $notrainservice = 'no train service';
 


if (empty($line_affected_stations)) {

    
    if ($linedelaytime == $notrainservice) {
        
        $line_affected_stations = $linemap;
        
    } else {
        
        $linedelaytime = null;
        
    }


    } 

    $linedirection = implode("", $linedirection);

    
}
    
    
    

getAffectedLines( $nsl , $nslmap , $nsl_affected_stations , $nsldelaytime , $nsl_security_incident , $nsldirection );
getAffectedLines( $ewl , $ewlmap , $ewl_affected_stations , $ewldelaytime , $ewl_security_incident , $ewldirection );
getAffectedLines( $ccl , $cclmap , $ccl_affected_stations , $ccldelaytime , $ccl_security_incident , $ccldirection );
getAffectedLines( $nel , $nelunreadable , $nel_affected_stations , $neldelaytime , $nel_security_incident , $neldirection );
getAffectedLines( $dtl , $dtlunreadable , $dtl_affected_stations , $dtldelaytime , $dtl_security_incident , $dtldirection );


$nsl_affected_stations_position = array();
$ewl_affected_stations_position = array();
$ccl_affected_stations_position = array();
$nel_affected_stations_position = array();
$dtl_affected_stations_position = array();





// include 'entities.php';

function generateArray( &$line_affected_stations , $line_affected_stations_position , &$linemap , &$readable , &$linedirection ) {

    
    if (empty($linedirection) != true) {
        
        $linedirection = $readable[array_search($linedirection, $linemap)];
        
    }
    
    if (count($line_affected_stations) > 1) {
    
    $line_affected_stations_position = array(array_search($line_affected_stations[0], $linemap), array_search(end($line_affected_stations), $linemap));
        
    $line_affected_stations = array_slice($readable, $line_affected_stations_position[0], $line_affected_stations_position[1] - $line_affected_stations_position[0] + 1, false);
    

    } elseif (count($line_affected_stations) == 1) {
        
        /* have not tested yet */
        
        $line_affected_stations_position = array_search($line_affected_stations, $linemap);
        $line_affected_stations = $readable[$line_affected_stations_position];
    
        
    }

}


generateArray( $nsl_affected_stations , $nsl_affected_stations_position , $nslmap , $nslmap , $nsldirection );
generateArray( $ewl_affected_stations , $ewl_affected_stations_position , $ewlmap , $ewlmap , $ewldirection );
generateArray( $ccl_affected_stations , $ccl_affected_stations_position , $cclmap , $cclmap , $ccldirection );
generateArray( $nel_affected_stations , $nel_affected_stations_position , $nelunreadable , $nelmap , $neldirection );
generateArray( $dtl_affected_stations , $dtl_affected_stations_position , $dtlunreadable , $dtlmap, $dtldirection );




// Put into three-dimensional array

$completetable['NSL'] = array('delay_time_in_minutes'=>$nsldelaytime, 'affected_stations'=>$nsl_affected_stations, 'direction'=>$nsldirection, 'security_incident'=>$nsl_security_incident);
$completetable['EWL'] = array('delay_time_in_minutes'=>$ewldelaytime, 'affected_stations'=>$ewl_affected_stations, 'direction'=>$ewldirection, 'security_incident'=>$ewl_security_incident);
$completetable['CCL'] = array('delay_time_in_minutes'=>$ccldelaytime, 'affected_stations'=>$ccl_affected_stations, 'direction'=>$ccldirection, 'security_incident'=>$ccl_security_incident);
$completetable['NEL'] = array('delay_time_in_minutes'=>$neldelaytime, 'affected_stations'=>$nel_affected_stations, 'direction'=>$neldirection, 'security_incident'=>$nel_security_incident);
$completetable['DTL'] = array('delay_time_in_minutes'=>$dtldelaytime, 'affected_stations'=>$dtl_affected_stations, 'direction'=>$dtldirection, 'security_incident'=>$dtl_security_incident);

?>

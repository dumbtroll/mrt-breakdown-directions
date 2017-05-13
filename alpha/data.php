<?php

include 'authorisation.php';

// Get SMRT Timeline
$smrtstatus = $connection->get("statuses/user_timeline", ["user_id" => 307781209, "count" => 50, "exclude_replies" => true, "trim_user" => true]);

$sbsstatus = $connection->get("statuses/user_timeline", ["user_id" => 3087502272, "count" => 150, "exclude_replies" => true, "trim_user" => true]);

$fullarray = array_merge(json_decode(json_encode($smrtstatus), true), json_decode(json_encode($sbsstatus), true));

date_default_timezone_set('UTC');

$currentdate = date("D M d");

/* $currentdate = 'Thu Apr 20'; */


// Date filtering

$new = array();

foreach ($fullarray as $i => $row) {

    $row['created_at'] = substr($row['created_at'], 0, 10);

    if ($row['created_at'] != $currentdate) {

        unset($new[$i]);

    } else {

        array_push($new, $row['text'] . " " . $row['created_at']);

    }

}


    $data = implode("", $new);

    $data = explode($currentdate, $data);

    $data = array_filter($data, function($value) { return $value !== ''; });


?>

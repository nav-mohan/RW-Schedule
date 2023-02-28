<?php

define('CUSTOM_POST_TYPE__RWSHOW', 'rwshow');
define('CUSTOM_FIELD_KEY__SHOWHOST', '__showhost');
define('CUSTOM_FIELD_LABEL__SHOWHOST', 'Show Hosts');
define('CUSTOM_FIELD_KEY__COHOST1', '__cohost1');
define('CUSTOM_FIELD_LABEL__COHOST1', 'Co-Host 1');
define('CUSTOM_FIELD_KEY__COHOST2', '__cohost2');
define('CUSTOM_FIELD_LABEL__COHOST2', 'Co-Host 2');
define('CUSTOM_FIELD_KEY__SHOWCATEGORY', '__showcategory');
define('CUSTOM_FIELD_LABEL__SHOWCATEGORY', 'Show Category');
define('CUSTOM_FIELD_KEY__DAYOFWEEK', '__dayofweek');
define('CUSTOM_FIELD_LABEL__DAYOFWEEK', 'Day of week');
define('CUSTOM_FIELD_KEY__STARTTIME', '__starttime');
define('CUSTOM_FIELD_LABEL__STARTTIME', 'Start Time');
define('CUSTOM_FIELD_KEY__ENDTIME', '__endtime');
define('CUSTOM_FIELD_LABEL__ENDTIME', 'End Time');

define('CUSTOMFORM_NONCE_ACTION', 'cptwrcf_nonce_action');
define('CUSTOMFORM_NONCE_NAME', 'cptwrcfn_nonce_name');

define('INVALID_CUSTOMFIELD_KEY', "invalid_customfield_key");
// define('CPTWRCF_NOT_UNIQUE_QUERY_STRING_KEY', 'cptwrcfn_not_unique');

// define('CPTWRCF_SQL', "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s AND post_id <> %d");

$ALL_SHOW_HOSTS = array_map(function($user_object){
    return array('user_id'=>$user_object->ID,'user_login'=>$user_object->user_login);
},get_users(array('role__in' => array('show_host'))));


$ALL_SHOW_CATEGORIES = array(
    "1"=>"Prerec Music Show",
    "2"=>"Prerec Talk Show",
    "3"=>"Live Music Show",
    "4"=>"Live Talk Show",
    "5"=>"News",
    "6"=>"Sports",
    "7"=>"Speciality",
    "8"=>"Playlist"
);
$ALL_DAYS_OF_WEEK = array(
    '1'=>'Sunday',
    '2'=>'Monday',
    '3'=>'Tuesday',
    '4'=>'Wednesday',
    '5'=>'Thursday',
    '6'=>'Friday',
    '7'=>'Saturday',
);

$ALL_TIMES_OF_DAY = array();
for ($i = 0; $i <= 23; $i++) {
    $istr = str_pad($i, 2, '0', STR_PAD_LEFT);
    array_push($ALL_TIMES_OF_DAY,"$istr:00:00","$istr:30:00");
}


;?>
<?php
/**
 * Created by PhpStorm.
 * User: JChang
 * Date: 9/25/2014
 * Time: 12:41 AM
 */


$base = 'https://ws.admin.washington.edu/student/v5/';

//curricula query
$query = 'curriculum.json?year=2014&quarter=autumn&sort_by=on';

if(isset($_GET['curr'])){
    //search for all sections in a curriclum and add delete flags
    $query = urlencode('section.json?year=2014&quarter=autumn&curriculum_abbreviation='.$_GET['curr']);
}

if(isset($_GET['sect'])){
    //search for specific section info

}

$curl = curl_init($base.$query);

//curl_exec prints results by default; this disables it
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer e6581efe-8b1c-4552-b7e3-10d264f6fd21',
    'Accept: text/plain'
));

$res = json_decode(curl_exec($curl));



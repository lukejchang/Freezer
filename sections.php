<?php
/**
 * Created by PhpStorm.
 * User: JChang
 * Date: 10/6/2014
 * Time: 11:42 PM
 */

if(isset($_POST["data"])){
    function pg_connection_string_from_database_url() {
        extract(parse_url($_ENV["DATABASE_URL"]));
        return "user=$user password=$pass host=$host dbname=" . substr($path, 1); # <- you may want to add sslmode=require there too
    }
    $con = pg_connect(pg_connection_string_from_database_url());
    $sections = (json_decode($_POST["data"]));

    $results = array();
    foreach($sections as $sect){
        //search for specific section info: sect should look like 'E%20E,142/A'
        $query = 'https://ws.admin.washington.edu/student/v5/course/2014,autumn,' . $sect . '.json';
        $curl = curl_init($query);

        //curl_exec prints results by default; this disables it
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer e6581efe-8b1c-4552-b7e3-10d264f6fd21',
            'Accept: text/plain'
        ));
        $secData = json_decode(curl_exec($curl));
        $meetings = (array) $secData->Meetings;
        $sln = (int) $secData->SLN;
        $count = 1;
        foreach($meetings as $meet){
            //print_r($meet);
            $days = $meet->DaysOfWeek;
            $days = $days->Days;
            //print_r($days);

            $course = explode(",", $sect);
            $cur = str_replace("%20", " ", $course[0]); //"E E"

            $class = $course[1];//class, looks like "142/A"
            $classArr = explode("/", $class);
            $num = $classArr[0];//"142"
            $secId = $classArr[1];//"A"
            $booldays = array(false, false, false, false, false);
            foreach($days as $day){
                switch($day->Name){
                    case "Monday":
                        $booldays[0] = true;
                        break;
                    case "Tuesday":
                        $booldays[1] = true;
                        break;
                    case "Wednesday":
                        $booldays[2] = true;
                        break;
                    case "Thursday":
                        $booldays[3] = true;
                        break;
                    case "Friday":
                        $booldays[4] = true;
                        break;
                }
            }
            //array_push($results, array($sln, $cur, $num, $secId, $booldays, $meet->StartTime, $meet->EndTime, $count));
            $start = explode(":", $meet->StartTime); //["11", "30"]
            $startTime = intval($start[0]) * 60 + intval($start[1]); //minutes past midnight
            $end = explode(":", $meet->EndTime); //["11", "30"]
            $endTime = intval($end[0]) * 60 + intval($end[1]); //minutes past midnight
            $sql = "INSERT INTO sections VALUES('$sln', '$cur', '$num', '$secId', '$booldays[0]','$booldays[1]','$booldays[2]','$booldays[3]','$booldays[4]', '$startTime', '$endTime', '$count')";
            pg_query($con, $sql);
            $count++;
        }
    }


}else{
    $con=mysqli_connect("jlchang.ovid.u.washington.edu","root","password","sections");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    $query = 'https://ws.admin.washington.edu/student/v5/course/2014,autumn,A%20A,210/A.json';

    $curl = curl_init($query);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer e6581efe-8b1c-4552-b7e3-10d264f6fd21',
        'Accept: text/plain'
    ));

//curl_exec prints results by default; this disables it
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    $results = json_decode(curl_exec($curl));
    $meetings = $results->Meetings;

//print_r($meetings);

    $add = array();
    $sln = (int) $results->SLN;
    $count = 1;

    foreach($meetings as $meet){
        //print_r($meet);
        $days = $meet->DaysOfWeek;
        $days = $days->Days;
        $booldays = array(false, false, false, false, false);
        foreach($days as $day){
            switch($day->Name){
                case "Monday":
                    $booldays[0] = true;
                    break;
                case "Tuesday":
                    $booldays[1] = true;
                    break;
                case "Wednesday":
                    $booldays[2] = true;
                    break;
                case "Thursday":
                    $booldays[3] = true;
                    break;
                case "Friday":
                    $booldays[4] = true;
                    break;
            }
        }

        $start = explode(":", $meet->StartTime); //["11", "30"]
        $startTime = intval($start[0]) * 60 + intval($start[1]); //minutes past midnight
        $end = explode(":", $meet->EndTime); //["11", "30"]
        $endTime = intval($end[0]) * 60 + intval($end[1]); //minutes past midnight
        $sql = "INSERT INTO sections VALUES('$sln', '$cur', '$num', '$secId', '$booldays[0]','$booldays[1]','$booldays[2]','$booldays[3]','$booldays[4]', '$startTime', '$endTime', '$count')";
        mysqli_query($con, $sql);
        $count++;
    }

    echo json_encode($add);
}
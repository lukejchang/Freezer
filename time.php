<?php
/**
 * User: JChang
 * Query the SQL database for the meeting time of a class, given the SLN or the course name/number/section
 */

if($_GET["cur"]) {
    $sql = "SELECT * FROM `Sections` WHERE `Curriculum` = '" . $_GET["cur"] . "' and `Course` = " . $_GET["num"] . " and `Section` = '" . $_GET["sec"] . "'";
    if (strlen($_GET["sec"]) > 1){
        //get lecture times too
        $sql = $sql . " or `Curriculum` = '" . $_GET["cur"] . "' and `Course` = " . $_GET["num"] . " and `Section` = '" . substr($_GET["sec"], 0, 1) . "'";
    }
    $con = new MySQLi('vergil.u.washington.edu', 'root', 'patty', 'Freezer', 40000);
    if($con->connect_error){
        echo "Not connected, error: ".$con->connect_error;
        exit();
    }
    $result = mysqli_query($con, $sql);
    if($result){
        print_r(mysqli_fetch_array($result));
    }else{
        print $sql;
    }

}
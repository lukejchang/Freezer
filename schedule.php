<?php
/**
 * Jin-li Chang
 * PHP file for filling out schedule based on classes
 */

if(post)

// creates 5x84 2d array of 0s
$week = array(); // array of columns
for($c=0; $c<5; $c++){ // 5 days of the week
    $week[$c] = array(); // array of cells for column $c
    for($r=0; $r<84; $r++){ //10 minute blocks from 8am to 10pm
        $week[$c][$r] = 0;
    }
}

if($_POST){
    print_r($_POST);
}
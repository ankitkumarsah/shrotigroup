<?php

//insert the calling result into intimation table

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
error_reporting(0);

// creating arrey for json response
$response=array();

//check if we got the field from the user
if(isset($_GET['mdn']) && isset($_GET['proc']) ) {
  // proc =1 - to start sms, 2-to stop , 0- 1st call after reset  
    // out ([2]) - quota completed, ([1]) start sms, ([0])  wait
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if($_GET['proc']==0){
        $sql = "SELECT * FROM noc_status WHERE mob_no='".$_GET['mdn']."'";
        $result = mysqli_query($conn, $sql);
        $rowcount=mysqli_num_rows($result);
        if($rowcount>0) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if ($row["sms_status"]==2) $text_out="([2])";
            else if ($row["sms_status"]==1) $text_out="([1])"; else $text_out="([0])";
        } else {
            $sql = "INSERT INTO noc_status (mob_no, call_status, sms_status) 
            VALUES ('".$_GET['mdn']."',0,0)";
            $result = mysqli_query($conn, $sql);
            $text_out="([0])";
        }
    }else if ($_GET['proc']==1){
        $sql = "SELECT * FROM noc_status WHERE sms_status=1";
        $result = mysqli_query($conn, $sql);
        $rowcount=mysqli_num_rows($result);
        if($rowcount>0) $text_out="([0])";
        else{
            $sql = "UPDATE noc_status SET sms_status=1, sms_start=CURRENT_TIMESTAMP() 
                    WHERE mob_no='".$_GET['mdn']."'";
            $result = mysqli_query($conn, $sql);
            $text_out="([1])";
        }
    }else if ($_GET['proc']==2){
        $sql = "UPDATE noc_status SET sms_status=2, sms_end=CURRENT_TIMESTAMP()
                    WHERE mob_no='".$_GET['mdn']."'";
        $result = mysqli_query($conn, $sql);
        $text_out="([2])";
    }
    
    echo $text_out;
    
    $conn->close();
    ob_flush();
    flush();
    
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
    
    // show JSON response
    echo json_encode($response);
}

?>
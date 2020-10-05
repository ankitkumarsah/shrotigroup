<?php

//insert the SMS received from TPMS into event table
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
if(isset ($_GET['imei']) &&($_GET['mdn']) && isset($_GET['sms']) && isset($_GET['req'])) {
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
   
    
    $sql="SELECT site_id FROM escalation_master WHERE imei='".$_GET['imei']."'";
    //echo $sql;
    
    $result=mysqli_query($conn, $sql);
    $rowcount=mysqli_num_rows($result);
    
    if ($rowcount>0) {
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $site_id=$row["site_id"];
    } else $site_id="";
        
    
    $sql = "INSERT INTO sms_response (imei, to_number, site_id, request, msg,status) 
           VALUES ('".$_GET['imei']."','".$_GET['mdn']."','".$site_id."','".$_GET['req']."','"
           .$_GET['sms']."',1)";
 //          echo $sql;
    mysqli_query($conn, $sql);
     
    
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
<?php

//insert the event data received through gprs
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
//error_reporting(0);


// creating arrey for json response
$response=array();
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "INSERT INTO noc_status_history (mob_no, sms_status, sms_count, sms_start, sms_end)
            SELECT mob_no, sms_status, sms_count, sms_start, sms_end FROM noc_status  WHERE 1";
     
    $result=mysqli_query($conn, $sql);
    
    $sql = "UPDATE noc_status SET sms_status=1,sms_count=0,sms_start=NULL, sms_end=NULL, call_status=0, call_start=NULL, 
            call_end=NULL WHERE 1";
            
            
    
        if (mysqli_query($conn, $sql)) {
            // successfully inserted
            $response["success"] = 1;
            $response["message"] = "record successfully created.";
            
            // show JSON response
            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Something has been wrong";
            
            // show JSON response
            echo json_encode($response);
            echo "Error: " . $sql . "" . mysqli_error($conn);
        }
        
    
    
    $conn->close();
    

    ob_flush();
    flush();

?>
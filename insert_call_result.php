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
if(isset($_GET['intim_id']) && isset($_GET['result']) && isset($_GET['sms']) && isset($_GET['mdn'])) {
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "UPDATE noc_status SET call_status=0, call_end=CURRENT_TIMESTAMP()
            WHERE mob_no='".$_GET['mdn']."'";
    
    $result = mysqli_query($conn, $sql);

    $sql = "select event_id,site_id, imei,priority_id, sms_text
            from intimation where intim_id=".$_GET['intim_id'];
    
    $result = mysqli_query($conn, $sql);
    
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    
    $start_n1=strpos($_GET['result'], '([');
    $start_n2=strpos($_GET['result'],'][', $start_n1+2);
    $start_n3=strpos($_GET['result'],'][', $start_n2+2);
    $start_n4=strpos($_GET['result'],'][', $start_n3+2);
    $start_n5=strpos($_GET['result'],'][', $start_n4+2);
    $end=strpos($_GET['result'],'])');
    
    $n1=substr($_GET['result'],$start_n1+2,$start_n2-$start_n1-2);
    $n2=substr($_GET['result'],$start_n2+2,$start_n3-$start_n2-2);
    $n3=substr($_GET['result'],$start_n3+2,$start_n4-$start_n3-2);
    $n4=substr($_GET['result'],$start_n4+2,$start_n5-$start_n4-2);
    $n5=substr($_GET['result'],$start_n5+2,$end-$start_n5-2);
        
    $sql = "INSERT INTO call_result(intim_id,event_id,call_status1,call_status2,call_status3,
        call_status4,call_status5,called_from)VALUES ('".$_GET['intim_id']."','".$row["event_id"].
        "','".$n1."','".$n2."','".$n3."','".$n4."','".$n5."','".$_GET['mdn']."')";
    
    $result = mysqli_query($conn, $sql);
    
    $sms_text=$row["sms_text"]."-Ack by-".$_GET['sms'];
    
    $sql = "INSERT INTO intimation (event_id,site_id,imei,priority_id,
           sms_intim,call_intim,sms_text)
           VALUES ('".$row["event_id"]."','".$row["site_id"]."','".$row["imei"].
           "','".$row["priority_id"]."','1','0','".$sms_text."')";
  //  $result = mysqli_query($conn, $sql);
 
    
    
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
    
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
    
    // show JSON response
    echo json_encode($response);
}

?>
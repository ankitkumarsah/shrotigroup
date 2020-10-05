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
    
    $sql = "INSERT INTO intimation(event_id, site_id, imei, priority_id, sms_intim, 
            call_intim, sms_text) SELECT e.event_id, es.site_id, e.imei,p.priority_id,
            if(e.alarm_status,(p.s1 OR p.s2 OR p.s3 OR p.s4 OR p.s5), '0') as sms_intim,
            if(e.alarm_status,(p.c1 OR p.c2 OR p.c3 OR p.c4 OR p.c5),'0') as call_intim,
            if (e.alarm_status , a.on_desc , a.off_desc) as sms_text
            FROM event as e 
            left join escalation_master as es on e.imei=es.imei
            left join alarm as a on e.alarm_id=a.alarm_id
            left join priority as p on if(e.alarm_status, a.priority=p.priority_id , 
            p.priority_id = 7)  WHERE 1
            and es.site_id is not null
            and e.event_id > (select max(event_id) from intimation where 1)";
            
            
            
    
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
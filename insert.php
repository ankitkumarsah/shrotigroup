
<?php

//insert the event data received through gprs
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
//set_time_limit(0);
//error_reporting(0);

function get_col($aid)
{
    switch ($aid) {
        case 1: $status="DOOPN";break;
        case 2: $status="BBLBK";break;
        case 3: $status="BTLV1";break;
        case 4: $status="BTLV2";break;
        case 5: $status="BTLV3";break;
        case 6: $status="BTLV4";break;
        case 7: $status="EXTAL";break;
        case 8: $status="TPCOV";break;
        case 9: $status="VDCFL";break;
        case 10: $status="SBTLO";break;
        case 11: $status="HOTER";break;
        default: $status="NA";break;
    } return $status;
}

// creating arrey for json response
$response=array();
$source='gprs';

//check if we got the field from the user
if(isset($_GET['imei']) && isset($_GET['seq']) && isset($_GET['aid']) 
&& isset($_GET['status']) && isset($_GET['scnt'])) {
    
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT count(*) as total from event where imei=".$_GET['imei']." 
            and seq_number=".$_GET['seq']." and date=CURRENT_DATE()";

    $result = mysqli_query($conn, $sql);
    
    $aux = mysqli_fetch_array($result,MYSQLI_ASSOC);
    //echo $aux;
    
    if(($aux['total']<1)) {
        $col=get_col($_GET['aid']);
        if($col!="NA"){
            $sql2= "UPDATE site_status SET ".$col."='".$_GET['status']."'
                WHERE imei = '".$_GET['imei']."'";
            mysqli_query($conn, $sql2);
        }
        
        
        $sql = "INSERT INTO event(imei,seq_number,alarm_id,alarm_status,source,
        sms_count,date)
            VALUES ('".$_GET['imei']."','".$_GET['seq']."','".$_GET['aid']."',
            '".$_GET['status']."','".$source."','".$_GET['scnt']."',CURRENT_DATE())";
        
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
        
    }else {
        $response["success"] = 1;
        $response["message"] = "Record already Exit";
        
        // show JSON response
        echo json_encode($response);
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
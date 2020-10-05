

<?php

//insert the SMS received from TPMS into event table
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
error_reporting(0);

function get_col($aid1)
{
    switch ($aid1) {
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
$source='sms';

//check if we got the field from the user
if(isset ($_GET['source']) &&($_GET['mdn']) && isset($_GET['sms'])) {
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //echo $_GET['sms'];
    
    if(strpos($_GET['sms'],'):')){
        $start=strpos($_GET['sms'], '(');
        $end=strpos($_GET['sms'], ')');
        $site_id=substr($_GET['sms'],0,$start);
        $sms_no=substr($_GET['sms'],$start+1,$end-$start-1);
        $time=substr($_GET['sms'],strlen($_GET['sms'])-5,5);
        if(strpos($_GET['sms'], '([')){
            $start_seq=strpos($_GET['sms'], '([');
            $start_aid=strpos($_GET['sms'],'][', $start_seq+1);
            $start_status=strpos($_GET['sms'],'][', $start_aid+1);
            $event_end=strpos($_GET['sms'],'])',$start_status+1);
            $seq=substr($_GET['sms'],$start_seq+2,$start_aid-$start_seq-2);
            $aid=substr($_GET['sms'],$start_aid+2,$start_status-$start_aid-2);
            $status=substr($_GET['sms'],$start_status+2,$event_end-$start_status-2);
        } else $seq =0;
        $sms=substr($_GET['sms'],$end+2,strlen($_GET['sms'])-6-$end-2);
    } else {
        $sms_no=0;  
        $site_id='NA';
        $time='--';
        $sms=$_GET['sms'];
        $seq =0;
    }
    
    $sql = "INSERT INTO sms_text(source,site_number,site_id, sms_no,seq_number,sms_time,sms_text)
            VALUES ('".$_GET['source']."','".$_GET['mdn']."','".$site_id."','".$sms_no."','".$seq."','".$time."','".$sms."')";
    
    mysqli_query($conn, $sql);
    
    if($seq){
        
        if($seq=="IMEI"){
            $sql = "INSERT INTO `mdn_master`(imei, mdn_loc,mdn,name,status)
               VALUES ('".$aid."','5','".$_GET['mdn']."','SITE_ID','1')";
            mysqli_query($conn, $sql);
        }
        else {   
        
            $sql2="SELECT imei FROM escalation_master WHERE site_number=".$_GET['mdn'];
            $result2 = mysqli_query($conn, $sql2);
            $aux2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
            
            
            $sql = "SELECT count(*) as total from event where imei=".$aux2['imei']."
                and seq_number=".$seq." and date=CURRENT_DATE()";
            
            $result = mysqli_query($conn, $sql);
            
            $aux = mysqli_fetch_array($result,MYSQLI_ASSOC);
            //echo $aux;
            
            if(($aux['total']<1)) {
                $col=get_col($aid);
                if($col!="NA"){
                    $sql3= "UPDATE site_status SET ".$col."='".$status."'
                WHERE imei = '".$aux2['imei']."'";
                    mysqli_query($conn, $sql3);
                }
                $sql = "INSERT INTO event(imei,seq_number,alarm_id,alarm_status,source,
                sms_count,date)
                VALUES ('".$aux2['imei']."','".$seq."','".$aid."',
                '".$status."','".$source."','".$sms_no."',CURRENT_DATE())";
                
                mysqli_query($conn, $sql);

            }
        }
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
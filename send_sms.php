<?php
//read the details from intimation table to which intimation SMS to be sent

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
error_reporting(0);

date_default_timezone_set("Asia/Kolkata");

// Account details
//$apiKey = urlencode('xr89z1XMiog-CXtHbGhOCPkm263jVyzAysGnUtkm3n');
//$apiKey = "xr89z1XMiog-CXtHbGhOCPkm263jVyzAysGnUtkm3n";
$filepath = realpath(dirname(__FILE__));
require_once ($filepath."/dbconfig.php");
$affectedRow=0;
//$numbers = '0';
$mdn="SMS_SERVER";
$sms_count=0;


function  send_sms($num,$msg) {
    // Account details
    $apiKey = urlencode('17064AfMSZB6dNk5f5325d1P15');
    // Message details
    $sender = urlencode('TPMSAL');
    $message = rawurlencode($msg);
    // $numbers = implode(',', $numbers);
    
    // Prepare data for POST request
    $data = 'authkey=' . $apiKey . '&mobiles=' . $num . "&message=" . $message . "&sender=" .$sender."&route=4&country=91" ;
    // Send the POST request with cURL
    $ch = curl_init('http://sms.onlinesystemssolutions.com/api/sendhttp.php?' . $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    // Process your response here
    //echo $response;
    $sms_count++;

//echo "<pre>";
//print_r($data);
//echo "</pre>";

}

$conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

$sql3="SELECT * FROM noc_status WHERE sms_status=1 and sms_count > 0 and mob_no != '".$mdn."' 
        union all SELECT * FROM noc_status WHERE sms_status=0 and mob_no = '".$mdn."'";
$result1 = mysqli_query($conn, $sql3);
$rowcount1=mysqli_num_rows($result1);

if ($rowcount1==0) { 
    $sql = "SELECT i.intim_id, e.site_number, CONCAT(i.site_id, '-', i.sms_text) AS sms_text,
    if(p.s1,e.l1_number,'0') as n1,
    if(p.s2,e.l2_number,'0') as n2,
    if(p.s3,e.l3_number,'0') as n3,
    if(p.s4,e.l4_number,'0') as n4,
    if(p.s5,e.l5_number,'0') as n5
    FROM intimation as i 
    left join priority as p on i.priority_id =p.priority_id
    left join escalation_master as e on e.site_id=i.site_id
    WHERE 1
    and i.sms_intim = 1
    LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    $rowcount=mysqli_num_rows($result);

    if ($rowcount>0) {
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $sql2 = "UPDATE intimation SET sms_intim=0, sms_from='".$mdn."',
                     sms_dt=CURRENT_TIMESTAMP() WHERE intim_id=".$row["intim_id"];
            $result=mysqli_query($conn, $sql2);
  
            if(strlen($row["n1"])>4) send_sms($row["n1"],$row["sms_text"]."-".date("h:i"));
            if(strlen($row["n2"])>4) send_sms($row["n2"],$row["sms_text"]."-".date("h:i"));
            if(strlen($row["n3"])>4) send_sms($row["n3"],$row["sms_text"]."-".date("h:i"));
            if(strlen($row["n4"])>4) send_sms($row["n4"],$row["sms_text"]."-".date("h:i"));
            if(strlen($row["n5"])>4) send_sms($row["n5"],$row["sms_text"]."-".date("h:i"));

    }else {

        $sql2="SELECT sr_id, imei, to_number, site_id, msg, status, create_dt, sms_from, sms_dt 
                FROM sms_response WHERE 1 and status=1 LIMIT 1";
        $result = mysqli_query($conn, $sql2);
        $rowcount=mysqli_num_rows($result);
        
        if ($rowcount>0) {
         
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $sql2 = "UPDATE sms_response SET status=0, sms_from='".$mdn."',
                     sms_dt=CURRENT_TIMESTAMP() WHERE sr_id=".$row["sr_id"];
            $result=mysqli_query($conn, $sql2);
            
            send_sms($row["to_number"],$row["site_id"]."-".$row["msg"]."-".date("h:i"));
            
            
        }
        
    }
        
}

if($sms_count>0){

    $sql3="UPDATE noc_status SET sms_count=sms_count+$sms_count, sms_status=if(sms_count>10000,0,1),
       sms_start=if((sms_count-$sms_count)=0,CURRENT_TIME(),sms_start),
       sms_end=if(sms_count>10000,CURRENT_TIME(),NULL), last_sms=CURRENT_TIME()
       WHERE mob_no='".$mdn."'";
    $result1 = mysqli_query($conn, $sql3);
}

//send_sms("9324677887","This is a test msg from server");

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

$result1 = mysqli_query($conn, $sql);
    

$conn->close();
ob_flush();
flush();

?>
<?php
//read the details from intimation table to which intimation SMS to be sent
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");



// creating arrey for json response
//$response=array();

if(isset($_GET['mdn'])) {
session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
//error_reporting(0);
$sms_count=0;

date_default_timezone_set("Asia/Kolkata");

    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
$sql2="SELECT noc_status FROM noc_status WHERE mob_no='".$_GET['mdn']."'";
$result = mysqli_query($conn, $sql2);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$status=$row["noc_status"];
if($status[2]=='0') {
    echo "([0][0][0])";
    return;
}
    
$sql3="SELECT * FROM noc_status WHERE sms_status=1 and mob_no='".$_GET['mdn']."'";
$result1 = mysqli_query($conn, $sql3);
$rowcount1=mysqli_num_rows($result1);

if ($rowcount1>0) { 
    $sql1 = "SELECT i.intim_id,i.site_id,i.sms_text,
    if(p.s1,e.l1_number,'0') as n1, if(p.s2,e.l2_number,'0') as n2, if(p.s3,e.l3_number,'0') as n3,
    if(p.s4,e.l4_number,'0') as n4, if(p.s5,e.l5_number,'0') as n5 
    FROM intimation as i 
    left join priority as p on i.priority_id =p.priority_id
    left join escalation_master as e on e.site_id=i.site_id
    WHERE 1
    and i.sms_intim = 1
    LIMIT 1";
    $out="";
    $result = mysqli_query($conn, $sql1);
    $rowcount=mysqli_num_rows($result);
    
    
    if ($rowcount>0) {
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $sql2 = "UPDATE intimation SET sms_intim=0, sms_from='".$_GET['mdn']."',
                     sms_dt=CURRENT_TIMESTAMP() WHERE intim_id=".$row["intim_id"];
            $result=mysqli_query($conn, $sql2);
     
            $out="([".$row["intim_id"]."][".$row["n1"]."][".$row["n2"]."][".$row["n3"].
            "][".$row["n4"]."][".$row["n5"]."][".$row["site_id"]."-".$row["sms_text"]."-".date("h:i")."])";
            
            if(strlen($row["n1"])>4) $sms_count+=1;
            if(strlen($row["n2"])>4) $sms_count+=1;
            if(strlen($row["n3"])>4) $sms_count+=1;
            if(strlen($row["n4"])>4) $sms_count+=1;
            if(strlen($row["n5"])>4) $sms_count+=1;
            
    }else {
        $sql2="SELECT sr_id, imei, to_number, site_id, msg, status, create_dt, sms_from, sms_dt 
                FROM sms_response WHERE 1 and status=1 LIMIT 1";
        $result = mysqli_query($conn, $sql2);
        $rowcount=mysqli_num_rows($result);
        
        if ($rowcount>0) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $sql2 = "UPDATE sms_response SET status=0, sms_from='".$_GET['mdn']."',
                     sms_dt=CURRENT_TIMESTAMP() WHERE sr_id=".$row["sr_id"];
            $result=mysqli_query($conn, $sql2);
            
            $out="([".$row["sr_id"]."][".$row["to_number"]."][0][0][0][0]["
                     .$row["site_id"]."-".$row["msg"]."-".date("h:i")."])";
            $sms_count=1;
            
        }else $out="([0][0][0])";
        
    }
        
}else $out="([1][1][1])";

echo $out;
if($sms_count>0){
    $sql3="UPDATE noc_status SET sms_count=sms_count+$sms_count, sms_status=if(sms_count>95,0,1),
       sms_start=if((sms_count-$sms_count)=0,CURRENT_TIME(),sms_start),
       sms_end=if(sms_count>95,CURRENT_TIME(),NULL),last_sms=CURRENT_TIME()
       WHERE mob_no='".$_GET['mdn']."'";
    $result1 = mysqli_query($conn, $sql3);
}

    

$conn->close();
ob_flush();
flush();
}


?>
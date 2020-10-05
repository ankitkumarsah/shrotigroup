<?php
// read the number for calling initate_call.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
//error_reporting(0);

// creating arrey for json response
$response=array();

if(isset($_GET['mdn'])) {
    
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
    echo "([0][0][0][0][0][0])";
    return;
}

$sql = "SELECT i.intim_id, if(p.c1,e.l1_number,'0') as n1,if(p.c2,e.l2_number,'0') as n2,
if(p.c3,e.l3_number,'0') as n3,if(p.c4,e.l4_number,'0') as n4,if(p.c5,e.l5_number,'0') as n5
FROM intimation as i 
left join priority as p on i.priority_id =p.priority_id
left join escalation_master as e on e.site_id=i.site_id
WHERE 1
and i.call_intim = 1   and i.sms_intim = 0      
limit 1";

//and i.sms_intim = 0
$out="";
    $result = mysqli_query($conn, $sql);
    $rowcount=mysqli_num_rows($result);
 
    if ($rowcount>0) {
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $out="([".$row["intim_id"]."]";
        $out=$out."[".$row["n1"]."]";
        $out=$out."[".$row["n2"]."]";
        $out=$out."[".$row["n3"]."]";
        $out=$out."[".$row["n4"]."]";
        $out=$out."[".$row["n5"]."])";
       
        $result -> free_result();
        
        $sql1 = "UPDATE intimation SET call_intim=0,call_from='".$_GET['mdn']."',
                 call_dt=CURRENT_TIMESTAMP() WHERE intim_id=".$row["intim_id"];
        $result = mysqli_query($conn, $sql1);
        
        $sql1 = "UPDATE noc_status SET call_status=1, call_start=CURRENT_TIMESTAMP() 
                WHERE mob_no='".$_GET['mdn']."'";
        $result = mysqli_query($conn, $sql1);

    } else $out="([0][0][0][0][0][0])";
        
echo $out;

$conn->close();

ob_flush();
flush();


}else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
    
    //show JSON response
    echo json_encode($response);
}

?>


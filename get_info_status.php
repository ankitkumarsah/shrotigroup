<?php
//read the details from intimation table to which intimation SMS to be sent
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
ob_end_flush();
ob_start();
set_time_limit(0);

// creating arrey for json response
//$response=array();


if(isset($_GET['imei']) && isset($_GET['ack'])) {


if (strlen($_GET['imei']) < 15) return;

//error_reporting(0);

    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    if(($_GET['ack'])&1) $BTLV1=1; else $BTLV1=0;
    if($_GET['ack']&2) $BTLV2=1; else $BTLV2=0;
    if($_GET['ack']&4) $BTLV3=1; else $BTLV3=0;
    if($_GET['ack']&8) $BTLV4=1; else $BTLV4=0;
    if($_GET['ack']&16) $BBLBK=1; else $BBLBK=0;
    if($_GET['ack']&32) $DOOPN=1; else $DOOPN=0;
    if($_GET['ack']&64) $EXTAL=1; else $EXTAL=0;
    if($_GET['ack']&128) $HOTER=1; else $HOTER=0;
    if($_GET['ack']&256) $SBTLO=1; else $SBTLO=0;
    if($_GET['ack']&512) $VDCFL=1; else $VDCFL=0;
    if($_GET['ack']&1024) $TPCOV=1; else $TPCOV=0;
    
    $sql="SELECT * FROM site_status WHERE imei='".$_GET['imei']."'";
    $result = mysqli_query($conn, $sql);
    
    
	$sql2="UPDATE site_status SET DOOPN='".$DOOPN."', BBLBK='".$BBLBK."', BTLV1='".$BTLV1."', BTLV2='".$BTLV2."', BTLV3='".$BTLV3."', BTLV4='".$BTLV4."', EXTAL='".$EXTAL."', TPCOV='".$TPCOV."', VDCFL='".$VDCFL."', SBTLO='".$SBTLO."', HOTER='".$HOTER."',create_dt=CURRENT_TIMESTAMP() WHERE imei='".$_GET['imei']."'";
	//echo $sql2;
	
    $result2 = mysqli_query($conn, $sql2);
    $aid=0;
    
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($row["DOOPN"] != $DOOPN ) { $aid=1 ; $status=$DOOPN;}
    else if($row["BBLBK"] != $BBLBK ) { $aid=2 ; $status=$BBLBK ;}
    else if($row["BTLV1"] != $BTLV1 ) { $aid=3 ; $status=$BTLV1;}
    else if($row["BTLV2"] != $BTLV2 ) { $aid=4 ; $status=$BTLV2;}
    else if($row["BTLV3"] != $BTLV3 ) { $aid=5 ; $status=$BTLV3;}
    else if($row["BTLV4"] != $BTLV4 ) { $aid=6 ; $status=$BTLV4;}
    else if($row["EXTAL"] != $EXTAL ) { $aid=7 ; $status=$EXTAL;}
    else if($row["TPCOV"] != $TPCOV ) { $aid=8 ; $status=$TPCOV;}
    else if($row["VDCFL"] != $VDCFL ) { $aid=9 ; $status=$VDCFL;}
    else if($row["SBTLO"] != $SBTLO ) { $aid=10 ; $status=$SBTLO;}
    else if($row["HOTER"] != $HOTER ) { $aid=11 ; $status=$HOTER;}

    
    if($aid){
        $sql="SELECT * FROM event WHERE 1 and imei='".$_GET['imei']."' and seq_number=(Select max(seq_number) from event where imei='".$_GET['imei']."' and create_dt > (CURRENT_DATE() - 1) )";
        $result = mysqli_query($conn, $sql);
        $row1 = mysqli_fetch_array($result,MYSQLI_ASSOC);
        echo $row1["alarm_id"];
        if($row1["alarm_id"] != $aid || $row1["alarm_status"]!=$status){
            $seq_number=$row1["seq_number"]*(-1);
            $sql = "INSERT INTO event(imei,seq_number,alarm_id,alarm_status,source,sms_count,date)
            VALUES ('".$_GET['imei']."','".$seq_number."','".$aid."','".$status."','AUTO','".$row1["sms_count"]."',CURRENT_DATE())";
            $result = mysqli_query($conn, $sql);
        }
    }
    
    $sql1 = "SELECT status FROM site_info WHERE imei='".$_GET['imei']."'";
    
    $result1 = mysqli_query($conn, $sql1);
    
    if (mysqli_num_rows($result1) > 0){
        $row = mysqli_fetch_array($result1,MYSQLI_ASSOC);
        $out="([".$row["status"]."])";
    }else{
        $sql2 ="INSERT INTO site_info(imei) VALUES ('".$_GET['imei']."')";
        mysqli_query($conn, $sql2);
        $out="([0])(--)";
    }
    echo $out;

$conn->close();
ob_flush();
flush();
}


?>


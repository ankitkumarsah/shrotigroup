

<?php
//read the details from intimation table to which intimation SMS to be sent
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// creating arrey for json response
//$response=array();

if(isset ($_GET['imei'])&&isset($_GET['uid'])&&isset($_GET['value'])&&isset($_GET['status'])){
session_start();
ob_end_flush();
ob_start();
set_time_limit(0);
//error_reporting(0);

    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    switch ($_GET['uid']) {
        case 1: $sql="UPDATE site_info SET site_id='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 2: $sql="UPDATE site_info SET site_number='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 4: $sql="UPDATE site_info SET geo_lat='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 8: $sql="UPDATE site_info SET geo_long='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 16: $sql="UPDATE site_info SET num_1='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 32: $sql="UPDATE site_info SET num_2='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 64: $sql="UPDATE site_info SET num_3='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 128: $sql="UPDATE site_info SET num_4='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 256: $sql="UPDATE site_info SET sig='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        case 512: $sql="UPDATE site_info SET ver='".$_GET['value']."',
                status='".$_GET['status']."' WHERE imei='".$_GET['imei']."'";break;
        default: break;
    }
    
    echo $sql;

    mysqli_query($conn, $sql);
    
$conn->close();
ob_flush();
flush();
}


?>
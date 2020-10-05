<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include 'db_connect.php';

// creating arrey for json response
$response=array();

//check if we got the field from the user
if(isset($_GET['mdn']) && isset($_GET['nstat'])) {
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $date = new DateTime();
    $ed = $date->format('Y-m-d H:i:s');
    
    $sql2="SELECT noc_status FROM noc_status WHERE mob_no='".$_GET['mdn']."'";
    $result = mysqli_query($conn, $sql2);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $status=$row["noc_status"];
    if($status[0]=='0') return;

    
    
    $sql = "UPDATE noc_status SET noc_status='".$_GET['nstat']."' WHERE mob_no='".$_GET['mdn']."'";

    
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
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
    
    // show JSON response
    echo json_encode($response);
}

?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include 'db_connect.php';

// creating arrey for json response
$response=array();

//check if we got the field from the user
if(isset($_GET['imei']) && isset($_GET['loc'])) {
    
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
    
    $sql = "UPDATE mdn_master SET created_dt='".$ed."', status ='0'
            WHERE imei='".$_GET['imei']."' and mdn_loc='".$_GET['loc']."'";
    
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

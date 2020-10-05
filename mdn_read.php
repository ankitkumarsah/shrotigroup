
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include 'db_connect.php';

// creating arrey for json response
$response=array();

//check if we got the field from the user
if (isset($_GET['imei'])) {
    
    
    // Include database connect class
    $filepath = realpath(dirname(__FILE__));
    require_once ($filepath."/dbconfig.php");
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM mdn_master WHERE status=1 and imei='".$_GET['imei']."'";
    $result = mysqli_query($conn, $sql);
    
    
    if (mysqli_num_rows($result) != 0){
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            //$loc = $row["mdn_loc"];
            // $number=$row["mdn"];
            // $name =$row["name"];
            echo "@".$row["mdn_loc"].",".$row["mdn"].",".$row["name"]."@";
            echo "\r";
        }
    }
    //echo "@@";
    $conn->close();
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
    
    // show JSON response
    echo json_encode($response);
}


?>


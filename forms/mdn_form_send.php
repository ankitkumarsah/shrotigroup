<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");



$site_id    = filter_input(INPUT_POST, 'site_id');
$site_mdn   = filter_input(INPUT_POST, 'site_mdn');
$imei       = filter_input(INPUT_POST, 'imei');
$num_1      = filter_input(INPUT_POST, 'num_1');
$mail_1     = filter_input(INPUT_POST, 'mail_1');
$num_2      = filter_input(INPUT_POST, 'num_2');
$mail_2     = filter_input(INPUT_POST, 'mail_2');
$num_3      = filter_input(INPUT_POST, 'num_3');
$mail_3     = filter_input(INPUT_POST, 'mail_3');
$num_4      = filter_input(INPUT_POST, 'num_4');
$mail_4     = filter_input(INPUT_POST, 'mail_4');
$num_5      = filter_input(INPUT_POST, 'num_5');
$mail_5     = filter_input(INPUT_POST, 'mail_5');


$filepath = realpath(dirname(__FILE__,2));
            require_once ($filepath."/dbconfig.php");

if (!empty($site_mdn) || !empty($site_id) || !empty($imei) ){
    
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
        
    if (mysqli_connect_error()){
        die('Connect Error ('. mysqli_connect_errno() .') '
            . mysqli_connect_error());
    }
    else{
        
        $sql="INSERT INTO site_data_entry_history (site_id, site_number, imei, l1_number, l1_mail, l2_number, l2_mail, l3_number, l3_mail, l4_number, l4_mail, l5_number, l5_mail) 
        VALUES ('$site_id', '$site_mdn', '$imei', '$num_1', '$mail_1', '$num_2', '$mail_2', '$num_3', '$mail_3', '$num_4', '$mail_4', '$num_5', '$mail_5')";
        if ($conn->query($sql)) echo "Site data changed Record Inserted Sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
        
        $sql="REPLACE INTO escalation_master(site_id,site_number,imei)VALUES ('$site_id','$site_mdn','$imei')";
        if ($conn->query($sql)) echo "Esclation Master Record Inserted Sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
            
        $sql="REPLACE INTO site_info(site_id,site_number,imei)VALUES ('$site_id','$site_mdn','$imei')";
        if ($conn->query($sql)) echo "Site Information Table : Record Inserted Sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
  
        $sql="REPLACE INTO site_status(site_id,site_number,imei)VALUES ('$site_id','$site_mdn','$imei')";
        if ($conn->query($sql)) echo "Site Status Table: Record Inserted Sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
            
        $sql = "REPLACE INTO mdn_master(imei,mdn_loc,mdn,name,status) VALUES ('$imei','5','$site_mdn','$site_id','1')";
            
        if ($conn->query($sql)) echo "Site Number & ID is inserted sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
            
        $sql = "REPLACE INTO mdn_master(imei,mdn_loc,mdn,name,status) VALUES ('$imei','4','9755904190','NOC','1')";
                
            if ($conn->query($sql)) echo "NOC Number is inserted sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
  
        if (!empty($num_1)){
            $sql = "REPLACE INTO mdn_master(imei,mdn_loc,mdn,name,status) VALUES ('$imei','1','$num_1','TECH','1')";
                
            if ($conn->query($sql)) echo "Technician Number is inserted sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
            $sql="UPDATE escalation_master SET l1_number='$num_1', l1_mail='$mail_1' WHERE  site_id = '$site_id'";
            
            if ($conn->query($sql)) echo "Esclation Master Update for level 1 mail and number Sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
        }
        if (!empty($num_2)){
            $sql = "REPLACE INTO mdn_master(imei,mdn_loc,mdn,name,status) VALUES ('$imei','2','$num_2','CI','1')";
                
            if ($conn->query($sql)) echo "CI Number is inserted sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
            $sql="UPDATE escalation_master SET l2_number='$num_2', l2_mail='$mail_2' WHERE  site_id = '$site_id'";
            
            if ($conn->query($sql)) echo "Esclation Master Update for level 2 mail and number Sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
        } 
        if (!empty($num_3)){
            $sql = "REPLACE INTO mdn_master(imei,mdn_loc,mdn,name,status) VALUES ('$imei','3','$num_3','ZOM','1')";
                
            if ($conn->query($sql)) echo "ZOM Number is inserted sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
            $sql="UPDATE escalation_master SET l3_number='$num_3', l3_mail='$mail_3' WHERE  site_id = '$site_id'";
            
            if ($conn->query($sql)) echo "Esclation Master Update for level 3 mail and number Sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
        }
        if (!empty($num_4)){
            $sql="UPDATE escalation_master SET l4_number='$num_4', l4_mail='$mail_4' WHERE  site_id = '$site_id'";
            
            if ($conn->query($sql)) echo "Esclation Master Update for level 4 mail and number Sucessfully\r\n";
                else echo "Error: ". $sql ."\r\n". $conn->error;
            
        }
        if (!empty($num_5)){
            $sql="UPDATE escalation_master SET l5_number='$num_5', l5_mail='$mail_5' WHERE  site_id = '$site_id'";
            
            if ($conn->query($sql)) echo "Esclation Master Update for level 5 mail and number Sucessfully\r\n";
            else echo "Error: ". $sql ."\r\n". $conn->error;
            
        } 
        $conn->close();
    }
}
else{
    echo "Site ID, TPMS Number and IMEI Number Should not be empty";
    die();

}
?>
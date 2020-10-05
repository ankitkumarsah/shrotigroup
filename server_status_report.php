<?php
//read the details from intimation table to which intimation SMS to be sent


// Account details

$filepath = realpath(dirname(__FILE__));
require_once ($filepath."/dbconfig.php");


$conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

$sql = "SELECT noc_entity_id, mob_no, sms_status, sms_count, sms_start, sms_end, last_sms, call_status, call_start, call_end
FROM noc_status WHERE 1";

$result=mysqli_query($conn, $sql);

echo '<table border="0" cellspacing="2" cellpadding="2">
      <tr>
          <td> <font face="Arial">Server No</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">SIM Num</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">SMS Status</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">No of SMS</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">SMS Enabled Time</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">SMS Disabled Time</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">Last SMS Time</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">Call Status</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">Call Start Time</font> </td>
          <td> <font face="Arial">|</font> </td>
          <td> <font face="Arial">Call End Time</font> </td>

      </tr>';


if ($result) {

        
        while ($row = $result->fetch_assoc()) {
            $field1name = $row["noc_entity_id"];
            $field2name = $row["mob_no"];
            $field3name = $row["sms_status"]? "Enabled":"Disabled"   ;
            $field4name = $row["sms_count"];
            $field5name = $row["sms_start"];
            $field6name = $row["sms_end"];
            $field7name = $row["last_sms"];
            $field8name = $row["call_status"]? "In Call":"Ideal" ;
            $field9name = $row["call_start"];
            $field10name = $row["call_end"];

 
            echo '<tr>
                  <td>'.$field1name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field2name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field3name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field4name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field5name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field6name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field7name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field8name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field9name.'</td>
                  <td> <font face="Arial">|</font> </td>
                  <td>'.$field10name.'</td>

              </tr>';
        }
        
        /*freeresultset*/
        $result->free();
   
}
$conn->close();


?>
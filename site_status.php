<?php
//read the details from intimation table to which intimation SMS to be sent


// Account details

$filepath = realpath(dirname(__FILE__));
require_once ($filepath."/dbconfig.php");


$conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

$sql = "SELECT site_id, imei, site_number, DOOPN, BBLBK, BTLV1, BTLV2, BTLV3, BTLV4, EXTAL, TPCOV, VDCFL, SBTLO, HOTER
FROM site_status
WHERE 1 ";

$result=mysqli_query($conn, $sql);

echo '<table border="0" cellspacing="2" cellpadding="2">
      <tr>
          <td> <font face="Arial">Site ID</font> </td>
          <td> <font face="Arial">IMEI</font> </td>
          <td> <font face="Arial">Site Number</font> </td>
          <td> <font face="Arial">DOOR</font> </td>
          <td> <font face="Arial">BB LOOP</font> </td>
          <td> <font face="Arial">BB1 VOLT</font> </td>
          <td> <font face="Arial">BB2 VOLT</font> </td>
          <td> <font face="Arial">BB3 VOLT</font> </td>
          <td> <font face="Arial">BB4 VOLT</font> </td>
          <td> <font face="Arial">EXTRA AL</font> </td>
          <td> <font face="Arial">COVER OPEN</font> </td>
          <td> <font face="Arial">48VDC FL</font> </td>
          <td> <font face="Arial">INT BAT LOW</font> </td>
          <td> <font face="Arial">HOOTER</font> </td>
      </tr>';


if ($result) {
        
        while ($row = $result->fetch_assoc()) {
            $field1name = $row["site_id"];
            $field2name = $row["imei"];
            $field3name = $row["site_number"];
            $field4name = $row["DOOPN"];
            $field5name = $row["BBLBK"];
            $field6name = $row["BTLV1"];
            $field7name = $row["BTLV2"];
            $field8name = $row["BTLV3"];
            $field9name = $row["BTLV4"];
            $field10name = $row["EXTAL"];
            $field11name = $row["TPCOV"];
            $field12name = $row["VDCFL"];
            $field13name = $row["SBTLO"];
            $field14name = $row["HOTER"];

            echo '<tr>
                  <td>'.$field1name.'</td>
                  <td>'.$field2name.'</td>
                  <td>'.$field3name.'</td>
                  <td>'.$field4name.'</td>
                  <td>'.$field5name.'</td>
                  <td>'.$field6name.'</td>
                  <td>'.$field7name.'</td>
                  <td>'.$field8name.'</td>
                  <td>'.$field9name.'</td>
                  <td>'.$field10name.'</td>
                  <td>'.$field11name.'</td>
                  <td>'.$field12name.'</td>
                  <td>'.$field13name.'</td>
                  <td>'.$field14name.'</td>

              </tr>';
        }
        
        /*freeresultset*/
        $result->free();
   
}
$conn->close();


?>
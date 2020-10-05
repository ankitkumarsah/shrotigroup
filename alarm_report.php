<?php include_once "main_head.php";
if(isset($_SESSION["email"]))  
{  
?>
<?php
//read the details from intimation table to which intimation SMS to be sent


// Account details

$filepath = realpath(dirname(__FILE__));
require_once ($filepath."/dbconfig.php");


$conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

$sql = "SELECT e.event_id, es.site_id, es.site_number,e.imei, p.priority_id,
(p.s1 OR p.s2 OR p.s3 OR p.s4 OR p.s5) as sms_intim,
(p.c1 OR p.c2 OR p.c3 OR p.c4 OR p.c5) as call_intim,
e.sms_count as sms_count,
if (e.alarm_status , a.on_desc , a.off_desc) as sms_text,
if(i.sms_text=if (e.alarm_status , a.on_desc , a.off_desc), 'N/A' , i.sms_text) as Acknowledge
FROM event as e 
left join escalation_master as es on e.imei=es.imei
left join alarm as a on e.alarm_id=a.alarm_id
left join priority as p on if(e.alarm_status, a.priority=p.priority_id , p.priority_id = 7)
left join (select event_id, max(intim_id) as intim_id from intimation group by event_id) as it 
on e.event_id=it.event_id
left join intimation as i on it.intim_id=i.intim_id
WHERE 1 
order by e.create_dt desc, es.site_number, e.event_id DESC
limit 100";

$result=mysqli_query($conn, $sql);
?>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>


    <div class="container-fluid">
        <h3 class="mt-4">Alarm Reports</h3>
       
      

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table mr-1"></i> Alarm Reports
                <button type="button" class="btn btn-secondary" style="float:right;">Print</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Event ID</th>
                                <th>Site ID</th>
                                <th>Site Number</th>
                                <th>Priority ID</th>
                                <th>SMS Intimation</th>
                                <th>Call Intimation</th>
                                <th>SMS Count</th>
                                <th>SMS Text</th>
                                <th>SMS Ack</th>
                                <th>IMEI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php

if ($result) {
        
    while ($row = $result->fetch_assoc()) {

?>

                                    <td>
                                        <?php echo htmlspecialchars($row["event_id"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["site_id"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["site_number"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["priority_id"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["sms_intim"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["call_intim"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["sms_count"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["sms_text"]); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row["Acknowledge"]); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row["imei"]); ?>
                                    </td>


                            </tr>
                            <?php        }
        
        /*freeresultset*/
        $result->free();
   
}
$conn->close();
?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    
<?php
 
}  

else  
{  
     header("location:index.php");  
}  
?> 
<?php include_once "footer.php"; ?>

    <?php include_once ("footer.php"); ?>
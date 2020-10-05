<?php require_once "main_head.php";
include_once "../pdo_connection.php"; 
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
                                <th>Circle</th>
                                <th>L2</th>
                                <th>L3</th>
                                <th>Site ID</th>
                                <th>Site Name</th>
                                <th>Site Number</th>
                                <th>Imei</th>
                                <th>Last Comm.</th>
                                <th>Status</th>
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


<?php require_once "footer.php"; ?>
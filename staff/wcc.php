<?php
 include_once "main_head.php"; 
 include_once "../pdo_connection.php"; 

 if(isset($_POST['submit'])){
 
  
        // Getting email and password
        $client=$_POST['client_name'];
       $circle=($_POST['circle']);
       $site_id=$_POST['site_id'];
       $global_id=($_POST['global_id']);

      $site_name=$_POST['site_name'];
       $disc_zon=($_POST['disct_zone']);
       $site_mob=$_POST['site_mobile_no'];
       $sim_serial=($_POST['sim_serial_no']);

       $imei=$_POST['sim_imei'];
       $ver=($_POST['version']);
       $sys_serial=$_POST['system_serial'];
       $lat=($_POST['lat']);
       $long=($_POST['long']);

      // $sql = "INSERT INTO student (name, roll, address) VALUES (:name, :roll, :address)";
      $sql="INSERT INTO `all_client_master`(`client_name`, `circle`, `site_id`, `global_id`, `site_name`, `dist_zone`, `site_mobile_no`, `sim_serial_no`, `gsm_imei_no`, `system_ver_type`, `system_serial_no`, `latitude`, `longitude`) 
      VALUES (:client, :circle, :site_id, :global_id, :site_name, :dz, :mob, :sim_serial, :imei, :ver, :sys_serial, :lat, :long)";
        //$sql ="SELECT email,password FROM main_login WHERE (email=:email) and (password=:pwd)";
       $query= $conn -> prepare($sql);
  
        $query-> bindParam(':client', $client, PDO::PARAM_STR);
       $query-> bindParam(':circle', $circle, PDO::PARAM_STR);
       $query-> bindParam(':site_id', $site_id, PDO::PARAM_STR);
       $query-> bindParam(':global_id', $global_id, PDO::PARAM_STR);

       $query-> bindParam(':site_name', $site_name, PDO::PARAM_STR);
       $query-> bindParam(':dz', $disc_zon, PDO::PARAM_STR);
       $query-> bindParam(':mob', $site_mob, PDO::PARAM_STR);
       $query-> bindParam(':sim_serial', $sim_serial, PDO::PARAM_STR);

       
       $query-> bindParam(':imei', $imei, PDO::PARAM_STR);
       $query-> bindParam(':ver', $ver, PDO::PARAM_STR);
       $query-> bindParam(':sys_serial', $sys_serial, PDO::PARAM_STR);
       $query-> bindParam(':lat', $lat, PDO::PARAM_STR);
       $query-> bindParam(':long', $long, PDO::PARAM_STR);

       $query-> execute();
  
       

      echo $query->rowCount() . " Row Inserted <br>";
      
      unset($query);
      
    }
  
  
?>
<div id="layoutAuthentication">
    <style>
        .small {
            font-weight: bolder;
            font-size: 15px;
        }
    </style>
    <main>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center my-4" style="font-weight: bolder;">WCC [Work Completion Cirtificate]</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Client Name</label>
                                            <select class="form-control" name="client_name">
                                          <option value="BIL">BIL</option>
                                          <option value="ATC">ATC</option>
                                          <option value="OTHERS">OTHERS</option>
                                        </select>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Circle</label>
                                            <input class="form-control py-4" type="text" name="circle" placeholder="Enter Circle name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Site Id</label>
                                            <input class="form-control py-4" type="text" name="site_id" placeholder="Enter Site Id" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Global Id/Infratel ID</label>
                                            <input class="form-control py-4" type="text" name="global_id" placeholder="Enter Global Id" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Site Name</label>
                                            <input class="form-control py-4" type="text" name="site_name" placeholder="Enter Site name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Distic/Zone</label>
                                            <input class="form-control py-4" type="text" name="disct_zone" placeholder="Enter Distic name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Site Mobile No</label>
                                            <input class="form-control py-4" type="text" name="site_mobile_no" placeholder="Enter first name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">SIM Serial No</label>
                                            <input class="form-control py-4" type="text" name="sim_serial_no" placeholder="Enter last name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="small mb-1">GSM IMEI No</label>
                                    <input class="form-control py-4" type="text" name="sim_imei" placeholder="Enter GSM IMEI No" />
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">System Version Type</label>
                                            <input class="form-control py-4" type="text" name="version" placeholder="Enter System Version Type" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">System Serial No</label>
                                            <input class="form-control py-4" type="texy" name="system_serial" placeholder="Enter System Serial No" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Site Latitude</label>
                                            <input class="form-control py-4" type="text" name="lat" placeholder="Enter Site Latitude" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Site Longitude</label>
                                            <input class="form-control py-4" type="texy" name="long" placeholder="Enter Site Longitude" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4 mb-0">
                                <button type="submit" class="btn btn-primary btn-block" name="submit">Submit</button>
</div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


</div>
<?php include_once "footer.php"; ?>
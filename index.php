<?php
session_start();
include_once "pdo_connection.php";
if(isset($_REQUEST['submit'])){
  // checking for empty field
  if(($_REQUEST['email'] == "") || ($_REQUEST['pwd'] == ""))
  {
    echo"<small>Fill all fields..</small><hr>";
  }
  else {



      // Getting email and password
      $email=$_POST['email'];
      $pwd=($_POST['pwd']);

      // Fetch data from database on the basis of email and password
      $sql ="SELECT email,password FROM main_login WHERE (email=:email) and (password=:pwd)";
      $query= $conn -> prepare($sql);

      $query-> bindParam(':email', $email, PDO::PARAM_STR);
      $query-> bindParam(':pwd', $pwd, PDO::PARAM_STR);
      $query-> execute();

      $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
      $_SESSION['email']=$_POST['email'];
      echo "<script > document.location = 'main_home.php'; </script>";
    } else{
      echo "<script>alert('Invalid Details');</script>";
    }
  
      // Close Prepared Statement
      unset($result);
  }
}

?>





    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Shorti Group || Login </title>
        <link href="css/styles.css" rel="stylesheet" />

    </head>

    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4"><b>SHORTI GROUP</b></h3>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" name="email" type="email" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="pwd" type="password" placeholder="Enter password" />
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.html">Forgot Password?</a>
                                                <button type="submit" class="btn btn-primary" name="submit">Login</button>

                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="register.html">Need an Help? Click Here!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

        </div>

    </body>

    </html>
<?php include_once "main_head.php";

if(isset($_SESSION["email"]))  
{  
?>


<?php
 
}  

else  
{  
     header("location:index.php");  
}  
?> 
<?php include_once "footer.php"; ?>

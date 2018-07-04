<?php 
<<<<<<< HEAD
include '../connect.php';
=======
include 'connect.php';
>>>>>>> 3672b3ca8a1ef25fa97b51c3e06c8c12f8599008
$strSQL = "INSERT INTO contact ";
$strSQL .="(name,email,contact.desc) ";
$strSQL .="VALUES ";
$strSQL .="('".$_POST["con_name"]."','".$_POST["con_email"]."','".$_POST["con_message"]."')";
$objQuery =  mysqli_query($conn, $strSQL);
if($objQuery)
{
<<<<<<< HEAD
    header("location:../home/en");
=======
    header("location:home.php");
>>>>>>> 3672b3ca8a1ef25fa97b51c3e06c8c12f8599008
	//echo "Save Done.";
}
else
{
<<<<<<< HEAD
    header("location:../home/en");
=======
    header("location:home.php");
>>>>>>> 3672b3ca8a1ef25fa97b51c3e06c8c12f8599008
}
?>

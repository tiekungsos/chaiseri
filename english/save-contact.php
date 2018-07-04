<?php 
include 'connect.php';
$strSQL = "INSERT INTO contact ";
$strSQL .="(name,email,contact.desc) ";
$strSQL .="VALUES ";
$strSQL .="('".$_POST["con_name"]."','".$_POST["con_email"]."','".$_POST["con_message"]."')";
$objQuery =  mysqli_query($conn, $strSQL);
if($objQuery)
{
    header("location:home.php");
	//echo "Save Done.";
}
else
{
    header("location:home.php");
}
?>

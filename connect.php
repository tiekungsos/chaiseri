<?php
$servername = "localhost";
$username = "root";
$password = "";
$databse = "chai";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$databse);
mysqli_set_charset($conn, "utf8");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// echo "Connected successfully";

    $blog_home = "SELECT * FROM blog limit 3";
    $blog = "SELECT * FROM blog dsc";
    $product = "SELECT * FROM product";
    $blog_query_home = mysqli_query($conn, $blog_home) or die('Error querying database.');
    $blog_query = mysqli_query($conn, $blog) or die('Error querying database.');
    $product_query = mysqli_query($conn, $product) or die('Error querying database.');
    
   
    // while ($row = mysqli_fetch_array($blog_query)) {
    //     echo $row['name'] . ' ' . $row['description_head'] . ' ' . $row['description'] . ' ' . $row['date'] .'' . $row['image_upload'] . '<br />';
    //    }

?>
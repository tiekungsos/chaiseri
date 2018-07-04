<?php 
// require_once 'connect.php';
// $product_id = $_GET["product_id"];
// $detail = "SELECT * FROM product WHERE product_id = $product_id ";
// $product_detail = mysqli_query($conn, $detail) or die('Error querying database.');
// $product = mysqli_fetch_object($product_detail);
// echo json_encode($product);
?>
<?php
   
   require_once '../connect.php' ;
   
   if (isset($_REQUEST['id'])) {
     
   $id = intval($_REQUEST['id']);
   $query = "SELECT * FROM product WHERE product_id=$id";
   $stmt = mysqli_query($conn, $query) or die('Error querying database.');
   $row = mysqli_fetch_array($stmt);

   ?>
    
    <div class="col-md-12 col-sm-12 about-image top_30">
            <div align="center">
                <h1><?php echo $row['name_en'];?></h1>
            </div>
            <div class="row top_30">
                    <img  src="../cms/images/product/<?php echo $row['img'] ?>" alt="" height="100%" width="42">
                    
            </div>

            <div class="top_30" align="center">
                <h1><?php echo $row['description_en']; ?></h1>
            </div>
            <div align="center"> <br>        
            <?php if($row['type'] == 1) { ?>
                <h1><?php echo 'Glass bottles   '.$row['size'] ?> ML</h1>     
             <?php } 
                else if($row['type'] == 2) { ?>
                <h1><?php echo 'Plastic bottles  '.$row['size'] ?> ML</h1>    
            <?php } 
                 else if($row['type'] == 3) { ?>
                <h1><?php echo 'PET   '.$row['size'] ?> CC</h1>   
             <?php } 
                else if($row['type'] == 4) { ?>
                <h1><?php echo 'Gallon   '.$row['size'] ?> KG</h1>   
             <?php } 
             else if($row['type'] == 5) { ?>
                <h1><?php echo 'button   '.$row['size'] ?> KG</h1>   
             <?php } 
             ?><br> <br> 
             </div>
    </div>
    
   <?php    
  }
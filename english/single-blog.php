<?php require_once '../connect.php' ;

 $blog_id = $_GET['id'];

 $view = "SELECT * FROM blog where blog_id = $blog_id";
 $blog_view = mysqli_query($conn, $view) or die('Error querying database.');
 $row = mysqli_fetch_array($blog_view);
 //echo $row['blog_id'] ;
 //echo $row['image_upload'];
 $image = explode(",",$row['image_upload']);
?>
<!doctype html>
<html lang="en">
<!-- Mirrored from tavonline.net/html/berlin/main/single-blog.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jun 2018 03:04:06 GMT -->
<head>
<title>Chaiseri</title>
<meta charset="UTF-8">
<meta name="description" content="cssesame">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Favicon -->   
<link rel="shortcut icon" href="../cms/images/logo.png">


<!-- Stylesheets -->
<link rel="stylesheet" href="../css/bootstrap.css"/>
<link rel="stylesheet" href="../css/reset.css"/>
<link rel="stylesheet" href="../css/style.css"/>
<link rel="stylesheet" href="../css/animate.css"/>
<link rel="stylesheet" href="../css/owl.carousel.css"/> 
<link rel="stylesheet" href="../css/magnific-popup.css"/> 
    
<!-- Google Web fonts -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Prompt:300,400,500,600,700" rel="stylesheet">

<!-- Font icons -->
<link rel="stylesheet" href="../icon-fonts/font-awesome-4.5.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="../icon-fonts/essential-regular-fonts/essential-icons.css"/>

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body class="diag">

    <!-- LOADER -->
    
    <nav class="subpage-nav">
        <div class="row">
            <div class="container">
                <div class="logo">
                    <img src="../cms/images/logo.png" alt="">
                </div>
                <div class="responsive"><i data-icon="m" class="icon"></i></div>
                <ul class="nav-menu">
                    <li><a href="../home/en#home" class="smoothScroll">Home</a></li>
                    <li><a href="../home/en#about" class="smoothScroll">About</a></li>
                    <li><a href="../home/en#portfolio" class="smoothScroll">Products</a></li>
                    <li><a href="../home/en#blog" class="smoothScroll">New & Activities</a></li>
                    <li><a href="../home/en#contact" class="smoothScroll">Contact us</a></li>
                    <li><a href="../home" class="smoothScroll">TH</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    
    <div class="container content">
        <div class="row">
            <div class="blog-single col-md-8 col-md-offset-2">
                <div class="blog-image">
                    <img src="../cms/images/<?php echo $row['image_upload_head'] ?>" alt="">
                </div>  
                <h1><?php echo $row['name'] ?></h1>
                <div class="blog-detail">Posted <span><?php echo $row['date'] ?></span>  </div>
                
                <div class="blog-content">
                    
                 <p> <?php echo $row['description_head'] ?></p>
                  <br/>  
                  <p> <?php echo $row['description'] ?> <p>

                <!-- Lightbox images -->   
                <div class="post-lightbox row">
                    <?php 
                       for($i=0;$i<count($image);$i++){  ?>
                        <a href="../cms/images/<?php echo $image[$i] ?>" class="col-md-4 col-sm-4 col-xs-6 lightbox-image link">
                        <img src="../cms/images/<?php echo $image[$i] ?>" alt="">
                    </a>
                    <?php  } ?>
                </div>
                
                </div>

                 <a href="../home/en" class="sitebtn pull-right top_45">Back To home</a>
                    <!-- <input id="con_submit" class="sitebtn" type="submit" value="Send a Message"> -->         
            </div>
        </div>
    </div>
         
         
    <footer>
        <div class="container">
            <div class="social">
                <a href="#">facebook </a>
                <a href="#">twitter </a>
                <a href="#">instagram </a>
                <a href="#">google plus </a>
                <a href="#">behance </a>
                <a href="#">dribbble  </a>
            </div>
            <p>Copyright © 2018 Berlin, All rights Reserved. <br/>
Created by tavonline</p>
        </div>
    </footer>
    
<!-- Javascripts -->
<script src="../js/jquery-2.1.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/wow.min.js"></script>
<script src="../js/isotope.pkgd.min.js"></script>
<script src="../js/typed.js"></script>
<script src="../js/jquery.magnific-popup.min.js"></script>
<script src="../js/jquery.superslides.min.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/main.js"></script>

 
</body>

<!-- Mirrored from tavonline.net/html/berlin/main/single-blog.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jun 2018 03:04:15 GMT -->
</html>

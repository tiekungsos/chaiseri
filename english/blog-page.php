<?php require_once '../connect.php' ?>
<!doctype html>
<html lang="en">

<!-- Mirrored from tavonline.net/html/berlin/main/blog-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jun 2018 03:04:15 GMT -->
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
<!-- <link rel="stylesheet" href="css/mdb.css"/>  -->

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
<!-- <div class="loader-wrapper">
    <div class="loader"></div>
</div> -->
    
   
<nav class="subpage-nav">
        <div class="row">
            <div class="container">
                <div class="logo">
                    <img src="cms/images/logo.png" alt="">
                </div>
                <div class="responsive"><i data-icon="m" class="icon"></i></div>
                <ul class="nav-menu">
<<<<<<< HEAD
                    <li><a href="../home/en#home" class="smoothScroll">Home</a></li>
                    <li><a href="../home/en#about" class="smoothScroll">About</a></li>
                    <li><a href="../home/en#portfolio" class="smoothScroll">Products</a></li>
                    <li><a href="../home/en#blog" class="smoothScroll">New & Activities</a></li>
                    <li><a href="../home/en#contact" class="smoothScroll">Contact us</a></li>
                    <li><a href="../home" class="smoothScroll">TH</a></li>
=======
                    <li><a href="home.php#home" class="smoothScroll">Home</a></li>
                    <li><a href="home.php#about" class="smoothScroll">About</a></li>
                    <li><a href="home.php#portfolio" class="smoothScroll">Products</a></li>
                    <li><a href="home.php#blog" class="smoothScroll">New & Activities</a></li>
                    <li><a href="home.php#contact" class="smoothScroll">Contact us</a></li>
                    <li><a href="../home.php" class="smoothScroll">TH</a></li>
>>>>>>> 3672b3ca8a1ef25fa97b51c3e06c8c12f8599008
                </ul>
            </div>
        </div>
    </nav>
    
    
    <div class="container content">
        <div class="row blog">
        <?php  while ($row = mysqli_fetch_array($blog_query)) { ?>     
<<<<<<< HEAD
             <a href="<?php echo $row['blog_id'] ?>" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 blog-content">
                <div class="blog-image">
                <img src="../cms/images/<?php echo $row['image_upload_head'] ?> ">
=======
             <a href="single-blog.php?id=<?php echo $row['blog_id'] ?>" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 blog-content">
                <div class="blog-image">
                <img src="cms/images/<?php echo $row['image_upload_head'] ?> ">
>>>>>>> 3672b3ca8a1ef25fa97b51c3e06c8c12f8599008
                </div>
                <h2 class="blog-title"><?php echo $row['name'] ?></h2>
                <p><?php echo $row['description_head'] ?></p>
                <span class="blog-info"><?php echo $row['date'] ?> </span>
            </a>
            <?php } ?>
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

<!-- Mirrored from tavonline.net/html/berlin/main/blog-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jun 2018 03:04:18 GMT -->
</html>

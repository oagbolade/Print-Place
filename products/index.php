<?php 
    require_once 'controller.php';
?>
<!doctype html>
<html class="no-js" lang="">

<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125740317-1"></script> -->
<script>
  // window.dataLayer = window.dataLayer || [];
  // function gtag(){dataLayer.push(arguments);}
  // gtag('js', new Date());

  // gtag('config', 'UA-125740317-1');
</script>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>The Print Place - Quality Prints, Flyers, Brochures, Jotters, Corporate Gifts, Product Branding and so much more </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="images/log.png">
    <!-- Place favicon.ico in the root directory -->
    <!-- all css here -->
    <!-- bootstrap v3.3.7 css -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- owl.carousel.2.0.0-beta.2.4 css -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <!-- font-awesome v4.6.3 css -->
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <!-- flaticon.css -->
    <link rel="stylesheet" href="../css/flaticon.css">
    <!-- jquery-ui.css -->
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <!-- metisMenu.min.css -->
    <link rel="stylesheet" href="../css/metisMenu.min.css">
    <!-- slicknav.min.css -->
    <link rel="stylesheet" href="../css/slicknav.min.css">
    <!-- swiper.min.css -->
    <link rel="stylesheet" href="../css/swiper.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- modernizr css -->
    <script src="../js/vendor/modernizr-2.8.3.min.js"></script>
    <style type="text/css">
        .table td, .table th{
            border: none;
        }
    </style>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!--Start Preloader-->
    <div class="preloader-wrap">
        <div class="spinner"></div>
    </div>
    <!-- header-area start -->
    <!-- <header class="header-area header-area2" id="sticky-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="logo">
                        <a href="index.php">
                            <img src="images/log.png" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="mainmenu">
                        <ul id="navigation" class="d-flex">
                            <li class="active"><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="free.php">Free Business Cards </a></li>
                            <li><a href="#">Portfolio </a></li>
                            <li><a href="contact.php">Request Quote</a></li>
                        </ul>
                    </div>
                </div>
                 <div class="col-lg-2 col-sm-5 col-4">
                    <div class="search-wrapper">
                        <ul class="d-flex">
                        </ul>
                    </div>
                </div>
                <div class="d-block d-lg-none col-sm-1 clear col-2">
                    <div class="responsive-menu-wrap floatright"></div>
                </div>
            </div>
        </div>
    </header> -->
    <!-- header-area end -->

    <!-- product-area start -->
    <div class="product-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title section-title2 text-center">
                        <h2><?php echo $products['name'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <ul class="row">
                    <li class='col-lg-4 col-sm-12 col-xs-12'>
                        <div class='product-wrap'>
                            <a href='products/?pid={$products['id']}'>
                                <div class='product-img'>
                                    <img src='<?php echo URL."images/products/".$products['image']?>' alt=''>
                                </div>
                            </a>
                            <div class='product-content fix'>
                                <h3><a href='#'></a></h3>
                            </div>
                        </div>
                    </li>
                    <li class='col-lg-4 col-sm-12 col-xs-12 text-justify'>
                        <div class='product-wrap'>
                           <div>
                               <table class="table">
                                   <tr>
                                       <td colspan="2"><?php echo "<h5>Delivery</h5>".$products['delivery'] ?></td>
                                   </tr>
                                   <tr>
                                       <td>Size</td>
                                       <td><?php echo $products['size'] ?></td>
                                   </tr>
                                   <?php
                                    if (isset($products['discount'])) {
                                        echo "<tr>
                                               <td>Discount</td>
                                               <td>{$products['discount']}</td>
                                           </tr>";
                                    }
                                   ?>
                                   <tr>
                                       <td>Cost</td>
                                       <td><?php echo $products['cost'] ?></td>
                                   </tr>
                                   <?php
                                    if (isset($products['discount'])) {
                                       $newCost = $products['cost'] - $products['discount'];
                                        echo "<tr>
                                               <td>New Cost</td>
                                               <td>$newCost</td>
                                           </tr>";
                                    }
                                   ?>
                               </table>
                           </div>
                        </div>
                    </li>
                    <li class='col-lg-4 col-sm-12 col-xs-12 text-justify'>
                        <div class='product-wrap'>
                           <div>
                               <h5>Description</h5>
                               <p>
                                   <?php echo $products['description'] ?>
                               </p>
                           </div>
                           <div>
                               <h5>Material</h5>
                               <p>
                                   <?php echo $products['material'] ?>
                               </p>
                           </div>
                           <div>
                               <h5>Finishing</h5>
                               <p>
                                   <?php echo $products['finishing'] ?>
                               </p>
                           </div>
                        </div>
                    </li>
                </ul>
                <div class="row">
                    <form method="post" action="processor.php" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Choose your design</label>
                                    <input type="file" class="form-control" name="image[]" multiple>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Your design description</label>
                                    <textarea name="description" class="form-control" style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control" value="1" name="quantity">
                                </div>
                            </div>
                            <div class="col-sm-2" style="margin-top: 20px;">
                                <input type="hidden" class="form-control" value="<?php echo $products['cost'] ?>" name="amount">
                                <?php
                                    if (isset($products['discount'])) {
                                        echo "<input type='hidden' class='form-control' value='{$products['discount']}' name='discount'>";
                                    }
                                ?>
                                <input type="hidden" class="form-control" value="<?php echo $products['id'] ?>" name="pid">
                                <button class="btn btn-danger" type="submit" class="form-control" name="order" value="{$products['id']}">Order Now</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <form method="post" action="<?php echo URL ?>admin/my-carts/">
                            <button class="btn btn-info" type="submit" class="form-control" name="cart" value="<?php echo $products['id'] ?>">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- product-area end -->

   
    <!-- blog-area end -->
    <footer class="footer-area">
        <div class="footer-top bg-1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-widget footer-logo">
                            <img src="images/log.png" alt="">
                            <p>The Print Place is a subsidiary of The Advert Place Network Ltd.</p>
                            <ul class="socil-icon d-flex">
                                <li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="javascript:void(0);"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-widget footer-menu">
                            <h2>Quick Links</h2>
                            <ul>
                            	<li><a href="index.html">Home</a></li>
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="checkout.html">Request Quote</a></li>
                                <li><a href="free.html">Free Business Cards</a></li>
                                <li><a href="javascript:void(0)">Portfolio</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-widget footer-menu">
                            <h2>Our Products</h2>
                            <ul>
                            	<li><a href="#">Flyers</a></li>
                                <li><a href="#">Brochures</a></li>
                                <li><a href="#">Calendars</a></li>
                                <li><a href="#">Jotters</a></li>
                                <li><a href="#">Business Cards</a></li>
                                <li><a href="#">Learn More...</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="footer-widget footer-contact">
                            <h2>Contact us</h2>
                            <ul>
                                <li><i class="fa fa-map-marker"></i>12, Seidu Ajibowu Street, Off Toyin Street, Ikeja, Lagos. </li>
                                <li><i class="fa fa-phone"></i>012915715 (Free Business Cards)<span>09092345715 </span> </li>
                                <li><i class="fa fa-envelope-o"></i>wow@theprintplace.com.ng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-buttom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <p>&copy;2018 The Print Place All Right Reserved</p>
                    </div>
                    <div class="col-md-6 col-12">
                   
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Modal area start -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body d-flex">
                    <div class="product-single-content w-50">
                        <img src="images/product/product-details.jpg" alt="">
                    </div>
                    <div class="product-single-content w-50">
                        <h3>Flower Vase</h3>
                        <div class="rating-wrap fix">
                            <span class="pull-left">$219.56</span>
                            <ul class="rating pull-right">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li>(05 Customar Review)</li>
                            </ul>
                        </div>
                        <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs</p>
                        <ul class="input-style">
                            <li class="quantity cart-plus-minus">
                                <input type="text" value="1" />
                            </li>
                            <li><a href="cart.html">Add to Cart</a></li>
                        </ul>
                        <ul class="cetagory">
                            <li>Categories:</li>
                            <li><a href="#">Chair,</a></li>
                            <li><a href="#">Sitting</a></li>
                        </ul>
                        <ul class="socil-icon">
                            <li>Share :</li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- jquery latest version -->
    <script src="../js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- owl.carousel.2.0.0-beta.2.4 css -->
    <script src="../js/owl.carousel.min.js"></script>
    <!-- mouse_scroll.js -->
    <script src="../js/mouse_scroll.js"></script>
    <!-- scrollup.js -->
    <script src="../js/scrollup.js"></script>
    <!-- slicknav.js -->
    <script src="../js/slicknav.js"></script>
    <!-- jquery.zoom.min.js -->
    <script src="../js/jquery.zoom.min.js"></script>
    <!-- swiper.min.js -->
    <script src="../js/swiper.min.js"></script>
    <!-- metisMenu.min.js -->
    <script src="../js/metisMenu.min.js"></script>
    <!-- mailchimp.js -->
    <script src="../js/mailchimp.js"></script>
    <!-- jquery-ui.min.js -->
    <script src="../js/jquery-ui.min.js"></script>
    <!-- main js -->
    <script src="../js/scripts.js"></script>
</body>

</html>
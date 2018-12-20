<?php 
	require_once 'controller.php'; 
	echo $head;

    $product_id = urldecode($_GET['id']);
    $error = false;
    if (!is_numeric($product_id)) {
        $_SESSION['error'] = 'Category reference not correct';
        //header("Location: ".URL.'error/');
    }

    if (isset($product_id) && trim($product_id) === ''){
        $_SESSION['response'] = "Cannot get category to be edited";
        //header("Location: .");
        exit();
    }

    $whereData = [
        'id' => $product_id
    ];
    $products = $product->getAProduct($whereData);
    $products_name = $products['name'];
    $products_image = $products['image'];
?>
<div class="container body">
	<div class="main_container">
		<?php echo $menu; ?>
		<?php echo $mastHead; ?>

    <!-- page content -->
    <div class="right_col" role="main">
      <div class="">
        <div class="page-title">
          <div class="title_left">
            <h3><?php echo $pageName ?></h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <?php echo $response; ?>
        <div class="row">
          <div class="col-sm-6 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Products</h2>
                <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form method="post" action="processor.php?id=<?php echo $product_id ?>" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" name="name" value="<?php echo $products_name ?>">
                  </div>
                  <div class="form-group">
                    <label for="image">Category Image <small class="text-danger">Files accepted(jpg, jpeg, png)</small></label>
                    <input type="file" id="image" class="form-control" name="image[]">
                    <input type="hidden" id="tmp_image" class="form-control" name="tmp_image" value="<?php echo $products_image ?>">
                  </div>
                  <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
                  <button type="submit" class="btn btn-success">Update</button>
                </form>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <?php echo $slogan; ?>
    <!-- /footer content -->
  </div>
</div>
<?php echo $footer; ?>
<?php 
	require_once 'controller.php'; 
	echo $head;
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
        <form method="post" action="processor.php" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Add a Product</h2>
                  <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="form-group">
                    <label for="name">Choose Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-control" required>
                      <?php echo $category; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" class="form-control" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="size">Size <span class="text-danger">*</span></label>
                    <input type="text" id="size" class="form-control" name="size" required>
                  </div>
                  <div class="form-group">
                    <label for="cost">Cost <span class="text-danger">*</span></label>
                    <input type="number" id="cost" class="form-control" name="cost" required>
                  </div>
                  <div class="form-group">
                    <label for="discount">Discount</label>
                    <input type="number" id="discount" class="form-control" name="discount">
                  </div>
                  <div class="form-group">
                    <label for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                  </div>
                  <!--
                  <div class="form-group">
                    <label for="designer">Designer</label>
                    <input type="text" id="designer" class="form-control" name="designer">
                  </div>
                  <div class="form-group">
                    <label for="designFee">Design Fee</label>
                    <input type="number" id="designFee" class="form-control" name="designFee">
                  </div>
                -->
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  
                  <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="form-group">
                    <label for="finishing">Finishing <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="finishing" name="finishing" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="material">Material <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="material" name="material" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="delivery">Delivery <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="delivery" name="delivery" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="image">Product Image <span class="text-danger">*</span></label>
                    <input type="file" id="image" class="form-control" name="image[]" required>
                  </div>
                  <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="x_panel">
              <div class="x_content">
                <button type="submit" class="btn btn-success">Create</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <?php echo $slogan; ?>
    <!-- /footer content -->
  </div>
</div>
<?php echo $footer; ?>
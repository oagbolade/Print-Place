<?php 
	require_once 'controller.php'; 
  $passwordResponse = '';
  if (isset($_SESSION['password-response'])) {
   $passwordResponse = "<h3 class='alert alert-success'>".$_SESSION['password-response']."</h3>";
   unset($_SESSION['password-response']);
  }
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
        <div class="row">
          <div class="col-sm-4 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Change Password</h2>
                <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <form method="post" action="processor.php" role="form">
                  <div class="form-group">
                    <label for="oldPassword">Old Password</label>
                    <input type="password"  class="form-control" id="oldPassword" name="oldPassword">
                  </div>
                  <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password"  class="form-control" id="newPassword" name="newPassword">
                  </div>
                <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
                  <button type="submit" class="btn btn-info" name="change">Change</button>
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
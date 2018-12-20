<?php 
	require_once 'controller.php'; 
  $signupResponse = $loginResponse = $changedPwdResponse = '';
  if (isset($_SESSION['login-response'])) {
    $loginResponse = "<h3 class='alert alert-success'>".$_SESSION['login-response']."</h3>";
    unset($_SESSION['login-response']);
  }
  if (isset($_SESSION['signup-response'])) {
      $signupResponse = "<h3 class='alert alert-success'>".$_SESSION['signup-response']."</h3>";
      unset($_SESSION['signup-response']);
  }
  if (isset($_SESSION['password-changed-success'])) {
      $changedPwdResponse = "<h3 class='alert alert-success'>".$_SESSION['password-changed-success']."</h3>";
      unset($_SESSION['password-changed-success']);
  }
  if (isset($_SESSION["order-product"])) {
    $orderProduct = "<h3 class='alert alert-success'>".$_SESSION["order-product"]."</h3>";
    unset($_SESSION["order-product"]);
  }
  if (isset($_SESSION['pid'])) $pid  = $_SESSION['pid']; unset($_SESSION['pid']);
	echo $head;
?>
 
<div>
  <a class="hiddenanchor" id="signup"></a>
  <a class="hiddenanchor" id="signin"></a>

  <div class="login_wrapper">
    <div class="animate form login_form al-home-form">
      <section class="login_content">
        <?php echo $pageHeader ?>
        <?php echo $loginMsg; ?>

        <?php echo $response; ?>
        <?php echo $signupResponse; ?>
        <?php echo $loginResponse; ?>
        <?php echo $changedPwdResponse; ?>
        <?php if(isset($orderProduct)) echo $orderProduct ?>
        <form action="<?php echo htmlspecialchars("processor-login.php"); ?>" method="post">
          <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
        	<input type="hidden" name="pid" value="<?php if(isset($pid)) echo $pid; ?>" />
          <h2>Login</h2>
          <div>
            <input type="email" name="email" class="form-control" placeholder="email" required />
          </div>
          <div>
            <input type="password" name="password" class="form-control" placeholder="password" required />
          </div>
          <div>
            <button class="btn btn-default submit" type="submit" name="login">Login</button>
          </div>
          <div class="clearfix"></div>
          <div class="separator">
            <p class="change_link">
            	<a class="to_register" href="" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#forgetPasswordModal">Lost password?</a>
            </p>
            <div class="clearfix"></div>
            <br />
            <?php echo $pageFooter; ?>
          </div>
        </form>
      </section>
    </div>
    
    <div id="register" class="animate form registration_form al-home-form"></div>
  </div>

	<div class="modal fade" id="forgetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-sm" role="document">
      <div class="modal-content al-home-password-modal">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	<span aria-hidden="true">&times;</span>
        </button>
        <form action="<?php echo htmlspecialchars("processor-reset.php"); ?>" method="post">
        	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
          <div class="modal-header text-center">
          	<h4 class="modal-title w-100 font-weight-bold">Lost Password</h4>
              <p>
              	Please enter the email associated with your account on Print Shop.
              </p>
              <?php echo $passwordMsg; ?>
          </div>
          <div class="modal-body mx-3">
            <div class="md-form mb-5">
              <i class="fa fa-envelope prefix grey-text"></i>
              <input type="email" id="defaultForm-email" name="resetEmail" class="form-control validate" required="">
              <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
            </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
          	<button type="submit" class="btn btn-default">Send</button>
          </div>
        </form>
      </div>
		</div>
	</div>
	
	<div class="modal fade" id="resetRespondModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
      <div class="modal-content al-home-password-modal">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	<span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-header text-center">
        	<h4 class="modal-title w-100 font-weight-bold">Lost Password</h4>
        </div>
        <div class="modal-body mx-3">
          <div class="md-form mb-5">
            <p>
            	<?php echo $pwdResetResponse; ?>
            </p>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
        	<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
      			<span aria-hidden="true">Close</span>
      		</button>
        </div>
      </div>
		</div>
	</div>

</div>
<?php echo $footer; ?>
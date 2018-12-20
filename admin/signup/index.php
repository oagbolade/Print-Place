<?php 
    require_once 'controller.php'; 
      $signupResponse = $loginResponse = '';
    if (isset($_SESSION['signup-response'])) {
        $signupResponse = "<h3 class='alert alert-info'>".$_SESSION['signup-response']."</h3>";
        unset($_SESSION['signup-response']);
    }
    if (isset($_SESSION['login-response'])) {
        $loginResponse = "<h3 class='alert alert-success'>".$_SESSION['login-response']."</h3>";
        unset($_SESSION['login-response']);
    }
        echo $head;
    ?> 
<div>
  <a class="hiddenanchor" id="signup"></a>
  <a class="hiddenanchor" id="signin"></a>

  <div class="login_wrapper">
    <div class="animate form login_form al-home-form">
      <section class="login_content">
        <?php echo $pageHeader ?>
        Sign up
        <form method="post" class="form-horizontal" action="processor.php" role="form">
            <div class="form-group">
                <!-- <label for="fname">First Name</label> -->
                <input type="text" class="form-control" placeholder="First Name" id="fname" name="fname">
            </div>
            <div class="form-group">
                <!-- <label for="lname">Last Name</label> -->
                <input type="text"  class="form-control" placeholder="Last Name" id="lname" name="lname">
            </div>
            <div class="form-group">
                <!-- <label for="phone">Phone</label> -->
                <input type="number"  class="form-control" placeholder="Phone" id="phone" name="phone">
            </div>
            <div class="form-group">
                <!-- <label for="email">Email</label> -->
                <input type="email"  class="form-control" placeholder="Email" id="email" name="email">
            </div>
            <div class="form-group">
                <!-- <label for="password">Password</label> -->
                <input type="password"  class="form-control" placeholder="Password" id="password" name="password">
            </div>
            <div class="form-group">
                <!-- <label for="rpassword">Retype Password</label> -->
                <input type="password"  class="form-control" placeholder="Retype Password" id="rpassword" name="rpassword">
            </div>
            <div class="form-group">
                <!-- <label for="accountType">Account Type</label> -->
                <select name="accountType"  class="form-control" id="accountType">
                    <option value="">Choose account type</option>
                    <option>Individual</option>
                    <option>Business</option>
                </select>
            </div>
            <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
            <button type="submit" class="btn btn-info" name="submit">Submit</button>
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

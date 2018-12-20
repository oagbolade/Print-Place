<?php 
	require_once 'controller.php'; 
	echo $head;
	$status_parameter = null;
	if (isset($_GET['status'])){
	    $status_parameter = $_GET['status'];
    }
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
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>View Order</h2>
                <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
                <div class="col-md-6 col-xs-12">
                    <form class="form-horizontal form-label-left" method="post" action="processor.php">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Sort By:</label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="form-control" name="status_options">
                                    <option value="">Choose option</option>
                                    <option value="new">New Orders</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipping">Shipped</option>
                                    <option value="paid">Paid</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <input type="hidden" value="<?php echo $_SESSION['token'] ?>" name="token">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <button type="submit" class="btn btn-success" name="sort">Sort</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                          <div class="x_content table-responsive">
                              <table class="table table-hover">
                                  <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Customer</th>
                                      <th>Quantity</th>
                                      <th>Amount</th>
                                      <th>Status</th>
                                      <th>Order Date</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                  function verifyBoolResults($orders){
                                      if ($orders === false){
                                          return false;
                                      }
                                      return true;
                                  }

                                  $order = new Orders($product);
                                  if (isset($status_parameter)){

                                      $whereData = [
                                        'status' => $status_parameter
                                      ];
                                      $orders = $order->sortOrders($whereData);
                                      if (is_array($orders)) {
                                        switch ($orders) {
                                            case false:
                                                $orders = $order->getAllOrders();
                                                break;
                                            default:
                                                $orders = $order->sortOrders($whereData);
                                        }
                                      }
                                  }
                                  else{
                                      $orders = $order->getAllOrders();
                                  }

                                  $numbering = 1;
                                  if (is_array($orders)) {
                                   foreach ($orders as $order) { ?>
                                       <?php
                                       $where = [
                                           'id' => $order['buyer']
                                       ];

                                       $customer = $users->getUserDetails($where);
                                           ?>
                                           <tr>
                                               <th scope="row"><?php echo $numbering ?></th>
                                               <td><?php echo $customer['lname'].' '.$customer['fname'] ?></td>
                                               <td><?php echo $order['quantity']." Item(s)" ?></td>
                                               <td><?php echo $order['amount'] ?></td>
                                               <td><?php echo $order['status'] ?></td>
                                               <td><?php echo date('M d, y', strtotime($order['order_date'])); ?></td>
                                               <td>
                                                   <?php echo showForm($order['id'], $order['status']); ?>
                                               </td>
                                           </tr>
                                       <?php
                                       $numbering++;
                                   } 
                                  }
                                  ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
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
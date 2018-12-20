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
        <?php
        echo $response;
        if (isset($_SESSION['error'])){
            echo $_SESSION['error'];
        }
        ?>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>View Product</h2>
                <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                          <div class="x_content table-responsive">
                              <table class="table table-hover">
                                  <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Name</th>
                                      <th>Cost</th>
                                      <th>Discount</th>
                                      
                                      <th>Created By</th>
                                      <th>Date Created</th>
                                      <th>Edited By</th>
                                      <th>Edit Date</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                  $products = $product->getAllProducts();
                                  $numbering = 1;
                                  foreach ($products as $product) { ?>
                                      <?php
                                      $where = [
                                          'id' => $product['added_by']
                                      ];

                                      $created_by = $users->getUserDetails($where);
                                      $where = [
                                          'id' => $product['edited_by']
                                      ];
                                      $changed_by = $users->getUserDetails($where);
                                          ?>
                                          <tr>
                                              <th scope="row"><?php echo $numbering ?></th>
                                              <td><?php echo $product['name'] ?></td>
                                              <td><?php echo $product['cost'] ?></td>
                                              <td><?php echo $product['discount'] ?></td>
                                              
                                              <td><?php echo (empty($created_by['email']) && trim($created_by['email']) == '')? "<span class=\"label label-info\">Unedited</span>": $created_by['email'] ?></td>
                                              <td><?php echo date('M d, y', strtotime($product['added_date'])); ?></td>
                                              <td><?php echo (empty($changed_by['email']) && trim($changed_by['email']) == '')? "<span class=\"label label-info\">Unedited</span>": $changed_by['email'] ?></td>
                                              <td><?php echo (empty($product['edited_date']) && $product['edited_date'] == '')? "<span class=\"label label-info\">Not Available</span>": date('M d, y', strtotime($product['edited_date'])); ?></td>
                                              <td>
                                                  <a href="../edit-product/index.php?id=<?php echo urlencode($product['id'])?>"><button type="button" class="btn btn-success btn-xs">Edit</button></a>
                                                  <a href="processor.php?id=<?php echo urlencode($product['id'])?>"><button type="button" class="btn btn-danger btn-xs">Delete</button></a>
                                              </td>
                                          </tr>
                                      <?php
                                      $numbering++;
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
<?php 
  require_once 'controller.php'; 
  echo $head;
?>
<style type="text/css">
  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    border:none;
  }
</style>
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
          <div class="col-md-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Application Fee <small>online payment using bank card (ATM)</small></h2>
                <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                  <li>
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <p class="text-center">
                 <div class="row">
                   <div class="col-sm-6">
                     PAID
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
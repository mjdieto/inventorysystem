<?php
  $page_title = 'Monthly Sales';
  require_once('includes/load.php');
  // Check what level user has permission to view this page
  page_require_level(3);
?>

<style>
  .panel-heading {
    background-color: #4682B4 !important;
    padding: 15px;
    color: white !important;
  }
</style>

<?php
  $year = date('Y');
  $sales = monthlySales($year);

  // Initialize an array to group products by name
  $grouped_sales = [];

  foreach ($sales as $sale) {
      $product_name = remove_junk($sale['name']);

      if (!isset($grouped_sales[$product_name])) {
          // Initialize product entry
          $grouped_sales[$product_name] = [
              'name' => $product_name,
              'qty' => (int)$sale['qty'], // Ensure integer
              'total_saleing_price' => (float)$sale['total_saleing_price'], // Ensure float
              'date' => $sale['date'] // Store latest sale date
          ];
      } else {
          // If product exists, sum up its quantity and total price
          $grouped_sales[$product_name]['qty'] += (int)$sale['qty'];
          $grouped_sales[$product_name]['total_saleing_price'] += (float)$sale['total_saleing_price'];

          // Update latest sale date if the new sale is more recent
          if (strtotime($sale['date']) > strtotime($grouped_sales[$product_name]['date'])) {
              $grouped_sales[$product_name]['date'] = $sale['date'];
          }
      }
  }

  // Initialize total quantity variable
  $total_quantity = array_sum(array_column($grouped_sales, 'qty'));
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Monthly Sales</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product Name </th>
              <th class="text-center" style="width: 15%;"> Total Quantity Sold </th>
              <th class="text-center" style="width: 15%;"> Total Sales </th>
              <th class="text-center" style="width: 15%;"> Latest Sale Date </th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $count = 1;
              foreach ($grouped_sales as $sale):
            ?>
            <tr>
              <td class="text-center"><?php echo $count++; ?></td>
              <td><?php echo $sale['name']; ?></td>
              <td class="text-center"><?php echo $sale['qty']; ?></td>
              <td class="text-center"><?php echo number_format($sale['total_saleing_price'], 2); ?></td>
              <td class="text-center"><?php echo $sale['date']; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2" class="text-right">Total Quantity Sold:</th>
              <th class="text-center"><?php echo $total_quantity; ?></th>
              <th colspan="2"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

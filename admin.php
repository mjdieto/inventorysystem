<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);
?>

<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 $products_sold   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_sale_added('5');
?>

<?php include_once('layouts/header.php'); ?>

<style>
  /* Panel Heading - Red */
  .panel-heading {
    background-color: #b22222 !important;
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }
  
  /* Panel Boxes */
  .panel-box {
    background-color: #f8d7da; /* Light red background */
    border: 1px solid #b22222;
    border-radius: 5px;
    padding: 10px;
  }

  /* Panel Icons */
  .panel-icon {
    background-color: #b22222 !important;
    color: white !important;
    padding: 10px;
    border-radius: 50%;
  }

  /* Link Text Color */
  a {
    color: black;
    text-decoration: none;
  }

  /* Sales Currency Icon */
  .sales-icon {
    font-size: 50px;
    color: white;
  }

  /* Buttons */
  .btn-danger {
    background-color: #b22222;
    border-color: #b22222;
  }
  .btn-danger:hover {
    background-color: #8b0000;
    border-color: #8b0000;
  }
</style>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<!-- Dashboard Panels -->
<div class="row">
  <a href="users.php">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_user['total']; ?> </h2>
          <p class="text-muted">Users</p>
        </div>
       </div>
    </div>
  </a>
  
  <a href="categorie.php">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left">
          <i class="glyphicon glyphicon-th-large"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Categories</p>
        </div>
       </div>
    </div>
  </a>
  
  <a href="product.php">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left">
          <i class="glyphicon glyphicon-shopping-cart"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_product['total']; ?> </h2>
          <p class="text-muted">Products</p>
        </div>
       </div>
    </div>
  </a>
  
  <a href="sales.php">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left">
          <span class="sales-icon">&#8369;</span>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php echo $c_sale['total']; ?></h2>
          <p class="text-muted">Sales</p>
        </div>
       </div>
    </div>
  </a>
</div>

<!-- Highest Selling Products -->
<div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Highest Selling Products</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered">
          <thead>
           <tr>
             <th>Title</th>
             <th>Total Sold</th>
             <th>Total Quantity</th>
           </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php echo (int)$product_sold['totalSold']; ?></td>
                <td><?php echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
         </table>
       </div>
     </div>
   </div>

   <!-- Latest Sales -->
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Latest Sales</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered">
       <thead>
         <tr>
           <th>#</th>
           <th>Product Name</th>
           <th>Date</th>
           <th>Total Sale</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_sales as $recent_sale): ?>
         <tr>
           <td><?php echo count_id();?></td>
           <td>
            <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>">
             <?php echo remove_junk(first_character($recent_sale['name'])); ?>
            </a>
           </td>
           <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
           <td>&#8369; <?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
        </tr>
       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>

  <!-- Recently Added Products -->
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recently Added Products</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <?php foreach ($recent_products as $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$recent_product['id'];?>">
                <h4 class="list-group-item-heading">
                 <?php if($recent_product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image'];?>" alt="">
                <?php endif;?>
                <?php echo remove_junk(first_character($recent_product['name']));?>
                  <span class="label label-warning pull-right">
                  &#8369; <?php echo (int)$recent_product['sale_price']; ?>
                  </span>
                </h4>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

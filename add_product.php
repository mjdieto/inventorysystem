<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>

<style>
  .panel-heading {
    background-color: #b22222 !important;
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }
  .panel-body {
    background-color: white; /* Light red background */
    padding: 15px;
    border-radius: 5px;
  }
  .btn-danger {
    background-color: #b22222;
    border-color: #b22222;
  }
  .btn-danger:hover {
    background-color: #8b0000;
    border-color: #8b0000;
  }
  .input-group-addon {
    background-color: #b22222 !important;
    color: white !important; /* White icon color */
    border: 1px solid #b22222;
  }
  .form-control {
    border: 1px solid #b22222;
  }
  .glyphicon {
    color: white !important; /* Makes all icons white */
  }
</style>

<?php
 if(isset($_POST['add_product'])){
   date_default_timezone_set('Asia/Manila');
   $date = date('Y-m-d H:i:s');

   $req_fields = array('product-title','product-categorie','product-Location','product-quantity','buying-price', 'saleing-price');
   validate_fields($req_fields);
   
   if(empty($errors)){
     $p_name     = remove_junk($db->escape($_POST['product-title']));
     $p_cat      = remove_junk($db->escape($_POST['product-categorie']));
     $p_Location = remove_junk($db->escape($_POST['product-Location']));
     $p_qty      = remove_junk($db->escape($_POST['product-quantity']));
     $p_buy      = remove_junk($db->escape($_POST['buying-price']));
     $p_sale     = remove_junk($db->escape($_POST['saleing-price']));

     $media_id = empty($_POST['product-photo']) ? '0' : remove_junk($db->escape($_POST['product-photo']));

     // Check if product already exists
     $query_check = "SELECT * FROM products WHERE name = '{$p_name}'";
     $result = $db->query($query_check);
     
     if($result && $db->num_rows($result) > 0) {
       $existing_product = $db->fetch_assoc($result);
       $new_quantity = $existing_product['quantity'] + $p_qty;

       $query_update = "UPDATE products SET quantity = '{$new_quantity}', Location = '{$p_Location}', date = '{$date}' WHERE id = '{$existing_product['id']}'";
       if($db->query($query_update)){
         $session->msg('s',"Product updated.");
         redirect('product.php', false);
       } else {
         $session->msg('d','Failed to update product!');
         redirect('product.php', false);
       }
     } else {
       $query_insert = "INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, media_id, Location, date) ";
       $query_insert .= "VALUES ('{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$p_Location}', '{$date}')";

       if($db->query($query_insert)){
         $session->msg('s',"New product added.");
         redirect('product.php', false);
       } else {
         $session->msg('d','Failed to add product!');
         redirect('product.php', false);
       }
     }
   } else {
     $session->msg("d", $errors);
     redirect('add_product.php', false);
   }
 }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Product</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Product Title">
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" name="product-categorie">
                    <option value="">Select Product Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <select class="form-control" name="product-photo">
                    <option value="">Select Product Photo</option>
                    <?php foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>"><?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-map-marker"></i>
                </span>
                <input type="text" class="form-control" name="product-Location" placeholder="Product Location">
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <span style='font-size:15px;'>&#8369;</span>
                    </span>
                    <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <span style='font-size:15px;'>&#8369;</span>
                    </span>
                    <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" name="add_product" class="btn btn-danger">Add Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

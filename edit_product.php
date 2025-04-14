<?php
$page_title = 'Edit Product';
require_once('includes/load.php');
page_require_level(2);

// Create stock_movements table if not exists (Run this once)
/* CREATE TABLE stock_movements (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    movement_type ENUM('in', 'out') NOT NULL,
    quantity INT(11) NOT NULL,
    date DATETIME NOT NULL
); */

$product = find_by_id('products', (int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');

if (!$product) {
    $session->msg("d", "Missing product id.");
    redirect('product.php');
}

if (isset($_POST['product'])) {
    $req_fields = array('product-title', 'product-categorie', 'buying-price', 'saleing-price', 'product-Location');
    validate_fields($req_fields);

    if (empty($errors)) {
        $in_qty  = (int)$_POST['in_quantity'];
        $out_qty = (int)$_POST['out_quantity'];
        $current_qty = (int)$product['quantity'];
        
        // Calculate new quantity
        $new_qty = $current_qty + $in_qty - $out_qty;

        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = (int)$_POST['product-categorie'];
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
        $p_Location = remove_junk($db->escape($_POST['product-Location']));
        $media_id = (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") ? '0' : remove_junk($db->escape($_POST['product-photo']));

        $query   = "UPDATE products SET 
            name ='{$p_name}', 
            quantity ='{$new_qty}', 
            buy_price ='{$p_buy}', 
            sale_price ='{$p_sale}', 
            categorie_id ='{$p_cat}', 
            media_id='{$media_id}', 
            location='{$p_Location}' 
            WHERE id ='{$product['id']}'";

        // Handle stock movements
        if ($in_qty > 0) {
            $db->query("INSERT INTO stock_movements (product_id, movement_type, quantity, date) 
                        VALUES ('{$product['id']}', 'in', '{$in_qty}', NOW())");
        }
        if ($out_qty > 0) {
            $db->query("INSERT INTO stock_movements (product_id, movement_type, quantity, date) 
                        VALUES ('{$product['id']}', 'out', '{$out_qty}', NOW())");
        }

        $result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Product updated successfully.");
            redirect('product.php', false);
        } else {
            $session->msg('d', ' Sorry, failed to update the product!');
            redirect('edit_product.php?id=' . $product['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_product.php?id=' . $product['id'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<style>
  .panel-heading {
    background-color: #b22222 !important;
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }
  .panel-body {
    background-color: white;
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
    color: white !important;
    border: 1px solid #b22222;
  }
  .form-control {
    border: 1px solid #b22222;
  }
  .glyphicon {
    color: white !important;
  }
  .stock-adjustment .input-group-addon {
    background-color: #4CAF50 !important;
    border-color: #4CAF50;
  }
  .stock-out .input-group-addon {
    background-color: #f44336 !important;
    border-color: #f44336;
  }
</style>

<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Edit Product</strong>
        <a href="stock_card_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info pull-right">Stock Card</a>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" class="clearfix">
          <div class="form-group">
            <label for="product-title">Product Title</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
              <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']); ?>">
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <label for="product-categorie">Category</label>
                <select class="form-control" name="product-categorie">
                  <option value="">Select a category</option>
                  <?php foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?>>
                      <?php echo remove_junk($cat['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="product-photo">Product Photo</label>
                <select class="form-control" name="product-photo">
                  <option value="">No image</option>
                  <?php foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int)$photo['id']; ?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?>>
                      <?php echo $photo['file_name']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="product-Location">Product Location</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
              <input type="text" class="form-control" name="product-Location" value="<?php echo remove_junk($product['location']); ?>">
            </div>
          </div>

          <!-- Stock Adjustment Section -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label>Current Stock</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
                  <input type="number" class="form-control" value="<?php echo (int)$product['quantity']; ?>" readonly>
                </div>
              </div>
              <div class="col-md-4 stock-adjustment">
                <label for="in_quantity">Stock In</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-arrow-down"></i></span>
                  <input type="number" class="form-control" name="in_quantity" min="0" value="0">
                </div>
              </div>
              <div class="col-md-4 stock-out">
                <label for="out_quantity">Stock Out</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-arrow-up"></i></span>
                  <input type="number" class="form-control" name="out_quantity" min="0" value="0">
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="buying-price">Buying Price</label>
                <div class="input-group">
                  <span class="input-group-addon">₱</span>
                  <input type="number" class="form-control" name="buying-price" value="<?php echo remove_junk($product['buy_price']); ?>">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>
              <div class="col-md-4">
                <label for="saleing-price">Selling Price</label>
                <div class="input-group">
                  <span class="input-group-addon">₱</span>
                  <input type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['sale_price']); ?>">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" name="product" class="btn btn-danger">Update Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
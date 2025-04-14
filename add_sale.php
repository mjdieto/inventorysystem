<?php
  $page_title = 'Add Sale';
  require_once('includes/load.php');
  page_require_level(3);
?>

<style>
  /* Panel Heading */
  .panel-heading {
    background-color: #b22222 !important; /* Dark red */
    padding: 15px;
    color: white !important;
  }

  /* Table Styling */
  .table thead {
    background: #b22222; /* Dark red */
    color: white;
  }

  .table thead th {
    text-align: center;
    border: 1px solid #b22222;
    padding: 12px;
  }

  .table tbody tr td {
    vertical-align: middle;
    border-bottom: 1px solid #ddd;
    padding: 12px;
    text-align: center;
  }

  /* Search Bar */
  #sug_input {
    width: 100%;
    padding: 8px;
    border: 2px solid #b22222; /* Red border */
    border-radius: 4px;
    font-size: 14px;
  }

  #sug_input:focus {
    border-color: #8b1a1a; /* Darker red on focus */
    outline: none;
  }

  /* Find It Button */
  .btn-custom {
    background: #b22222; /* Dark red */
    color: white;
    border-radius: 5px;
    border: none;
    padding: 8px 12px;
  }

  .btn-custom:hover {
    background: #8b1a1a; /* Darker red */
    color: white !important;
  }
</style>

<?php
  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);
    if(empty($errors)){
      $p_id    = $db->escape((int)$_POST['s_id']);
      $s_qty   = $db->escape((int)$_POST['quantity']);
      $s_total = $db->escape($_POST['total']);
      $date    = $db->escape($_POST['date']);
      $s_date  = make_date();

      $sql  = "INSERT INTO sales (product_id, qty, price, date) ";
      $sql .= "VALUES ('{$p_id}','{$s_qty}','{$s_total}','{$s_date}')";

      if($db->query($sql)){
        update_product_qty($s_qty, $p_id);
        $session->msg('s',"Sale added.");
        redirect('sales.php', false);
      } else {
        $session->msg('d','Sorry, failed to add!');
        redirect('add_sale.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_sale.php', false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
      <div class="form-group">
        <div class="input-group">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-custom">Find It</button>
          </span>
          <input type="text" id="sug_input" class="form-control" name="title" placeholder="Search for product name">
        </div>
        <div id="result" class="list-group"></div>
      </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th> Item </th>
              <th> Price </th>
              <th> Qty </th>
              <th> Total </th>
              <th> Date </th>
              <th> Action </th>
            </thead>
            <tbody id="product_info"></tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

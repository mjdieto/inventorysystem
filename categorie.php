<?php
  $page_title = 'All Categories';
  require_once('includes/load.php');

  // Check user permission level
  page_require_level(1);
  
  $all_categories = find_all('categories');
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

  /* Button Styling */
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

  /* Edit Button Styling (Light Blue) */
  .btn-edit {
      background: rgb(60, 149, 223) !important; /* Light Blue */
      color: white !important;
      border-radius: 5px;
      border: none;
      padding: 5px 10px;
      font-weight: bold;
  }

  .btn-edit:hover {
      background: rgb(40, 120, 190) !important; /* Slightly Darker Blue */
      color: white !important;
  }
</style>

<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('categorie-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO categories (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Category");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
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
  <!-- Add New Category Panel -->
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Category</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="categorie.php">
          <div class="form-group">
              <input type="text" class="form-control" name="categorie-name" placeholder="Category Name">
          </div>
          <button type="submit" name="add_cat" class="btn btn-custom">Add Category</button>
      </form>
      </div>
    </div>
  </div>

  <!-- All Categories Panel -->
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Categories</span>
       </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
          <thead>
              <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Categories</th>
                  <th class="text-center" style="width: 100px;">Actions</th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $cat):?>
              <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <!-- Updated Edit Button Color -->
                      <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-edit" data-toggle="tooltip" title="Edit">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>
                    </div>
                  </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
     </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

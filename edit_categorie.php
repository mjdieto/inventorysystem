<?php
  $page_title = 'Edit Category';
  require_once('includes/load.php');
  page_require_level(1);

  $categorie = find_by_id('categories', (int)$_GET['id']);
  if (!$categorie) {
    $session->msg("d", "Missing category id.");
    redirect('categorie.php');
  }
?>

<style>
  .panel-heading {
    background-color: #b22222 !important;
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }
  .panel-body {
    background-color: #f8d7da; /* Light red background */
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
  .form-control {
    border: 1px solid #b22222;
  }
</style>

<?php
if (isset($_POST['edit_cat'])) {
  $req_field = array('categorie-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  
  if (empty($errors)) {
    $sql = "UPDATE categories SET name='{$cat_name}' WHERE id='{$categorie['id']}'";
    $result = $db->query($sql);

    if ($result && $db->affected_rows() === 1) {
      $session->msg("s", "Successfully updated Category");
      redirect('categorie.php', false);
    } else {
      $session->msg("d", "Sorry! Failed to Update");
      redirect('categorie.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('categorie.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Editing <?php echo remove_junk(ucfirst($categorie['name'])); ?></strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id']; ?>">
          <div class="form-group">
            <label for="categorie-name">Category Name</label>
            <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name'])); ?>">
          </div>
          <button type="submit" name="edit_cat" class="btn btn-danger">Update Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

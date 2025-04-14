<?php
  $page_title = 'Add Group';
  require_once('includes/load.php');
  page_require_level(1);
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
    color: white !important;
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
  if(isset($_POST['add'])){
   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);

   if(find_by_groupName($_POST['group-name']) === false ){
     $session->msg('d','<b>Sorry!</b> Entered Group Name already in database!');
     redirect('add_group.php', false);
   } elseif(find_by_groupLevel($_POST['group-level']) === false) {
     $session->msg('d','<b>Sorry!</b> Entered Group Level already in database!');
     redirect('add_group.php', false);
   }

   if(empty($errors)){
       $name = remove_junk($db->escape($_POST['group-name']));
       $level = remove_junk($db->escape($_POST['group-level']));
       $status = remove_junk($db->escape($_POST['status']));

       $query  = "INSERT INTO user_groups (group_name, group_level, group_status) ";
       $query .= "VALUES ('{$name}', '{$level}', '{$status}')";
       
       if($db->query($query)){
          $session->msg('s',"Group has been created!");
          redirect('add_group.php', false);
       } else {
          $session->msg('d','Sorry, failed to create Group!');
          redirect('add_group.php', false);
       }
   } else {
       $session->msg("d", $errors);
       redirect('add_group.php',false);
   }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New User Group</span>
        </strong>
      </div>
      <div class="panel-body">
        <?php echo display_msg($msg); ?>
        <form method="post" action="add_group.php" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Group Name</label>
            <input type="text" class="form-control" name="group-name">
          </div>
          <div class="form-group">
            <label for="level" class="control-label">Group Level</label>
            <input type="number" class="form-control" name="group-level">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
              <option value="1">Active</option>
              <option value="0">Deactive</option>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="add" class="btn btn-danger">Add Group</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

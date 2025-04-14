<?php
  $page_title = 'Edit User';
  require_once('includes/load.php');
  page_require_level(1);
?>

<?php
  $e_user = find_by_id('users', (int)$_GET['id']);
  $groups = find_all('user_groups');
  if (!$e_user) {
    $session->msg("d", "Missing user ID.");
    redirect('users.php');
  }
?>

<?php
// Update User basic info
if (isset($_POST['update'])) {
  $req_fields = array('name', 'username', 'level');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id'];
    $name = remove_junk($db->escape($_POST['name']));
    $username = remove_junk($db->escape($_POST['username']));
    $level = (int)$db->escape($_POST['level']);
    $status = remove_junk($db->escape($_POST['status']));
    
    $sql = "UPDATE users SET name='{$name}', username='{$username}', user_level='{$level}', status='{$status}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Account Updated");
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    } else {
      $session->msg('d', 'Sorry, failed to update!');
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id=' . (int)$e_user['id'], false);
  }
}

// Update user password
if (isset($_POST['update-pass'])) {
  $req_fields = array('password');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id'];
    $password = remove_junk($db->escape($_POST['password']));
    $h_pass = sha1($password);
    
    $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "User password has been updated");
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    } else {
      $session->msg('d', 'Sorry, failed to update user password!');
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user.php?id=' . (int)$e_user['id'], false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<style>
  /* Panel Headers */
  .panel-heading {
    background-color: #b22222 !important; 
    color: white !important;
    padding: 15px;
    font-weight: bold;
  }

  /* Form Inputs */
  .form-control {
    border: 2px solidrgb(63, 59, 59);
    border-radius: 5px;
    padding: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  }

  /* Labels */
  .form-group label {
    font-weight: bold;
    color:rgb(53, 50, 50);
  }

  /* Dropdown Styling */
  select.form-control {
    background-color: white;
    color:rgb(75, 71, 71);
    font-weight: bold;
  }

  /* Buttons */
  .btn-info {
    background-color: #b22222;
    border: none;
    font-weight: bold;
    padding: 10px;
    width: 100%;
    border-radius: 5px;
  }

  .btn-info:hover {
    background-color: #8b1a1a;
  }

  .btn-danger {
    background-color: #8b0000;
    border: none;
    font-weight: bold;
    padding: 10px;
    width: 100%;
    border-radius: 5px;
  }

  .btn-danger:hover {
    background-color: #660000;
  }
</style>

<div class="row">
  <div class="col-md-12"> 
    <?php echo display_msg($msg); ?> 
  </div>

  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="glyphicon glyphicon-th"></span> Update <?php echo remove_junk(ucwords($e_user['name'])); ?> Account
      </div>
      <div class="panel-body">
        <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($e_user['name'])); ?>">
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>">
          </div>
          <div class="form-group">
            <label for="level">User Role</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group): ?>
                <option <?php if ($group['group_level'] === $e_user['user_level']) echo 'selected="selected"'; ?> value="<?php echo $group['group_level']; ?>">
                  <?php echo ucwords($group['group_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
              <option <?php if ($e_user['status'] === '1') echo 'selected="selected"'; ?> value="1">Active</option>
              <option <?php if ($e_user['status'] === '0') echo 'selected="selected"'; ?> value="0">Deactive</option>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Change password form -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="glyphicon glyphicon-th"></span> Change <?php echo remove_junk(ucwords($e_user['name'])); ?> Password
      </div>
      <div class="panel-body">
        <form action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" method="post" class="clearfix">
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Type user new password">
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update-pass" class="btn btn-danger">Change</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

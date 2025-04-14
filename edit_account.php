<?php
  $page_title = 'Edit Account';
  require_once('includes/load.php');
  page_require_level(3);
?>

<style>
  /* Panel Styling */
  .panel-heading {
    background-color: #b22222 !important; /* Dark red */
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }

  /* Profile Image Styling */
  .profile-pic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #b22222;
    display: block;
    margin: 10px auto;
  }

  /* File Input Styling */
  .file-upload-container {
    position: relative;
    text-align: center;
    cursor: pointer;
  }

  .btn-file {
    width: 100%;
    display: inline-block;
    padding: 10px;
    background-color: white;
    border: 2px solid #b22222;
    color: #b22222;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
  }

  .btn-file:hover {
    background-color: #b22222;
    color: white;
  }

  /* Hide the default file input but keep it functional */
  .file-upload-container input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    cursor: pointer;
  }

  /* Form Input Styling */
  .form-control {
    border: 2px solid #b22222;
    border-radius: 5px;
  }

  /* Button Styling */
  .btn-warning {
    background-color: #b22222;
    border: none;
  }

  .btn-warning:hover {
    background-color: #8b1a1a;
  }

  .btn-info {
    background-color: #b22222;
    border: none;
  }

  .btn-info:hover {
    background-color: #8b1a1a;
  }

  .btn-danger {
    background-color: #8b1a1a;
    border: none;
  }

  .btn-danger:hover {
    background-color: #5a0e0e;
  }
</style>

<?php
// Update profile photo
if (isset($_POST['submit'])) {
  $photo = new Media();
  $user_id = (int)$_POST['user_id'];
  $photo->upload($_FILES['file_upload']);
  if ($photo->process_user($user_id)) {
    $session->msg('s', 'Photo has been uploaded.');
    redirect('edit_account.php');
  } else {
    $session->msg('d', join($photo->errors));
    redirect('edit_account.php');
  }
}

// Update user details
if (isset($_POST['update'])) {
  $req_fields = array('name', 'username');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$_SESSION['user_id'];
    $name = remove_junk($db->escape($_POST['name']));
    $username = remove_junk($db->escape($_POST['username']));
    $sql = "UPDATE users SET name='{$name}', username='{$username}' WHERE id='{$id}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Account updated");
      redirect('edit_account.php', false);
    } else {
      $session->msg('d', 'Failed to update!');
      redirect('edit_account.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_account.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>

  <!-- Profile Image Update Section -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Change My Photo</span>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <img class="profile-pic" src="uploads/users/<?php echo $user['image']; ?>" alt="">
          </div>
          <div class="col-md-8">
            <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data">
              <div class="form-group file-upload-container">
                <label class="btn-file">
                  Click here to change photo ←
                  <input type="file" name="file_upload" multiple="multiple" />
                </label>
              </div>
              <div class="form-group">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <button type="submit" name="submit" class="btn btn-warning">Change</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Account Details Section -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Edit My Account</span>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
          </div>
          <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($user['username'])); ?>">
          </div>
          <div class="form-group clearfix">
            <a href="change_password.php" title="Change password" class="btn btn-danger pull-right">Change Password</a>
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

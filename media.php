<?php
  $page_title = 'All Images';
  require_once('includes/load.php');
  page_require_level(2);
?>

<style>
  /* Panel Header */
  .panel-heading {
    background-color: #b22222 !important;
    padding: 15px;
    color: white !important;
    font-weight: bold;
  }

  /* Panel Body */
  .panel-body {
    background-color: #f8d7da; /* Light red background */
    padding: 15px;
    border-radius: 5px;
  }

  /* Table Styling */
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  .table th, .table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #b22222; /* Red border */
  }
  .table thead {
    background: #b22222; /* Dark red */
    color: white;
  }

  /* Upload Button */
  .btn-primary {
    background-color: #b22222;
    border-color: #b22222;
  }
  .btn-primary:hover {
    background-color: #8b1a1a;
    border-color: #8b1a1a;
  }

  /* Delete Button */
  .btn-danger {
    background-color: #8b0000;
    border-color: #8b0000;
  }
  .btn-danger:hover {
    background-color: #600000;
    border-color: #600000;
  }

  /* Custom File Upload Button */
  .custom-file-upload {
    display: inline-block;
    padding: 12px 20px;
    background-color: #b22222;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    border: 2px solid #b22222;
    transition: background 0.3s, color 0.3s;
  }

  .custom-file-upload:hover {
    background-color: white;
    color: #b22222;
  }

  .custom-file-upload i {
    margin-right: 8px;
  }

  /* Hide default file input */
  #file_upload {
    display: none;
  }

  /* Image Styling */
  .img-thumbnail {
    width: 100px;
    height: 100px;
    object-fit: cover;
    padding: 2px;
    border: 1px solid #b22222;
  }
</style>

<?php 
  $media_files = find_all('media');

  if (isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    if ($photo->process_media()) {
      $session->msg('s', 'Photo has been uploaded.');
      redirect('media.php');
    } else {
      $session->msg('d', join($photo->errors));
      redirect('media.php');
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>All Photos</span>
        <div class="pull-right">
          <!-- File Upload Form -->
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="file_upload" class="custom-file-upload">
                <i class="glyphicon glyphicon-upload"></i> Click to Upload
              </label>
              <input type="file" name="file_upload" id="file_upload" multiple="multiple" />
              <button type="submit" name="submit" class="btn btn-primary">Upload</button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center">Photo</th>
              <th class="text-center">Photo Name</th>
              <th class="text-center">Photo Type</th>
              <th class="text-center" style="width: 50px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td class="text-center">
                  <img src="uploads/products/<?php echo $media_file['file_name']; ?>" class="img-thumbnail"/>
                </td>
                <td class="text-center"><?php echo $media_file['file_name']; ?></td>
                <td class="text-center"><?php echo $media_file['file_type']; ?></td>
                <td class="text-center">
                  <a href="delete_media.php?id=<?php echo (int) $media_file['id']; ?>" class="btn btn-danger btn-xs" title="Delete">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
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

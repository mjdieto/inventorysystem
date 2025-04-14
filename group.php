<?php
  $page_title = 'All Group';
  require_once('includes/load.php');

  // Check user permission level
  page_require_level(1);

  // Fetch all groups from the database
  $all_groups = find_all('user_groups');
?>

<style>
  /* General Page Styling */
  body {
      background-color: #f8d7da; /* Light red background */
      font-family: 'Arial', sans-serif;
  }

  /* Panel Heading Styling */
  .panel-heading {
      background-color: #b22222 !important; /* Dark red */
      padding: 15px;
      color: white !important;
  }

  /* Table Styling */
  .table {
      background: white;
      border-radius: 5px;
      overflow: hidden;
  }

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

  .table tfoot {
      background: #f4c2c2; /* Light red */
      font-weight: bold;
  }

  /* Add New Group Button Styling */
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

  /* Edit Button Styling */
  .btn-edit {
      background:rgb(60, 149, 223) !important; 
      color: white !important;
      border-radius: 5px;
      border: none;
      padding: 5px 10px;
      font-weight: bold;
  }
</style>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Groups</span>
        </strong>
        <a href="add_group.php" class="btn btn-custom pull-right btn-sm">Add New Group</a>
      </div>
      
     <div class="panel-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>Group Name</th>
            <th class="text-center" style="width: 20%;">Group Level</th>
            <th class="text-center" style="width: 15%;">Status</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_groups as $a_group): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_group['group_level']))?></td>
           <td class="text-center">
             <?php if($a_group['group_status'] === '1'): ?>
               <span class="label label-success"><?php echo "Active"; ?></span>
             <?php else: ?>
               <span class="label label-danger"><?php echo "Deactive"; ?></span>
             <?php endif;?>
           </td>
           <td class="text-center">
             <div class="btn-group">
                <!-- Updated Edit Button with New Color -->
                <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-edit" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
             </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
       <tfoot>
          <tr>
              <td colspan="5" class="text-center">End of Group List</td>
          </tr>
       </tfoot>
     </table>
     </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

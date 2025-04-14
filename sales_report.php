<?php
$page_title = 'Sale Report';
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

  /* Date Range Input */
  .datepicker {
    border: 2px solid #b22222; /* Red border */
    border-radius: 4px;
    padding: 8px;
    font-size: 14px;
  }

  .datepicker:focus {
    border-color: #8b1a1a; /* Darker red on focus */
    outline: none;
  }

  /* Generate Report Button */
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

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-file"></span>
          <span>Sale Report</span>
        </strong>
      </div>
      <div class="panel-body">
        <form class="clearfix" method="post" action="sale_report_process.php">
          <div class="form-group">
            <label class="form-label">Date Range</label>
            <div class="input-group">
              <input type="text" class="datepicker form-control" name="start-date" placeholder="From">
              <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
              <input type="text" class="datepicker form-control" name="end-date" placeholder="To">
            </div>
          </div>
          <div class="form-group">
            <button type="submit" name="submit" class="btn btn-custom">Generate Report</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

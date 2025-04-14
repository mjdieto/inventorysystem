<?php
  $page_title = 'My Profile';
  require_once('includes/load.php');
  page_require_level(3);
  
  $user_id = (int)$_GET['id'];
  if(empty($user_id)):
    redirect('home.php', false);
  else:
    $user_p = find_by_id('users', $user_id);
  endif;
?>
<?php include_once('layouts/header.php'); ?>

<style>
  /* Profile Card */
  .profile-card {
    background-color: #b22222; /* Dark red */
    color: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
  }

  .profile-card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 10px;
  }

  .profile-card h3 {
    font-weight: bold;
  }

  .nav-pills > li > a {
    background-color: white !important;
    color: #b22222 !important;
    border-radius: 5px;
    font-weight: bold;
  }

  .nav-pills > li > a:hover {
    background-color: #8b1a1a !important;
    color: white !important;
  }
</style>

<div class="row">
  <div class="col-md-4">
    <div class="profile-card">
      <img class="img-circle" src="uploads/users/<?php echo $user_p['image'];?>" alt="">
      <h3><?php echo first_character($user_p['name']); ?></h3>
      <?php if($user_p['id'] === $user['id']): ?>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="edit_account.php"><i class="glyphicon glyphicon-edit"></i> Edit Profile</a></li>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

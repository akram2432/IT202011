<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$username = "";
$email = "";
$message = "";
if (isset($_GET["user_id"]) && !empty($_GET["user_id"])) {
  $user_id = $_GET["user_id"];
} else if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}
// if (isset($_SESSION['username'])) {
$query = "SELECT * FROM users WHERE id = '{$user_id}'";
$select_user_query = mysqli_query($connection, $query);

if (!$select_user_query) {

  die("<h5 style='text-align: center;'>Something went wrong</h5>");
}

while ($row = mysqli_fetch_array($select_user_query)) {
  $username = $row['username'];
  $email = $row['email'];
  $user_id = $row["id"];
  $is_public = $row["is_public"];
}

// } else {
//   $message = "User not logged in";
// }

?>

<div class="container">
  <div class="row">
    <div class="col-xs-6 col-xs-offset-3">
      <h1>User Profile</h1>
      <h4 class="text-center"><?php echo $message; ?></h4>
      <h4><span class="font-weight-bold">Username: </span><span><?php echo htmlspecialchars($username); ?> </span></h3>
        <?php if ($is_public == 1) { ?><h4><span class="font-weight-bold">Email:
          </span><span><?php echo htmlspecialchars($email); ?> </span></h3><?php } ?>
          <?php if ($user_id == $_SESSION["user_id"]) { ?> <button class="btn btn-default"><a
              href="edit-profile.php">Edit Profile</a></button>
          <button class="btn btn-default"><a href="reset-password.php">Reset Password</a></button> <?php } ?>
    </div>
  </div>
</div>
</div>

<?php include "includes/footer.php"; ?>
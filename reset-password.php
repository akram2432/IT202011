<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$old_password = "";
$new_password = "";
$confirm_password = "";
$message = "";
if (isset($_SESSION['username'])) {

  if (isset($_POST['reset-password'])) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($old_password) && !empty($new_password) && !empty($confirm_password)) {

      $old_password = mysqli_real_escape_string($connection, $old_password);
      $new_password = mysqli_real_escape_string($connection, $new_password);
      $confirm_password = mysqli_real_escape_string($connection, $confirm_password);

      if (!password_verify($old_password, $_SESSION["password"])) {
        $message = "Old password is wrong";
      } else {
        if (strlen($new_password) < 6) {
          $message = "Password should have atleast 6 characters";
        } else if ($new_password != $confirm_password) {
          $message = "New Password and Confirm Password does not match";
        } else {
          $hash_password =
            password_hash(
              $new_password,
              PASSWORD_DEFAULT
            );
          $update = "UPDATE users SET `password` = '{$hash_password}' WHERE id = {$_SESSION["user_id"]}";
          $update_query = mysqli_query($connection, $update);

          if (!$update_query) {
            $message = "Some went wrong again. Try again later";
          } else {
            $_SESSION["password"] = $hash_password;
            header("Location: profile.php");
          }
        }
      }
    } else {
      $message = "Fields cannot be empty";
    }
  } else {
    $query = "SELECT * FROM users WHERE username = '{$_SESSION['username']}'";
    $select_user_query = mysqli_query($connection, $query);

    if (!$select_user_query) {

      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

    while ($row = mysqli_fetch_array($select_user_query)) {
      $username = $row['username'];
      $email = $row['email'];
    }
  }
} else {
  $message = "User not logged in";
}

?>

<div class="container">
  <div class="row">
    <div class="col-xs-6 col-xs-offset-3">
      <div class="form-wrap">
        <h1>Edit Profile</h1>
        <form role="form" action="reset-password.php" method="post" id="login-form" autocomplete="off">
          <h5 class="text-center"><?php echo $message; ?></h5>
          <div class="form-group">
            <label for="old_password" class="sr-only">Old Password</label>
            <input type="password" name="old_password" id="old_password" class="form-control"
              value="<?php echo htmlspecialchars($old_password); ?>" placeholder="Enter Old Password">
          </div>
          <div class="form-group">
            <label for="new_password" class="sr-only">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control"
              value="<?php echo htmlspecialchars($new_password); ?>" placeholder="Enter New Password">
          </div>
          <div class="form-group">
            <label for="confirm_password" class="sr-only">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
              value="<?php echo htmlspecialchars($confirm_password); ?>" placeholder="Enter New Password">
          </div>

          <input type="submit" name="reset-password" class="btn btn-custom btn-lg btn-block" value="Reset Password">
        </form>

      </div>
    </div>
  </div>
</div>
</div>

<?php include "includes/footer.php"; ?>
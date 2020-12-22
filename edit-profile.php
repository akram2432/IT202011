<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$username = "";
$email = "";
$message = "";
if (isset($_SESSION['username'])) {

  if (isset($_POST['edit-profile'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($email)) {

      $username = mysqli_real_escape_string($connection, $username);
      $email = mysqli_real_escape_string($connection, $email);

      $query = "SELECT * FROM users WHERE username = '{$username}' and username != '{$_SESSION['username']}'";
      $check_username_query = mysqli_query($connection, $query);

      if (!$check_username_query) {

        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }

      if (mysqli_num_rows($check_username_query)) {
        $message = "Username already taken";
      } else {
        $check_email =
          "SELECT * FROM users WHERE email = '{$email}' and email != '{$_SESSION['email']}'";
        $check_email_query = mysqli_query($connection, $check_email);

        if (mysqli_num_rows($check_email_query)) {
          $message = "Email already taken";
        } else {
          $update = "UPDATE users SET email = '{$email}', username = '{$username}' WHERE id = {$_SESSION['user_id']}";
          $update_query = mysqli_query($connection, $update);
          if (!$update_query) {

            die("<h5 style='text-align: center;'>Something went wrong</h5>");
          }
          $_SESSION['username'] = $username;
          $_SESSION['email'] = $email;
          header("Location: profile.php");
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
        <form role="form" action="edit-profile.php" method="post" id="login-form" autocomplete="off">
          <h5 class="text-center"><?php echo $message; ?></h5>
          <div class="form-group">
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" id="username" class="form-control"
              value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter username">
          </div>
          <div class="form-group">
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" class="form-control"
              value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter email">
          </div>

          <input type="submit" name="edit-profile" class="btn btn-custom btn-lg btn-block" value="Edit Profile">
        </form>

      </div>
    </div>
  </div>
</div>
</div>

<?php include "includes/footer.php"; ?>
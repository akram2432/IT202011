<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$username = "";
$email = "";
$password = "";
$confirm_password = "";
$profile_public = 1;

if (isset($_POST['submit'])) {

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $profile_public = $_POST["profile_public"];

  if (
    !empty($username) && !empty($email) && !empty($password) &&
    !empty($confirm_password) && !empty($profile_public)
  ) {

    if ($password != $confirm_password) {
      $message = "Password and Confirm Password did not match";
    } else if (strlen($password) < 6) {
      $message = "Password should have atleast 6 characters";
    } else {

      $username = mysqli_real_escape_string($connection, $username);
      $email = mysqli_real_escape_string($connection, $email);
      $password = mysqli_real_escape_string($connection, $password);


      $check_username =
        "SELECT * FROM users WHERE username = '{$username}'";
      $check_username_query = mysqli_query($connection, $check_username);

      if (mysqli_num_rows($check_username_query)) {
        $message = "Username already taken";
      } else {
        $check_email =
          "SELECT * FROM users WHERE email = '{$email}'";
        $check_email_query = mysqli_query($connection, $check_email);

        if (mysqli_num_rows($check_email_query)) {
          $message = "Email already taken";
        } else {

          $hash_password =
            password_hash(
              $password,
              PASSWORD_DEFAULT
            );

          $query = "INSERT INTO users (username, email, `password`, is_public) ";
          $query .= "VALUES('{$username}','{$email}','{$hash_password}', {$profile_public})";
          $register_user_query = mysqli_query($connection, $query);

          if (!$register_user_query) {

            die("<h5 style='text-align: center;'>Something went wrong</h5>");
          }

          $select_query = "SELECT * FROM users where username = '{$username}' and email = '{$email}'";
          $select_user_query = mysqli_query($connection, $select_query);
          if (!$select_user_query) {

            die("<h5 style='text-align: center;'>Something went wrong</h5>");
          }

          while ($row = mysqli_fetch_array($select_user_query)) {

            $db_id = $row['id'];
            $db_username = $row['username'];
            $db_email = $row['email'];
          }

          $role_query = "INSERT INTO userroles (`user_id`, role_id, is_active) ";
          $role_query .= "VALUES({$db_id} , 2, 1)";
          $role_user_query = mysqli_query($connection, $role_query);

          if (!$role_user_query) {

            die("<h5 style='text-align: center;'>Something went wrong</h5>");
          }


          $message = "Your Registration Is Successful";
          $username = "";
          $email = "";
          $password = "";
          $confirm_password = "";

          header("Location: login.php");
        }
      }
    }
  } else {

    $message = "Fields Cannnot Be Empty";
  }
} else {

  $message = "";
}

?>

<!-- Page Content -->
<div class="container">

  <section id="login">
    <div class="container">
      <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
          <div class="form-wrap">
            <h1>Register</h1>
            <form role="form" action="register.php" method="post" id="login-form" autocomplete="off">

              <h5 class="text-center"><?php echo $message; ?></h5>
              <div class="form-group">
                <label for="username" class="sr-only">username</label>
                <input type="text" name="username" id="username" class="form-control"
                  value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter Username">
              </div>
              <div class="form-group">
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                  value="<?php echo htmlspecialchars($email); ?>" placeholder="somebody@example.com">
              </div>
              <div class="form-group">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="key" class="form-control"
                  value="<?php echo htmlspecialchars($password); ?>" placeholder="Password">
              </div>
              <div class="form-group">
                <label for="confirm_password" class="sr-only">Confirm Password</label>
                <input type="password" name="confirm_password" id="key" class="form-control"
                  value="<?php echo htmlspecialchars($confirm_password); ?>" placeholder="Confirm Password">
              </div>

              <div class="form-group">
                <label for="category" class="sr-only">Profile Public</label>
                <select class="form-control form-control-lg" name="profile_public">
                  <option value="">Profile Public</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>

              <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block"
                value="Register">
            </form>

          </div>
        </div> <!-- /.col-xs-12 -->
      </div> <!-- /.row -->
    </div> <!-- /.container -->
  </section>


  <?php include "includes/footer.php"; ?>
<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<!-- <?php session_start(); ?> -->

<?php

$message = "";

if (isset($_POST['login'])) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!empty($username) && !empty($password)) {

    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE username = '{$username}' or email = '{$username}'";
    $select_user_query = mysqli_query($connection, $query);

    if (!$select_user_query) {

      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

    while ($row = mysqli_fetch_array($select_user_query)) {

      $db_id = $row['id'];
      $db_username = $row['username'];
      $db_email = $row['email'];
      $db_user_password = $row['password'];
    }

    $get_role = "SELECT * FROM userroles WHERE user_id = {$db_id}";
    $get_role_query = mysqli_query($connection, $get_role);
    if (!$get_role_query) {

      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }

    while ($row = mysqli_fetch_array($get_role_query)) {
      $db_user_role = $row['role_id'];
    }

    // $password = crypt($password, $db_user_password);

    if (password_verify($password, $db_user_password)) {
      $_SESSION['user_id'] = $db_id;
      $_SESSION['username'] = $db_username;
      $_SESSION['email'] = $db_email;
      $_SESSION['password'] = $db_user_password;
      $_SESSION['user_role'] = $db_user_role;

      header("Location: index.php");
    } else {
      $message = "Fields cannot be empty";
    }
  } else {

    $message = "Email or password is wrong";
  }
}



?>

<div class="container">

  <section id="login">
    <div class="container">
      <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
          <div class="form-wrap">
            <h1>Login</h1>
            <form role="form" action="login.php" method="post" id="login-form" autocomplete="off">
              <h5 class="text-center"><?php echo $message; ?></h5>
              <div class="form-group">
                <label for="username" class="sr-only">Username or Email</label>
                <input type="text" name="username" id="username" class="form-control"
                  placeholder="Enter username or emai">
              </div>
              <div class="form-group">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="key" class="form-control" placeholder="Password">
              </div>

              <input type="submit" name="login" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Login">
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>


  <?php include "includes/footer.php"; ?>
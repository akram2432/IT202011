<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$message = "";

$select = "SELECT * FROM products WHERE id = {$_GET['product_id']}";
$select_query = mysqli_query($connection, $select);
if (!$select_query) {
  die("<h5 style='text-align: center;'>Something went wrong</h5>");
}
while ($row = mysqli_fetch_assoc($select_query)) {
  $id = $row["id"];
  $name = $row["name"];
  $quantity = $row["quantity"];
  $price = $row["price"];
  $description = $row["description"];
  $user_id = $row["user_id"];
  $category = $row["category"];
  $visibility = $row["visibility"];
}

if (isset($_POST["add-to-cart"])) {
  $product_id = $_POST["product_id"];
  $user_id = $_SESSION["user_id"];
  $price = $_POST["price"];
  $quantity = $_POST["quantity"];

  $select = "SELECT * FROM cart WHERE product_id = {$product_id} and `user_id` = {$user_id}";
  $select_query = mysqli_query($connection, $select);
  if (!$select_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  if (mysqli_num_rows($select_query)) {
    $row = mysqli_fetch_assoc($select_query);
    $quantity += $row["quantity"];
    $update = "UPDATE cart SET quantity = {$quantity} WHERE id = {$row['id']}";
    $update_query = mysqli_query($connection, $update);

    if (!$update_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
  } else {
    $insert = "INSERT INTO cart (product_id, quantity, `user_id`, price) ";
    $insert .= "VALUES ({$product_id}, {$quantity}, {$user_id}, {$price})";
    $insert_query = mysqli_query($connection, $insert);

    if (!$insert_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
  }

  $message = "Item added to cart successfully";
}

?>

<div class="container">
  <section id="product_detail">
    <div class="container">
      <h5 class="text-center"><?php echo $message; ?></h5>
      <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
          <div class="form-wrap">
            <h1>Product Detail</h1>
            <h3 class="card-title"><?php echo $name ?></h3>
            <h5 class="card-subtitle mb-2 text-muted"><?php echo $category ?></h5>
            <h4>$ <?php echo $price ?></h4>
            <p class="card-text"><span style="font-weight: bold;">Description:</span> <?php echo $description ?></p>

            <form style="display:inline-block;" role="form"
              action="<?php echo $base_url . '/product_details.php?product_id=' . $id; ?>" method="post" id="login-form"
              autocomplete="off">

              <p>Quantity</p>
              <div class="form-group">
                <input style="width:7rem;" type="number" min="1" max="<?php echo $quantity ?>" value="1" name="quantity"
                  class="form-control" placeholder="Quantity">
              </div>
              <?php if (isset($_SESSION["username"])) { ?>
              <input type='hidden' name='product_id' value="<?php echo $id ?>" />
              <input type='hidden' name='price' value="<?php echo $price ?>" />
              <input type="submit" name="add-to-cart" class="btn btn-primary" value="Add to Cart">
              <?php } else { ?><a href="<?php echo $base_url . '/login.php'; ?>" class="btn btn-primary">Login to
                buy</a><?php } ?>
            </form>
            <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
            ?><a style="display:inline-block;"
              href="<?php echo $base_url . '/admin/edit_product.php?product_id=' . $id; ?>" class="btn btn-primary">Edit
              Product</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <hr />

  <div>
    <section class="content-item">

      <div class="container">
        <h5 class="text-center"><?php echo $message; ?></h5>
        <div class="span6" style="float: none; margin: '0 auto';">
          <?php

          if (isset($_POST["save-rating"])) {
            $review = $_POST["review"];
            $rating = $_POST["rating"];
            $user_id = $_SESSION["user_id"];
            $product_id = $id;

            $give_rating = "INSERT INTO ratings(product_id, `user_id`, rating, comment)";
            $give_rating .= " VALUES({$product_id}, {$user_id}, {$rating}, '{$review}')";
            $give_rating_query = mysqli_query($connection, $give_rating);

            if (!$give_rating_query) {
              // die("<h5 style='text-align: center;'>Something went wrong</h5>");
              die(mysqli_error($connection));
            }
          }

          if (isset($_SESSION["username"])) { ?>
          <div>
            <form action="<?php echo $base_url . '/product_details.php?product_id=' . $id; ?>" method="post">
              <h3 class="">Rate and Review Product</h3>
              <div class="form-group">
                <h4 style="display:inline-block; font-weight: bold;">Rating: </h4>
                <select class="form-control form-control-lg" name="rating" style="display:inline-block; width:7rem;">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
              <div class="form-group">

                <h4 style="font-weight: bold;">Review</h4>
                <fieldset>
                  <div class="row">
                    <textarea class="form-control" id="review" name="review" placeholder="Your Review"
                      required=""></textarea>
                  </div>
                </fieldset>

              </div>
              <input type="submit" name="save-rating" class="btn btn-primary" value="Submit">
            </form>
          </div>

          <?php } else { ?>
          <h3 style="margin-top: 9%;margin-bottom: 7%;">Login or Signup to give a review</h3>

          <?php } ?>

          <h3>User Reviews</h3>
          <?php

          $get_ratings = "SELECT u.id as u_id, u.username as username, r.rating as rating, r.comment as review, r.created as created
           FROM ratings as r
          INNER JOIN users as u ON u.id = r.user_id WHERE r.product_id = {$_GET['product_id']}";
          $get_ratings_query = mysqli_query($connection, $get_ratings);

          if (!$get_ratings_query) {
            die("<h5 style='text-align: center;'>Something went wrong</h5>");
          }

          while ($row = mysqli_fetch_assoc($get_ratings_query)) {
            $username = $row["username"];
            $rating = $row["rating"];
            $review = $row["review"];
            $created = $row["created"];
            $u_id = $row["u_id"];

          ?>

          <div class="media">
            <div class="media-body">
              <h4 class="media-heading"><a
                  href="<?php echo $base_url . '/profile.php?user_id=' . $u_id; ?>"><?php echo $username ?></a></h4>
              <p><span style="font-weight: bold;">Rating: </span><?php echo $rating; ?></p>
              <p><?php echo $review; ?></p>
              <ul class="list-unstyled list-inline media-detail pull-left">
                <li><span style="font-weight: bold;">Date: </span> <?php echo $created; ?></li>
              </ul>
            </div>
          </div>

          <?php } ?>
        </div>
      </div>
    </section>
  </div>


</div>
<? include "includes/footer.php" ?>
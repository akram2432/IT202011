<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
$cart = "SELECT DISTINCT p.name as name, p.category as category, p.id as product_id, c.quantity as quantity, p.quantity as p_quantity, c.price as price, c.id as id
       FROM cart as c INNER JOIN products as p ON c.product_id = p.id where c.user_id = {$_SESSION["user_id"]}";
$cart_query = mysqli_query($connection, $cart);
if (!$cart_query) {
  die("<h5 style='text-align: center;'>Something went wrong</h5>");
}

if (mysqli_num_rows($cart_query) === 0) {
  die("<h3 style='text-align: center;'>No Items in Cart</h3>");
} ?>


<?php
if (!isset($_SESSION["message"])) {
  $_SESSION["message"] = "";
}

if (isset($_POST["remove-item"])) {
  $id = $_POST["cart_id"];
  $user_id = $_SESSION["user_id"];

  $delete = "DELETE FROM cart WHERE id = {$id} and `user_id` = {$user_id}";
  $delete_query = mysqli_query($connection, $delete);

  if (!$delete_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  $_SESSION["message"] = "Item removed from cart successfully";
  echo "<script type='text/javascript'>alert('$message');</script>";
  header("Location: cart.php");
}

if (isset($_POST["remove-all-item"])) {
  $user_id = $_SESSION["user_id"];

  $delete = "DELETE FROM cart WHERE `user_id` = {$user_id}";
  $delete_query = mysqli_query($connection, $delete);

  if (!$delete_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  $_SESSION["message"] = "All cart items removed successfully";
  echo "<script type='text/javascript'>alert({$_SESSION['message']});</script>";
  header("Location: cart.php");
}

if (isset($_POST["update-quantity"])) {
  $new_quantity = $_POST["new_quantity"];
  $cart_id = $_POST["cart_id"];
  $user_id = $_SESSION["user_id"];
  echo $new_quantity;
  if ($new_quantity == 0) {
    $delete = "DELETE FROM cart WHERE id = {$cart_id} and `user_id` = {$user_id}";
    $delete_query = mysqli_query($connection, $delete);

    if (!$delete_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
    header("Location: cart.php");
  } else {
    $update_quantity = "UPDATE cart SET quantity = {$new_quantity} WHERE id = {$cart_id}";
    $update_quantity_query = mysqli_query($connection, $update_quantity);

    if (!$update_quantity_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
    $_SESSION["message"] = "Quantity updated successfully";
    // echo "<script type='text/javascript'>alert('$message');</script>";
    header("Location: cart.php");
  }
}

if (isset($_POST["order"])) {
  $get_pro = "SELECT DISTINCT p.name as name, p.category as category, p.id as product_id, c.quantity as quantity, p.quantity as p_quantity, c.price as price, c.id as id
       FROM cart as c INNER JOIN products as p ON c.product_id = p.id where c.user_id = {$_SESSION["user_id"]}";
  $get_pro_query = mysqli_query($connection, $get_pro);

  if (!$get_pro_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  $flag = false;
  while ($row = mysqli_fetch_assoc($get_pro_query)) {
    if ($row["p_quantity"] == 0) {
      $flag = true;
      $_SESSION["message"] = "{$row['name']} is out of stock.";
      header("Location: cart.php");
    }
    if ($row["p_quantity"] < $row["quantity"]) {
      $flag = true;
      $_SESSION["message"] = "{$row['name']} has less available quantity than added in the cart. Available quantity is {$row['p_quantity']}. Please update quantity and try again.";
      header("Location: cart.php");
    }
  }

  if (!$flag) {
    header("Location: order.php");
  }
}

?>

<div class="container">
  <div class="row">
    <h5 class="text-center"><?php echo $_SESSION["message"]; ?></h5>
    <div class="col-6 col-md-6" style="border-right: 1px solid #ccc; text-align: center;">
      <h3>Cart Items</h3>
      <form name="cart_remove" action="cart.php" method="POST">
        <input type="submit" name="remove-all-item" class="btn btn-danger" value="Remove All Cart Items">
      </form>
      <hr />
      <?php
      $total_cart_value = 0;
      $total_item = 0;
      while ($row = mysqli_fetch_assoc($cart_query)) {
        $name = $row["name"];
        $category = $row["category"];
        $quantity = $row["quantity"];
        $price = $row["price"];
        $id = $row["id"];
        $product_id = $row["product_id"];
        $p_quantity = $row["p_quantity"];
        $total_price = $quantity * $price;

        $total_cart_value += $total_price;
        $total_item += 1;
      ?>
      <div class="card" style="width: 54rem; text-align: center;">
        <div class="card-body">
          <a href="<?php echo $base_url . '/product_details.php?product_id=' . $product_id; ?>"
            style="color: inherit; text-decoration: none;">

            <h5 class="card-title"><?php echo $name ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?php echo $category ?></h6>
          </a>
          <p style="display:inline-block; font-weight: bold;">Quantity: </p>

          <form name="cart_form" action="cart.php" method="POST">
            <div class="form-group" style="display:inline-block;">
              <input style="width:7rem;" type="number" min="0" max="" value="<?php echo $quantity ?>"
                name="new_quantity" class="form-control" placeholder="Quantity">
            </div>

            <p class="card-text"><span style="font-weight: bold;">Total Price: </span>$ <?php echo $total_price; ?>
            </p>
            <input type='hidden' name='cart_id' value="<?php echo $id; ?>" />
            <input type='hidden' name='quantity' value="<?php echo $quantity; ?>" />
            <input type="submit" name="update-quantity" class="btn btn-primary" value="Update Quantity">
            <input type="submit" name="remove-item" class="btn btn-danger" value="Remove Item">
          </form>

        </div>
      </div>


      <hr />


      <?php } ?>

    </div>
    <div class="col-6 col-md-6" style="text-align: center;">
      <h3>Cart Total</h3>
      <hr />
      <h5><span style="font-weight: bold;">Total Items in Cart: </span> <?php echo $total_item ?></h5>
      <h5><span style="font-weight: bold;">Total Cart Value: </span>$ <?php echo $total_cart_value ?></h5>
      <form name="cart_form" action="cart.php" method="POST">
        <input type="submit" name="order" class="btn btn-primary" value="Proceed to Checkout">
      </form>
    </div>
  </div>
</div>

<? include "includes/footer.php" ?>
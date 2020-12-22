<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php

$message = "";

if (isset($_POST["place-order"])) {
  $payment_method = $_POST["payment_method"];
  $address_line1 = $_POST["address1"];
  $address_line2 = $_POST["address2"];

  if (empty($payment_method) || empty($address_line1) || empty($address_line2)) {
    $message = "Fields cannot be empty";
  } else {
    $address = $address_line1 . " " . $address_line2;
    $user_id = $_SESSION["user_id"];
    $get_cart_details = "SELECT DISTINCT p.name as name, p.category as category, p.id as product_id, c.quantity as quantity, p.quantity as p_quantity, c.price as price, c.id as id
       FROM cart as c INNER JOIN products as p ON c.product_id = p.id where c.user_id = {$user_id}";
    $get_cart_details_query = mysqli_query($connection, $get_cart_details);
    if (!$get_cart_details) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
      // die(mysqli_error($connection));
    }

    $array_cart_items = array();
    $total_cart_value = 0;
    while ($row = mysqli_fetch_assoc($get_cart_details_query)) {
      array_push(
        $array_cart_items,
        array(
          "product_id" => $row['product_id'],
          "quantity" => $row['quantity'],
          "price" => $row['price'],
          "p_quantity" => $row["p_quantity"]
        )
      );
      $quantity = $row["quantity"];
      $price = $row["price"];
      $total_price = $quantity * $price;
      $total_cart_value += $total_price;
    }
    var_dump($array_cart_items);

    $insert_order = "INSERT INTO orders (`user_id`, total_price, `address`, payment_method) ";
    $insert_order .= "VALUES({$user_id}, {$total_cart_value}, '{$address}', '{$payment_method}')";
    $insert_order_query = mysqli_query($connection, $insert_order);
    if (!$insert_order_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
      // die(mysqli_error($connection));
    }
    $order_id = $connection->insert_id;

    $order_item = "INSERT INTO orderitems(order_id, product_id, quantity, unit_price) VALUES";

    foreach ($array_cart_items as $row) {
      $p_id = $row["product_id"];
      $quantity = $row["quantity"];
      $price = $row["price"];

      $order_item .= " ({$order_id}, {$p_id}, {$quantity}, {$price}),";
    }
    $order_item = substr($order_item, 0, strlen($order_item) - 1);
    echo $order_item;
    $order_item_query = mysqli_query($connection, $order_item);

    if (!$order_item_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
      // echo mysqli_error($connection);
    }

    foreach ($array_cart_items as $row) {
      $q = $row['p_quantity'] - $row['quantity'];
      $update = "UPDATE products SET quantity = {$q} WHERE id = {$row['product_id']}";
      $update_query = mysqli_query($connection, $update);

      if (!$update_query) {
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }
    }

    $delete = "DELETE FROM cart WHERE user_id = {$user_id}";
    $delete_query = mysqli_query($connection, $delete);

    if (!$delete_query) {
      die("<h5 style='text-align: center;'>Something went wrong</h5>");
    }
    header("Location: order_successful.php");
  }
}

?>

<section id="order">
  <div class="container">
    <div class="row">
      <div class="col-xs-6 col-xs-offset-3">
        <div class="form-wrap">
          <h1>Enter below details to complete order</h1>
          <form role="form" action="order.php" method="post" autocomplete="off">

            <h5 class="text-center"><?php echo $message; ?></h5>
            <div class="form-group">
              <label for="payment_method" class="sr-only">Select Payment Method</label>
              <select class="form-control form-control-lg" name="payment_method">
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="Amex">Amex</option>
              </select>
            </div>

            <div class="form-group">
              <label for="address1" class="sr-only">Address Line 1</label>
              <input type="text" name="address1" id="address1" class="form-control" value="<?php ?>"
                placeholder="* Address Line 1">
            </div>
            <div class="form-group">
              <label for="address2" class="sr-only">Address Line 2</label>
              <input type="text" name="address2" id="address2" class="form-control" value="<?php ?>"
                placeholder="* Address Line 2">
            </div>

            <input type="submit" name="place-order" class="btn btn-custom btn-lg btn-block" value="Place Order">
          </form>

        </div>
      </div> <!-- /.col-xs-12 -->
    </div> <!-- /.row -->
  </div> <!-- /.container -->
</section>

<? include "includes/footer.php" ?>
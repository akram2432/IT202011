<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<div class="container" style="text-align: center;">
  <h3>Purchase History</h3>
  <hr />
  <?php

  $per_page = 3;

  if (isset($_GET['page'])) {

    $page = $_GET['page'];
  } else {

    $page = "";
  }

  if ($page == "" || $page == 1) {

    $page_1 = 0;
  } else {

    $page_1 = ($page * $per_page) - $per_page;
  }

  $message = "No items purchased yet";
  if ($_SESSION["user_role"] == 1) {
    $purchase_history = "SELECT DISTINCT oi.quantity as oi_quantity, oi.unit_price as price,
     oi.product_id as product_id, oi.id as oi_id, p.name as name, p.category as category,
     o.created as placed_on
    FROM orderitems as oi
    INNER JOIN orders as o ON oi.order_id = o.id
    INNER JOIN products as p ON oi.product_id = p.id ORDER BY o.created DESC";
  } else {
    $purchase_history = "SELECT DISTINCT oi.quantity as oi_quantity, oi.unit_price as price,
     oi.product_id as product_id, oi.id as oi_id, p.name as name, p.category as category
    FROM orderitems as oi
    INNER JOIN orders as o ON oi.order_id = o.id
    INNER JOIN products as p ON oi.product_id = p.id WHERE o.user_id = {$_SESSION['user_id']} ORDER BY o.created DESC";
  }

  $find_query = mysqli_query($connection, $purchase_history);
  $count = mysqli_num_rows($find_query);
  $count = ceil($count / 3);

  $new_query = $purchase_history . " LIMIT $page_1, $per_page";

  $purchase_history_query = mysqli_query($connection, $new_query);
  if (!$purchase_history_query) {
    die("<h5 style='text-align: center;'>Something went wrong</h5>");
  }

  if (mysqli_num_rows($purchase_history_query) < 1) {
    die("<h5 style='text-align: center;'>No items purchased yet</h5>");
  }



  while ($row = mysqli_fetch_assoc($purchase_history_query)) {
    $name = $row["name"];
    $quantity = $row["oi_quantity"];
    $price = $row["price"];
    $total_price = $price * $quantity;
    $category = $row["category"];
    $product_id = $row["product_id"];
    $placed_on = $row["placed_on"];

  ?>
  <a href="<?php echo $base_url . '/product_details.php?product_id=' . $product_id; ?>"
    style="color: inherit; text-decoration: none;">

    <div class="card" style="width: 54rem; margin-left: 30rem;">
      <div class="card-body">

        <h5 class="card-title"><?php echo $name ?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?php echo $category ?></h6>

        <p style="display:inline-block;"><span style="font-weight: bold;">Quantity:
          </span><?php echo $quantity ?>
        </p>

        <p class="card-text"><span style="font-weight: bold;">Total Price: </span>$ <?php echo $total_price; ?>
        <p class="card-text"><span style="font-weight: bold;">Placed On: </span> <?php echo $placed_on; ?>
      </div>
    </div>
  </a>
  <hr />
  <?php } ?>

</div>

<ul class="pager">

  <?php

  for ($i = 1; $i <= $count; $i++) {

    if ($i == $page) {

      echo "<li><a class='active_link' href='index.php?page=$i'>$i</a></li>";
    } else {

      echo "<li><a href='purchase_history.php?page=$i'>$i</a></li>";
    }
  }

  ?>

</ul>

<? include "includes/footer.php" ?>
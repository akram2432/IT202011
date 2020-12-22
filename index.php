<?php include "includes/db.php"; ?>

<?php include "includes/header.php"; ?>

<?php
$category = "";
$search = "";
$sort_by = "";
$message = "";

if (isset($_POST["filter"])) {
  $category = $_POST["category"];
  $search = $_POST["search"];
  $sort_by = $_POST["sort"];
}

if (isset($_POST["add-to-cart"])) {
  $product_id = $_POST["product_id"];
  $user_id = $_SESSION["user_id"];
  $price = $_POST["price"];
  $quantity = 1;

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


<!-- Page Content -->
<div class="container">
  <div class="row">
    <h4 class="text-center"><?php echo $message; ?></h4>
    <form role="form" action="index.php" method="post" id="login-form" autocomplete="off">
      <div class="col-6 col-md-3">
        <div class="form-group">
          <label for="category" class="sr-only">Categories</label>
          <select class="form-control form-control-lg" name="category">
            <option value="">Category</option>
            <option value="Electronics">Electronics</option>
            <option value="Fashion">Fashion</option>
            <option value="Home">Home</option>
            <option value="Beauty">Beauty</option>
            <option value="Health">Health</option>
            <option value="Books">Books</option>
            <option value="Sports">Sports</option>
            <option value="Fitness">Fitness</option>
            <option value="Grocery">Grocery</option>
          </select>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="form-group">
          <label for="search" class="sr-only">Search Word</label>
          <input type="text" name="search" id="search" class="form-control" value="" placeholder="Enter Search Word">
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="form-group">
          <label for="sort" class="sr-only">Sort by Price</label>
          <select class="form-control form-control-lg" name="sort">
            <option value="">Sort By Price</option>
            <option value="asc">Low to High</option>
            <option value="desc">High to Low</option>
          </select>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <input type="submit" name="filter" id="btn-login" class="btn btn-custom btn-block" value="Filter">
      </div>
    </form>
  </div>

  <br /><br /><br />
  <div>
    <div class="row">

      <?php
      $per_page = 4;

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

      if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
        $query = "SELECT * FROM products";
      } else {
        $query = "SELECT * FROM products WHERE visibility = 1";
      }

      if (!empty($category)) {
        if (strpos($query, 'WHERE') !== false) {
          $query .= " and category = '{$category}'";
        } else {
          $query .= " WHERE category = '{$category}'";
        }
      }

      if (!empty($search)) {
        if (strpos($query, 'WHERE') !== false) {
          $query .= " and name LIKE '%{$search}%'";
        } else {
          $query .= " WHERE name LIKE '%{$search}%'";
        }
      }

      if (!empty($sort_by)) {
        $query .= " order by price {$sort_by}";
      } else {
        $query .= " order by created desc";
      }

      $pproduct_query_count = "SELECT * FROM products";
      $find_query = mysqli_query($connection, $pproduct_query_count);
      $count = mysqli_num_rows($find_query);
      $count = ceil($count / 4);

      $query .= " LIMIT $page_1, $per_page";


      $get_products = mysqli_query($connection, $query);
      if (!$get_products) {
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }

      while ($row = mysqli_fetch_assoc($get_products)) {
        $id = $row["id"];
        $name = $row["name"];
        $quantity = $row["quantity"];
        $price = $row["price"];
        $description = $row["description"];
        $user_id = $row["user_id"];
        $category = $row["category"];
        $visibility = $row["visibility"];

      ?>
      <div class="col-6 col-md-3">
        <a href="<?php echo $base_url . '/product_details.php?product_id=' . $id; ?>"
          style="color: inherit; text-decoration: none;">
          <div class="card"
            style="width: 25rem; height: 18rem; padding-top:2px; background-color:lightgrey; text-align: center; border-radius: 15px; margin-top: 10px">
            <div class="card-body">
              <h4 class="card-title"><?php echo $name ?></h5>
                <h5 class="card-subtitle mb-2 text-muted"><?php echo $category ?></h5>
                <p class="card-text">
                  <?php if (strlen($description) > 20) {
                      echo substr($description, 0, 20) . "...";
                    } else {
                      echo $description;
                    } ?>
                </p>
                <p class="card-text">Price: $<?php echo $price ?></p>
                <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
                  ?><a style="display:inline-block;"
                  href="<?php echo $base_url . '/admin/edit_product.php?product_id=' . $id; ?>"
                  class="btn btn-primary">Edit
                  Product</a>
                <?php } ?>
                <?php if (isset($_SESSION["username"])) { ?><form style="display:inline-block;" name="cart_form"
                  action="index.php" method="POST">
                  <input type='hidden' name='product_id' value="<?php echo $id ?>" />
                  <input type='hidden' name='price' value="<?php echo $price ?>" />
                  <input type="submit" name="add-to-cart" class="btn btn-primary" value="Add to Cart">
                </form><?php } else { ?><a href="<?php echo $base_url . '/login.php'; ?>" class="btn btn-primary">Login
                  to
                  buy</a><?php } ?>
            </div>
          </div>
        </a>
      </div>
      <?php } ?>

    </div>
  </div>

</div>


</div>

<ul class="pager">

  <?php

  for ($i = 1; $i <= $count; $i++) {

    if ($i == $page) {

      echo "<li><a class='active_link' href='index.php?page=$i'>$i</a></li>";
    } else {

      echo "<li><a href='index.php?page=$i'>$i</a></li>";
    }
  }

  ?>

</ul>

<?php include "includes/footer.php"; ?>
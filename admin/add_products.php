<?php include "../includes/db.php"; ?>
<?php include "../includes/header.php"; ?>

<?php
$product_name = "";
$product_description = "";
$category = "";
$quantity = "";
$price = "";
$visibility = "";
$message = "";

if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
  if (isset($_POST["add-product"])) {
    $product_name = $_POST["product_name"];
    $product_description = $_POST["description"];
    $category = $_POST["category"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $visibility = $_POST["visibility"];
    $user_id = $_SESSION["user_id"];

    if (
      empty($product_name) || empty($product_description) || empty($category) ||
      empty($quantity) || empty($price)
    ) {
      $message = "* Fields cannot be empty";
    } else {
      $insert_query = "INSERT INTO products (`name`, quantity, price, `description`, `user_id`, category, visibility) ";
      $insert_query .= "VALUES ('{$product_name}', ${quantity}, {$price}, '{$product_description}', {$user_id}, '{$category}', {$visibility})";
      $insert_product = mysqli_query($connection, $insert_query);

      if (!$insert_product) {
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }

      $message = "Product added successfully";
      $product_name = "";
      $product_description = "";
      $category = "";
      $quantity = "";
      $price = "";
      $visibility = "";
    }
  }
} else {
  $message = "User not authorized to add products";
}

?>


<section id="add_products">
  <div class="container">
    <div class="row">
      <div class="col-xs-6 col-xs-offset-3">
        <div class="form-wrap">
          <h1>Add Products</h1>
          <form role="form" action="add_products.php" method="post" id="login-form" autocomplete="off">

            <h5 class="text-center"><?php echo $message; ?></h5>
            <div class="form-group">
              <label for="product_name" class="sr-only">Product Name</label>
              <input type="text" name="product_name" id="product_name" class="form-control"
                value="<?php echo htmlspecialchars($product_name); ?>" placeholder="* Enter Product Name">
            </div>
            <div class="form-group">
              <label for="description" class="sr-only">Description</label>
              <input type="text" name="description" id="description"
                value="<?php echo htmlspecialchars($product_description); ?>" class="form-control"
                placeholder="* Enter Product Description">
            </div>
            <div class="form-group">
              <label for="category" class="sr-only">Categories</label>
              <select class="form-control form-control-lg" name="category">
                <option disabled>Category</option>
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
            <div class="form-group">
              <label for="quantity" class="sr-only">Quantity</label>
              <input type="number" name="quantity" id="quantity" class="form-control"
                value="<?php echo htmlspecialchars($quantity); ?>" min="1" placeholder="* Product Quantity">
            </div>
            <div class="form-group">
              <label for="price" class="sr-only">Price</label>
              <input type="number" name="price" id="price" class="form-control"
                value="<?php echo htmlspecialchars($price); ?>" min="1" placeholder="* Product Price">
            </div>

            <div class="form-group">
              <label for="visibility" class="sr-only">Visibile to Customer</label>
              <select class="form-control form-control-lg" name="visibility">
                <option disabled>Visibility to Customer</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>

            <input type="submit" name="add-product" id="btn-login" class="btn btn-custom btn-lg btn-block"
              value="Add Product">
          </form>

        </div>
      </div>
    </div>
  </div>

  <?php include "../includes/footer.php"; ?>
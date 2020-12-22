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

$product_id = $_GET["product_id"];

$select = "SELECT * FROM products WHERE id = {$product_id}";
$select_query = mysqli_query($connection, $select);

if (!$select_query) {
  die("<h5 style='text-align: center;'>Something went wrong</h5>");
}

$row = mysqli_fetch_assoc($select_query);
$id = $row["id"];
$product_name = $row["name"];
$product_description = $row["description"];
$category = $row["category"];
$quantity = $row["quantity"];
$price = $row["price"];
$visibility = $row["visibility"];


if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1) {
  if (isset($_POST["edit-product"])) {
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
      $update_query = "UPDATE products SET `name`= '{$product_name}', quantity = ${quantity},
      price = {$price}, `description` = '{$product_description}', `user_id` = {$user_id}, category = '{$category}', visibility = {$visibility} WHERE id = {$id}";
      $update_query = mysqli_query($connection, $update_query);

      if (!$update_query) {
        die("<h5 style='text-align: center;'>Something went wrong</h5>");
      }

      $message = "Product edited successfully";
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
          <h1>Edit Products</h1>
          <form role="form" action="<?php echo $base_url . '/admin/edit_product.php?product_id=' . $id; ?>"
            method="post" id="login-form" autocomplete="off">

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
                <option value="Electronics" <?php if ($category == "Electronics") {
                                              echo ' selected="selected"';
                                            }  ?>>
                  Electronics</option>
                <option value="Fashion" <?php if ($category == "Fashion") {
                                          echo ' selected="selected"';
                                        }  ?>>Fashion
                </option>
                <option value="Home" <?php if ($category == "Home") {
                                        echo ' selected="selected"';
                                      }  ?>>Home</option>
                <option value="Beauty" <?php if ($category == "Beauty") {
                                          echo ' selected="selected"';
                                        }  ?>>Beauty
                </option>
                <option value="Health" <?php if ($category == "Health") {
                                          echo ' selected="selected"';
                                        }  ?>>Health
                </option>
                <option value="Books" <?php if ($category == "Books") {
                                        echo ' selected="selected"';
                                      }  ?>>Books</option>
                <option value="Sports" <?php if ($category == "Sports") {
                                          echo ' selected="selected"';
                                        }  ?>>Sports
                </option>
                <option value="Fitness" <?php if ($category == "Fitness") {
                                          echo ' selected="selected"';
                                        }  ?>>Fitness
                </option>
                <option value="Grocery" <?php if ($category == "Grocery") {
                                          echo ' selected="selected"';
                                        }  ?>>Grocery
                </option>
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
                <option value="1" <?php if ($visibility == 1) {
                                    echo ' selected="selected"';
                                  } ?>>Yes</option>
                <option value="0" <?php if ($visibility == 0) {
                                    echo ' selected="selected"';
                                  }  ?>>No</option>
              </select>
            </div>

            <input type="submit" name="edit-product" id="btn-login" class="btn btn-custom btn-lg btn-block"
              value="Edit Product">
          </form>

        </div>
      </div>
    </div>
  </div>

  <?php include "../includes/footer.php"; ?>
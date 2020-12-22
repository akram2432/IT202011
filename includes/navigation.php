 <?php session_start(); ?>
 <?php $base_url = "http://localhost/ecommerce"; ?>
 <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
   <div class="container">
     <!-- Brand and toggle get grouped for better mobile display -->
     <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </button>
       <a class="navbar-brand" href="<?php echo $base_url . '/index.php'; ?>">Ecommerce</a>
     </div>
     <!-- Collect the nav links, forms, and other content for toggling -->
     <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
       <ul class="nav navbar-nav">
         <li>
           <a href="<?php echo $base_url . '/index.php'; ?>">Home</a>
         </li>

         <?php


          if (isset($_SESSION['username'])) {
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
              echo "<li><a href='{$base_url}/admin/add_products.php'>Add Products</a>";
            }
            echo "<li><a href='{$base_url}/cart.php'>Cart</a></li><li><a href='{$base_url}/purchase_history.php'>Purchase History</a></li><li><a href='{$base_url}/profile.php'>Profile</a></li><li><a href='{$base_url}/includes/logout.php'>Logout</a></li>";
          } else {
            echo "<li><a href='{$base_url}/login.php'>Login</a></li><li><a href='{$base_url}/register.php'>Register</a></li>";
          }


          ?>



       </ul>
     </div>

   </div>
   s
 </nav>
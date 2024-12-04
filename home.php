<?php

include 'config.php';
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();

$jwt_secret_key = 'mukul1234567890'; // Same key as used in login
$jwt_token = $_SESSION['jwt_token'] ?? null;

// Validate the token
if (!isset($_SESSION['jwt_token'])) {
   header('location:login.php');
   exit();
}

$user_id = $_SESSION['user_id'];
$jwt_token = $_SESSION['jwt_token']; 

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear message after displaying it
}


try {
    $decoded = JWT::decode($jwt_token, new Key($jwt_secret_key, 'HS256'));
    $user_id = $decoded->sub;
    $user_name = $decoded->name;
    $user_email = $decoded->email;
} catch (Exception $e) {
    // Invalid JWT, redirect to login
    session_destroy();
    header('location:login.php');
    exit();
}


if(isset($_POST['add_to_cart'])){

   $product_id = $_POST['product_id']??'';
   $product_name = $_POST['product_name']??'';
   $product_price = $_POST['product_price']??'';
   $product_image = $_POST['product_image']??'';
   $product_stock = $_POST['product_stock']??0;

  
   if ($product_stock <= 0) {
      $message[] = 'Product is out of stock!';
  } else {
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_cart_numbers) > 0) {
          $message[] = 'Already added to cart!';
      } else {
          // Deduct stock on adding to cart
          $new_stock = $product_stock - 1;
            $update_stock_query = "UPDATE `products` SET stock = '$new_stock' WHERE id = '$product_id'";
            mysqli_query($conn, $update_stock_query) or die('Stock Update Failed: ' . mysqli_error($conn));

            $insert_cart_query = "INSERT INTO `cart`(user_id, name, price, image) VALUES('$user_id', '$product_name', '$product_price', '$product_image')";
            mysqli_query($conn, $insert_cart_query) or die('Insert Into Cart Failed: ' . mysqli_error($conn));

            $message[] = 'Product added to cart!';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>




<section class="home">

   <div class="content">
   <h3>Welcome back, User ID: <?php echo htmlspecialchars($user_id); ?>!</h3>
        <?php if (isset($message)) : ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <p>Your session token: </p>
        <div class="token-box" style="word-wrap: break-word; background: #f9f9f9; padding: 10px; border-radius: 5px;">
            <?php echo htmlspecialchars($jwt_token); ?>
        </div>
      <p>Hand Picked Book to your door.</p>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi, quod? Reiciendis ut porro iste totam.</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">


      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">$<?php echo $fetch_products['price']; ?></div>
            <div class="stock">Stock: <?php echo $fetch_products['stock']; ?></div>

            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <input type="hidden" name="product_stock" value="<?php echo $fetch_products['stock']; ?>">

            <input type="submit" value="<?php echo $fetch_products['stock'] > 0 ? 'Add to Cart' : 'Out of Stock'; ?>" name="add_to_cart" class="btn" <?php echo $fetch_products['stock'] > 0 ? '' : 'disabled'; ?>>
        </form>
        <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>about us</h3>
         <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>have any questions?</h3>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet ullam voluptatibus?</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>





<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
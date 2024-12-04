<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_stock = $_POST['product_stock'];
 

  
   // Validate if the product exists
   $check_product = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'") or die('query failed');
   if (mysqli_num_rows($check_product) == 0) {
      $message[] = 'Product does not exist!';
   } else {
      $fetch_product = mysqli_fetch_assoc($check_product);

      // Check stock availability
      $stock = isset($fetch_product['stock']) ? $fetch_product['stock'] : 0;
        if ($stock <= 0) {
            $message[] = 'Product is out of stock!';
        } else{
         // Check if the product is already in the cart
         $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE product_id = '$product_id' AND user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($check_cart) > 0) {
            $message[] = 'Product already in cart!';
         } else {
            // Add product to the cart with quantity = 1
            mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, quantity) VALUES('$user_id', '$product_id', 1)") or die('query failed');
            $message[] = 'Product added to cart!';
         }
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
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>our shop</h3>
   <p> <a href="home.php">home</a> / shop </p>
</div>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed' . mysqli_error($conn));
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="home.php" method="post" class="box">
            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
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

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
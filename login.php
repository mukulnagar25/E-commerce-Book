<?php
include 'config.php';
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
ob_start(); // Start output buffering

$jwt_secret_key = 'mukul1234567890';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];

    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format!';
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $payload = [
                    'sub' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'user_type' => $user['user_type'], // Include user type
                    'iat' => time(),
                    'exp' => time() + 3600
                ];

                $jwt = JWT::encode($payload, $jwt_secret_key, 'HS256');
                $_SESSION['jwt_token'] = $jwt;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['message'] = 'Login successful!';

                // Redirect based on user type
                if ($user['user_type'] === 'admin') {
                    $_SESSION['admin_name'] = $user['name'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_id'] = $user['id'];
                    header('Location: admin_page.php');
                } elseif ($user['user_type'] === 'user') {
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: home.php');
                }
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "Email not registered!";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . '<br>';
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
   <title>Login</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
       .password-group {
           position: relative;
           display: flex;
           align-items: center;
       }
       .password-group .box {
           width: 100%;
       }
       .password-group .toggle-password {
           position: absolute;
           right: 10px;
           cursor: pointer;
           font-size: 18px;
           color: #666;
       }
       .password-group .toggle-password:hover {
           color: #333;
       }
   </style>
</head>
<body>

<div class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <div class="password-group">
         <input type="password" name="password" placeholder="Enter your password" required class="box" id="loginPassword">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('loginPassword', this)"></i>
      </div>
      <input type="submit" value="Login Now" class="btn">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</div>

<script>
   function togglePasswordVisibility(fieldId, icon) {
       const passwordField = document.getElementById(fieldId);
       if (passwordField.type === "password") {
           passwordField.type = "text";
           icon.classList.remove('fa-eye');
           icon.classList.add('fa-eye-slash');
       } else {
           passwordField.type = "password";
           icon.classList.remove('fa-eye-slash');
           icon.classList.add('fa-eye');
       }
   }
</script>

</body>
</html>

<?php
include 'config.php';
require_once 'vendor/autoload.php'; // Include the autoloader for the JWT library

use Firebase\JWT\JWT; // Correct namespace for JWT class
use Firebase\JWT\Key; // Correct namespace for Key class

$jwt_secret_key = 'mukul1234567890'; // Secret key for JWT encoding/decoding

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $address = isset($_POST['address']) ? trim(mysqli_real_escape_string($conn, $_POST['address'])) : null;
    $user_type = $_POST['user_type']; // Capture user type
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('query failed');
    $errors = [];
    $message = null;

    // Validate input data
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format!';
    }
    if (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
        $errors[] = 'Password must be at least 8 characters long and include numbers and special characters!';
    }
    if ($password !== $cpassword) {
        $errors[] = 'Passwords do not match!';
    }

    // Check for existing email
    $email_check_query = "SELECT id FROM users WHERE email = '$email'";
    $email_check_result = mysqli_query($conn, $email_check_query);
    if (mysqli_num_rows($email_check_result) > 0) {
        $errors[] = 'Email already registered!';
    }

    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $insert_query = "INSERT INTO users (name, email, password, user_type, address) VALUES ('$name', '$email', '$hashed_password', '$user_type','$address')";
        if (mysqli_query($conn, $insert_query)) {
            $user_id = mysqli_insert_id($conn);
            
            // Generate JWT token after registration
            $payload = [
                'sub' => $user_id, // Subject (user ID)
                'email' => $email,
                'name' => $name,
                'iat' => time(), // Issued at time
                'exp' => time() + 3600 // Token expiration time (1 hour)
            ];

            // Generate JWT token using the three required parameters
            $jwt = JWT::encode($payload, $jwt_secret_key, 'HS256'); // Specify the algorithm here

            // Return success message along with the JWT token
            $message = [
                'type' => 'success',
                'text' => 'Registration successful! Your User ID is #' . $user_id,
                'token' => $jwt // Send the token as part of the message
            ];

            header("Refresh:1; url=login.php"); // Redirect to login after 1 second
        } else {
            $message = ['type' => 'error', 'text' => 'Registration failed!'];
        }
    } else {
        $message = ['type' => 'error', 'text' => implode('<br>', $errors)];
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

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
       .message {
           padding: 10px;
           margin: 10px auto;
           border-radius: 5px;
           width: 90%;
           max-width: 500px;
           text-align: center;
           font-size: 16px;
       }
       .message.success {
           background-color: #d4edda;
           color: #155724;
           border: 1px solid #c3e6cb;
       }
       .message.error {
           background-color: #f8d7da;
           color: #721c24;
           border: 1px solid #f5c6cb;
       }
       .message .fas {
           cursor: pointer;
           margin-left: 10px;
       }
   </style>
</head>
<body>

<?php
if (isset($message)) {
    echo '
    <div class="message ' . $message['type'] . '">
        <span>' . $message['text'] . '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    ';
}
?>

<div class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" placeholder="Enter your name" required class="box">
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <div class="password-group">
         <input type="password" name="password" placeholder="Enter your password" required class="box" id="password">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
      </div>
      <div class="password-group">
         <input type="password" name="cpassword" placeholder="Confirm your password" required class="box" id="cpassword">
         <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('cpassword', this)"></i>
      </div>
      <input type="text" name="address" placeholder="Enter your address (optional)" class="box">
      <select name="user_type" class="box">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
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

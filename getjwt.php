<?php
// jwtMiddleware.php
require_once 'vendor/autoload.php'; //Include JWT library
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Secret key for JWT encoding/decoding
$jwt_secret_key = 'mukul1234567890';

function validateJWT($jwt) {
    global $jwt_secret_key;

    try {
        $decoded = JWT::decode($jwt, new Key($jwt_secret_key, 'HS256'));
        return $decoded; // Return decoded data if valid
    } catch (Exception $e) {
        return false; // Return false if token is invalid
    }
}
?>
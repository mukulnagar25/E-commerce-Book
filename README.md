# E-commerce-Book
## Problem Statement 

You can check the problem statement :

[Click](https://github.com/mukulnagar25/E-commerce-Book/raw/main/Flipr%20Labs%20Backend%20Assignment.pdf)

# API Project for Online Store


## Project Overview

This is a RESTful API for an online store that allows users to register, log in, place orders, and manage products. The API also includes an admin section for managing products and viewing orders. This project is designed to facilitate basic e-commerce functionality.

## Features

- **User Authentication**: Users can register, log in, and manage their profiles.
- **Product Management**: Admins can add, update, and delete products in the store.
- **Order Management**: Users can place orders, and admins can view all orders placed by users.
- **Session Management**: The API includes authentication using session tokens to keep users logged in.

## API Endpoints

### User Endpoints

- **POST `/api/users/register`**: Registers a new user.  
   - Body: `{"id":"11","name":"mukul","email":"mukul@gmail.com","password":"$2y$10$bRwQGUrkE73yx123gVu1bOt6EKJ8QSPoAsofCAysMqRvNIGasWZdm","user_type":"user","address":"Narmadapuram"}`
    - Response: User object with details.

- **POST `/api/users/login`**: Logs in a user and returns a session token.  
    - Body: `{"id":"58","user_id":"1","name":"Book","price":"10","quantity":"1","image":"shattered.jpg"}`
    - Response: Session token.

- **GET `/api/users`**: Fetches all users (Admin only).

### Product Endpoints

- **GET `/api/products`**: Retrieves all products available in the store.  
    - Response: Array of product objects.

- **POST `/api/products`**: Adds a new product (Admin only).  
    - Body: `{"id":"3","name":"Book1","price":"100","image":"red_queen.jpg","stock":"8"}`
    - Response: Product object.

### Order Endpoints

- **GET `/api/orders`**: Retrieves all orders placed by the user (Authenticated users only).  
    - Response: Array of order objects.

- **POST `/api/orders`**: Places a new order.  
    - Body: `{ "product_id": 1, "quantity": 2, "total_price": 100 }`
    - Response: Order object.

## Postman Collection

You can import the Postman collection for this API to test the endpoints easily.  
Download the collection from the following link:  
[Download Postman Collection](https://documenter.getpostman.com/view/40192073/2sAYBa9pSV)

## Postman Collection json file 

You can access postman exported file from the following link:  

[JSON](https://github.com/yourusername/your-repository-name/raw/main/Shop API.postman_collection.json)

## API FILE

You can check the API file from the link below:

[API file](https://github.com/mukulnagar25/E-commerce-Book/raw/main/api.php)

## DEPLOY LINK WILL BE PROVIDED SOON

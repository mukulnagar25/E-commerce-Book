# E-commerce-Book
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
    - Body: `{ "name": "John", "email": "john@example.com", "password": "password" }`
    - Response: User object with details.

- **POST `/api/users/login`**: Logs in a user and returns a session token.  
    - Body: `{ "email": "john@example.com", "password": "password" }`
    - Response: Session token.

- **GET `/api/users`**: Fetches all users (Admin only).

### Product Endpoints

- **GET `/api/products`**: Retrieves all products available in the store.  
    - Response: Array of product objects.

- **POST `/api/products`**: Adds a new product (Admin only).  
    - Body: `{ "name": "Product Name", "price": 10, "description": "Product Description" }`
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
[Download Postman Collection](https://documenter.getpostman.com/view/40192073/2sAYBa9pSV))


<?php

require 'vendor/autoload.php';

use App\UserService;

// Instantiate the UserService class and create a new user.
$userService = new UserService();


// Get a single user by their ID.
$user = $userService->getUserById(1);
header('Content-Type: application/json');
echo json_encode($user, JSON_PRETTY_PRINT);


// Get all users from the database.
$user = $userService->getUsers(2);
header('Content-Type: application/json');
echo json_encode($user, JSON_PRETTY_PRINT);

// Create a new user
$user = $userService->createUser('kane james', 'Cleaner');
header('Content-Type: application/json');
echo json_encode($user, JSON_PRETTY_PRINT);



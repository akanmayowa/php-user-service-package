## PHP User Service Package

### Description

This project is a framework-agnostic Composer package that provides a service for retrieving and creating users via a remote API. It integrates with the ReqRes API for demonstration purposes. The package is designed to be easily integrated into any PHP project, regardless of the framework being used

### Features

- Retrieve a single user by ID.
- Retrieve a paginated list of users.
- Create a new user with a name and job, and return the user ID.
- Convert all users into well-defined DTO models.
- JSON serializable interfaces for DTO models, supporting conversion to a standard array structure.
- Robust error handling to manage unstable and unreliable remote APIs.
- Framework-agnostic, suitable for use with Drupal, Laravel, WordPress, or any other PHP software.
- Adheres to modern PSR standards targeting PHP 8.2.
- Thoroughly testable with unit tests provided.

### Prerequisites

Before you begin, ensure you have the following installed on your machine:

- PHP 8.2 or higher
- A web server (Apache, Nginx, or PHP's built-in server)
- Composer (for dependency management)

### Installation

Follow these steps to set up the package:

1. **Install the package using Composer:**

   ```bash
   composer install
   ```

2. **Configuration::**

    Update your configuration file (e.g., .env) with your API base URL 

   ```bash
   BASE_URL=https://reqres.in/api
   ```
   
3. **Set Up a Web Server Using PHP’s Built-In Server:**

    You can use Apache, Nginx, or PHP’s built-in server to run the project

      Navigate to the `public` directory and start the PHP server:

      ```bash
      cd public
      php -S localhost:8000
      ```
      Now, open your web browser and visit `http://localhost:8000`.

4. **Usage:** 

     get user By Id,
      ```bash
      <?php
   
       require 'vendor/autoload.php';
   
       use App\UserService;
   
       $userService = new UserService();
       $user = $userService->getUserById(2);
       header('Content-Type: application/json');
       echo json_encode($user, JSON_PRETTY_PRINT);
      ```   

   get all users,
      ```bash
      <?php
   
       require 'vendor/autoload.php';
   
       use App\UserService;
   
       $userService = new UserService();
       $user = $userService->getUsers(2);
       header('Content-Type: application/json');
       echo json_encode($user, JSON_PRETTY_PRINT);
      ```   

   create new user,
      ```bash
      <?php
   
       require 'vendor/autoload.php';
   
       use App\UserService;
   
       $userService = new UserService();
       $user = $userService->createUser('kane jacobs', 'plumber tech');
       header('Content-Type: application/json');
       echo json_encode($user, JSON_PRETTY_PRINT);
      ```   

5. **Testing the Application:**

   ```bash
   vendor/bin/phpunit tests
   ```
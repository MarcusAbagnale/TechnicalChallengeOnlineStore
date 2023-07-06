# TechnicalChallengeOnlineStore
You are tasked with building an online store for a company that sells various products. The goal is to create a web application that allows users to browse products, add them to a shopping cart, and complete the purchase.

# Online Store - Readme

This repository contains the source code for an online store web application. The application allows users to browse products, add them to a shopping cart, and complete the purchase. It is built using PHP and utilizes a MySQL database for data storage.

## Setup Instructions

1. Clone the Repository: git clone


2. Set Up the Database:
- Import the SQL Script: Use a database management tool (e.g., phpMyAdmin, MySQL Workbench) to import the provided SQL script into your database server. This script will create the necessary tables and populate them with sample data.

3. Configure PHP Environment:
- Ensure that you have PHP installed on your system. You can check the PHP version by running the following command:
  ```
  php -v
  ```

4. Start the PHP Development Server:
- Open a terminal or command prompt and navigate to the root directory of the cloned repository.
- Start the PHP development server by running the following command:
  ```
  php -S localhost:8000
  ```

5. Access the Online Store:
- Open your web browser and visit the following URL:
  ```
  http://localhost:8000
  ```
- You should now be able to browse the products, add them to the shopping cart, and proceed with the checkout process.

## Repository Structure

The repository is organized as follows:

- `index.php`: The main entry point of the application.
- `config.php`: Configuration file for connecting to the database.
- `assets/`: Directory containing CSS and JavaScript files for styling and interactivity.
- `templates/`: Directory containing reusable templates for different parts of the application.
- `sql/`: Directory containing the SQL script for creating the database schema and sample data.
- `images/`: Directory for storing product images.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).




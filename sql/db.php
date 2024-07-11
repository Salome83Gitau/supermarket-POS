<?php
$servername= "localhost";
$username="Sally";
$password="5411y";
$dbname="POS";

// create connection to the db
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection failed:" .$conn->connect_error);
}

// Create User table
$sql = "CREATE TABLE User (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name CHAR(50) NOT NULL,
    Name CHAR(50) NOT NULL,
    password CHAR(50) NOT NULL,
    email CHAR(50) NOT NULL,
    role CHAR(50) NOT NULL,
    status CHAR(50) NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table User created successfully<br>";
  } else {
    echo "Error creating table User: " . $conn->error . "<br>";
  }
  
  // Create Product table
  $sql = "CREATE TABLE Product (
    product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_name CHAR(50) NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price INT NOT NULL,
    cost INT NOT NULL,
    stock_quantity INT NOT NULL,
    expiration_date DATE NOT NULL,
    barcode VARCHAR(70) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES Category(category_id),
    FOREIGN KEY (supplier_id) REFERENCES Supplier(supplier_id)
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Product created successfully<br>";
  } else {
    echo "Error creating table Product: " . $conn->error . "<br>";
  }
  
  // Create Category table
  $sql = "CREATE TABLE Category (
    category_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    category_name CHAR(50) NOT NULL,
    description CHAR(50) NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Category created successfully<br>";
  } else {
    echo "Error creating table Category: " . $conn->error . "<br>";
  }
  
  // Create Supplier table
  $sql = "CREATE TABLE Supplier (
    supplier_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    supplier_name CHAR(50) NOT NULL,
    email CHAR(50) NOT NULL,
    phone CHAR(50) NOT NULL,
    product_name CHAR(50) NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Supplier created successfully<br>";
  } else {
    echo "Error creating table Supplier: " . $conn->error . "<br>";
  }
  
  // Create Sale table
  $sql = "CREATE TABLE Sale (
    sale_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_id INT,
    sale_date DATE,
    total_amount INT NOT NULL,
    discount_id INT,
    invoice_number INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id),
    FOREIGN KEY (discount_id) REFERENCES Discounts(discount_id)
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Sale created successfully<br>";
  } else {
    echo "Error creating table Sale: " . $conn->error . "<br>";
  }
  
  // Create SaleItem table
  $sql = "CREATE TABLE SaleItem (
    saleItem_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    sale_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES Sale(sale_id),
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table SaleItem created successfully<br>";
  } else {
    echo "Error creating table SaleItem: " . $conn->error . "<br>";
  }
  
  // Create Customer table
  $sql = "CREATE TABLE Customer (
    customer_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    customer_name CHAR(50) NOT NULL,
    email CHAR(50) NOT NULL,
    phone CHAR(50) NOT NULL,
    loyalty_points INT NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Customer created successfully<br>";
  } else {
    echo "Error creating table Customer: " . $conn->error . "<br>";
  }
  
  // Create Discounts table
  $sql = "CREATE TABLE Discounts (
    discount_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    discount_name CHAR(50) NOT NULL,
    description CHAR(50) NOT NULL,
    discount_percent INT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Discounts created successfully<br>";
  } else {
    echo "Error creating table Discounts: " . $conn->error . "<br>";
  }
  
  // Create Store table
  $sql = "CREATE TABLE Store (
    store_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    store_name CHAR(50) NOT NULL,
    location CHAR(50) NOT NULL
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Store created successfully<br>";
  } else {
    echo "Error creating table Store: " . $conn->error . "<br>";
  }
  
  // Create Shift table
  $sql = "CREATE TABLE Shift (
    shift_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table Shift created successfully<br>";
  } else {
    echo "Error creating table Shift: " . $conn->error . "<br>";
  }
  
  // Create AuditLog table
  $sql = "CREATE TABLE AuditLog (
    log_id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
  )";
  if ($conn->query($sql) === TRUE) {
    echo "Table AuditLog created successfully<br>";
  } else {
    echo "Error creating table AuditLog: " . $conn->error . "<br>";
  }
  
  $conn->close();
  ?>
?>
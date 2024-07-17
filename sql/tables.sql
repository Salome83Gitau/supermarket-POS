-- Create Users table
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `role` varchar(32) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
--create table company
CREATE TABLE company (
    company_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    location VARCHAR(255),
    logo BLOB, 
    PRIMARY KEY (company_name)
);

-- Create Category table
CREATE TABLE `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
);

-- Create Supplier table
CREATE TABLE `supplier` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `product_name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
);

-- Create Product table
CREATE TABLE `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(64) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `barcode` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `supplier`(`supplier_id`)
);

-- Create Customer table
CREATE TABLE `customer` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `loyalty_points` int DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
);

-- Create Discounts table
CREATE TABLE `discounts` (
  `discount_id` int NOT NULL AUTO_INCREMENT,
  `discount_name` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_percent` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`discount_id`)
);

-- Create Sale table
CREATE TABLE `sale` (
  `sale_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `discount_id` int DEFAULT NULL,
  `invoice_number` int DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`),
  FOREIGN KEY (`discount_id`) REFERENCES `discounts`(`discount_id`)
);

-- Create SaleItem table
CREATE TABLE `saleitem` (
  `saleitem_id` int NOT NULL AUTO_INCREMENT,
  `sale_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`saleitem_id`),
  FOREIGN KEY (`sale_id`) REFERENCES `sale`(`sale_id`),
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
);

-- Create Store table
CREATE TABLE `store` (
  `store_id` int NOT NULL AUTO_INCREMENT,
  `store_name` varchar(64) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`store_id`)
);

-- Create Shift table
CREATE TABLE `shift` (
  `shift_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  PRIMARY KEY (`shift_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

-- Create AuditLog table
CREATE TABLE `auditlog` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

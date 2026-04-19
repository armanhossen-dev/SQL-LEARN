-- MySQL Practice Workbook
-- Work through these exercises sequentially
-- Solutions at the bottom (try without looking first!)

-- ============================================================================
-- SETUP: Create Practice Database
-- ============================================================================

DROP DATABASE IF EXISTS practice_db;
CREATE DATABASE practice_db;
USE practice_db;

-- ============================================================================
-- LEVEL 1: BASICS - Table Creation & Data Insertion
-- ============================================================================

-- Exercise 1.1: Create a 'products' table
-- Columns: product_id (PK, auto-increment), name (varchar 100), 
--          price (decimal), stock (int), category (varchar 50)
-- YOUR CODE HERE:




-- Exercise 1.2: Insert 5 products
-- Include at least 2 different categories
-- YOUR CODE HERE:




-- Exercise 1.3: Create a 'suppliers' table
-- Columns: supplier_id (PK), name, city, country
-- YOUR CODE HERE:




-- Exercise 1.4: Add supplier_id column to products (foreign key)
-- YOUR CODE HERE:




-- ============================================================================
-- LEVEL 2: BASIC QUERIES
-- ============================================================================

-- Exercise 2.1: Select all products with price > 100
-- YOUR CODE HERE:


-- Exercise 2.2: Find products in 'Electronics' category, ordered by price DESC
-- YOUR CODE HERE:


-- Exercise 2.3: Count how many products are in stock (stock > 0)
-- YOUR CODE HERE:


-- Exercise 2.4: Find the average price of all products
-- YOUR CODE HERE:


-- Exercise 2.5: List products with names containing 'Phone'
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 3: INTERMEDIATE QUERIES
-- ============================================================================

-- Exercise 3.1: Show total stock value per category (price * stock)
-- YOUR CODE HERE:


-- Exercise 3.2: Find the most expensive product in each category
-- YOUR CODE HERE:


-- Exercise 3.3: Update: Increase all prices by 10%
-- YOUR CODE HERE:


-- Exercise 3.4: Delete products with stock = 0
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 4: JOINS
-- ============================================================================

-- Setup for joins
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    city VARCHAR(50)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date DATE,
    total_amount DECIMAL(10,2),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Insert sample data
INSERT INTO customers (name, email, city) VALUES
    ('Arman Ripon', 'arman@email.com', 'Dhaka'),
    ('John Doe', 'john@email.com', 'Chittagong'),
    ('Jane Smith', 'jane@email.com', 'Dhaka'),
    ('Mike Wilson', 'mike@email.com', 'Sylhet');

INSERT INTO orders (customer_id, order_date, total_amount) VALUES
    (1, '2024-01-15', 5000.00),
    (1, '2024-02-20', 3000.00),
    (2, '2024-01-18', 7500.00),
    (3, '2024-03-10', 2000.00);

-- Exercise 4.1: List all orders with customer names
-- YOUR CODE HERE:


-- Exercise 4.2: Show customers who haven't placed any orders (LEFT JOIN)
-- YOUR CODE HERE:


-- Exercise 4.3: Count total orders per customer
-- YOUR CODE HERE:


-- Exercise 4.4: Find total spending per customer
-- YOUR CODE HERE:


-- Exercise 4.5: List all order items with product names and customer names
-- (Join 3 tables: orders, customers, order_items, products)
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 5: SUBQUERIES
-- ============================================================================

-- Exercise 5.1: Find products with price above average
-- YOUR CODE HERE:


-- Exercise 5.2: Find customers who spent more than 5000 total
-- (Use subquery with IN or EXISTS)
-- YOUR CODE HERE:


-- Exercise 5.3: Show products never ordered
-- YOUR CODE HERE:


-- Exercise 5.4: Find the customer with the highest total spending
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 6: AGGREGATE FUNCTIONS & GROUP BY
-- ============================================================================

-- Exercise 6.1: Total sales per month
-- YOUR CODE HERE:


-- Exercise 6.2: Average order value per customer
-- YOUR CODE HERE:


-- Exercise 6.3: Category with highest total stock value
-- YOUR CODE HERE:


-- Exercise 6.4: Customers who placed more than 1 order (HAVING clause)
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 7: STORED PROCEDURES
-- ============================================================================

-- Exercise 7.1: Create a procedure to get orders by customer ID
-- YOUR CODE HERE:
DELIMITER //

DELIMITER ;


-- Exercise 7.2: Create a procedure to place a new order
-- Parameters: customer_id, total_amount, order_date
-- Should INSERT into orders and return the new order_id
-- YOUR CODE HERE:
DELIMITER //

DELIMITER ;


-- Exercise 7.3: Create a procedure to update product stock
-- Parameters: product_id, quantity (can be negative for sales)
-- Should check if enough stock exists
-- YOUR CODE HERE:
DELIMITER //

DELIMITER ;


-- ============================================================================
-- LEVEL 8: TRIGGERS
-- ============================================================================

-- Exercise 8.1: Create a trigger to validate product price
-- Should prevent negative prices on INSERT
-- YOUR CODE HERE:
DELIMITER //

DELIMITER ;


-- Exercise 8.2: Create audit table and trigger
-- Track all product price changes
-- YOUR CODE HERE:

-- Create audit table first


-- Then create trigger
DELIMITER //

DELIMITER ;


-- Exercise 8.3: Create trigger to update order total
-- When order_item is inserted, update orders.total_amount
-- YOUR CODE HERE:
DELIMITER //

DELIMITER ;


-- ============================================================================
-- LEVEL 9: VIEWS
-- ============================================================================

-- Exercise 9.1: Create view for customer order summary
-- Show: customer_id, name, total_orders, total_spent
-- YOUR CODE HERE:


-- Exercise 9.2: Create view for low stock products (stock < 10)
-- YOUR CODE HERE:


-- Exercise 9.3: Create view for monthly sales report
-- Show: month, year, total_orders, total_revenue
-- YOUR CODE HERE:


-- ============================================================================
-- LEVEL 10: TRANSACTIONS
-- ============================================================================

-- Exercise 10.1: Write a transaction to transfer stock between products
-- Transfer 5 units from product_id=1 to product_id=2
-- Should ROLLBACK if source doesn't have enough stock
-- YOUR CODE HERE:




-- Exercise 10.2: Create a complete order transaction
-- 1. Insert order
-- 2. Insert order items
-- 3. Update product stock
-- 4. COMMIT only if all succeed
-- YOUR CODE HERE:




-- ============================================================================
-- CHALLENGE EXERCISES
-- ============================================================================

-- Challenge 1: Find top 3 customers by spending in each city
-- (Use window functions: ROW_NUMBER() or RANK())
-- YOUR CODE HERE:


-- Challenge 2: Calculate running total of daily sales
-- (Use window function with SUM() OVER())
-- YOUR CODE HERE:


-- Challenge 3: Find customers who ordered ALL products in a category
-- (Complex subquery with COUNT and GROUP BY)
-- YOUR CODE HERE:


-- Challenge 4: Create a recursive CTE for product recommendations
-- If product A is often bought with B, and B with C, suggest C when A is viewed
-- YOUR CODE HERE:


-- ============================================================================
-- SOLUTIONS (Try exercises first!)
-- ============================================================================

/*

-- SOLUTION 1.1:
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(50)
);

-- SOLUTION 1.2:
INSERT INTO products (name, price, stock, category) VALUES
    ('iPhone 15', 999.99, 50, 'Electronics'),
    ('Samsung Galaxy S24', 899.99, 30, 'Electronics'),
    ('Office Desk', 299.99, 20, 'Furniture'),
    ('Gaming Chair', 199.99, 15, 'Furniture'),
    ('Wireless Mouse', 29.99, 100, 'Electronics');

-- SOLUTION 1.3:
CREATE TABLE suppliers (
    supplier_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    city VARCHAR(50),
    country VARCHAR(50)
);

-- SOLUTION 1.4:
ALTER TABLE products 
ADD COLUMN supplier_id INT,
ADD FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id);

-- SOLUTION 2.1:
SELECT * FROM products WHERE price > 100;

-- SOLUTION 2.2:
SELECT * FROM products 
WHERE category = 'Electronics' 
ORDER BY price DESC;

-- SOLUTION 2.3:
SELECT COUNT(*) FROM products WHERE stock > 0;

-- SOLUTION 2.4:
SELECT AVG(price) AS average_price FROM products;

-- SOLUTION 2.5:
SELECT * FROM products WHERE name LIKE '%Phone%';

-- SOLUTION 3.1:
SELECT 
    category, 
    SUM(price * stock) AS total_value
FROM products
GROUP BY category;

-- SOLUTION 3.2:
SELECT category, MAX(price) AS max_price
FROM products
GROUP BY category;

-- OR with product names:
SELECT p1.*
FROM products p1
WHERE p1.price = (
    SELECT MAX(p2.price)
    FROM products p2
    WHERE p2.category = p1.category
);

-- SOLUTION 3.3:
UPDATE products SET price = price * 1.10;

-- SOLUTION 3.4:
DELETE FROM products WHERE stock = 0;

-- SOLUTION 4.1:
SELECT o.order_id, c.name, o.order_date, o.total_amount
FROM orders o
INNER JOIN customers c ON o.customer_id = c.customer_id;

-- SOLUTION 4.2:
SELECT c.name, c.email
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
WHERE o.order_id IS NULL;

-- SOLUTION 4.3:
SELECT c.name, COUNT(o.order_id) AS order_count
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;

-- SOLUTION 4.4:
SELECT c.name, SUM(o.total_amount) AS total_spent
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;

-- SOLUTION 4.5:
SELECT 
    c.name AS customer,
    p.name AS product,
    oi.quantity,
    o.order_date
FROM order_items oi
JOIN orders o ON oi.order_id = o.order_id
JOIN customers c ON o.customer_id = c.customer_id
JOIN products p ON oi.product_id = p.product_id;

-- SOLUTION 5.1:
SELECT * FROM products
WHERE price > (SELECT AVG(price) FROM products);

-- SOLUTION 5.2:
SELECT name FROM customers
WHERE customer_id IN (
    SELECT customer_id 
    FROM orders 
    GROUP BY customer_id 
    HAVING SUM(total_amount) > 5000
);

-- SOLUTION 5.3:
SELECT * FROM products
WHERE product_id NOT IN (
    SELECT DISTINCT product_id FROM order_items
);

-- SOLUTION 5.4:
SELECT c.name, SUM(o.total_amount) AS total
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name
ORDER BY total DESC
LIMIT 1;

-- SOLUTION 6.1:
SELECT 
    YEAR(order_date) AS year,
    MONTH(order_date) AS month,
    SUM(total_amount) AS monthly_sales
FROM orders
GROUP BY YEAR(order_date), MONTH(order_date)
ORDER BY year, month;

-- SOLUTION 6.2:
SELECT 
    c.name,
    AVG(o.total_amount) AS avg_order_value
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;

-- SOLUTION 6.3:
SELECT category, SUM(price * stock) AS total_value
FROM products
GROUP BY category
ORDER BY total_value DESC
LIMIT 1;

-- SOLUTION 6.4:
SELECT c.name, COUNT(o.order_id) AS order_count
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name
HAVING COUNT(o.order_id) > 1;

-- SOLUTION 7.1:
DELIMITER //
CREATE PROCEDURE GetCustomerOrders(IN cust_id INT)
BEGIN
    SELECT * FROM orders WHERE customer_id = cust_id;
END //
DELIMITER ;

-- Call: CALL GetCustomerOrders(1);

-- SOLUTION 7.2:
DELIMITER //
CREATE PROCEDURE PlaceOrder(
    IN cust_id INT,
    IN amount DECIMAL(10,2),
    IN ord_date DATE,
    OUT new_order_id INT
)
BEGIN
    INSERT INTO orders (customer_id, total_amount, order_date)
    VALUES (cust_id, amount, ord_date);
    
    SET new_order_id = LAST_INSERT_ID();
END //
DELIMITER ;

-- Call:
-- CALL PlaceOrder(1, 500.00, CURDATE(), @id);
-- SELECT @id;

-- SOLUTION 7.3:
DELIMITER //
CREATE PROCEDURE UpdateStock(
    IN prod_id INT,
    IN qty INT
)
BEGIN
    DECLARE current_stock INT;
    
    SELECT stock INTO current_stock 
    FROM products 
    WHERE product_id = prod_id;
    
    IF current_stock + qty < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient stock';
    ELSE
        UPDATE products 
        SET stock = stock + qty 
        WHERE product_id = prod_id;
    END IF;
END //
DELIMITER ;

-- SOLUTION 8.1:
DELIMITER //
CREATE TRIGGER validate_price
BEFORE INSERT ON products
FOR EACH ROW
BEGIN
    IF NEW.price < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Price cannot be negative';
    END IF;
END //
DELIMITER ;

-- SOLUTION 8.2:
CREATE TABLE product_price_audit (
    audit_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    old_price DECIMAL(10,2),
    new_price DECIMAL(10,2),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE TRIGGER track_price_changes
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    IF OLD.price != NEW.price THEN
        INSERT INTO product_price_audit (product_id, old_price, new_price)
        VALUES (NEW.product_id, OLD.price, NEW.price);
    END IF;
END //
DELIMITER ;

-- SOLUTION 8.3:
DELIMITER //
CREATE TRIGGER update_order_total
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE orders o
    SET total_amount = (
        SELECT SUM(p.price * oi.quantity)
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = NEW.order_id
    )
    WHERE o.order_id = NEW.order_id;
END //
DELIMITER ;

-- SOLUTION 9.1:
CREATE VIEW customer_summary AS
SELECT 
    c.customer_id,
    c.name,
    COUNT(o.order_id) AS total_orders,
    COALESCE(SUM(o.total_amount), 0) AS total_spent
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;

-- SOLUTION 9.2:
CREATE VIEW low_stock_products AS
SELECT * FROM products WHERE stock < 10;

-- SOLUTION 9.3:
CREATE VIEW monthly_sales_report AS
SELECT 
    YEAR(order_date) AS year,
    MONTH(order_date) AS month,
    COUNT(order_id) AS total_orders,
    SUM(total_amount) AS total_revenue
FROM orders
GROUP BY YEAR(order_date), MONTH(order_date);

-- SOLUTION 10.1:
START TRANSACTION;

UPDATE products 
SET stock = stock - 5 
WHERE product_id = 1 AND stock >= 5;

IF ROW_COUNT() = 0 THEN
    ROLLBACK;
    SELECT 'Insufficient stock in source product' AS error;
ELSE
    UPDATE products 
    SET stock = stock + 5 
    WHERE product_id = 2;
    
    COMMIT;
    SELECT 'Transfer successful' AS message;
END IF;

-- SOLUTION 10.2:
START TRANSACTION;

-- Insert order
INSERT INTO orders (customer_id, order_date, total_amount)
VALUES (1, CURDATE(), 0);

SET @order_id = LAST_INSERT_ID();

-- Insert order items
INSERT INTO order_items (order_id, product_id, quantity)
VALUES (@order_id, 1, 2);

-- Update stock
UPDATE products 
SET stock = stock - 2 
WHERE product_id = 1 AND stock >= 2;

IF ROW_COUNT() = 0 THEN
    ROLLBACK;
    SELECT 'Order failed - insufficient stock' AS error;
ELSE
    -- Update order total
    UPDATE orders o
    SET total_amount = (
        SELECT SUM(p.price * oi.quantity)
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = @order_id
    )
    WHERE o.order_id = @order_id;
    
    COMMIT;
    SELECT 'Order placed successfully' AS message, @order_id AS order_id;
END IF;

-- CHALLENGE 1:
WITH customer_spending AS (
    SELECT 
        c.customer_id,
        c.name,
        c.city,
        SUM(o.total_amount) AS total_spent,
        RANK() OVER (PARTITION BY c.city ORDER BY SUM(o.total_amount) DESC) AS city_rank
    FROM customers c
    JOIN orders o ON c.customer_id = o.customer_id
    GROUP BY c.customer_id, c.name, c.city
)
SELECT * FROM customer_spending
WHERE city_rank <= 3;

-- CHALLENGE 2:
SELECT 
    order_date,
    total_amount,
    SUM(total_amount) OVER (ORDER BY order_date) AS running_total
FROM orders
ORDER BY order_date;

-- CHALLENGE 3:
SELECT c.customer_id, c.name
FROM customers c
WHERE NOT EXISTS (
    SELECT p.product_id
    FROM products p
    WHERE p.category = 'Electronics'
    AND NOT EXISTS (
        SELECT 1
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.customer_id = c.customer_id
        AND oi.product_id = p.product_id
    )
);

*/

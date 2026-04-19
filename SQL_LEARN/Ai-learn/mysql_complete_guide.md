# MySQL Complete Guide - From Zero to Hero

## Table of Contents
1. [Database Basics](#database-basics)
2. [MySQL Setup](#mysql-setup)
3. [DDL - Data Definition Language](#ddl)
4. [DML - Data Manipulation Language](#dml)
5. [Querying Data](#querying-data)
6. [Joins](#joins)
7. [Aggregate Functions & GROUP BY](#aggregates)
8. [Subqueries](#subqueries)
9. [Indexes & Performance](#indexes)
10. [Stored Procedures & Functions](#stored-procedures)
11. [Triggers](#triggers)
12. [Transactions & ACID](#transactions)
13. [Views](#views)
14. [Advanced Topics](#advanced)

---

## 1. Database Basics {#database-basics}

### What is a Database?
A structured collection of data that can be easily accessed, managed, and updated.

### RDBMS Concepts
- **Database**: Collection of tables
- **Table**: Collection of rows and columns
- **Row/Record**: Single entry in a table
- **Column/Field**: Attribute of data
- **Primary Key**: Unique identifier for each record
- **Foreign Key**: Links two tables together
- **Index**: Improves query performance

---

## 2. MySQL Setup {#mysql-setup}

### Installation (XAMPP - you're already using this!)
```bash
# Start MySQL in XAMPP Control Panel
# Access via terminal:
mysql -u root -p

# Or use phpMyAdmin:
# http://localhost/phpmyadmin
```

### Basic Commands
```sql
-- Show all databases
SHOW DATABASES;

-- Create a database
CREATE DATABASE learning_db;

-- Use a database
USE learning_db;

-- Show all tables
SHOW TABLES;

-- Show table structure
DESC table_name;
```

---

## 3. DDL - Data Definition Language {#ddl}

### Creating Tables

```sql
CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    age INT CHECK (age >= 18),
    enrollment_date DATE DEFAULT CURRENT_DATE,
    gpa DECIMAL(3,2)
);
```

### Data Types

**Numeric Types:**
```sql
TINYINT     -- 1 byte (-128 to 127)
INT         -- 4 bytes
BIGINT      -- 8 bytes
DECIMAL(p,s) -- Fixed precision (p=total digits, s=after decimal)
FLOAT       -- Approximate, 4 bytes
DOUBLE      -- Approximate, 8 bytes
```

**String Types:**
```sql
CHAR(n)     -- Fixed length (faster for known sizes)
VARCHAR(n)  -- Variable length (saves space)
TEXT        -- Large text (up to 65,535 chars)
LONGTEXT    -- Very large text (4GB)
ENUM        -- Predefined values
```

**Date/Time Types:**
```sql
DATE        -- YYYY-MM-DD
TIME        -- HH:MM:SS
DATETIME    -- YYYY-MM-DD HH:MM:SS
TIMESTAMP   -- Auto-updates on modification
YEAR        -- YYYY
```

### Constraints

```sql
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) CHECK (total_amount > 0),
    status ENUM('pending', 'processing', 'shipped', 'delivered'),
    
    -- Foreign key
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
```

### Modifying Tables

```sql
-- Add column
ALTER TABLE students ADD phone VARCHAR(15);

-- Modify column
ALTER TABLE students MODIFY COLUMN phone VARCHAR(20);

-- Drop column
ALTER TABLE students DROP COLUMN phone;

-- Rename table
RENAME TABLE students TO learners;

-- Drop table
DROP TABLE IF EXISTS learners;
```

---

## 4. DML - Data Manipulation Language {#dml}

### INSERT - Adding Data

```sql
-- Single insert
INSERT INTO students (name, email, age, gpa) 
VALUES ('Arman Ripon', 'arman@diu.edu', 22, 3.75);

-- Multiple inserts
INSERT INTO students (name, email, age, gpa) VALUES
    ('John Doe', 'john@diu.edu', 21, 3.50),
    ('Jane Smith', 'jane@diu.edu', 23, 3.90),
    ('Mike Wilson', 'mike@diu.edu', 22, 3.65);

-- Insert from SELECT
INSERT INTO honor_students (name, gpa)
SELECT name, gpa FROM students WHERE gpa >= 3.70;
```

### UPDATE - Modifying Data

```sql
-- Update single record
UPDATE students 
SET gpa = 3.80 
WHERE student_id = 1;

-- Update multiple records
UPDATE students 
SET age = age + 1 
WHERE enrollment_date < '2024-01-01';

-- Update with calculation
UPDATE students 
SET gpa = CASE 
    WHEN gpa < 3.0 THEN gpa + 0.1
    ELSE gpa
END;
```

### DELETE - Removing Data

```sql
-- Delete specific records
DELETE FROM students WHERE gpa < 2.0;

-- Delete all records (but keep table structure)
DELETE FROM students;

-- Better for deleting all: TRUNCATE (faster, resets AUTO_INCREMENT)
TRUNCATE TABLE students;
```

---

## 5. Querying Data {#querying-data}

### Basic SELECT

```sql
-- Select all columns
SELECT * FROM students;

-- Select specific columns
SELECT name, gpa FROM students;

-- Select with alias
SELECT name AS student_name, gpa AS grade_point 
FROM students;

-- Select distinct values
SELECT DISTINCT age FROM students;
```

### WHERE Clause - Filtering

```sql
-- Comparison operators
SELECT * FROM students WHERE gpa > 3.5;
SELECT * FROM students WHERE age >= 21;
SELECT * FROM students WHERE name = 'Arman Ripon';

-- BETWEEN
SELECT * FROM students WHERE gpa BETWEEN 3.0 AND 3.5;

-- IN
SELECT * FROM students WHERE age IN (20, 21, 22);

-- LIKE (pattern matching)
SELECT * FROM students WHERE name LIKE 'A%';    -- Starts with A
SELECT * FROM students WHERE email LIKE '%@diu.edu';  -- Ends with
SELECT * FROM students WHERE name LIKE '%man%'; -- Contains

-- IS NULL / IS NOT NULL
SELECT * FROM students WHERE email IS NULL;

-- Logical operators
SELECT * FROM students 
WHERE gpa > 3.5 AND age < 23;

SELECT * FROM students 
WHERE gpa > 3.8 OR age > 24;

SELECT * FROM students 
WHERE NOT (gpa < 3.0);
```

### ORDER BY - Sorting

```sql
-- Ascending (default)
SELECT * FROM students ORDER BY gpa;

-- Descending
SELECT * FROM students ORDER BY gpa DESC;

-- Multiple columns
SELECT * FROM students 
ORDER BY gpa DESC, name ASC;
```

### LIMIT - Restricting Results

```sql
-- First 5 records
SELECT * FROM students LIMIT 5;

-- Pagination: skip 10, get next 5
SELECT * FROM students LIMIT 10, 5;
-- or
SELECT * FROM students LIMIT 5 OFFSET 10;

-- Top 3 students
SELECT * FROM students 
ORDER BY gpa DESC 
LIMIT 3;
```

---

## 6. Joins {#joins}

### Sample Tables Setup

```sql
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    city VARCHAR(50)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    amount DECIMAL(10,2),
    order_date DATE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

-- Sample data
INSERT INTO customers (name, city) VALUES
    ('Arman', 'Dhaka'),
    ('Sabbir', 'Chittagong'),
    ('Hasib', 'Sylhet'),
    ('Rahul', 'Khulna');

INSERT INTO orders (customer_id, amount, order_date) VALUES
    (1, 5000.00, '2024-01-15'),
    (1, 3000.00, '2024-02-20'),
    (2, 7500.00, '2024-01-18'),
    (4, 2000.00, '2024-03-10');
```

### INNER JOIN
Returns records with matching values in both tables.

```sql
SELECT c.name, o.amount, o.order_date
FROM customers c
INNER JOIN orders o ON c.customer_id = o.customer_id;

-- Result: Only customers who have orders
```

### LEFT JOIN (LEFT OUTER JOIN)
Returns all records from left table, matching records from right.

```sql
SELECT c.name, o.amount, o.order_date
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id;

-- Result: All customers, NULL for those without orders
```

### RIGHT JOIN
Returns all records from right table, matching records from left.

```sql
SELECT c.name, o.amount, o.order_date
FROM customers c
RIGHT JOIN orders o ON c.customer_id = o.customer_id;
```

### CROSS JOIN
Cartesian product (every row combined with every row).

```sql
SELECT c.name, o.amount
FROM customers c
CROSS JOIN orders o;
-- Result: 4 customers × 4 orders = 16 rows
```

### Self Join
Join table to itself.

```sql
-- Find students with same age
SELECT s1.name AS student1, s2.name AS student2, s1.age
FROM students s1
JOIN students s2 ON s1.age = s2.age AND s1.student_id < s2.student_id;
```

### Multiple Joins

```sql
CREATE TABLE products (
    product_id INT PRIMARY KEY,
    product_name VARCHAR(100),
    price DECIMAL(10,2)
);

CREATE TABLE order_items (
    item_id INT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Join 3 tables
SELECT 
    c.name AS customer,
    p.product_name,
    oi.quantity,
    p.price * oi.quantity AS total
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id;
```

---

## 7. Aggregate Functions & GROUP BY {#aggregates}

### Aggregate Functions

```sql
-- COUNT
SELECT COUNT(*) FROM students;
SELECT COUNT(DISTINCT age) FROM students;

-- SUM
SELECT SUM(amount) AS total_sales FROM orders;

-- AVG
SELECT AVG(gpa) AS average_gpa FROM students;

-- MAX / MIN
SELECT MAX(gpa) AS highest_gpa FROM students;
SELECT MIN(gpa) AS lowest_gpa FROM students;
```

### GROUP BY

```sql
-- Count students by age
SELECT age, COUNT(*) AS student_count
FROM students
GROUP BY age;

-- Total sales per customer
SELECT customer_id, SUM(amount) AS total_spent
FROM orders
GROUP BY customer_id;

-- Average GPA by enrollment year
SELECT 
    YEAR(enrollment_date) AS year,
    AVG(gpa) AS avg_gpa
FROM students
GROUP BY YEAR(enrollment_date);
```

### HAVING - Filtering Groups

```sql
-- Customers who spent more than 5000
SELECT customer_id, SUM(amount) AS total
FROM orders
GROUP BY customer_id
HAVING SUM(amount) > 5000;

-- Ages with more than 2 students
SELECT age, COUNT(*) AS count
FROM students
GROUP BY age
HAVING COUNT(*) > 2;
```

### GROUP BY with Joins

```sql
SELECT 
    c.name,
    COUNT(o.order_id) AS order_count,
    SUM(o.amount) AS total_spent
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;
```

---

## 8. Subqueries {#subqueries}

### Subquery in WHERE

```sql
-- Students with above-average GPA
SELECT name, gpa
FROM students
WHERE gpa > (SELECT AVG(gpa) FROM students);

-- Customers who placed orders
SELECT name
FROM customers
WHERE customer_id IN (
    SELECT DISTINCT customer_id FROM orders
);
```

### Subquery in FROM (Derived Table)

```sql
SELECT avg_data.year, avg_data.avg_gpa
FROM (
    SELECT 
        YEAR(enrollment_date) AS year,
        AVG(gpa) AS avg_gpa
    FROM students
    GROUP BY YEAR(enrollment_date)
) AS avg_data
WHERE avg_data.avg_gpa > 3.5;
```

### Correlated Subquery

```sql
-- Students with GPA higher than average of their age group
SELECT s1.name, s1.age, s1.gpa
FROM students s1
WHERE s1.gpa > (
    SELECT AVG(s2.gpa)
    FROM students s2
    WHERE s2.age = s1.age
);
```

### EXISTS

```sql
-- Customers with at least one order
SELECT name
FROM customers c
WHERE EXISTS (
    SELECT 1 FROM orders o 
    WHERE o.customer_id = c.customer_id
);
```

---

## 9. Indexes & Performance {#indexes}

### Creating Indexes

```sql
-- Single column index
CREATE INDEX idx_student_name ON students(name);

-- Composite index
CREATE INDEX idx_name_age ON students(name, age);

-- Unique index
CREATE UNIQUE INDEX idx_email ON students(email);

-- Full-text index (for text search)
CREATE FULLTEXT INDEX idx_bio ON students(bio);
```

### Viewing Indexes

```sql
SHOW INDEX FROM students;
```

### Dropping Indexes

```sql
DROP INDEX idx_student_name ON students;
```

### When to Use Indexes
✅ **Use indexes on:**
- Primary keys (automatic)
- Foreign keys
- Columns in WHERE clauses
- Columns in JOIN conditions
- Columns in ORDER BY

❌ **Avoid indexes on:**
- Small tables
- Columns with frequent updates
- Columns with low cardinality (few distinct values)

### Query Optimization

```sql
-- Explain query execution plan
EXPLAIN SELECT * FROM students WHERE age = 22;

-- Analyze table
ANALYZE TABLE students;
```

---

## 10. Stored Procedures & Functions {#stored-procedures}

### Stored Procedures

```sql
-- Simple procedure
DELIMITER //
CREATE PROCEDURE GetAllStudents()
BEGIN
    SELECT * FROM students;
END //
DELIMITER ;

-- Call procedure
CALL GetAllStudents();

-- Procedure with parameters
DELIMITER //
CREATE PROCEDURE GetStudentsByGPA(IN min_gpa DECIMAL(3,2))
BEGIN
    SELECT * FROM students WHERE gpa >= min_gpa;
END //
DELIMITER ;

CALL GetStudentsByGPA(3.5);

-- Procedure with OUT parameter
DELIMITER //
CREATE PROCEDURE GetStudentCount(OUT total INT)
BEGIN
    SELECT COUNT(*) INTO total FROM students;
END //
DELIMITER ;

CALL GetStudentCount(@count);
SELECT @count;

-- Procedure with INOUT
DELIMITER //
CREATE PROCEDURE IncrementGPA(INOUT student_gpa DECIMAL(3,2))
BEGIN
    SET student_gpa = student_gpa + 0.1;
END //
DELIMITER ;

SET @my_gpa = 3.50;
CALL IncrementGPA(@my_gpa);
SELECT @my_gpa;
```

### Control Flow in Procedures

```sql
DELIMITER //
CREATE PROCEDURE GradeStudent(IN student_gpa DECIMAL(3,2))
BEGIN
    IF student_gpa >= 3.7 THEN
        SELECT 'A Grade';
    ELSEIF student_gpa >= 3.0 THEN
        SELECT 'B Grade';
    ELSEIF student_gpa >= 2.0 THEN
        SELECT 'C Grade';
    ELSE
        SELECT 'F Grade';
    END IF;
END //
DELIMITER ;

-- CASE statement
DELIMITER //
CREATE PROCEDURE GetGrade(IN gpa DECIMAL(3,2), OUT grade CHAR(1))
BEGIN
    SET grade = CASE
        WHEN gpa >= 3.7 THEN 'A'
        WHEN gpa >= 3.0 THEN 'B'
        WHEN gpa >= 2.0 THEN 'C'
        ELSE 'F'
    END;
END //
DELIMITER ;

-- Loop
DELIMITER //
CREATE PROCEDURE InsertNumbers(IN max_num INT)
BEGIN
    DECLARE counter INT DEFAULT 1;
    WHILE counter <= max_num DO
        INSERT INTO numbers VALUES (counter);
        SET counter = counter + 1;
    END WHILE;
END //
DELIMITER ;
```

### User-Defined Functions

```sql
DELIMITER //
CREATE FUNCTION CalculateGrade(gpa DECIMAL(3,2))
RETURNS CHAR(1)
DETERMINISTIC
BEGIN
    DECLARE grade CHAR(1);
    IF gpa >= 3.7 THEN SET grade = 'A';
    ELSEIF gpa >= 3.0 THEN SET grade = 'B';
    ELSEIF gpa >= 2.0 THEN SET grade = 'C';
    ELSE SET grade = 'F';
    END IF;
    RETURN grade;
END //
DELIMITER ;

-- Use function
SELECT name, gpa, CalculateGrade(gpa) AS grade
FROM students;
```

### Managing Procedures/Functions

```sql
-- Show all procedures
SHOW PROCEDURE STATUS WHERE Db = 'learning_db';

-- Show procedure code
SHOW CREATE PROCEDURE GetAllStudents;

-- Drop procedure
DROP PROCEDURE IF EXISTS GetAllStudents;

-- Drop function
DROP FUNCTION IF EXISTS CalculateGrade;
```

---

## 11. Triggers {#triggers}

### BEFORE INSERT Trigger

```sql
DELIMITER //
CREATE TRIGGER before_student_insert
BEFORE INSERT ON students
FOR EACH ROW
BEGIN
    -- Auto-set enrollment date if not provided
    IF NEW.enrollment_date IS NULL THEN
        SET NEW.enrollment_date = CURRENT_DATE;
    END IF;
    
    -- Validate GPA
    IF NEW.gpa < 0 OR NEW.gpa > 4 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'GPA must be between 0 and 4';
    END IF;
END //
DELIMITER ;
```

### AFTER INSERT Trigger

```sql
-- Create audit table
CREATE TABLE student_audit (
    audit_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    action VARCHAR(10),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //
CREATE TRIGGER after_student_insert
AFTER INSERT ON students
FOR EACH ROW
BEGIN
    INSERT INTO student_audit (student_id, action)
    VALUES (NEW.student_id, 'INSERT');
END //
DELIMITER ;
```

### UPDATE Trigger

```sql
DELIMITER //
CREATE TRIGGER before_student_update
BEFORE UPDATE ON students
FOR EACH ROW
BEGIN
    -- Prevent GPA decrease
    IF NEW.gpa < OLD.gpa THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'GPA cannot decrease';
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_student_update
AFTER UPDATE ON students
FOR EACH ROW
BEGIN
    INSERT INTO student_audit (student_id, action)
    VALUES (NEW.student_id, 'UPDATE');
END //
DELIMITER ;
```

### DELETE Trigger

```sql
-- Archive deleted students
CREATE TABLE deleted_students LIKE students;

DELIMITER //
CREATE TRIGGER before_student_delete
BEFORE DELETE ON students
FOR EACH ROW
BEGIN
    INSERT INTO deleted_students 
    SELECT * FROM students WHERE student_id = OLD.student_id;
END //
DELIMITER ;
```

### Managing Triggers

```sql
-- Show all triggers
SHOW TRIGGERS;

-- Show trigger code
SHOW CREATE TRIGGER before_student_insert;

-- Drop trigger
DROP TRIGGER IF EXISTS before_student_insert;
```

---

## 12. Transactions & ACID {#transactions}

### ACID Properties
- **Atomicity**: All or nothing
- **Consistency**: Valid state before/after
- **Isolation**: Concurrent transactions don't interfere
- **Durability**: Committed data persists

### Basic Transaction

```sql
START TRANSACTION;

UPDATE accounts SET balance = balance - 1000 WHERE account_id = 1;
UPDATE accounts SET balance = balance + 1000 WHERE account_id = 2;

COMMIT;  -- Save changes

-- OR

ROLLBACK;  -- Undo changes
```

### Transaction with Error Handling

```sql
START TRANSACTION;

-- Transfer money
UPDATE accounts 
SET balance = balance - 1000 
WHERE account_id = 1 AND balance >= 1000;

-- Check if update succeeded
IF ROW_COUNT() = 0 THEN
    ROLLBACK;
    SELECT 'Insufficient funds' AS error;
ELSE
    UPDATE accounts 
    SET balance = balance + 1000 
    WHERE account_id = 2;
    COMMIT;
    SELECT 'Transfer successful' AS message;
END IF;
```

### Savepoints

```sql
START TRANSACTION;

INSERT INTO students VALUES (...);
SAVEPOINT sp1;

INSERT INTO students VALUES (...);
SAVEPOINT sp2;

INSERT INTO students VALUES (...);

-- Rollback to savepoint
ROLLBACK TO sp2;  -- Keeps first 2 inserts

COMMIT;
```

### Isolation Levels

```sql
-- View current isolation level
SELECT @@transaction_isolation;

-- Set isolation level
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;

-- Levels (from least to most strict):
-- READ UNCOMMITTED
-- READ COMMITTED (default in most systems)
-- REPEATABLE READ (MySQL default)
-- SERIALIZABLE
```

---

## 13. Views {#views}

### Creating Views

```sql
-- Simple view
CREATE VIEW high_achievers AS
SELECT name, gpa, age
FROM students
WHERE gpa >= 3.7;

-- Use view like a table
SELECT * FROM high_achievers;

-- Complex view with joins
CREATE VIEW customer_orders AS
SELECT 
    c.customer_id,
    c.name,
    COUNT(o.order_id) AS total_orders,
    SUM(o.amount) AS total_spent
FROM customers c
LEFT JOIN orders o ON c.customer_id = o.customer_id
GROUP BY c.customer_id, c.name;
```

### Updating Through Views

```sql
-- Only works if view is updatable
UPDATE high_achievers 
SET gpa = 3.90 
WHERE name = 'Arman Ripon';

-- Create updatable view with check option
CREATE VIEW adult_students AS
SELECT * FROM students
WHERE age >= 18
WITH CHECK OPTION;  -- Prevents updates that violate WHERE clause
```

### Managing Views

```sql
-- Show all views
SHOW FULL TABLES WHERE Table_type = 'VIEW';

-- Show view definition
SHOW CREATE VIEW high_achievers;

-- Modify view
ALTER VIEW high_achievers AS
SELECT name, gpa, age, enrollment_date
FROM students
WHERE gpa >= 3.5;

-- Drop view
DROP VIEW IF EXISTS high_achievers;
```

---

## 14. Advanced Topics {#advanced}

### Window Functions (MySQL 8.0+)

```sql
-- Row number
SELECT 
    name, 
    gpa,
    ROW_NUMBER() OVER (ORDER BY gpa DESC) AS rank
FROM students;

-- Rank with gaps for ties
SELECT 
    name, 
    gpa,
    RANK() OVER (ORDER BY gpa DESC) AS rank
FROM students;

-- Dense rank (no gaps)
SELECT 
    name, 
    gpa,
    DENSE_RANK() OVER (ORDER BY gpa DESC) AS rank
FROM students;

-- Partition by
SELECT 
    name, 
    age,
    gpa,
    RANK() OVER (PARTITION BY age ORDER BY gpa DESC) AS rank_in_age_group
FROM students;

-- Running total
SELECT 
    order_date,
    amount,
    SUM(amount) OVER (ORDER BY order_date) AS running_total
FROM orders;
```

### Common Table Expressions (CTE)

```sql
-- Simple CTE
WITH high_gpa AS (
    SELECT * FROM students WHERE gpa >= 3.5
)
SELECT * FROM high_gpa WHERE age < 23;

-- Multiple CTEs
WITH 
    top_students AS (
        SELECT * FROM students WHERE gpa >= 3.7
    ),
    top_customers AS (
        SELECT customer_id, SUM(amount) AS total
        FROM orders
        GROUP BY customer_id
        HAVING SUM(amount) > 5000
    )
SELECT * FROM top_students
UNION
SELECT * FROM top_customers;

-- Recursive CTE (organizational hierarchy)
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor: Top-level employees
    SELECT employee_id, name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id IS NULL
    
    UNION ALL
    
    -- Recursive: Employees under each manager
    SELECT e.employee_id, e.name, e.manager_id, eh.level + 1
    FROM employees e
    JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
SELECT * FROM employee_hierarchy;
```

### JSON Functions (MySQL 5.7+)

```sql
-- Create table with JSON
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    profile JSON
);

-- Insert JSON data
INSERT INTO user_profiles VALUES 
(1, '{"name": "Arman", "skills": ["PHP", "MySQL", "React"], "rating": 4.5}');

-- Query JSON
SELECT 
    user_id,
    JSON_EXTRACT(profile, '$.name') AS name,
    JSON_EXTRACT(profile, '$.skills[0]') AS first_skill
FROM user_profiles;

-- Using -> operator (shorthand)
SELECT 
    profile->>'$.name' AS name,
    profile->>'$.rating' AS rating
FROM user_profiles;

-- Modify JSON
UPDATE user_profiles
SET profile = JSON_SET(profile, '$.rating', 5.0)
WHERE user_id = 1;
```

### Full-Text Search

```sql
-- Create full-text index
ALTER TABLE articles 
ADD FULLTEXT(title, content);

-- Search
SELECT * FROM articles
WHERE MATCH(title, content) AGAINST ('MySQL database');

-- Boolean mode
SELECT * FROM articles
WHERE MATCH(title, content) 
AGAINST ('+MySQL -Oracle' IN BOOLEAN MODE);
```

### Partitioning

```sql
-- Range partitioning (by date)
CREATE TABLE sales (
    sale_id INT,
    sale_date DATE,
    amount DECIMAL(10,2)
)
PARTITION BY RANGE (YEAR(sale_date)) (
    PARTITION p2022 VALUES LESS THAN (2023),
    PARTITION p2023 VALUES LESS THAN (2024),
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p_future VALUES LESS THAN MAXVALUE
);

-- List partitioning
CREATE TABLE customers_regional (
    customer_id INT,
    name VARCHAR(100),
    region VARCHAR(50)
)
PARTITION BY LIST COLUMNS(region) (
    PARTITION p_north VALUES IN ('Dhaka', 'Rajshahi'),
    PARTITION p_south VALUES IN ('Chittagong', 'Khulna'),
    PARTITION p_east VALUES IN ('Sylhet', 'Comilla')
);
```

---

## Practice Exercises

### Exercise 1: University Database
Create a complete university management system with:
- Students, Courses, Enrollments tables
- Procedures for enrollment, grade calculation
- Triggers for capacity management
- Views for honor roll, course statistics

### Exercise 2: E-commerce Database
Build an e-commerce system with:
- Products, Orders, Customers, Reviews
- Transaction handling for orders
- Inventory management triggers
- Sales analytics with window functions

### Exercise 3: Banking System
Implement:
- Accounts, Transactions, Branches
- Transfer procedure with proper transaction handling
- Audit trail with triggers
- Balance calculation with CTEs

---

## Best Practices

1. **Naming Conventions**
   - Use lowercase with underscores: `customer_orders`
   - Be descriptive: `total_amount` not `amt`
   - Prefix tables by domain if needed: `finance_accounts`

2. **Performance**
   - Index foreign keys
   - Use EXPLAIN to analyze queries
   - Avoid SELECT * in production
   - Use appropriate data types
   - Normalize to 3NF (usually)

3. **Security**
   - Never store passwords in plain text
   - Use prepared statements (prevents SQL injection)
   - Principle of least privilege for users
   - Regular backups

4. **Data Integrity**
   - Use foreign keys
   - Add appropriate constraints
   - Validate data in triggers if needed
   - Use transactions for related operations

---

## Quick Reference Commands

```sql
-- Database
CREATE DATABASE db_name;
DROP DATABASE db_name;
USE db_name;

-- Table
CREATE TABLE table_name (...);
DROP TABLE table_name;
ALTER TABLE table_name ADD column_name datatype;

-- Data
INSERT INTO table_name VALUES (...);
UPDATE table_name SET ... WHERE ...;
DELETE FROM table_name WHERE ...;

-- Query
SELECT ... FROM ... WHERE ... GROUP BY ... HAVING ... ORDER BY ... LIMIT ...;

-- Join
INNER JOIN, LEFT JOIN, RIGHT JOIN, CROSS JOIN

-- Functions
COUNT(), SUM(), AVG(), MAX(), MIN()
CONCAT(), SUBSTRING(), UPPER(), LOWER()
NOW(), DATE(), YEAR(), MONTH()

-- Advanced
CREATE INDEX, CREATE VIEW, CREATE PROCEDURE, CREATE TRIGGER
START TRANSACTION, COMMIT, ROLLBACK
```

---

Happy Learning! 🚀

For hands-on practice, try:
1. Build your own project database
2. Practice on LeetCode SQL problems
3. Set up sample databases (Sakila, Employees)
4. Work through MySQL official documentation

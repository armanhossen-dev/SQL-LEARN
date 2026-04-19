-- SELECT statement is used to select data from a database

show databases;
create database w3scl;
use w3scl;

--CustomerID, CustomerName, ContactName, Address, City, PostalCode, and Country).,
create table customers(
    CustomerID INT PRIMARY KEY AUTO_INCREMENT,
    CustomerName VARCHAR(100) NOT NULL, 
    ContactName VARCHAR(100) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    City VARCHAR(100) NOT NULL,
    PostalCode VARCHAR(100) NOT NULL,
    Country VARCHAR(100) NOT NULL    
);

DESC customers; -- describes table!

INSERT INTO customers VALUES
(1, 'ARMAN', 'AHRN', 'Ashulia, Savar', 'Dhaka', '12011', 'Bangladesh');

INSERT INTO customers (CustomerName, ContactName, Address, City, PostalCode, Country) VALUES
('Rakib Hasan', 'Arman', 'Ashulia, Savar', 'Dhaka', '12011', 'Bangladesh'),
('Rahul vau', 'Rahul', 'Dhanmondi 27', 'Dhaka', '1209', 'Bangladesh'),
('Sabbir Ahmed', 'Sabbir', 'Agrabad', 'Chittagong', '4100', 'Bangladesh'),
('Saief Karim', 'Saief', 'Khulna Sadar', 'Khulna', '9000', 'Bangladesh'),
('Hasib Hossain', 'Tanvir', 'Sylhet Town', 'Sylhet', '3100', 'Bangladesh');


select * from customers;
+------------+---------------+-------------+----------------+------------+------------+------------+
| CustomerID | CustomerName  | ContactName | Address        | City       | PostalCode | Country    |
+------------+---------------+-------------+----------------+------------+------------+------------+
|          1 | ARMAN         | AHRN        | Ashulia, Savar | Dhaka      | 12011      | Bangladesh |
|          2 | Rakib Hasan   | Arman       | Ashulia, Savar | Dhaka      | 12011      | Bangladesh |
|          3 | Rahul vau     | Rahul       | Dhanmondi 27   | Dhaka      | 1209       | Bangladesh |
|          4 | Sabbir Ahmed  | Sabbir      | Agrabad        | Chittagong | 4100       | Bangladesh |
|          5 | Saief Karim   | Saief       | Khulna Sadar   | Khulna     | 9000       | Bangladesh |
|          6 | Hasib Hossain | Tanvir      | Sylhet Town    | Sylhet     | 3100       | Bangladesh |
+------------+---------------+-------------+----------------+------------+------------+------------+


select CustomerID, City FROM customers;
+------------+------------+
| CustomerID | City       |
+------------+------------+
|          1 | Dhaka      |
|          2 | Dhaka      |
|          3 | Dhaka      |
|          4 | Chittagong |
|          5 | Khulna     |
|          6 | Sylhet     |
+------------+------------+
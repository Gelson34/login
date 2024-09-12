CREATE DATABASE login_db;
USE login_db;

CREATE TABLE logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

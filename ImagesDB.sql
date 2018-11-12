--Craig Huff 11/10/18 Project 3 - Part 1

-- These are the SQL commands used to generate the database for the start of the project. 
-- This configuration is not final and will be updated as new features are added accordingly.

CREATE DATABASE IMAGINEIMAGES;

USE IMAGINEIMAGES;

CREATE TABLE IF NOT EXISTS Users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50),
  reg_date TIMESTAMP,
  token VARCHAR(255),
  sub INT(20) UNIQUE
);

CREATE TABLE IF NOT EXISTS UploadedImages (
  image_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT(20) UNSIGNED NOT NULL,
  image_name VARCHAR(20) NOT NULL UNIQUE,
  filepath VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS PurchasedImages (
  image_id INT(6) UNSIGNED PRIMARY KEY,
  publisher_id INT(20) UNSIGNED NOT NULL,
  buyer_id INT(20) UNSIGNED NOT NULL,
  image_name VARCHAR(20) NOT NULL UNIQUE,
  filepath VARCHAR(50) NOT NULL
);


SELECT image_name FROM UploadedImages;

-- Test to insert into the users database --
INSERT INTO Users (name, email, token , sub) values ('Craig Huff', 'test@craig.com', 'eToken', 10 );
INSERT INTO Users (name, email, token , sub) values ('Kevin Huff', 'test@kevin.com', 'eToken1', 11 );

-- Test to insert into the uploaded images database --
-- *** NOTE: These are not acutal images, just plaintext to simulate a filepath and file name ***--
INSERT INTO UploadedImages (user_id, image_name, filepath) values (10, 'img1.jpg', 'uploads/img1.jpg' );
INSERT INTO UploadedImages (user_id, image_name, filepath) values (10, 'img2.jpg', 'uploads/img2.jpg' );
INSERT INTO UploadedImages (user_id, image_name, filepath) values (11, 'img3.jpg', 'uploads/img3.jpg');

-- Test to insert into the purchased images database --
-- *** NOTE: These are not acutal images, just plaintext to simulate a filepath and file name ***--
-- This is not implemeted in the project yet, just as an example of the database --

INSERT INTO PurchasedImages (image_id, publisher_id, buyer_id , image_name, filepath) values (2, 11, 10, 'img3.jpg', 'uploads/img3.jpg');
INSERT INTO PurchasedImages (image_id, publisher_id, buyer_id , image_name, filepath) values (0, 10, 11, 'img1.jpg', 'uploads/img1.jpg');

SELECT * from Users;

SELECT image_name 
FROM PurchasedImages
WHERE buyer_id = 10;
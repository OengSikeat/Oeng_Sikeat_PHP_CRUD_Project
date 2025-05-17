CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL UNIQUE,
                       password TEXT NOT NULL,
                       role VARCHAR(50) NOT NULL DEFAULT 'user'
);

CREATE TABLE stocks (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        quantity INT NOT NULL
);

CREATE TABLE staff (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(255) NOT NULL,
                       role VARCHAR(100) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE purchases (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           stock_id INT,
                           quantity INT NOT NULL,
                           price DECIMAL(10,2) NOT NULL,
                           purchase_date DATETIME NOT NULL,
                           FOREIGN KEY (stock_id) REFERENCES stocks(id)
);

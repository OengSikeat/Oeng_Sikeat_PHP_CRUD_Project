CREATE TABLE users (
                       id SERIAL PRIMARY KEY,
                       username VARCHAR(255) NOT NULL UNIQUE,
                       password TEXT NOT NULL,
                       role VARCHAR(50) NOT NULL DEFAULT 'user'
);

CREATE TABLE stocks (
                        id SERIAL PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        quantity INT NOT NULL
);

CREATE TABLE staff (
                       id SERIAL PRIMARY KEY,
                       name VARCHAR(255) NOT NULL,
                       role VARCHAR(100) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE purchases (
                           id SERIAL PRIMARY KEY,
                           stock_id INT REFERENCES stocks(id),
                           quantity INT NOT NULL,
                           price DECIMAL(10,2) NOT NULL,
                           purchase_date TIMESTAMP NOT NULL
);
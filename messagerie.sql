CREATE DATABASE messagerie

CREATE TABLE messages
(
    id INT
    AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR
    (255) NOT NULL,
    receiver VARCHAR
    (255) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
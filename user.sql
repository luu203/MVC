CREATE DATABASE utilisateurs_data;

USE utilisateurs_data;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    adresse VARCHAR(50) NOT NULL,
    ville VARCHAR(50) NOT NULL,
    code_postal VARCHAR(5) NOT NULL
);


CREATE TABLE IF NOT EXISTS annonces(

    id_annonce INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    contrainte varchar(50) NOT NULL,
    category VARCHAR(10) NOT NULL, 
    id_utilisateur INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id),
    annonce_image BLOB,
    Date_annonce TIMESTAMP,  

)



CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(255) NOT NULL,
    receiver VARCHAR(255) NOT NULL,
    message_content TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
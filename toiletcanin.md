## Explication :

Here all the sql query to create the database "ToiletageCanin"

# Creation of the Database :
```sql
CREATE DATABASE ToiletageCanin;
```

# Creation of all the Tables :

```sql
CREATE TABLE customers
(
	id int PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(45),
    lastname VARCHAR(45),
    mail VARCHAR(255),
    telephone VARCHAR(10),
    postal_adress VARCHAR(45),
    commentary LONGTEXT
);

CREATE TABLE users
(
	id int PRIMARY KEY AUTO_INCREMENT,
    is_admin tinyint,
    firstname VARCHAR(45),
    lastname VARCHAR(45),
    telephone VARCHAR(10),
    mail VARCHAR(255),
    postal_adress VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE services
(
	id int PRIMARY KEY AUTO_INCREMENT,
   	name VARCHAR(45),
    price FLOAT
);

CREATE TABLE animals
(
    id int PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(45),
    breed VARCHAR(45),
    age INT,
    weight INT,
    height INT,
    commentary LONGTEXT,
    customer_id int,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE appointments
(
	id int PRIMARY KEY AUTO_INCREMENT,
   	date_start DATETIME,
   	date_end DATETIME,
   	is_paid TINYINT,
    user_id int,
    FOREIGN KEY (user_id) REFERENCES users(id),
    animal_id int,
    FOREIGN KEY (animal_id) REFERENCES animals(id),
    service_id int,
    FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE capabilities
(
    user_id int,
    service_id int,
    PRIMARY KEY (user_id, service_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Création d'un jeu de données pour chaque Table


INSERT INTO customers (firstname, lastname, mail, telephone, postal_adress, commentary) VALUES
("Emily", "Johnson", "emily.johnson@example.com", "0524665457", "14 rue des fleurs", ""),
("Daniel", "Wilson", "daniel.wilson@example.com", "0245876954", "456 Elm Avenue", ""),
("Olivia", "Smith", "olivia.smith@example.com", "0354876152", "789 Oak Road", ""),
("Samuel", "Taylor", "samuel.taylor@example.com", "0345812654", "567 Pine Lane", "");

INSERT INTO animals (name, breed, age, weight, height, commentary, customer_id) VALUES
("Isla", "Labrador Retriever", 5, 60, 152, "/", "1"),
("Milo", "German Shepherd", 4, 57, 150, "Cute dog", "2"),
("Aurora", "Golden Retriever", 8, 45, 100, "/", "3"),
("Zephyr", "Bulldog", 1, 56, 120, "/", "4");

INSERT INTO users (is_admin ,firstname, lastname, telephone, mail, postal_adress, password) VALUES
(1, "Pierre", "Dupont", "0123456789", "pierre.dupont@example.com", "123 Rue de la Liberté", "P@ssw0rd1"),
(0, "Sophie", "Martin", "0612345678", "sophie.martin@email.fr", "45 Rue de la Paix", "Secure123!"),
(0, "Jean", "Dubois", "0456789012", "jean.dubois@email.fr", "789 Avenue des Champs-Élysées", "Pass1234"),
(0, "Marie", "Leclerc", "0345678001", "marie.leclerc@example.com", "567 Rue de la République", "SecretPwd"),
(0, "Olivier", "Laurent", "0234567890", "olivier.laurent@email.fr", "234 Boulevard Saint-Germain", "MyP@ssw0rd");

INSERT INTO services (name, price) VALUES
("toilettage", 20),
("découpage", 15.80),
("vaccination", 19),
("shampoing", 15);

INSERT INTO capabilities (user_id, service_id) VALUES
(2, 1),
(2, 2),
(2, 3),
(3, 3),
(3, 4),
(4, 1),
(5, 1),
(5, 4);

INSERT INTO appointments (date_start, date_end, is_paid, user_id, animal_id, service_id) VALUES
('2023-11-15 14:00:00', '2023-11-15 15:00:00', 0, 4, 3, 1),
('2023-11-17 10:00:00', '2023-11-17 11:30:00', 1, 2, 1, 2),
('2023-11-21 15:00:00', '2023-11-21 16:00:00', 1, 5, 4, 4);
```
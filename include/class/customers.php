<?php
require_once 'include/conn.php';

class Customer {
    
    public $connexion;
    public $id;
    public $firstname;
    public $lastname;
    public $mail;
    public $telephone;
    public $postal_adress;
    public $commentary;

    function __construct($customer_bdd = NULL) {
        $this->connexion = new Connexion();
        if ($customer_bdd !== NULL) {
            if (is_array($customer_bdd)) {
                $this->id = $customer_bdd['id'];
                $this->firstname = $customer_bdd['firstname'];
                $this->lastname = $customer_bdd['lastname'];
                $this->mail = $customer_bdd['mail'];
                $this->telephone = $customer_bdd['telephone'];
                $this->postal_adress = $customer_bdd['postal_adress'];
                $this->commentary = $customer_bdd['commentary'];
            } else {
                $this->id = $customer_bdd->id;
                $this->firstname = $customer_bdd->firstname;
                $this->lastname = $customer_bdd->lastname;
                $this->mail = $customer_bdd->mail;
                $this->telephone = $customer_bdd->telephone;
                $this->postal_adress = $customer_bdd->postal_adress;
                $this->commentary = $customer_bdd->commentary;
            }
        }
    }

    public function getAll() {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM customers;");
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Database query failed.");
        }
        $customers = [];
        while ($customer_bdd = $result->fetch_assoc()) {
            $customers[] = new Customer($customer_bdd);
        }        
        return $customers;
    }
    
    public function getByMail($mail) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM customers WHERE mail = ? ");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $customerData = $result->fetch_assoc();
        return new Customer($customerData);
    }
    
    public function getByName($name) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM customers WHERE CONCAT(firstname,' ',lastname) = ?;");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $customerData = $result->fetch_assoc();
        return new Customer($customerData);
        
    }

    public function getCount() {
        $stmt = $this->connexion->conn->prepare("SELECT COUNT(*) as count FROM customers;");
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Database query failed.");
        }        
        $countData = $result->fetch_assoc();
        return $countData['count'];
    }
    
    public function Exist($mail) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM customers WHERE mail = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();  // Close the statement after getting the result
        return $result->num_rows === 1;
    }
    
    public function AddCustomer($firstname, $lastname, $mail, $telephone, $postal_adress, $commentary) {
        if ($this->Exist($mail) === false) {
            if (
            strlen($firstname) > 0 && strlen($firstname) <= 45 &&
            strlen($lastname) > 0 && strlen($lastname) <= 45 &&
            strlen($mail) > 0 && strlen($mail) <= 45 &&
            strlen($telephone) === 10 &&
            strlen($postal_adress) >= 0 && strlen($postal_adress) <= 100 &&
            strlen($commentary) >= 0 && strlen($commentary) <= 255
            ){
                $stmt = $this->connexion->conn->prepare("INSERT INTO customers (firstname, lastname, mail, telephone, postal_adress, commentary) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $firstname, $lastname, $mail, $telephone, $postal_adress, $commentary);
                $stmt->execute();
                $stmt->close();
                $_SESSION["error_message_InscriptionCustomer"] = NULL;
            } else {
                $_SESSION["error_message_InscriptionCustomer"] = "Données d'entrée non-valide. <br>(le prénom, nom et l'adresse mail doivent faire entre 0 et 45 caractères. le téléphone doit fait exactement 10 caractères.)";
            }
        } else {
            $_SESSION["error_message_InscriptionCustomer"] = "Le client existe déjà.";
        }
    }

    public function getById($id) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM customers WHERE id = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $CustomerData = $result->fetch_assoc();
        return new Customer($CustomerData);
    }
    
    public function getNames() {
        $stmt = $this->connexion->conn->prepare("SELECT CONCAT(firstname,' ',lastname) as name FROM customers");
        $stmt->execute();
        $result = $stmt->get_result();
        $customers = [];
        while ($customerData = $result->fetch_assoc()) {
            $customers[] = $customerData['name'];
        }
        return $customers;
    }

    public function constructList() {
        $stmt = $this->connexion->conn->prepare("SELECT CONCAT(c.firstname, ' ', c.lastname) as name, COUNT(a.customer_id) as CountAnimals, c.id as ID FROM customers as c LEFT JOIN animals as a ON a.customer_id = c.id GROUP BY c.id;");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            $rows = [];
            // Fetch rows as associative array
            while ($customerData = $result->fetch_assoc()) {
                // Choose an avatar randomly
                $avatarNumber = rand(3, 4);

                // Use the appropriate avatar based on the random number
                $avatar = ($avatarNumber == 3) ? "avatar3.png" : "avatar4.png";

                $rows[] = [
                    "customerData" => $customerData,
                    "avatar" => $avatar,
                ];
            }
            return $rows;
        } else {
            // Handle the case where the query was not successful
            echo "Error: " . mysqli_error($conn);
        }
    }

    public function deleteCustomer($id) {
        $stmtAnimal = $this->connexion->conn->prepare("DELETE FROM animals WHERE customer_id = ?;");
        $stmtCustomer = $this->connexion->conn->prepare("DELETE FROM customers WHERE id = ?;");

        $stmtAnimal->bind_param("s", $id);
        $stmtCustomer->bind_param("s", $id);

        $stmtAnimal->execute();
        $stmtCustomer->execute();
    }

    public function update($id, $firstname, $lastname, $mail, $telephone, $postal_adress, $commentary) {
        $_SESSION["error_message_customerEdit"] = NULL;

        if (strlen($postal_adress) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($mail) > 0  && strlen($postal_adress) <= 45 && strlen($firstname) <= 45 && strlen($lastname) <= 45 && strlen($telephone) === 10) {
            $stmt = $this->connexion->conn->prepare("UPDATE customers SET firstname = ?, lastname = ?, mail = ?, telephone = ?, postal_adress = ?, commentary = ? WHERE id = ?;");
            $stmt->bind_param("sssssss", $firstname, $lastname, $mail, $telephone, $postal_adress, $commentary, $id);
            $stmt->execute();
        } else {
            $_SESSION["error_message_customerEdit"] = "Les données sont invalides.";
        }
    }
}
?>
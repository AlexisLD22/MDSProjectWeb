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
        $customers_result = mysqli_query($this->connexion->conn, "SELECT * FROM customers;");
        if (!$customers_result) {
            die("Database query failed.");
        }
        $customers = [];
        while ($customer_bdd = mysqli_fetch_assoc($customers_result)) {
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
        $CountResult = mysqli_query($this->connexion->conn, "SELECT COUNT(*) as count FROM Customers;");
        if (!$CountResult) {
            die("Database query failed.");
        }
        $CountData = mysqli_fetch_assoc($CountResult);
        return $CountData['count'];
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
            if (strlen($postal_adress) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($mail) > 0  && strlen($postal_adress) <= 45 && strlen($firstname) <= 45 && strlen($lastname) <= 45 && strlen($telephone) === 10) {
                $stmt = $this->connexion->conn->prepare("INSERT INTO customers (firstname, lastname, mail, telephone, postal_adress, commentary) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $firstname, $lastname, $mail, $telephone, $postal_adress, $commentary);
                $stmt->execute();
                $stmt->close();
                $_SESSION["error_message_InscriptionCustomer"] = NULL;
            } else {
                $_SESSION["error_message_InscriptionCustomer"] = "Invalid input data.";
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
}
?>
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
                $stmt->close();  // Close the statement after execution
            } else {
                $error_message1 = "Invalid input data.";
            }
        } else {
            $error_message1 = "Le client existe déjà.";
        }
    }
}
?>
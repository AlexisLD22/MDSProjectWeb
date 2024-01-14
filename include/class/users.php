<?php
require_once 'include/conn.php';

class User {
    
    public $connexion;
    public $id;
    public $is_admin;
    public $firstname;
    public $lastname;
    public $telephone;
    public $mail;
    public $postal_adress;
    public $password;

    function __construct($user_bdd = NULL) {
        $this->connexion = new Connexion();
        if ($user_bdd !== NULL) {
            $this->id = $user_bdd['id'];
            $this->is_admin = $user_bdd['is_admin'];
            $this->firstname = $user_bdd['firstname'];
            $this->lastname = $user_bdd['lastname'];
            $this->telephone = $user_bdd['telephone'];
            $this->mail = $user_bdd['mail'];
            $this->postal_adress = $user_bdd['postal_adress'];
            $this->password = $user_bdd['password'];
        }
    }

    public function getAll() {
        $users_result = mysqli_query($this->connexion->conn, "SELECT * FROM users;");
        if (!$users_result) {
            die("Database query failed.");
        }
        $users = [];
        while ($user_bdd = mysqli_fetch_assoc($users_result)) {
            $users[] = new User($user_bdd);
        }
        return $users;
    }
    
    public function login($mail, $password) {
        $LoginQuery = "SELECT * FROM users WHERE mail = ? AND password = ?";
        $stmt = $this->connexion->conn->prepare($LoginQuery);
        $stmt->bind_param("ss", $mail, $password);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            session_start();
            $_SESSION['is_logged_in'] = true;
    
            // Récupérer le nom et prénom de l'utilisateur qui vient de se connecter :
            $this->GetName($mail);
            header("Location: index.php"); // Redirect to the index.php page
            exit();
        } else {
            // Login failed
            $_SESSION["error_message"] = "Login failed. Please check your email and password.";
            // Add error handling here (e.g., set $error_message and display it in the form)
        }
        $stmt->close();
    }

    public function GetName($mail) {
        $stmt = $this->connexion->conn->prepare("SELECT CONCAT(firstname,' ',lastname) as FullName, is_admin FROM users WHERE mail = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $names_result = $stmt->get_result();

        $UserDetail = $names_result->fetch_assoc();
        $_SESSION["FullName"] = $UserDetail["FullName"];
        $_SESSION["is_admin"] = $UserDetail["is_admin"];
    
        $stmt->close();
    }

    public function getNames() {
        $stmt = $this->connexion->conn->prepare("SELECT CONCAT(firstname,' ',lastname) as name FROM users;");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($userData = $result->fetch_assoc()) {
            $users[] = $userData['name'];
        }
        return $users;
    }

    public function getByName($User) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM users WHERE CONCAT(firstname,' ',lastname) = ?;");
        $stmt->bind_param("s", $User);
        $stmt->execute();
        $result = $stmt->get_result();
        $UserData = $result->fetch_assoc();
        return new User($UserData);
    }

    public function getById($id) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM users WHERE id = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $UserData = $result->fetch_assoc();
        return new User($UserData);
    }

    public function getCapabilityById($id){
        $stmt = $this->connexion->conn->prepare("SELECT s.name as Able FROM users as u INNER JOIN capabilities as c ON c.user_id = u.id INNER JOIN services as s ON s.id = c.service_id WHERE u.id = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $capability = [];
        while ($userData = $result->fetch_assoc()) {
            $capability[] = $userData['Able'];
        }
        return $capability;

    }

    public function constructList() {
        $stmt = $this->connexion->conn->prepare("SELECT u.firstname as FirstName, u.lastname as LastName, COUNT(c.user_id) as CountCapability, u.id as ID FROM users as u LEFT JOIN capabilities as c ON c.user_id = u.id GROUP BY u.id;");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            $rows = [];
            // Fetch rows as associative array
            while ($userData = $result->fetch_assoc()) {
                // Choose an avatar randomly
                $avatarNumber = rand(3, 4);

                // Use the appropriate avatar based on the random number
                $avatar = ($avatarNumber == 3) ? "avatar3.png" : "avatar4.png";

                // Calculate the progress percentage based on CountCapability
                $progressPercentage = $userData['CountCapability'] * 25;

                $rows[] = [
                    "userData" => $userData,
                    "avatar" => $avatar,
                    "progressPercentage" => $progressPercentage
                ];
            }
            return $rows;
        } else {
            // Handle the case where the query was not successful
            echo "Error: " . mysqli_error($conn);
        }
    }

    public function deleteUser($id) {
        $stmtCapability = $this->connexion->conn->prepare("DELETE FROM capabilities WHERE user_id = ?;");
        $stmtAppointment = $this->connexion->conn->prepare("DELETE FROM appointments WHERE user_id = ?;");
        $stmtUser = $this->connexion->conn->prepare("DELETE FROM users WHERE id = ?;");

        $stmtCapability->bind_param("s", $id);
        $stmtAppointment->bind_param("s", $id);
        $stmtUser->bind_param("s", $id);

        $stmtCapability->execute();
        $stmtAppointment->execute();
        $stmtUser->execute();
    }
    
    public function createUser($is_admin, $firstname, $lastname, $capabalities, $telephone, $mail, $postal_adress, $password) {
        $stmt = $this->connexion->conn->prepare("INSERT INTO users (is_admin, firstname, lastname, telephone, mail, postal_adress, password) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sssssss", $is_admin, $firstname, $lastname, $telephone, $mail, $postal_adress, $password);
        $stmt->execute();

        $User = $firstname . " " . $lastname;
        $u = new User();
        $userData = $u->getByName($User);

        $s = new Service();
        $services = $s->getServices();
        $index = 0;
        foreach ($capabalities as $capability) {
            $serviceData = $s->getByName($services[$index]);
            // Use a comparison operator (==) instead of assignment (=)
            if ($capability == "1") {
                $stmtAddCapability = $this->connexion->conn->prepare("INSERT INTO capabilities (user_id, service_id) VALUES (?, ?); ");
                $stmtAddCapability->bind_param("ss", $userData->id, $serviceData->id);
                $stmtAddCapability->execute();
            }
            $index = $index + 1;
        }
    }
    
    public function update($id, $is_admin, $firstname, $lastname, $capabalities, $telephone, $mail, $postal_adress) {
        // Requête pour changer les informations dans la table users :
        $stmt = $this->connexion->conn->prepare("UPDATE users SET is_admin = ?, firstname = ?, lastname = ?, telephone = ?, mail = ?, postal_adress = ? WHERE id = ?;");
        $stmt->bind_param("sssssss", $is_admin, $firstname, $lastname, $telephone, $mail, $postal_adress, $id);
        $stmt->execute();
    
        // Suppression de toutes les compétences d'avant :
        $stmtDeleteFromCapabilities = $this->connexion->conn->prepare("DELETE FROM capabilities WHERE user_id = ?;");
        $stmtDeleteFromCapabilities->bind_param("s", $id);
        $stmtDeleteFromCapabilities->execute();
    
        $s = new Service();
        $services = $s->getServices();
        $index = 0;
        foreach ($capabalities as $capability) {
            $serviceData = $s->getByName($services[$index]);
            if ($capability == "1") {
                $stmtAddCapability = $this->connexion->conn->prepare("INSERT INTO capabilities (user_id, service_id) VALUES (?, ?); ");
                $stmtAddCapability->bind_param("ss", $id, $serviceData->id);
                $stmtAddCapability->execute();
            }
            $index = $index + 1;
        }
    }
}
?>

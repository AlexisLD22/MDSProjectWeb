<?php

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
        $stmt = $this->connexion->conn->prepare("SELECT firstname as FirstName, lastname as LastName FROM users WHERE mail = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        
        $names_result = $stmt->get_result();

        if (!$names_result) {
            die("Database query failed.");
        }
        
        $UserDetail = $names_result->fetch_assoc();
        $_SESSION['FirstName'] = $UserDetail["FirstName"];
        $_SESSION['LastName'] = $UserDetail["LastName"];
    
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

}

?>

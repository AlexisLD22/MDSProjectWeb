<?php
require_once 'include/conn.php';
require_once 'include/class/customers.php';

class Animal {
    
    public $connexion;
    public $id;
    public $name;
    public $breed;
    public $age;
    public $weight;
    public $height;
    public $commentary;
    public $customer_id;

    function __construct($animal_bdd = NULL) {
        $this->connexion = new Connexion();
        if ($animal_bdd !== NULL) {
            if (is_array($animal_bdd)) {
                $this->id = $animal_bdd['id'];
                $this->name = $animal_bdd['name'];
                $this->breed = $animal_bdd['breed'];
                $this->age = $animal_bdd['age'];
                $this->weight = $animal_bdd['weight'];
                $this->height = $animal_bdd['height'];
                $this->commentary = $animal_bdd['commentary'];
                $this->customer_id = $animal_bdd['customer_id'];
            } else {
                $this->id = $animal_bdd->id;
                $this->name = $animal_bdd->name;
                $this->breed = $animal_bdd->breed;
                $this->age = $animal_bdd->age;
                $this->weight = $animal_bdd->weight;
                $this->height = $animal_bdd->height;
                $this->commentary = $animal_bdd->commentary;
                $this->customer_id = $animal_bdd->customer_id;
            }
        }
    }

    public function getAll() {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals;");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result) {
            die("Database query failed.");
        }
        $animals = [];
        while ($animal_bdd = $result->fetch_assoc()) {
            $animals[] = new Animal($animal_bdd);
        }
        return $animals;
    }
    
    public function getAvgWeight() {
        $stmt = $this->connexion->conn->prepare("SELECT ROUND(AVG(weight)) AS average_weight FROM animals;");
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Database query failed.");
        }

        $avgWeightData = $result->fetch_assoc();
        return $avgWeightData['average_weight'];
    }

    public function getBreedData() {
        $stmt = $this->connexion->conn->prepare("SELECT breed, COUNT(*) as count FROM animals GROUP BY breed");
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Database query failed.");
        }

        $breedData = [];
        while ($data = $result->fetch_assoc()) {
            $breedData[] = [
                'breed' => $data['breed'],
                'count' => $data['count'],
            ];
        }
        return $breedData;
    }

    public function getHeightData() {
        $stmt = $this->connexion->conn->prepare("SELECT type_height, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN height < 110 THEN 'petit' WHEN (height BETWEEN 110 AND 130) THEN 'moyen' WHEN height > 130 THEN 'grand' END AS type_height FROM animals ) AS subquery GROUP BY type_height;");
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Database query failed.");
        }

        $HeightData = [];
        while ($data = $result->fetch_assoc()) {
            $HeightData[] = [
                'type_height' => $data['type_height'],
                'count' => $data['count'],
            ];
        }
        return $HeightData;
    }

    public function getCapabilitiesData() {
        $stmt = $this->connexion->conn->prepare("SELECT s.name, COUNT(c.service_id) as count FROM capabilities as c INNER JOIN services as s ON s.id = c.service_id GROUP BY name;");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result) {
            die("Database query failed.");
        }
        
        $CapabilitiesData = [];
        while ($data = $result->fetch_assoc()) {
            $CapabilitiesData[] = [
                'name' => $data['name'],
                'count' => $data['count'],
            ];
        }
        return $CapabilitiesData;
    }

    public function getWeightData() {
        $stmt = $this->connexion->conn->prepare("SELECT type_weight, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN weight < 40 THEN 'léger' WHEN (weight BETWEEN 40 AND 55) THEN 'normal' WHEN weight > 55 THEN 'gros' END AS type_weight FROM animals ) AS subquery GROUP BY type_weight;");
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Database query failed.");
        }

        $WeightData = [];
        while ($data = $result->fetch_assoc()) {
            $WeightData[] = [
                'type_weight' => $data['type_weight'],
                'count' => $data['count'],
            ];
        }
        return $WeightData;
    }

    public function Exist($name, $customer) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals AS a INNER JOIN customers AS c ON c.id = a.customer_id WHERE a.name = ? AND CONCAT(c.firstname,' ',c.lastname) = ?;");
        $stmt->bind_param("ss", $name, $customer);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
    }

    public function AddAnimal($name, $breed, $height, $weight, $age, $customer, $commentary) {
        if ($this->Exist($name, $customer) === false) {
            $c = new Customer();
            $Customer = $c->getByName($customer);
            $CustomerId = $Customer->id;
            if (strlen($name) > 0 && strlen($breed) > 0 && strlen($name) <= 45 && strlen($breed) <= 45 && strlen($age) > 0 && strlen($age) <= 3 && strlen($weight) > 0 && strlen($height) > 0 && strlen($weight) <= 3 && strlen($height) <= 3) {
                $stmt = $this->connexion->conn->prepare("INSERT INTO animals (name, breed, age, weight, height, commentary, customer_id) VALUES (?, ?, ?, ?, ?, ?, ?);");
                $stmt->bind_param("sssssss", $name, $breed, $age, $weight, $height, $commentary, $CustomerId);
                $stmt->execute();
                $stmt->close();
                $_SESSION["error_message_InscriptionAnimal"] = NULL;
            } else {
                $_SESSION["error_message_InscriptionAnimal"] = "Les données sont invalides.";
            }
        } else {
            $_SESSION["error_message_InscriptionAnimal"] = "Le chien existe déjà.";
        }
    }

    public function getByNameAndCustomer($name, $customer) {
        $stmt = $this->connexion->conn->prepare("SELECT a.id, a.name, a.breed, a.age, a.weight, a.height, a.commentary, a.customer_id FROM animals as a INNER JOIN customers as c on c.id = a.customer_id WHERE CONCAT(c.firstname,' ',c.lastname) = ? AND a.name = ?; ");
        $stmt->bind_param("ss", $customer, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $AnimalData = $result->fetch_assoc();
        return new Animal($AnimalData);
    }

    public function isLink($name, $customer) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals as a INNER JOIN customers as c ON c.id = a.customer_id WHERE a.name = ? AND CONCAT(c.firstname,' ',c.lastname) = ?;");
        $stmt->bind_param("ss", $name, $customer);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
    }

    public function getById($id) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals WHERE id= ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $AnimalData = $result->fetch_assoc();
        return new Animal($AnimalData);
    }

    public function getNames() {
        $stmt = $this->connexion->conn->prepare("SELECT name FROM animals");
        $stmt->execute();
        $result = $stmt->get_result();
        $animals = [];
        while ($animalData = $result->fetch_assoc()) {
            $animals[] = $animalData['name'];
        }
        return $animals;
    }

    public function constructList() {
        $stmt = $this->connexion->conn->prepare("SELECT a.id, a.name, CONCAT(c.firstname, ' ', c.lastname) as customerName FROM animals as a INNER JOIN customers as c ON c.id = a.customer_id;");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            $rows = [];
            while ($animalData = $result->fetch_assoc()) {
                // Choose an avatar randomly
                $avatarNumber = rand(3, 4);
                $avatar = ($avatarNumber == 3) ? "avatar3.png" : "avatar4.png";

                $rows[] = [
                    "animalData" => $animalData,
                    "avatar" => $avatar,
                ];
            }
            return $rows;
        } else {
            // Handle the case where the query was not successful
            echo "Error: " . mysqli_error($conn);
        }
    }

    public function update($id, $name, $breed, $customer, $height, $weight, $age, $commentary) {
        $_SESSION["error_message_animalEdit"] = NULL;
        
        $c = new Customer();
        $customerData = $c->getByName($customer);
        $customerId = $customerData->id;

        if (strlen($name) > 0 && strlen($breed) > 0 && strlen($name) <= 45 && strlen($breed) <= 45 && strlen($age) > 0 && strlen($age) <= 3 && strlen($weight) > 0 && strlen($height) > 0 && strlen($weight) <= 3 && strlen($height) <= 3) {
            $stmt = $this->connexion->conn->prepare("UPDATE animals SET name = ?, breed = ?, age = ?, weight = ?, height = ?, commentary = ?, customer_id = ? WHERE id = ?;");
            $stmt->bind_param("ssssssss", $name, $breed, $height, $weight, $age, $commentary, $customerId, $id);
            $stmt->execute();
        } else {
            $_SESSION["error_message_animalEdit"] = "Les données sont invalides.";
        }
    }

    public function deleteAnimal($id) {
        $stmtAnimal = $this->connexion->conn->prepare("DELETE FROM animals WHERE id = ?;");
        $stmtAppointment = $this->connexion->conn->prepare("DELETE FROM appointments WHERE animal_id = ?;");
        
        $stmtAnimal->bind_param("s", $id);
        $stmtAppointment->bind_param("s", $id);
        
        $stmtAnimal->execute();
        $stmtAppointment->execute();
    }
    
    public function getAnimalsByCustomerId($id) {
        $stmt = $this->connexion->conn->prepare("SELECT * from animals where customer_id = ?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $animals = [];
        while ($animal_bdd = $result->fetch_assoc()) {
            $animals[] = new Animal($animal_bdd);
        }
        return $animals;
    }
}
?>
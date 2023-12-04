<?php

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
        $animals_result = mysqli_query($this->connexion->conn, "SELECT * FROM animals;");
        if (!$animals_result) {
            die("Database query failed.");
        }
        $animals = [];
        while ($animal_bdd = mysqli_fetch_assoc($animals_result)) {
            $animals[] = new Animal($animal_bdd);
        }
        return $animals;
    }
    
    public function getAvgWeight() {
        $avgWeightResult = mysqli_query($this->connexion->conn, "SELECT ROUND(AVG(weight)) AS average_weight FROM animals;");
        if (!$avgWeightResult) {
            die("Database query failed.");
        }
        $avgWeightData = mysqli_fetch_assoc($avgWeightResult);
        return $avgWeightData['average_weight'];
    }

    public function getBreedData() {
        $query = "SELECT breed, COUNT(*) as count FROM animals GROUP BY breed";
        $result = mysqli_query($this->connexion->conn, $query);
        if (!$result) {
            die("Database query failed.");
        }

        $breedData = [];
        while ($data = mysqli_fetch_assoc($result)) {
            $breedData[] = [
                'breed' => $data['breed'],
                'count' => $data['count'],
            ];
        }
        return $breedData;
    }
    
    public function getHeightData() {
        $query = "SELECT type_height, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN height < 110 THEN 'petit' WHEN (height BETWEEN 110 AND 130) THEN 'moyen' WHEN height > 130 THEN 'grand' END AS type_height FROM animals ) AS subquery GROUP BY type_height;";
        $result = mysqli_query($this->connexion->conn, $query);
        if (!$result) {
            die("Database query failed.");
        }

        $HeightData = [];
        while ($data = mysqli_fetch_assoc($result)) {
            $HeightData[] = [
                'type_height' => $data['type_height'],
                'count' => $data['count'],
            ];
        }
        return $HeightData;
    }
    
    public function getCapabilitiesData() {
        $query = "SELECT s.name, COUNT(c.service_id) as count FROM capabilities as c INNER JOIN services as s ON s.id = c.service_id GROUP BY name;";
        $result = mysqli_query($this->connexion->conn, $query);
        if (!$result) {
            die("Database query failed.");
        }

        $CapabilitiesData = [];
        while ($data = mysqli_fetch_assoc($result)) {
            $CapabilitiesData[] = [
                'name' => $data['name'],
                'count' => $data['count'],
            ];
        }
        return $CapabilitiesData;
    }
    
    public function getWeightData() {
        $query = "SELECT type_weight, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN weight < 40 THEN 'léger' WHEN (weight BETWEEN 40 AND 55) THEN 'normal' WHEN weight > 55 THEN 'gros' END AS type_weight FROM animals ) AS subquery GROUP BY type_weight;";
        $result = mysqli_query($this->connexion->conn, $query);
        if (!$result) {
            die("Database query failed.");
        }

        $WeightData = [];
        while ($data = mysqli_fetch_assoc($result)) {
            $WeightData[] = [
                'type_weight' => $data['type_weight'],
                'count' => $data['count'],
            ];
        }
        return $WeightData;
    }
    
    public function Exist($name, $mail) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals AS a INNER JOIN customers AS C ON c.id = a.customer_id WHERE a.name = ? AND c.mail = ?;");
        $stmt->bind_param("ss", $name, $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
    }
    
    public function AddAnimal($name, $breed, $height, $weight, $age, $mail, $commentary) {
        if ($this->Exist($name, $mail) === false) {
            $c = new Customer();
            $Customer = $c->getByMail($mail);
            $CustomerId = $Customer->id;
            if (strlen($name) > 0 && strlen($breed) > 0 && strlen($name) <= 45 && strlen($breed) <= 45 && strlen($age) > 0 && strlen($age) <= 3 && strlen($weight) > 0 && strlen($height) > 0 && strlen($weight) <= 3 && strlen($height) <= 3) {
                $stmt = $this->connexion->conn->prepare("INSERT INTO animals (name, breed, age, weight, height, commentary, customer_id) VALUES (?, ?, ?, ?, ?, ?, ?);");
                $stmt->bind_param("sssssss", $name, $breed, $age, $weight, $height, $commentary, $CustomerId);
                $stmt->execute();
                $stmt->close();
            } else {
                $error_message1 = "Invalid input data.";
            }
        } else {
            $error_message1 = "Le chien existe déjà.";
        }
    }
    
    public function getByNameAndMail($name, $mail) {
        $stmt = $this->connexion->conn->prepare("SELECT a.id, a.name, a.breed, a.age, a.weight, a.height, a.commentary, a.customer_id FROM animals as a INNER JOIN customers as c on c.id = a.customer_id WHERE c.mail = ? AND a.name = ?; ");
        $stmt->bind_param("ss", $mail, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $AnimalData = $result->fetch_assoc();
        return new Animal($AnimalData);
    }

    public function isLink($name, $mail) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM animals as a INNER JOIN customers as c ON c.id = a.customer_id WHERE a.name= ? AND c.mail = ?;");
        $stmt->bind_param("ss", $name, $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
    }
}

?>

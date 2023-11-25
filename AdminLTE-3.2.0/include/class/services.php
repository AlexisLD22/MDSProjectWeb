<?php

require_once 'include/conn.php';

class Service {
    
    public $connexion;
    public $id;
    public $name;
    public $price;

    function __construct($service_bdd = NULL) {
        $this->connexion = new Connexion();
        if ($service_bdd !== NULL) {
            if (is_array($service_bdd)) {
                $this->id = $service_bdd['id'];
                $this->name = $service_bdd['name'];
                $this->price = $service_bdd['price'];
            } else {
                $this->id = $service_bdd->id;
                $this->name = $service_bdd->name;
                $this->price = $service_bdd->price;
            }
        }
    }

    public function getAll() {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM services");
        $stmt->execute();
        $result = $stmt->get_result();
        $ServiceData = $result->fetch_assoc();
        return new Service($ServiceData);
    }
    
    public function getServices() {
        $stmt = $this->connexion->conn->prepare("SELECT name FROM services");
        $stmt->execute();
        $result = $stmt->get_result();

        $services = [];
        while ($serviceData = $result->fetch_assoc()) {
            $services[] = $serviceData['name'];
        }

        return $services;
    }
    
    public function getByName($Service) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM services WHERE name = ?");
        $stmt->bind_param("s", $Service);
        $stmt->execute();
        $result = $stmt->get_result();
        $ServiceData = $result->fetch_assoc();
        return new Service($ServiceData);
    }
}
?>
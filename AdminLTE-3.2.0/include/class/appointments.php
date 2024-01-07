<?php
require_once 'include/conn.php';
require_once 'include/class/animals.php';
require_once 'include/class/services.php';
require_once 'include/class/users.php';

class Appointment {
    
    public $connexion;
    public $id;
    public $date_start;
    public $date_end;
    public $is_paid;
    public $user_id;
    public $animal_id;
    public $service_id;

    function __construct($appointment_bdd = NULL) {
        $this->connexion = new Connexion();
        if ($appointment_bdd !== NULL) {
            if (is_array($appointment_bdd)) {
                $this->id = $appointment_bdd['id'];
                $this->date_start = $appointment_bdd['date_start'];
                $this->date_end = $appointment_bdd['date_end'];
                $this->is_paid = $appointment_bdd['is_paid'];
                $this->user_id = $appointment_bdd['user_id'];
                $this->animal_id = $appointment_bdd['animal_id'];
                $this->service_id = $appointment_bdd['service_id'];
            } else {
                $this->id = $appointment_bdd->id; 
                $this->date_start = $appointment_bdd->date_start; 
                $this->date_end = $appointment_bdd->date_end; 
                $this->is_paid = $appointment_bdd->is_paid; 
                $this->user_id = $appointment_bdd->user_id; 
                $this->animal_id = $appointment_bdd->animal_id; 
                $this->service_id = $appointment_bdd->service_id; 
            }
        }
    }

    public function getAll() {
        $appointments_result = mysqli_query($this->connexion->conn, "SELECT * FROM appointments;");
        if (!$appointments_result) {
            die("Database query failed.");
        }
        $appointments = [];
        while ($appointment_bdd = mysqli_fetch_assoc($appointments_result)) {
            $appointments[] = new Appointment($appointment_bdd);
        }
        return $appointments;
    }

    public function getById($id) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM appointments WHERE id=?;");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointmentData = $result->fetch_assoc();
        return new Appointment($appointmentData);
    }

    public function IsAble($user, $service) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM users as u INNER JOIN capabilities as c on c.user_id = u.id INNER JOIN services as s on s.id = c.service_id WHERE CONCAT(u.firstname,' ',u.lastname) = ? AND s.name = ?;");
        $stmt->bind_param("ss", $user, $service);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
    }

    public function IsDateValid($start, $end) {
        // Mise en forme des dates pour la bdd
        $dateTime_start = DateTime::createFromFormat('Y-m-d\TH:i', $start);
        $dateTime_end = DateTime::createFromFormat('Y-m-d\TH:i', $end);
    
        // Check if the DateTime objects are valid
        if ($dateTime_start === false || $dateTime_end === false) {
            return array(
                'valid' => false,
                'reason' => "Format de date invalide."
            );
        }
    
        // Check if the start date is before the end date
        if ($dateTime_start >= $dateTime_end) {
            return array(
                'valid' => false,
                'reason' => "La date de début doit être antérieure à la date de fin."
            );
        }
    
        // Check if the years are between 2020 and 2100
        if ($dateTime_start->format('Y') < 2020 || $dateTime_start->format('Y') > 2100 ||
            $dateTime_end->format('Y') < 2020 || $dateTime_end->format('Y') > 2100) {
            
            return array(
                'valid' => false,
                'reason' => "L'année doit être comprise entre 2020 et 2100."
            );
        }
    
        // Check if the duration is between 1 hour and 2 hours
        $interval = $dateTime_start->diff($dateTime_end);
        $durationInMinutes = $interval->h * 60 + $interval->i;
    
        if ($durationInMinutes < 60 || $durationInMinutes > 120) {
            return array(
                'valid' => false,
                'reason' => "La durée doit être comprise entre 1 heure et 2 heures."
            );
        }
    
        // Check if the start time is after 10 AM and the end time is before 6 PM
        $startTime = $dateTime_start->format('H:i');
        $endTime = $dateTime_end->format('H:i');
        if ($startTime < '10:00' || $endTime > '18:00') {
            return array(
                'valid' => false,
                'reason' => "L'heure de début doit être après 10h et l'heure de fin doit être avant 18h."
            );
        }
    
        // Format the dates for the database
        $date_start = $dateTime_start->format('Y-m-d H:i:s');
        $date_end = $dateTime_end->format('Y-m-d H:i:s');
    
        // Return an array with the validity status and formatted date strings
        return array(
            'valid' => true,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'reason' => ""
        );
    }
    
    public function AddAppointment($mail, $name, $service, $user, $date_start, $date_end, $is_paid) {
        // Récupération de l'id de l'animal :
        $a = new Animal();
        $Animal_a = $a->getByNameAndMail($name, $mail);
        $AnimalId = $Animal_a->id;
        // Récupération de l'id du service :
        $s = new Service();
        $service_s = $s->getByName($service);
        $serviceId = $service_s->id;
        // Récupération de l'id de l'utilisateur:
        $u = new User();
        $user_u = $u->getByName($user);
        $userId = $user_u->id;

        $dateValidationResult = $this->IsDateValid($date_start, $date_end);

        // L'employée est-il capable de faire le rendez-vous demander ?
        if ($dateValidationResult["valid"] && $this->IsAble($user, $service) && $Animal_a->IslInk($name, $mail)) {
            $stmt = $this->connexion->conn->prepare("INSERT INTO appointments (date_start, date_end, is_paid, user_id, animal_id, service_id) VALUES (?, ?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssss", $dateValidationResult["date_start"], $dateValidationResult["date_end"], $is_paid, $userId, $AnimalId, $serviceId);
            $stmt->execute();
            $stmt->close();
            $_SESSION["error_message"] = $dateValidationResult["reason"];
        } elseif ($dateValidationResult["valid"] && $this->IsAble($user, $service)) {
            $_SESSION["error_message"] = "Il semblerait que le client ou l'animal n'existe pas";
        } elseif ($dateValidationResult["valid"] && $Animal_a->IslInk($name, $mail)) {
            $_SESSION["error_message"] = "L'employé n'a pas les compétences de faire la tâche demandée";
        } else {
            $_SESSION["error_message"] = $dateValidationResult["reason"];
        }
    }

    public function getCalendar() {
        $calendarQuery = mysqli_query($this->connexion->conn, "SELECT a.id as id, a.date_start as start, a.date_end as end, is_paid, CONCAT(u.firstname, ' ', u.lastname) as nom_client, an.name as nom_animal, s.name as nom_service FROM appointments as a INNER JOIN users as u ON u.id = a.user_id INNER JOIN animals as an ON an.id = a.animal_id INNER JOIN services as s ON s.id = a.service_id;");
        $calendar = [];
        while ($row = mysqli_fetch_assoc($calendarQuery)) {
            $event = [
                'title' => $row['nom_service'] .' de ' . $row['nom_animal'] . ' - ' . $row['nom_client'],
                'start' => $row['start'],
                'end' => $row['end'],
                'color' => $row['is_paid'] ? 'green' : 'red', // Adjust color based on is_paid value
                'url' => 'appointmentView.php?id=' . $row['id'], // Récupère l'id & le met dans un lien
            ];
            $calendar[] = $event;
        }
        return json_encode($calendar);
    }
    
    public function announceDate($dateString) {
        $date = new DateTime($dateString);
        
        // Define a mapping of English to French month names
        $monthTranslations = [
            'January' => 'janvier',
            'February' => 'février',
            'March' => 'mars',
            'April' => 'avril',
            'May' => 'mai',
            'June' => 'juin',
            'July' => 'juillet',
            'August' => 'août',
            'September' => 'septembre',
            'October' => 'octobre',
            'November' => 'novembre',
            'December' => 'décembre',
        ];
    
        // Define a mapping of English to French day names
        $dayTranslations = [
            'Monday' => 'lundi',
            'Tuesday' => 'mardi',
            'Wednesday' => 'mercredi',
            'Thursday' => 'jeudi',
            'Friday' => 'vendredi',
            'Saturday' => 'samedi',
            'Sunday' => 'dimanche',
        ];
    
        // Format the date for announcement with manual translations
        $formattedDate = strtr(
            $date->format('l j F Y \à G\hi'),
            array_merge($monthTranslations, $dayTranslations)
        );
    
        // Create the announcement sentence
        $announcement = $formattedDate;
    
        return $announcement;
    }

    public function ChangeAppointment($id, $service, $user, $date_start, $date_end, $is_paid, $userId) {
        
        $dateValidationResult = $this->IsDateValid($date_start, $date_end);
        
        // Récupération de l'id du service :
        $s = new Service();
        $service_s = $s->getByName($service);
        $serviceId = strval($service_s->id);
        // Récupération de l'id de l'utilisateur:
        $u = new User();
        $user_u = $u->getByName($user);
        $userId = $user_u->id;
        
        if ($dateValidationResult["valid"] && $this->IsAble($user, $service)) {
            $stmt = $this->connexion->conn->prepare("UPDATE appointments SET service_id = ?, user_id = ?, date_start = ?, date_end = ?, is_paid = ? WHERE id = ?;");
            $stmt->bind_param("ssssss", $serviceId, $userId, $dateValidationResult["date_start"], $dateValidationResult["date_end"], $is_paid, $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION["error_message"] = "";
        } elseif ($dateValidationResult["valid"]) {
            $_SESSION["error_message"] = "L'employé n'a pas les compétences de faire la tâche demandée du service " . $service;
        } else {
            $_SESSION["error_message"] = $dateValidationResult["reason"];
        }
    }
}
?>

<?php

require_once 'include/conn.php';

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

    public function IsAble($user, $service) {
        $stmt = $this->connexion->conn->prepare("SELECT * FROM users as u INNER JOIN capabilities as c on c.user_id = u.id INNER JOIN services as s on s.id = c.service_id WHERE CONCAT(u.firstname,' ',u.lastname) = ? AND s.name = ?;");
        $stmt->bind_param("ss", $user, $service);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->num_rows === 1;
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

        // Mise en forme des date pour la bdd
        $dateTime_start = DateTime::createFromFormat('Y-m-d\TH:i', $date_start);
        $dateTime_end = DateTime::createFromFormat('Y-m-d\TH:i', $date_end);

        if ($dateTime_start && $dateTime_start->format('Y') >= 2000 && $dateTime_start->format('Y') <= 2050 && 
            $dateTime_end && $dateTime_end->format('Y') >= 2000 && $dateTime_end->format('Y') <= 2050) {
                $date_start = $dateTime_start->format('Y-m-d H:i:s');
                $date_end = $dateTime_end->format('Y-m-d H:i:s');
            }

        // L'employée est-il capable de faire le rendez-vous demander ?
        if ($this->IsAble($user, $service) && $Animal_a->IslInk($name, $mail)) {
            $stmt = $this->connexion->conn->prepare("INSERT INTO appointments (date_start, date_end, is_paid, user_id, animal_id, service_id) 
                                                    VALUES (?, ?, ?, ?, ?, ?);");
            $stmt->bind_param("ssssss", $date_start, $date_end, $is_paid, $userId, $AnimalId, $serviceId);
            $stmt->execute();
            $stmt->close();
        } elseif ($Animal_a->IslInk($name, $mail)) {
            $error_message = "L'employé n'a pas les compétences de faire la tâche demandée";
            echo($error_message);
        } else {
            $error_message = "Il semblerait que le client ou l'animal n'existe pas";
            echo($error_message);
        }
    }
    // Inside the Appointment class in appointments.php

    public function getCalendar() {
        $calendarQuery = mysqli_query($this->connexion->conn, "SELECT a.id, a.date_start as start, a.date_end as end, is_paid, CONCAT(u.firstname, ' ', u.lastname) as nom_client, an.name as nom_animal, s.name as nom_service FROM appointments as a INNER JOIN users as u ON u.id = a.user_id INNER JOIN animals as an ON an.id = a.animal_id INNER JOIN services as s ON s.id = a.service_id;");
        $calendar = [];
        while ($row = mysqli_fetch_assoc($calendarQuery)) {
            $event = [
                'title' => $row['nom_service'] .' de ' . $row['nom_animal'] . ' - ' . $row['nom_client'],
                'start' => $row['start'],
                'end' => $row['end'],
                'color' => $row['is_paid'] ? 'green' : 'red', // Adjust color based on is_paid value
            ];
            $calendar[] = $event;
        }
        return json_encode($calendar);
    }

}

?>

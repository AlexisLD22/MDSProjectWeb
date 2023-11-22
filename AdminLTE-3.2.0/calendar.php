<?php
require_once 'include/session.php';
require_once 'include/conn.php';


// Requêtes pour récuperer les différents noms de services
$Req_Services = mysqli_query($conn, "SELECT name FROM services;");

// Requêtes pour récuperer les différents noms d'employées
$Req_Users = mysqli_query($conn, "SELECT CONCAT(firstname,' ',lastname) as name FROM users;");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  
  // Récuperation des informations du formulaire d'ajout d'un rendez-vous
  $mail = $_POST["InputMail"];
  $name = $_POST["InputName"];
  $service = $_POST["InputService"];
  $user = $_POST["InputUser"];
  $date_start = $_POST["InputDate1"];
  $date_end = $_POST["InputDate2"];
  $is_paid = strlen($_POST["InputPaid"]);
  
  // Sanitize the inputs (prevent SQL injection)
  $mail = $conn->real_escape_string($mail);

  // Récupère l'id du service choisi :
    $Req_service = mysqli_query($conn, "SELECT id FROM services WHERE name = '$service';");
    $ServiceData = mysqli_fetch_assoc($Req_service);
    $ServiceID = $ServiceData['id'];

  // Récupère l'id de l'employé :
    $Req_User = mysqli_query($conn, "SELECT id FROM users WHERE CONCAT(firstname,' ',lastname) = '$user';");
    $UserData = mysqli_fetch_assoc($Req_User);
    $UserID = $UserData['id'];

  // Récupère l'id du nom de l'animal:
    $Req_name = mysqli_query($conn, "SELECT id FROM animals WHERE name = '$name';");
    $NameData = mysqli_fetch_assoc($Req_name);
    $NameID = $NameData['id'];

  // Requête pour vérifier si les différents champs sont bons:
    $Query_Verif_service = "SELECT * FROM users as u INNER JOIN capabilities as c on c.user_id = u.id INNER JOIN services as s on s.id = c.service_id WHERE CONCAT(u.firstname,' ',u.lastname) = '$user' AND s.name = '$service';";
    $Query_Verif_name = "SELECT * FROM animals as a INNER JOIN customers as c ON c.id = a.customer_id WHERE c.mail = '$mail' AND a.name= '$name';";

  // Check si l'employé à les capacités de faire le service demandé :
  $result_service = $conn->query($Query_Verif_service);
  // Check si le nom de l'animal et le mail du client coïncide :
  $result_name = $conn->query($Query_Verif_name);


  //Chgmt date pour coller avec la bdd
  $dateTime_start = DateTime::createFromFormat('Y-m-d\TH:i', $date_start);
  $dateTime_end = DateTime::createFromFormat('Y-m-d\TH:i', $date_end);

  // Changement du format de la date pour le début et la fin en adéquation avec les infos dans la base de données
  if ($dateTime_start && $dateTime_start->format('Y') >= 2000 && $dateTime_start->format('Y') <= 2050 && $dateTime_end && $dateTime_end->format('Y') >= 2000 && $dateTime_end->format('Y') <= 2050) {
      $date_start = $dateTime_start->format('Y-m-d H:i:s');
      $date_end = $dateTime_end->format('Y-m-d H:i:s');

    } else {
  }
  // // Ajout Enlève les erreurs si il y en a 
  // $date1 = new DateTime($date_start);
  // $date2 = new DateTime($date_end);

  // $interval = $date1->diff($date2);
  // echo $interval->format('%r%a days, %h hours, %i minutes');


  // Ajout dans la bdd

  // Check si animal + mail correct :
  if ($result_name->num_rows === 1 && isset($result_service) && is_object($result_service) && $result_service->num_rows === 1) {
    
    //l'animal est bien relier au client + le client existe
    $Query_add_appointment = "INSERT INTO appointments (date_start, date_end, is_paid, user_id, animal_id, service_id) VALUES ('$date_start', '$date_end', '$is_paid', '$UserID','$NameID', '$ServiceID')";
    $conn->query($Query_add_appointment);
  } elseif ($result_name->num_rows === 1) {
      $error_message = "L'employé n'a pas les compétences de faire la tâche demandée";
  } else {
      $error_message = "Il semblerait que le client ou l'animal n'existe pas";
  }
}


// Récuperation des informations pour ajouter dans le calendar

$Req_Calendar = mysqli_query($conn, "SELECT a.id, a.date_start as start, a.date_end as end, is_paid, CONCAT(u.firstname, ' ', u.lastname) as nom_client, an.name as nom_animal, s.name as nom_service FROM appointments as a INNER JOIN users as u ON u.id = a.user_id INNER JOIN animals as an ON an.id = a.animal_id INNER JOIN services as s ON s.id = a.service_id;");

$result = array();
while ($row = mysqli_fetch_assoc($Req_Calendar)) {
    $event = array(
        'title' => $row['nom_service'] .' de ' . $row['nom_animal'] . ' - ' . $row['nom_client'],
        'start' => $row['start'],
        'end' => $row['end'],
        'color' => $row['is_paid'] ? 'green' : 'red', // Adjust color based on is_paid value
    );
    $result[] = $event;
}

$result = json_encode($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Calendar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
  <!-- Importation du fichier naviation.php -->
  <?php require_once 'include/navigation.php';?>
  <!-- Importation du fichier aside.php -->
  <?php require_once 'include/aside.php';?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Calendar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Calendar</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <div class="sticky-top mb-3">
              <!-- /.card -->
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Ajout d'un rendez-vous</h3>
                </div>
                <div class="card-body">
                  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <div class="form-group">
                      <label for="InputMail">Email</label>
                      <input type="Email" class="form-control" name="InputMail" placeholder="adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="InputName">Nom de l'animal</label>
                        <input type="Text" class="form-control" name="InputName" placeholder="Nom de l'animal">
                    </div>
                    <div class="form-group">
                      <label for="InputService">Raison du rendez-vous</label>
                      <select class="form-control" name="InputService">
                          <?php
                          while ($service_Info = mysqli_fetch_assoc($Req_Services)) {
                              echo '<option value="' . $service_Info['name'] . '">' . $service_Info['name'] . '</option>';
                          }
                          ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="InputUser"> Ajout de l'employé </label>
                      <select class="form-control" name="InputUser">
                          <?php
                          while ($user_Info = mysqli_fetch_assoc($Req_Users)) {
                              echo '<option value="' . $user_Info['name'] . '">' . $user_Info['name'] . '</option>';
                          }
                          ?>
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="InputDate1">Date début du rendez-vous</label>
                        <input type="datetime-local" class="form-control" name="InputDate1">
                    </div>
                    <div class="form-group">
                        <label for="InputDate2">Date fin du rendez-vous</label>
                        <input type="datetime-local" class="form-control" name="InputDate2">
                    </div>
                    <div class="form-group row">
                      <label for="InputPaid">Etat du payement :</label>
                      <input type="checkbox" name="InputPaid" value="1">
                      <div class="input-group">
                        <button type="submit" class="btn btn-primary">Ajouter le rendez vous</button>
                      </div>
                      <?php if (isset($error_message)) : ?>
                          <div class="error-message"><?php echo $error_message; ?></div>
                      <?php endif; ?>
                    </div>
                    <!-- /input-group -->
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <input id="event-datas" type="hidden" value='<?php echo($result); ?>'>
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Importation du fichier footer.php -->
  <?php require_once 'include/footer.php';?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/fullcalendar/main.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    // function ini_events(ele) {
    //   ele.each(function () {

    //     // create an Event Object (https://fullcalendar.io/docs/event-object)
    //     // it doesn't need to have a start or end
    //     var eventObject = {
    //       title: $.trim($(this).text()) // use the element's text as the event title
    //     }

    //     // store the Event Object in the DOM element so we can get to it later
    //     $(this).data('eventObject', eventObject)

    //     // make the event draggable using jQuery UI
    //     $(this).draggable({
    //       zIndex        : 1070,
    //       revert        : true, // will cause the event to go back to its
    //       revertDuration: 0  //  original position after the drag
    //     })

    //   })
    // }

    // ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/    
    var Calendar = FullCalendar.Calendar;

    let event_datas = document.querySelector("#event-datas").value;
    let tableau = JSON.parse(event_datas);

    var containerEl = document.getElementById('external-events');
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      themeSystem: 'bootstrap',
      events: tableau,
      locale: 'fr',
      editable  : true
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    
    // /* ADDING EVENTS */
    // var currColor = '#3c8dbc' //Red by default
    // // Color chooser button
    // $('#color-chooser > li > a').click(function (e) {
    //   e.preventDefault()
    //   // Save color
    //   currColor = $(this).css('color')
    //   // Add color effect to button
    //   $('#add-new-event').css({
    //     'background-color': currColor,
    //     'border-color'    : currColor
    //   })
    // })
    // $('#add-new-event').click(function (e) {
    //   e.preventDefault()
    //   // Get value and make sure it is not null
    //   var val = $('#new-event').val()
    //   if (val.length == 0) {
    //     return
    //   }

    //   // Remove event from text input
    //   $('#new-event').val('')
    // })
  })
</script>
</body>
</html>

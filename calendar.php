<?php
require_once 'include/session.php';
require_once 'include/class/services.php';
require_once 'include/class/users.php';
require_once 'include/class/animals.php';
require_once 'include/class/appointments.php';
require_once 'include/class/customers.php';

$s = new Service();
$services = $s->getServices();
$u = new User();
$users = $u->getNames();
$an = new Animal();
$animals = $an->getNames();
$c = new Customer();
$customers = $c->getNames();


if (isset($_POST["Appointment"])) {
  $a = new Appointment();
  $a->AddAppointment(
    $_POST["InputCustomer"],
    $_POST["InputName"],
    $_POST["InputService"],
    $_POST["InputUser"],
    $_POST["InputDate1"],
    $_POST["InputDate2"],
    strlen($_POST["InputPaid"])
  );
}

// a_c pour Appointment_Calendar
$a_c = new Appointment();
$calendar = $a_c->getCalendar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Calendar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Include the select2 CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
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
            <h1>Calendrier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Calendrier</li>
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
                  <form method="POST" action="<?= $_SERVER['PHP_SELF']?>">
                  
                    <div class="form-group">
                      <label for="InputCustomer">Nom du propriétaire</label>
                      <select class="form-control" name="InputCustomer" id="customerDropdown">
                        <?php
                        foreach ($customers as $customersName) {
                          echo '<option value="' . $customersName . '">' . $customersName . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group" style="padding: px">
                      <label for="InputName">Nom de l'animal</label>
                      <select class="form-control" name="InputName" id="animalDropdown">
                        <?php
                        foreach ($animals as $animalName) {
                          echo '<option value="' . $animalName . '">' . $animalName . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="InputService">Raison du rendez-vous</label>
                      <select class="form-control" name="InputService">
                          <?php
                          foreach ($services as $serviceName) {
                            echo '<option value="' . $serviceName . '">' . $serviceName . '</option>';
                          }
                          ?>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="InputUser"> Ajout de l'employé </label>
                      <select class="form-control" name="InputUser">
                          <?php
                          foreach ($users as $userName) {
                            echo '<option value="' . $userName . '">' . $userName . '</option>';
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
                      <label for="InputPaid">Etat du paiement :</label>
                      <input type="checkbox" name="InputPaid" value="1">
                    </div>

                    <div class="input-group">
                      <button type="submit" name="Appointment" class="btn btn-primary">Ajouter le rendez vous</button>
                      <?php if (isset($_SESSION["error_message"])) : ?>
                        <div class="error-message"><?= $_SESSION["error_message"]; ?></div>
                      <?php endif; ?>
                    </div>
                    <!-- /input-group -->
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <input id="event-datas" type="hidden" value='<?= $calendar;?>'>
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

<!-- Include the Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Page specific script -->
<style>
  .select2-container--default .select2-selection--single {
    line-height: 15px;
    height: 40px;
  }
</style>
<script>
  // Initialize Select2 for the animal dropdown
    $(document).ready(function() {
        $('#animalDropdown').select2();
    });
  // Initialize Select2 for the customer dropdown
    $(document).ready(function() {
        $('#customerDropdown').select2();
    });
</script>
<!-- Page specific script for the calendar -->
<script>
  $(function () {
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
  })
</script>
</body>
</html>

<?php
require_once 'include/session.php';
require_once 'include/class/services.php';
require_once 'include/class/users.php';
require_once 'include/class/appointments.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';

$s = new Service();
$services = $s->getServices();

$u = new User();
$users = $u->getNames();

if (isset($_POST["Appointment"])) {
  $a = new Appointment();
  $a->AddAppointment($_POST["InputMail"], $_POST["InputName"], $_POST["InputService"], $_POST["InputUser"], 
  $_POST["InputDate1"], $_POST["InputDate2"], strlen($_POST["InputPaid"]));
}

// a_c pour Appointment_Calendar
$a_c = new Appointment();
$calendar = $a->getCalendar();
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
                      <label for="InputPaid">Etat du payement :</label>
                      <input type="checkbox" name="InputPaid" value="1">
                      <div class="input-group">
                        <button type="submit" name="Appointment" class="btn btn-primary">Ajouter le rendez vous</button>
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
          <input id="event-datas" type="hidden" value='<?php echo($calendar);?>'>
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

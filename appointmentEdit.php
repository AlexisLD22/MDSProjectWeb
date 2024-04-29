<?php
require_once 'include/session.php';
require_once 'include/class/appointments.php';
require_once 'include/class/users.php';
require_once 'include/class/services.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';

if(isset($_GET['id'])) {
  $appointmentId = $_GET['id'];
} else {
  header("Location: calendar.php");
}

$a = new Appointment();
$appointmentData = $a->getById($appointmentId);

$u = new User();
$userData = $u->getById($appointmentData->user_id);
$users = $u->getNames();
$s = new Service();
$serviceData = $s->getById($appointmentData->service_id);
$services = $s->getServices();
$an = new Animal();
$animalData = $an->getById($appointmentData->animal_id);

$c = new Customer();
$customerData = $c->getById($animalData->customer_id);

if(isset($_POST["Confirmation"])) {
  $is_paid = isset($_POST["Inputis_paid"]) == 1 ? 1 : 0;
  $a->ChangeAppointment(
    strval($appointmentData->id),
    $_POST["InputService"],
    $_POST["InputUser"],
    $_POST["InputDate1"],
    $_POST["InputDate2"],
    strval($is_paid),
    strval($userData->id)
  );
  header("Location: calendar.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | User Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="dist/img/user1-128x128.jpg" alt="user image">
                        <span class="username">
                          <a href="#"><?= $customerData->firstname . ' ' . $customerData->lastname?></a>
                          </span>
                        </div>
                        <div class="card-header">
                          <h3 class="card-title">
                            <i class="fas fa-text-width"></i>
                            Informations du rendez-vous
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="<?= 'appointmentEdit.php?id='. $appointmentId?>">
                          <div class="card-body">
                            <dl class="row">
                              <input type="hidden" name="user_id" value="<?= $userData->id?>"> 
                              
                              <dt class="col-sm-4">Prénom et nom du propriétaire</dt>
                              <dd class="col-sm-8"><?= $customerData->firstname . ' ' . $customerData->lastname?></dd>
                            
                              <dt class="col-sm-4">Nom de l'animal</dt>
                              <dd class="col-sm-8"><?= $animalData->name?></dd>
                              
                              <dt class="col-sm-4">Service</dt>
                              <dd class="col-sm-8">
                                <select class="form-control" name="InputService">
                                    <?php
                                    foreach ($services as $serviceName) {
                                      $selected = ($serviceName == $serviceData->name) ? 'selected' : '';
                                      echo '<option value="' . $serviceName . '" ' . $selected . '>' . $serviceName . '</option>';
                                    }
                                    ?>
                                </select>
                              </dd>
                              
                              <dt class="col-sm-4">Employé</dt>
                              <dd class="col-sm-8">
                                <select class="form-control" name="InputUser">
                                    <?php
                                    foreach ($users as $userName) {
                                      $selected = ($userName == $userData->firstname.' '.$userData->lastname) ? 'selected' : '';
                                      echo '<option value="' . $userName . '" ' . $selected . '>' . $userName . '</option>';
                                    }
                                    ?>
                                </select>
                              </dd>
                              
                              <dt class="col-sm-4">Date du début du rendez-vous</dt>
                              <dd class="col-sm-8">
                                <input type="datetime-local" class="form-control" name="InputDate1" value="<?= $appointmentData->date_start?>">
                              </dd>
                              
                              <dt class="col-sm-4">Date de fin du rendez-vous</dt>
                              <dd class="col-sm-8">
                                <input type="datetime-local" class="form-control" name="InputDate2" value="<?= $appointmentData->date_end?>">
                              </dd>
                              
                              <dt class="col-sm-4">Etat du payement </dt>
                              <dd class="col-sm-8">
                                <?php if ($appointmentData->is_paid): ?>
                                  <input type='checkbox' name='Inputis_paid' checked>
                                <?php else: ?>
                                  <input type='checkbox' name='Inputis_paid'>
                                <?php endif ?>
                              </dd>
                            </dl>
                          </div>
                          <button type="submit" name="Confirmation" class="btn btn-primary btn-block"><b>Confirmations des informations</b></button>
                          <?php if (isset($_SESSION["error_message"])) : ?>
                            <div class="error-message"><?= $_SESSION["error_message"]; ?></div>
                          <?php endif; ?>
                      </form>
                    </div>
                  </div>
                </div>
              </div><!-- /.card-body -->
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

  <!-- Importation du fichier footer.php -->
  <?php require_once 'include/footer.php';?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
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
$s = new Service();
$serviceData = $s->getById($appointmentData->service_id);
$an = new Animal();
$animalData = $an->getById($appointmentData->animal_id);

$c = new Customer();
$customerData = $c->getById($animalData->customer_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Appointment View</title>

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
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" src="dist/img/user4-128x128.jpg" alt="User profile picture">
                </div>
                <br>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Nom du service</b> <a class="float-right"><?= $serviceData->name?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Nom de l'animal</b> <a class="float-right"><?= $animalData->name?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Nom de l'employé</b> <a class="float-right"><?= $userData->firstname . ' ' . $userData->lastname?></a>
                  </li>
                  </li>
                  <li class="list-group-item">
                    <b>Date début</b> <a class="float-right"><?= $a->announceDate($appointmentData->date_start)?></a>
                  </li>
                  </li>
                  <li class="list-group-item">
                    <b>Date fin</b> <a class="float-right"><?= $a->announceDate($appointmentData->date_end)?></a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
                      <form method="post" action="<?= 'appointmentEdit.php?id='. $appointmentId?>">
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
                        <div class="card-body">
                          <dl class="row">
                            <input type="hidden" name="user_id" value="<?= $userData->id?>">
                            <dt class="col-sm-4">ID</dt>
                            <dd class="col-sm-8"><?= $appointmentData->id?></dd>
                            
                            <dt class="col-sm-4">Prénom et nom du propriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->firstname . ' ' . $customerData->lastname?></dd>
                            
                            <dt class="col-sm-4">Nom de l'animal</dt>
                            <dd class="col-sm-8"><?= $animalData->name?></dd>
                            
                            <dt class="col-sm-4">Téléphone du propriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->telephone?></dd>
                            
                            <dt class="col-sm-4">Nom de l'employé</dt>
                            <dd class="col-sm-8"><?= $userData->firstname . ' ' . $userData->lastname?></dd>
                            
                            <dt class="col-sm-4">Service</dt>
                            <dd class="col-sm-8"><?= $serviceData->name?></dd>
                            
                            <dt class="col-sm-4">Date du début du rendez-vous</dt>
                            <dd class="col-sm-8"><?= $a->announceDate($appointmentData->date_start)?></dd>
                            
                            <dt class="col-sm-4">Date de fin du rendez-vous</dt>
                            <dd class="col-sm-8"><?= $a->announceDate($appointmentData->date_end)?></dd>
                            
                            <dt class="col-sm-4">Etat du payement </dt>
                            <dd class="col-sm-8"><?= $appointmentData->is_paid ? "Vrai" : "Faux" ?></dd>
                            
                            <dt class="col-sm-4">Prix</dt>
                            <dd class="col-sm-8"><?= $serviceData->price?> €</dd>
                          </dl>
                        </div>
                        <button type="submit" name="Edit" class="btn btn-primary btn-block"><b>Changer les informations</b></button>
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
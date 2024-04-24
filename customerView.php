<?php
require_once 'include/session.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';
require_once 'include/class/appointments.php';

if(isset($_GET['id'])) {
  $customerId = $_GET['id'];
} else {
  header("Location: listingCustomers.php");
}

$c = new Customer();
$customerData = $c->getById($customerId);

$a = new Animal();
$animals = $a->getAnimalsByCustomerId($customerData->id);

$an = new Appointment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Customer Profile</title>

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
              <li class="breadcrumb-item active">Customer Profile</li>
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
                <h3 class="profile-username text-center"><?= $customerData->firstname . ' ' . $customerData->lastname?></h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Telephone</b> <a class="float-right"><?= $customerData->telephone?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Adresse mail</b> <a class="float-right"><?= $customerData->mail?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Adresse postale</b> <a class="float-right"><?= $customerData->postal_adress?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Nombre d'animaux</b> <a class="float-right"><?= $customerData->id?></a>
                  </li>
                </ul>
              </div>
            </div>
            
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center">Rendez-vous des animaux</h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <?php foreach($animals as $animal): ?>
                    <li class="list-group-item">
                      <?= $animal->name?>
                      <br>
                      <?= $an->GetNextAppointmentByAnimalId($animal->id)?>
                    </li>
                  <?php endforeach ?>
                </ul>
              </div>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
                      <form method="post" action="<?= 'CustomerEdit.php?id=' . $customerData->id?>">
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="dist/img/user1-128x128.jpg" alt="user image">
                          <span class="username">
                            <a href="#"><?= $customerData->firstname . ' ' . $customerData->lastname?></a>
                          </span>
                        </div>
                        <div class="card-header">
                          <h3 class="card-title">
                            <i class="fas fa-text-width"></i>
                            Fiche d'informations
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <dl class="row">
                            <input type="hidden" name="customer_id" value="<?= $customerData->id?>">
                            <dt class="col-sm-4">ID</dt>
                            <dd class="col-sm-8"><?= $customerData->id?></dd>
                            
                            <dt class="col-sm-4">Nom du client</dt>
                            <dd class="col-sm-8"><?= $customerData->firstname . ' ' . $customerData->lastname?></dd>

                            <dt class="col-sm-4">Telephone</dt>
                            <dd class="col-sm-8"><?= $customerData->telephone?></dd>
                            
                            <dt class="col-sm-4">Mail</dt>
                            <dd class="col-sm-8"><?= $customerData->mail?></dd>
                            
                            <dt class="col-sm-4">Adresse postal</dt>
                            <dd class="col-sm-8"><?= $customerData->postal_adress?></dd>

                            <dt class="col-sm-4">Animaux :</dt>
                            <?php foreach($animals as $animal): ?>
                            <dd class="col-sm-8 offset-sm-4">
                              <?= $animal->name?>
                              <a href="<?= 'animalView.php?id=' . $animal->id?>" class="btn btn-primary btn-sm">
                              <i class="fas fa-folder"></i>Voir</a>
                            </dd>
                            <?php endforeach?>

                            <dt class="col-sm-4">Commentaire</dt>
                            <dd class="col-sm-8"><?= $customerData->commentary?></dd>
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

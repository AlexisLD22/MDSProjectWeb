<?php
require_once 'include/session.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';
require_once 'include/class/appointments.php';

if(isset($_GET['id'])) {
  $animalId = $_GET['id'];
} else {
  header("Location: listingAnimals.php");
}

$a = new Animal();
$animalData = $a->getById($animalId);

$c = new Customer();
$customerData = $c->getById($animalData->customer_id);

$an = new Appointment();
$appointments = $an->getAll();

$currentDate = getdate();
$currentDateFormated = sprintf(
    "%04d-%02d-%02d %02d:%02d:%02d",
    $currentDate["year"],
    $currentDate["mon"],
    $currentDate["mday"],
    $currentDate["hours"],
    $currentDate["minutes"],
    $currentDate["seconds"]
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Animal Profile</title>

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
            <h1>Animal View</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="listingAnimals.php">Liste Animaux</a></li>
              <li class="breadcrumb-item active">Animal View</li>
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
                  <i class="fas fa-paw fa-6x"></i>
                </div>
                <h3 class="profile-username text-center"><?= $animalData->name?></h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Race</b> <a class="float-right"><?= $animalData->breed?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Propriétaire</b> <a class="float-right"><?= $customerData->firstname . ' ' . $customerData->lastname ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Age</b> <a class="float-right"><?= $animalData->age?></a>
                  </li>
                </ul>
              </div>
            </div>
            
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center">Listes des prochains rendez-vous</h3>
                <ul class="list-group list-group-unbordered mb-3">
                  <?php foreach($appointments as $appointment): ?>
                    <?php if ($currentDateFormated < $appointment->date_start && intval($appointment->animal_id) === $animalData->id): ?>
                      <li class="list-group-item">
                        <?= $an->announceDate($appointment->date_start)?>
                      </li>
                    <?php endif; ?>
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
                      <form method="post" action="<?= 'AnimalEdit.php?id=' . $animalData->id?>">
                        <div class="card-header">
                          <h3 class="card-title">
                            <i class="fas fa-paw fa-2x"></i>
                            Fiche d'informations de <?= $animalData->name?>
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <dl class="row">
                            <input type="hidden" name="user_id" value="<?= $animalData->id?>">
                            <dt class="col-sm-4">ID</dt>
                            <dd class="col-sm-8"><?= $animalData->id?></dd>
                            
                            <dt class="col-sm-4">Nom de l'animal</dt>
                            <dd class="col-sm-8"><?= $animalData->name?></dd>
                            
                            <dt class="col-sm-4">Race de l'animal</dt>
                            <dd class="col-sm-8"><?= $animalData->breed?></dd>

                            <dt class="col-sm-4">Nom du propriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->firstname . ' ' . $customerData->lastname?></dd>
                            
                            <dt class="col-sm-4">Téléphone du proriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->telephone?></dd>

                            <dt class="col-sm-4">Adresse mail du propriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->mail?></dd>
                            
                            <dt class="col-sm-4">Taille</dt>
                            <dd class="col-sm-8"><?= $animalData->height?></dd>
                            
                            <dt class="col-sm-4">Poids</dt>
                            <dd class="col-sm-8"><?= $animalData->weight?></dd>
                            
                            <dt class="col-sm-4">Age</dt>
                            <dd class="col-sm-8"><?= $animalData->age?></dd>
                            
                            <dt class="col-sm-4">Commentaire</dt>
                            <dd class="col-sm-8"><?= $animalData->commentary?></dd>
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

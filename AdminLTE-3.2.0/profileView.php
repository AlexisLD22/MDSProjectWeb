<?php
require_once 'include/session.php';
require_once 'include/class/users.php';
require_once 'include/class/services.php';

$u = new User();
$userData = $u->getById($_POST['user_id']);
$userAbilities = $u->getCapabilityById($_POST['user_id']);

$s = new Service();
$services = $s->getServices();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | User Profile</title>

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
                  <img class="profile-user-img img-fluid img-circle"
                       src="dist/img/user4-128x128.jpg"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?= $userData->firstname . ' ' . $userData->lastname?></h3>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Téléphone</b> <a class="float-right"><?= $userData->telephone?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Mail</b> <a class="float-right"><?= $userData->mail?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Postal address</b> <a class="float-right"><?= $userData->postal_adress?></a>
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
                      <form method="post" action="profileEdit.php">
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="dist/img/user1-128x128.jpg" alt="user image">
                          <span class="username">
                            <a href="#"><?= $userData->firstname . ' ' . $userData->lastname?></a>
                          </span>
                        </div>
                        <div class="card-header">
                          <h3 class="card-title">
                            <i class="fas fa-text-width"></i>
                            Fiche de poste
                          </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                          <dl class="row">
                            <input type="hidden" name="user_id" value="<?= $userData->id?>">
                            <dt class="col-sm-4">ID</dt>
                            <dd class="col-sm-8"><?= $userData->id?></dd>
                            
                            <dt class="col-sm-4">Est administrateur</dt>
                            <dd class="col-sm-8"><?= $userData->is_admin ? "Vrai" : "Faux" ?></dd>
                            
                            <dt class="col-sm-4">Prénom</dt>
                            <dd class="col-sm-8"><?= $userData->firstname?></dd>
                            
                            <dt class="col-sm-4">Nom </dt>
                            <dd class="col-sm-8"><?= $userData->lastname?></dd>
                            
                            <dt class="col-sm-4">Formations :</dt>
                            <?php foreach($services as $service): ?>
                            <dd class="col-sm-8 offset-sm-4"><?= in_array($service, $userAbilities) ? "Possède la formation $service." : "Ne possède pas la formation $service."?></dd>
                            <?php endforeach?>
                            
                            <dt class="col-sm-4">Téléphone</dt>
                            <dd class="col-sm-8"><?= $userData->telephone?></dd>

                            <dt class="col-sm-4">Adresse mail</dt>
                            <dd class="col-sm-8"><?= $userData->mail?></dd>
                            
                            <dt class="col-sm-4">Adresse postal</dt>
                            <dd class="col-sm-8"><?= $userData->postal_adress?></dd>
                          </dl>
                        </div>
                        <button type="submit" name="Edit" class="btn btn-primary btn-block"> <b>Changer les informations</b></button>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

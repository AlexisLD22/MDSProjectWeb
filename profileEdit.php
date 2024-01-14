<?php
require_once 'include/session.php';
require_once 'include/class/users.php';
require_once 'include/class/services.php';

if(isset($_GET['id'])) {
  $userId = $_GET['id'];
  $info = "Update";
} else {
  $info = "Creation";
}

// Récupération des services pour les afficher
$s = new Service();
$services = $s->getServices();

if ($info == "Update") {
  
  $u = new User();
  $userData = $u->getById($userId);
  $userAbilities = $u->getCapabilityById($userId);
  
  if (isset($_POST['Confirmation'])) {

    $capabilities = [
      "c1" => isset($_POST["Capability1"]) == 1 ? 1 : 0,
      "c2" => isset($_POST["Capability2"]) == 1 ? 1 : 0,
      "c3" => isset($_POST["Capability3"]) == 1 ? 1 : 0,
      "c4" => isset($_POST["Capability4"]) == 1 ? 1 : 0
    ];
    $is_admin = isset($_POST["is_admin"]) == 1 ? 1 : 0;
    $u->update(strval($userData->id), strval($is_admin), $_POST["firstname"], $_POST["lastname"], $capabilities, $_POST["telephone"], $_POST["mail"], $_POST["postal_adress"]);
    header("Location: admin.php");
  }
} elseif ($info == "Creation") {

  $u = new User();
  $userData = $u->getById($userId);
  $userAbilities = $u->getCapabilityById($userId);

  if (isset($_POST['Confirmation'])) {
    $capabilities = [
      "c1" => isset($_POST["Capability1"]) == 1 ? 1 : 0,
      "c2" => isset($_POST["Capability2"]) == 1 ? 1 : 0,
      "c3" => isset($_POST["Capability3"]) == 1 ? 1 : 0,
      "c4" => isset($_POST["Capability4"]) == 1 ? 1 : 0
    ];
    $is_admin = isset($_POST["is_admin"]) == 1 ? 1 : 0;
    $u->createUser(strval($is_admin), $_POST["firstname"], $_POST["lastname"], $capabilities, $_POST["telephone"], $_POST["mail"], $_POST["postal_adress"], $_POST["password"]);
    header("Location: admin.php");
  }
} else {
  header("Location: admin.php");
}
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
          <!-- /.col -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <div class="post">
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
                      <form method="POST" action="">
                        <div class="card-body">
                          <dl class="row">
                          <input type="hidden" name="user_id" value="<?= $userData->id?>">
                            
                          <dt class="col-sm-4">Est administrateur</dt>
                          <dd class="col-sm-8">
                            <?php if ($userData->is_admin): ?>
                              <input type='checkbox' name='is_admin' checked>
                            <?php else: ?>
                              <input type='checkbox' name='is_admin'>
                            <?php endif ?>
                          </dd>
                          
                          <dt class="col-sm-4">Prénom</dt>
                          <dd class="col-sm-8">
                            <input type="text" name="firstname" class="col-sm-8" value="<?= $userData->firstname?>">
                          </dd>

                          <dt class="col-sm-4">Nom </dt>
                          <dd class="col-sm-8">
                            <input type="text" name="lastname" class="col-sm-8" value="<?= $userData->lastname?>">
                          </dd>
                          
                          <dt class="col-sm-4">Formations :</dt>
                          <?php  
                            $number = 1;
                            foreach($services as $service):
                          ?>
                          <dd class="col-sm-8 offset-sm-4">
                            <?php if (in_array($service, $userAbilities)): ?>
                              Formation <?=$service?> :
                              <input type='checkbox' name='Capability<?=$number?>' checked>
                            <?php else: ?>
                              Formation <?=$service?> :
                              <input type='checkbox' name='Capability<?=$number?>'>
                            <?php endif ?>
                          </dd>
                          <?php
                            $number = $number + 1;
                            endforeach;
                          ?>
                          
                          <dt class="col-sm-4">Téléphone</dt>
                          <dd class="col-sm-8">
                            <input type="text" name="telephone" class="col-sm-8" value="<?= $userData->telephone?>">
                          </dd>
                          
                          <dt class="col-sm-4">Adresse postal</dt>
                          <dd class="col-sm-8">
                            <input type="text" name="postal_adress" class="col-sm-8" value="<?= $userData->postal_adress?>">
                          </dd>
                          
                          <dt class="col-sm-4">Adresse mail</dt>
                          <dd class="col-sm-8">
                            <input type="text" name="mail" class="col-sm-8" value="<?= $userData->mail?>">
                          </dd>
                          
                          <?php if ($info == "Creation"): ?>
                            <dt class="col-sm-4">Mot de passe</dt>
                            <dd class="col-sm-8">
                              <input type="password" name="password" class="col-sm-8">
                            </dd>
                          <?php endif ?>

                          </dl>
                        </div>
                        <button type="submit" name="Confirmation" class="btn btn-primary btn-block"><b>Confirmations des informations</b</button>
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

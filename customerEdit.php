<?php
require_once 'include/session.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';

if(isset($_GET['id'])) {
  $animalId = $_GET['id'];
} else {
  header("Location: listingAnimals.php");
}

$a = new Animal();
$animalData = $a->getById($animalId);

$c = new Customer();
$customerData = $c->getById($animalData->customer_id);
$customers = $c->getNames();

if (isset($_POST['Confirmation'])) {
  $a->update($_POST["animal_id"], $_POST["name"], $_POST["breed"], $_POST["customer"], $_POST["height"], $_POST["weight"], $_POST["age"], $_POST["commentary"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Animal Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Animal Profile</li>
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
                          <a href="#"><?= $animalData->name?></a>
                        </span>
                      </div>
                      <div class="card-header">
                        <h3 class="card-title">
                          <i class="fas fa-text-width"></i>
                          Fiche d'informations
                        </h3>
                      </div>
                      <!-- /.card-header -->
                      <form method="POST" action="<?= $_SERVER['PHP_SELF']?>">
                        <div class="card-body">
                          <dl class="row">
                            <input type="hidden" name="animal_id" value="<?= $animalData->id?>">
                            
                            <dt class="col-sm-4">Nom de l'animal</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="name" class="col-sm-8" value="<?= $animalData->name?>">
                            </dd>
                            
                            <dt class="col-sm-4">Race de l'animal</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="breed" class="col-sm-8" value="<?= $animalData->breed?>">
                            </dd>

                            <dt class="col-sm-4">Nom du proriétaire</dt>
                            <dd class="col-sm-8">
                              <select class="form-control" name="customer" id="customerDropdown">
                                <?php
                                foreach ($customers as $customersName) {
                                  $selected = ($customersName == $customerData->firstname.' '.$customerData->lastname) ? 'selected' : '';
                                  echo '<option value="' . $customersName . '" ' . $selected . '>' . $customersName . '</option>';
                                }
                                ?>
                              </select>
                            </dd>

                            <dt class="col-sm-4">Nom du proriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->firstname.' '.$customerData->lastname?></dd>
                            
                            <dt class="col-sm-4">Téléphone du proriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->telephone?></dd>
                            
                            <dt class="col-sm-4">Adresse mail du propriétaire</dt>
                            <dd class="col-sm-8"><?= $customerData->mail?></dd>
                            
                            <dt class="col-sm-4">Taille</dt>
                            <dd class="col-sm-8">
                              <input type="number" name="height" class="col-sm-8" value="<?= $animalData->height?>">
                            </dd>
                            
                            <dt class="col-sm-4">Poids</dt>
                            <dd class="col-sm-8">
                              <input type="number" name="weight" class="col-sm-8" value="<?= $animalData->weight?>">
                            </dd>
                            
                            <dt class="col-sm-4">Age</dt>
                            <dd class="col-sm-8">
                              <input type="number" name="age" class="col-sm-8" value="<?= $animalData->age?>">
                            </dd>
                            
                            <dt class="col-sm-4">Commentaire</dt>
                            <dd class="col-sm-8">
                              <input type="textarea" name="commentary" class="col-sm-8" value="<?= $animalData->commentary?>">
                            </dd>
                          </dl>
                        </div>
                        <button type="submit" name="Confirmation" class="btn btn-primary btn-block"><b>Confirmations des informations</b</button>
                        <?php if (isset($_SESSION["error_message_animalEdit"])) : ?>
                          <div class="error-message"><?= $_SESSION["error_message_animalEdit"]; ?></div>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Include the Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Page specific script -->
<script>
  // Initialize Select2 for the customer dropdown
    $(document).ready(function() {
        $('#customerDropdown').select2();
    });
</script>
</body>
</html>

<?php
require_once 'include/session.php';
require_once 'include/class/animals.php';
require_once 'include/class/customers.php';

$c = new Customer();
$customers = $c->getNames();

if (isset($_POST['AO'])) {
  $a = new Animal();
  $a->AddAnimal(
    $_POST["inputName"],
    $_POST["inputBreed"],
    $_POST["InputHeight"],
    $_POST["InputWeight"],
    $_POST["InputAge"],
    $_POST["InputCustomer"],
    $_POST["inputCommentary"]
  );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Animal Inscription</title>

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
            <h1>Inscription Animal</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
              <li class="breadcrumb-item active">Inscriptions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- DEBUT COLONNE GAUCHE -->
          <div class="col-md-12">
            <!-- DEBUT FORM ANIMAL ONLY -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Inscription Animal</h3>
              </div>
              <form class="form-horizontal" method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                <div class="card-body">    
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Nom de l'animal</label>
                    <div class="input-group mb-3 col-sm-10">
                      <input type="Name" required class="form-control" name="inputName" placeholder="Nom de l'animal">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputBreed" class="col-sm-2 col-form-label">Race de l'animal</label>
                    <div class="input-group mb-3 col-sm-10">
                      <input type="text" required class="form-control" name="inputBreed" placeholder="Race de l'animal">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-paw"></i></span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-4">
                      <label for="InputHeight">Taille</label>
                      <div class="input-group mb-3">
                        <input type="number" class="form-control" name="InputHeight" placeholder="Height">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-ruler-vertical"></i></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <label for="InputWeight">Poids</label>
                      <div class="input-group mb-3">
                        <input type="number" class="form-control" name="InputWeight" placeholder="Weight">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <label for="InputAge">Age</label>
                      <div class="input-group mb-3">
                        <input type="number" class="form-control" name="InputAge" placeholder="Age">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-hourglass"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail" class="col-sm-3 col-form-label">Nom du propriétaire</label>
                    <div class="input-group col-sm-9">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                      </div>
                      <select class="form-control" name="InputCustomer" id="customerDropdown">
                        <?php
                        foreach ($customers as $customersName) {
                          echo '<option value="' . $customersName . '">' . $customersName . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="InputCommentary">Commentaire pour le chien</label>
                    <div class="input-group mb-3">
                      <textarea class="form-control" rows="3" name="inputCommentary" placeholder="Commentaire ..."></textarea>
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-file-word"></i></span>
                      </div>
                    </div>
                  </div>

                  <?php if (isset($_SESSION["error_message_InscriptionAnimal"])) : ?>
                    <div class="error-message"><?= $_SESSION["error_message_InscriptionAnimal"]?></div>
                  <?php endif; ?>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="AO" class="btn btn-info">Enregistrer nouveau chien</button>
                  <!-- <button type="button" class="btn btn-default float-right" onclick="clearForm()">Cancel</button> -->
                </div>
                <!-- /.card-footer -->
              </form>
              <!-- FIN FORM ANIMAL ONLY -->
            </div>
          </div>
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
<!-- Bootstrap 4 -->
<!-- bs-custom-file-input -->
<!-- AdminLTE App -->
<!-- Page specific script -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<!-- Page specific script -->
<style>
  .select2-container--default .select2-selection--single {
    line-height: 15px;
    height: 40px;
  }
</style>
<script>
  // Initialize Select2 for the customer dropdown
    $(document).ready(function() {
        $('#customerDropdown').select2();
    });
</script>
</body>
</html>

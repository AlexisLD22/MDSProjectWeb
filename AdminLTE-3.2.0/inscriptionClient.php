<?php
require_once 'include/session.php';
require_once 'include/class/customers.php';

if (isset($_POST['CO'])) {
  $c = new Customer();
  $c->AddCustomer($_POST["InputFirstName"], $_POST["InputLastName"], $_POST["InputEmail"], $_POST["InputPhone"], $_POST["InputAdress"], $_POST["InputCommentary"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Inscriptions Client</title>

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
            <h1>Inscriptions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Inscription Client</h3>
              </div>
              <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                <div class="card-body">
                  
                  <div class="form-group row">
                    <label for="InputFirstName" class="col-sm-2 col-form-label">Prénom</label>
                    <div class="input-group mb-3 col-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                      </div>
                      <input type="text" class="form-control" name="InputFirstName" placeholder="Prénom">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="InputLastName" class="col-sm-2 col-form-label">Nom de famille</label>
                    <div class="input-group mb-2 col-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                      </div>
                      <input type="text" class="form-control" name="InputLastName" placeholder="Nom de famille">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="InputEmail" class="col-sm-2 col-form-label">Adresse mail</label>
                    <div class="input-group mb-2 col-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                      <input type="email" class="form-control" name="InputEmail" placeholder="Adresse mail">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="InputPhone" class="col-sm-2 col-form-label">Téléphone</label>
                    <div class="input-group mb-2 col-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-mobile"></i></span>
                      </div>
                      <input type="text" class="form-control" name="InputPhone" placeholder="Téléphone">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="InputAdress" class="col-sm-2 col-form-label">Adresse postale</label>
                    <div class="input-group mb-2 col-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                      </div>
                      <input type="text" class="form-control" name="InputAdress" placeholder="Adresse postale">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="InputCommentary">Commentaire</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-file-word"></i></span>
                      </div>
                      <textarea class="form-control" rows="3" name="InputCommentary" placeholder="Commentaire ..."></textarea>
                    </div>
                  </div>

                  <?php if (isset($_SESSION["error_message_InscriptionCustomer"])) : ?>
                      <div class="error-message"><?= $_SESSION["error_message_InscriptionCustomer"]; ?></div>
                  <?php endif; ?>
                </div>
                <div class="card-footer">
                  <button type="submit" name="CO" class="btn btn-primary">Enregistrer nouveau client</button>
                </div>
              </form>
            </div>
          </div>
          <!-- FIN COLONNE GAUCHE -->
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
<!-- AdminLTE for demo purposes -->
<!-- Page specific script -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>

<?php
require_once 'include/session.php';
require_once 'include/class/customers.php';

if(isset($_GET['id'])) {
  $customerId = $_GET['id'];
} else {
  header("Location: listingCustomers.php");
}

$c = new Customer();
$customerData = $c->getById($customerId);

if (isset($_POST['Confirmation'])) {
  $c->update(
    $_POST["customer_id"],
    $_POST["firstname"],
    $_POST["lastname"],
    $_POST["mail"],
    $_POST["telephone"],
    $_POST["postal_adress"],
    $_POST["commentary"]
  );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Customer Profile</title>

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
            <h1>Client Edit</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="listingCustomers.php">Liste Clients</a></li>
              <li class="breadcrumb-item active">Client Edit</li>
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
                        <i class="fas fa-child fa-2x"></i>
                        <?= $customerData->firstname . ' ' . $customerData->lastname?>
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
                            <input type="hidden" name="customer_id" value="<?= $customerData->id?>">
                            
                            <dt class="col-sm-4">Prénom du client</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="firstname" class="col-sm-8" value="<?= $customerData->firstname?>">
                            </dd>
                            
                            <dt class="col-sm-4">Nom du client</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="lastname" class="col-sm-8" value="<?= $customerData->lastname?>">
                            </dd>
                            
                            <dt class="col-sm-4">E-mail</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="mail" class="col-sm-8" value="<?= $customerData->mail?>">
                            </dd>
                            
                            <dt class="col-sm-4">Téléphone</dt>
                            <dd class="col-sm-8">
                              <input type="number" name="telephone" class="col-sm-8" value="<?= $customerData->telephone?>">
                            </dd>
                            
                            <dt class="col-sm-4">Adresse postale</dt>
                            <dd class="col-sm-8">
                              <input type="text" name="postal_adress" class="col-sm-8" value="<?= $customerData->postal_adress?>">
                            </dd>
                                                        
                            <dt class="col-sm-4">Commentaire</dt>
                            <dd class="col-sm-8">
                              <input type="textarea" name="commentary" class="col-sm-8" value="<?= $customerData->commentary?>">
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

<?php

require_once 'include/session.php';
require_once 'include/conn.php';

// Recupérer les informations pour ajouter seulement un nouveau client
$firstname = $_POST["InputFirstName1"];
$lastname= $_POST["InputLastName1"];
$mail= $_POST["InputEmail1"];
$telephone= $_POST["InputPhone1"];
$postal_address= $_POST["InputAdress1"];
$commentary= $_POST["InputCommentary1"];

// Récuperer les informations pour ajouter seulement un nouvel animal
$name2 = $_POST["inputName2"];
$breed2 = $_POST["inputBreed2"];
$weight2 = $_POST["InputHeight2"];
$height2 = $_POST["InputWeight2"];
$age2 = $_POST["InputAge2"];
$mail2 = $_POST["inputEmail2"];
$commentary2 = $_POST["inputCommentary2"];

//Récupérer les informations pour ajouter un nouveau client et un animal 
$firstname3 = $_POST["InputFirstName3"];
$lastname3 = $_POST["InputLastName3"];
$mail3 = $_POST["InputEmail3"];
$telephone3 = $_POST["InputPhone3"];
$postal_address3 = $_POST["InputAdress3"];
$commentary3 = $_POST["InputCommentary3"];
$name3 = $_POST["inputName3"];
$breed3 = $_POST["inputBreed3"];
$weight3 = $_POST["InputHeight3"];
$height3 = $_POST["InputWeight3"];
$age3 = $_POST["InputAge3"];
$commentary4 = $_POST["inputCommentary4"];

// Sanitize the inputs (prevent SQL injection)
$mail = $conn->real_escape_string($mail);
$mail2 = $conn->real_escape_string($mail2);
$mail3 = $conn->real_escape_string($mail3);

// On verifie que la personne n'existe pas déjà pour les Customers Only (CO) :
$query_Verif_CO = "SELECT * FROM customers WHERE mail = '$mail';";
$result = $conn->query($query_Verif_CO);

// On verifie que le chien n'existe pas déjà pour les Animals Only (AO) :
$query_Verif_AO = "SELECT * FROM animals AS A INNER JOIN customers AS C ON c.id = a.customer_id WHERE a.name = '$name2' AND c.mail = '$mail2';";
$result2 = $conn->query($query_Verif_AO);

// On vérifie que la personne n'existe pas déjà pour les Customers & Animals (CA) :
$query_Verif_CA = "SELECT * FROM customers WHERE mail = '$mail3';";
$result3 = $conn->query($query_Verif_CA);


// Creation d'un client venant du formulaire CO :
if ($result->num_rows === 1) {
    $error_message1 = "Il semblerait que le client existe déjà";
} else {
    // test pour se prévenir de tout bug éventuel
    if (strlen($postal_address) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($mail) > 0  && 
    strlen($postal_address) <= 45 && strlen($firstname) <= 45 && strlen($lastname) <= 45 && strlen($telephone) === 10) {
        $query_add_customerOnly = "INSERT INTO customers (firstname, lastname, mail, telephone, postal_adress, commentary) VALUES
        ('$firstname', '$lastname', '$mail', '$telephone', '$postal_address', '$commentary')";
        $conn->query($query_add_customerOnly);
    } else {
        $error_message1 = "Il y a une erreur lors de l'enregistrement du client";
    }
}

// Creation d'un animal venant du formulaire AO :
  if ($result2->num_rows === 1) {
    $error_message2 = "Il semblerait que l'animal existe déjà";
} else {
    // On récupere le customer id
    $Req_CustomerId2 = mysqli_query($conn, "SELECT id FROM customers where mail='$mail2';");
    $CustomerData2 = mysqli_fetch_assoc($Req_CustomerId2);
    $CustomerId2 = $CustomerData2['id'];
    // test pour se prévenir de tout bug éventuel
    if (strlen($name2) > 0 && strlen($breed2) > 0 && strlen($name2) <= 45 && strlen($breed2) <= 45 &&
    strlen($age2) > 0 && strlen($age2) <= 3 && strlen($weight2) > 0 && strlen($height2) > 0 && 
    strlen($weight2) <= 3 && strlen($height2) <= 3) {
        $query_add_animalOnly = "INSERT INTO animals (name, breed, age, weight, height, commentary, customer_id) VALUES
        ('$name2', '$breed2', '$age2', '$weight2', '$height2', '$commentary2', '$CustomerId2');";
        $conn->query($query_add_animalOnly);
    } else {
        $error_message2 = "Il y a une erreur lors de l'enregistrement de l'animal";
    }
}


// Creation d'un client et d'un animal venant du formulaire CA :
if ($result3->num_rows === 1) {
  $error_message3 = "Il semblerait que le client existe déjà";
} else {
  // Test pour ajouter le clien uniquement :
  if (strlen($postal_address3) > 0 && strlen($firstname3) > 0 && strlen($lastname3) > 0 && strlen($mail3) > 0  && 
  strlen($postal_address3) <= 45 && strlen($firstname3) <= 45 && strlen($lastname3) <= 45 && strlen($telephone3) === 10) {

    $query_add_customerCA = "INSERT INTO customers (firstname, lastname, mail, telephone, postal_adress, commentary) VALUES
    ('$firstname3', '$lastname3', '$mail3', '$telephone3', '$postal_address3', '$commentary3')";
    $conn->query($query_add_customerCA);

    // On récupere le customer id
    $Req_CustomerId3 = mysqli_query($conn, "SELECT id FROM customers where mail='$mail3';");
    $CustomerData3 = mysqli_fetch_assoc($Req_CustomerId3);
    $CustomerId3 = $CustomerData3['id'];
    // test pour se prévenir de tout bug éventuel
    if (strlen($name3) > 0 && strlen($breed3) > 0 && strlen($name3) <= 45 && strlen($breed3) <= 45 &&
    strlen($age3) > 0 && strlen($age3) <= 3 && strlen($weight3) > 0 && strlen($height3) > 0 && 
    strlen($weight3) <= 3 && strlen($height3) <= 3) {
        $query_add_animalCA = "INSERT INTO animals (name, breed, age, weight, height, commentary, customer_id) VALUES
        ('$name3', '$breed3', '$age3', '$weight3', '$height3', '$commentary4', '$CustomerId3');";
        $conn->query($query_add_animalCA);
    } else {
        $error_message3 = "Il y a une erreur lors de l'enregistrement du client ou de l'animal";
    }
  } else {
    $error_message3 = "Il y a une erreur lors de l'enregistrement du client ou de l'animal";
  }
}

// Close the database connection
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | General Form Elements</title>

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
          <div class="col-md-6">
            <!-- DEBUT FORM CUSTOMER ONLY -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Inscription Client Uniquement</h3>
              </div>
              <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="InputFirstName1">Prénom</label>
                    <input type="FirstName" class="form-control" name="InputFirstName1" placeholder="Prénom">
                  </div>
                  <div class="form-group">
                    <label for="InputLastName1">Nom de Famille</label>
                    <input type="LastName" class="form-control" name="InputLastName1" placeholder="Nom de Famille">
                  </div>
                  <div class="form-group">
                    <label for="InputEmail1">Adresse mail</label>
                    <input type="Email" class="form-control" name="InputEmail1" placeholder="Adresse mail">
                  </div>
                  <div class="form-group">
                    <label for="InputPhone1">Téléphone</label>
                    <input type="Phone" class="form-control" name="InputPhone1" placeholder="Téléphone">
                  </div>
                  <div class="form-group">
                    <label for="InputAdress1">Adresse postale</label>
                    <input type="Adress" class="form-control" name="InputAdress1" placeholder="Adresse postale">
                  </div>
                  <div class="form-group">
                    <label for="InputCommentaire1">Commentaire</label>
                    <textarea class="form-control" rows="3" name="InputCommentary1" placeholder="Commentaire ..."></textarea>
                  </div>
                  
                    <?php if (isset($error_message1)) : ?>
                        <div class="error-message"><?php echo $error_message1; ?></div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Enregistrer nouveau client</button>
                </div>
              </form>
            </div>
            <!-- FIN FORM CUSTOMER ONLY -->

            <!-- DEBUT FORM ANIMAL ONLY -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Inscription Animal Uniquement</h3>
              </div>
              <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputName2" class="col-sm-3 col-form-label">Nom de l'animal</label>
                    <div class="col-sm-9">
                      <input type="Name" class="form-control" name="inputName2" placeholder="Nom de l'animal">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputBreed2" class="col-sm-3 col-form-label">Race de l'animal</label>
                    <div class="col-sm-9">
                      <input type="Breed" class="form-control" name="inputBreed2" placeholder="Race de l'animal">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-4">
                        <label for="InputHeight2">Taille</label>
                        <input type="Height" class="form-control" name="InputHeight2" placeholder="Height">
                    </div>
                    <div class="col-4">
                        <label for="InputWeight2">Poids</label>
                        <input type="Weight" class="form-control" name="InputWeight2" placeholder="Weight">
                    </div>
                    <div class="col-4">
                        <label for="InputAge2">Age</label>
                        <input type="Age" class="form-control" name="InputAge2" placeholder="Age">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail2" class="col-sm-4 col-form-label">E-mail du propriétaire</label>
                    <div class="col-sm-8">
                      <input type="Email" class="form-control" name="inputEmail2" placeholder="E-mail du propriétaire">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="InputCommentaire2">Commentaire pour le chien</label>
                    <textarea class="form-control" rows="3" name="inputCommentary2" placeholder="Commentaire ..."></textarea>
                  </div>
                    <?php if (isset($error_message2)) : ?>
                        <div class="error-message"><?php echo $error_message2; ?></div>
                    <?php endif; ?>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Enregistrer nouveau chien</button>
                  <button type="submit" class="btn btn-default float-right">Cancel</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- FIN FORM ANIMAL ONLY -->
          </div>
          <!-- FIN COLONNE GAUCHE -->

          <!-- DEBUT COLONNE DROITE -->
          <div class="col-md-6">

            <!-- Input addon -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Inscription Client et Animal</h3>
              </div>
              
              <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="card-body">
                  <label for="InputFirstName3">Prénom</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-font"></i></span>
                    </div>
                    <input type="text" class="form-control" name="InputFirstName3" placeholder="Prénom">
                  </div>
                  
                  <label for="InputLastName3">Nom de famille</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-font"></i></span>
                    </div>
                    <input type="text" class="form-control" name="InputLastName3" placeholder="Nom de famille">
                  </div>

                  <label for="InputEmail3">Adresse mail</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" class="form-control" name="InputEmail3" placeholder="Adresse mail">
                  </div>

                  <label for="InputPhone3">Téléphone</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-mobile"></i></span>
                    </div>
                    <input type="text" class="form-control" name="InputPhone3" placeholder="Téléphone">
                  </div>

                  <label for="InputAdress3">Adresse postale</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-map"></i></span>
                    </div>
                    <input type="text" class="form-control" name="InputAdress3" placeholder="Adresse postale">
                  </div>
                  
                  <label for="InputCommentary3">Commentaire</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-file-word"></i></span>
                    </div>
                      <textarea class="form-control" rows="3" name="InputCommentary3" placeholder="Commentaire ..."></textarea>
                  </div>

                <div class="card-header">
                  <h3 class="card-title">Information pour l'animal</h3>
                </div>
                <br>

                  <div class="form-group row">
                    <label for="inputName3" class="col-sm-3 col-form-label">Nom de l'animal</label>
                    <div class="col-sm-9">
                      <div class="input-group mb-3">
                        <input type="Name" class="form-control" name="inputName3" placeholder="Nom de l'animal">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-font"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputBreed3" class="col-sm-3 col-form-label">Race de l'animal</label>
                    <div class="col-sm-9">
                      <div class="input-group mb-3">
                        <input type="Breed" class="form-control" name="inputBreed3" placeholder="Race de l'animal">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-paw"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-4">
                      <label for="InputHeight3">Taille</label>
                      <div class="input-group mb-3">
                        <input type="Height" class="form-control" name="InputHeight3" placeholder="Height">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-ruler-vertical"></i></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <label for="InputWeight3">Poids</label>
                      <div class="input-group mb-3">
                        <input type="Weight" class="form-control" name="InputWeight3" placeholder="Weight">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                        </div>
                      </div>
                    </div>

                    <div class="col-4">
                      <label for="InputAge3">Age</label>
                      <div class="input-group mb-3">
                        <input type="Age" class="form-control" name="InputAge3" placeholder="Age">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-hourglass"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="InputCommentary4">Commentaire pour le chien</label>
                    <div class="col-sm-12">
                      <div class="input-group mb-3">
                        <textarea class="form-control" rows="3" name="inputCommentary4" placeholder="Commentaire ..."></textarea>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fas fa-file-word"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php if (isset($error_message3)) : ?>
                      <div class="error-message"><?php echo $error_message3; ?></div>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Enregistrer nouveau chien</button>
                  <button type="submit" class="btn btn-default float-right">Cancel</button>
                </div>
              </form>
              <!-- /.card-body -->
            </div>
          </div>
          <!-- FIN COLONNE DROITE -->
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

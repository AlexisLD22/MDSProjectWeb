<?php

require_once 'include/session.php';
require_once 'include/conn.php';

// Requête pour récuperer les Clients :                            
$Req_Customers = mysqli_query($conn, "SELECT c.firstname as FirstName, c.lastname as LastName, COUNT(a.customer_id) as CountAnimals, c.id as ID FROM customers as c LEFT JOIN animals as a ON a.customer_id = c.id GROUP BY c.id;");

// Requête pour récuperer les Utilisateurs :
$Req_Users = mysqli_query($conn, "SELECT u.firstname as FirstName, u.lastname as LastName, COUNT(c.user_id) as CountCapability, u.id as ID FROM users as u LEFT JOIN capabilities as c ON c.user_id = u.id GROUP BY u.id;");


// Check if the form for deleting customers is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["customer_id"])) {
    $customer_id = $_POST["customer_id"];

    // Perform deletion from related tables
    $deleteAnimalsQuery = "DELETE FROM animals WHERE customer_id = $customer_id";
    $deleteCustomerQuery = "DELETE FROM customers WHERE id = $customer_id";

    // Execute the deletion queries
    if (mysqli_query($conn, $deleteAnimalsQuery) && mysqli_query($conn, $deleteCustomerQuery)) {
        header("Location: {$_SERVER['PHP_SELF']}");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if the form for deleting users is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
    $user_id = $_POST["user_id"];

    // Perform deletion from related tables
    $deleteAppointmentsQuery = "DELETE FROM appointments WHERE user_id = $user_id";
    $deleteCapabilitiesQuery = "DELETE FROM capabilities WHERE user_id = $user_id";
    $deleteUserQuery = "DELETE FROM users WHERE id = $user_id";

    // Execute the deletion queries
    if (mysqli_query($conn, $deleteAppointmentsQuery) && mysqli_query($conn, $deleteCapabilitiesQuery) && mysqli_query($conn, $deleteUserQuery)) {
        header("Location: {$_SERVER['PHP_SELF']}");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Listings</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
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
            <h1>Listing des Clients et des Utilisateurs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Listing</li>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Clients</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">
                                            Prénom et Nom
                                        </th>
                                        <th style="width: 25%">
                                            Avatar
                                        </th>
                                        <th style="width: 10%">
                                            Nombre d'animaux
                                        </th>
                                        <th style="width: 20%">
                                            Delete
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($Req_Customers) {
                                        // Fetch rows as associative array
                                        while ($customerData = mysqli_fetch_assoc($Req_Customers)) {
                                            // Generate a random number (1 or 2) to determine which avatar to use
                                            $avatarNumber = rand(1, 2);

                                            // Use the appropriate avatar based on the random number
                                            $avatar = ($avatarNumber == 1) ? "avatar.png" : "avatar2.png";

                                            // Output HTML row for each customer
                                            echo '<tr>';
                                            echo '<td>' . $customerData['FirstName'] . ' ' . $customerData['LastName'] . '</td>';
                                            echo '<td>';
                                            echo '<ul class="list-inline">';
                                            echo '<li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="dist/img/' . $avatar . '"></li>';
                                            // You can add more avatar images here if needed
                                            echo '</ul>';
                                            echo '</td>';
                                            echo '<td>' . $customerData['CountAnimals'] . '</td>';
                                            echo '<td class="project-actions text-right">';
                                            echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                                            echo '<input type="hidden" name="customer_id" value="' . $customerData['ID'] . '">';
                                            echo '<button type="submit" class="btn btn-danger btn-sm">';
                                            echo '<i class="fas fa-trash"></i> Delete</button>';
                                            echo '</form>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        // Handle the case where the query was not successful
                                        echo "Error: " . mysqli_error($conn);
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>    
                <!-- FIN COLONNE GAUCHE -->
                <!-- DEBUT COLONNE DROITE -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Utilisateurs</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">
                                            Prénom et Nom
                                        </th>
                                        <th style="width: 25%">
                                            Avatar
                                        </th>
                                        <th style="width: 25%">
                                            Avancement formations
                                        </th>
                                        <th style="width: 20%">
                                            Delete
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($Req_Users) {
                                        // Fetch rows as associative array
                                        while ($userData = mysqli_fetch_assoc($Req_Users)) {
                                            // Choose an avatar randomly
                                            $avatarNumber2 = rand(3, 4);

                                            // Use the appropriate avatar based on the random number
                                            $avatar2 = ($avatarNumber2 == 3) ? "avatar3.png" : "avatar4.png";

                                            // Calculate the progress percentage based on CountCapability
                                            $progressPercentage = $userData['CountCapability'] * 25;

                                            // Output HTML row for each user
                                            echo '<tr>';
                                            echo '<td>' . $userData['FirstName'] . ' ' . $userData['LastName'] . '</td>';
                                            echo '<td>';
                                            echo '<ul class="list-inline">';
                                            echo '<li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="dist/img/' . $avatar2 . '"></li>';
                                            echo '</ul>';
                                            echo '</td>';
                                            echo '<td class="project_progress">';
                                            echo '<div class="progress progress-sm">';
                                            echo '<div class="progress-bar bg-green" role="progressbar" aria-valuenow="' . $progressPercentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $progressPercentage . '%"></div>';
                                            echo '</div>';
                                            echo '<small>' . $userData['CountCapability'] . ' formations achevées</small>';
                                            echo '</td>';
                                            echo '<td class="project-actions text-right">';
                                            echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                                            echo '<input type="hidden" name="user_id" value="' . $userData['ID'] . '">';
                                            echo '<button type="submit" class="btn btn-danger btn-sm">';
                                            echo '<i class="fas fa-trash"></i> Delete</button>';
                                            echo '</form>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        // Handle the case where the query was not successful
                                        echo "Error: " . mysqli_error($conn);
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- FIN COLONNE DROITE -->
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Importation du fichier footer.php -->
  <?php require_once 'include/footer.php';?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>

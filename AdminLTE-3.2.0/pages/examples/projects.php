<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    // Redirect to login.php if not logged in
    header("Location: ../../login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "root";
$database = "toiletagecanin";

// Connexion avec la base de donnée
$conn = new mysqli($host, $username, $password, $database);

// Check for database connection error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Requête pour récuperer les Clients :                            
$Req_Customers = mysqli_query($conn, "SELECT c.firstname as FirstName, c.lastname as LastName, COUNT(a.customer_id) as CountAnimals, c.id as ID FROM customers as c INNER JOIN animals as a ON a.customer_id = c.id GROUP BY c.id;");

// Requête pour récuperer les Utilisateurs :
$Req_Users = mysqli_query($conn, "SELECT u.firstname as FirstName, u.lastname as LastName, COUNT(c.user_id) as CountCapability, u.id as ID FROM users as u INNER JOIN capabilities as c ON c.user_id = u.id GROUP BY u.id;");


// Check if the form for deleting customers is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["customer_id"])) {
    $customer_id = $_POST["customer_id"];

    // Perform deletion from related tables
    $deleteAnimalsQuery = "DELETE FROM animals WHERE customer_id = $customer_id";
    $deleteCustomerQuery = "DELETE FROM customers WHERE id = $customer_id";

    // Execute the deletion queries
    if (mysqli_query($conn, $deleteAnimalsQuery) && mysqli_query($conn, $deleteCustomerQuery)) {
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
  <title>AdminLTE 3 | Projects</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li>
            <a href="../../index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <!--Forms-->
          <a href="../forms/general.php" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>General Elements</p>
          </a>
          <!--DataTable-->
          <a href="../tables/data.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>DataTables</p>
          </a>
          <a href="../calendar.php" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Calendar
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <a href="../examples/projects.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Projects</p>
            </a>
            <a href="../charts/chartjs.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Charts</p>
            </a>
            <a href="https://adminlte.io/docs/3.1/" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Documentation</p>
            </a>
        </ul>
      </nav> 
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Projects</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">Projects</li>
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
                                    echo '<li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="../../dist/img/' . $avatar . '"></li>';
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
                                        echo '<li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="../../dist/img/' . $avatar2 . '"></li>';
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
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>

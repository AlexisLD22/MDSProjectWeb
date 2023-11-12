<?php
// Check de la connexion de l'utilisateur
  session_start();

  // Check if the user is not logged in
  if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
      header("Location: ../../login.php");
      exit();
  }

  $serveur = "localhost"; // Remplacez localhost par l'adresse de votre serveur
  $username = "root"; // Remplacez par votre nom d'utilisateur
  $password = "root"; // Remplacez par votre mot de passe
  $database = "toiletagecanin"; // Remplacez par le nom de votre base de données

  // Connexion à la base de données
  $conn = new mysqli($serveur, $username, $password, $database);

  // Vérifier la connexion
  if ($conn->connect_error) {
      die("La connexion a échoué : " . $conn->connect_error);
  }

  // Requête for Chart Doughnut breed     : Req_Doughnut
  // Requête pour Diagramme Taille        : Req_Bar_Taille
  // Requêtre pour Diagramme Capabilities : Req_Line
  // Requête pour Diagramme Poids         : Req_Bar_Poids

  $Req_Doughnut = mysqli_query($conn,"SELECT breed, COUNT(*) as count FROM animals GROUP BY breed;");
  $Req_Bar_Taille = mysqli_query($conn,"SELECT type_height, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN height < 110 THEN 'petit' WHEN (height BETWEEN 110 AND 130) THEN 'moyen' WHEN height > 130 THEN 'grand' END AS type_height FROM animals ) AS subquery GROUP BY type_height;");
  $Req_Line = mysqli_query($conn,"SELECT s.name, COUNT(c.service_id) as count FROM capabilities as c INNER JOIN services as s ON s.id = c.service_id GROUP BY name;");
  $Req_Bar_Poids = mysqli_query($conn,"SELECT type_weight, COUNT(*) AS count FROM ( SELECT breed, CASE WHEN weight < 40 THEN 'léger' WHEN (weight BETWEEN 40 AND 55) THEN 'normal' WHEN weight > 55 THEN 'gros' END AS type_weight FROM animals ) AS subquery GROUP BY type_weight;");

  // Affecte des données pour le Chart Doughnut
  foreach($Req_Doughnut as $data){
      $Doughnut_count[] = $data['count'];
      $Doughnut_breed[] = $data['breed'];
  }

  // Affecte Donnée de la requête pour Diagramme Taille
  foreach($Req_Bar_Taille as $data){
      $Bar_Type_height[] = $data['type_height'];
      $Bar_Height_count[] = $data['count'];
  }

  // Affecte Donnée de la requête pour Diagramme Capabilities
  foreach($Req_Line as $data){
    $Line_name[]= $data['name'];
    $Line_count[] = $data['count'];
  }

  // affecte Donnée de la requête pour Diagramme Poids
  foreach($Req_Bar_Poids as $data){
    $Bar_Type_Weight[] = $data['type_weight'];
    $Bar_Weight_count[] = $data['count'];
  }
  
  // Fermer la connexion
  $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | ChartJS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
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
      <img src="../../dist/img/AdminLTELogo.png"
           alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
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
          <a href="../forms/general.html" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>General Elements</p>
          </a>
          <!--DataTable-->
          <a href="../tables/data.html" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>DataTables</p>
          </a>
          <a href="../calendar.html" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Calendar
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <a href="../examples/projects.html" class="nav-link">
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
            <h1>ChartJS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">ChartJS</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Colonne Droite -->
          <div class="col-md-6">

            <!-- Diagramme Breed -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Diagramme Race</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>

            <!-- Diagramme Taille -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Diagramme de taille</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- Colonne Gauche -->
          <div class="col-md-6">

            <!-- Diagramme Service-->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Diagramme des formationes</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Diagramme Weight-->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Diagramme de poids</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<!-- ./wrapper -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/chart.js/Chart.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--------------------------------------------------------------------------------------------------->
<!-------------------------------------- SCRIPT DIAGRAMME RACE -------------------------------------->
<!--------------------------------------------------------------------------------------------------->
<script>
    new Chart(document.getElementById('donutChart'),{
            type: 'doughnut',
            data:{
                labels: <?php echo json_encode($Doughnut_breed)?>,
                datasets: [{
                label:'Quantité',
                data: <?php echo json_encode($Doughnut_count)?>,
                backgroundColor: [
                    'rgb(255, 99, 132)', 
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 40, 200'
                    ],
                hoverOffset: 4
                }]
            },
        });
</script>
<!--------------------------------------------------------------------------------------------------->
<!-------------------------------------- SCRIPT DIAGRAMME TAILLE ------------------------------------>
<!--------------------------------------------------------------------------------------------------->
<script>
    new Chart(document.getElementById('areaChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($Bar_Type_height)?>,
            datasets: [{
                label: ['Résumé taille'],
                data: <?php echo json_encode($Bar_Height_count)?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                } 
            }
        }
    });
</script>
<!--------------------------------------------------------------------------------------------------->
<!----------------------------------- SCRIPT DIAGRAMME CAPABILITIES --------------------------------->
<!--------------------------------------------------------------------------------------------------->
<script>
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($Line_name)?>,
            datasets: [{
                label: "Nombre d'employés formés pour ce service",
                data: <?php echo json_encode($Line_count)?>,
                borderWidth: 1
            }]
        },
        options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
        }
    });
</script>
<!--------------------------------------------------------------------------------------------------->
<!-------------------------------------- SCRIPT DIAGRAMME POIDS ------------------------------------->
<!--------------------------------------------------------------------------------------------------->
<script>
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($Bar_Type_Weight)?>,
            datasets: [{
                label: ['Résumé Poids'],
                data: <?php echo json_encode($Bar_Weight_count)?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)'
                ],
                borderWidth: 1
            }]
        },
        options: {
        scales: {
            y: {
                beginAtZero: true
            } 
        }
        }
    });
</script>
</body>
</html>
<?php
// Check de la connexion de l'utilisateur
  session_start();

  // Check if the user is not logged in
  if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
      header("Location: login.php");
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
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Importation du fichier naviation.php -->
  <?php require_once 'navigation.php';?>
  <!-- Importation du fichier aside.php -->
  <?php require_once 'aside.php';?>

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
  
  <!-- Importation du fichier footer.php -->
  <?php require_once 'footer.php';?>
</div>
<!-- ./wrapper -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
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
<?php
require_once 'include/session.php';

require_once 'include/class/users.php';

$u = new User();
$rows = $u->constructList();

if (isset($_POST["Delete"])) {
  $u->deleteUser($_POST["user_id"]);
  header("Location: admin.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ToilettageCanin | Admin</title>

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
            <h1>Administation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
              <li class="breadcrumb-item active">Utilisateurs</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
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
                          <th style="width: 20%">
                              Actions
                          </th>
                          <th style="width: 30%">
                              Prénom et Nom
                          </th>
                          <th style="width: 25%">
                              Avatar
                          </th>
                          <th style="width: 25%">
                              Avancement formations
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach($rows as $row): ?>
                        <tr>

                          <td class="project-actions text-right">
                            <ul style="display: flex; list-style-type: none;">
                              <li>
                                <a href="<?= 'profileView.php?id=' . $row["userData"]["ID"]?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-folder"></i>View</a>
                              </li>
                              <li>
                                <a href="<?= 'profileEdit.php?id=' . $row["userData"]["ID"]?>" class="btn btn-info btn-sm">
                                <i class="fas fa-pencil-alt"></i>Edit</a>
                              </li>
                              <li>
                                <button name="Delete" class="btn btn-danger btn-sm delete-btn" data-toggle="modal" data-id="<?= $row["userData"]["ID"]?>" data-target="#modal-default">
                                <i class="fas fa-trash"></i>Delete</button>
                              </li>
                            </ul>
                          </td>
                          
                          <td>
                            <?= $row["userData"]["FirstName"] . ' ' . $row["userData"]["LastName"]?>
                          </td>

                          <td>
                            <ul class="list-inline">
                              <li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="dist/img/<?= $row["avatar"]?>"></li>
                            </ul>
                          </td>
                          
                          <td class="project_progress">
                            <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?= $row["progressPercentage"]?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $row["progressPercentage"]?>%"></div>
                              </div>
                            <small><?= $row["userData"]["CountCapability"]?> formations achevées</small>
                          </td>

                        </tr>
                      <?php endforeach;?>
                  </tbody>
              </table>
              <div>
                <a href="profileEdit.php" class="btn btn-block btn-info btn-lg">Ajout d'un Utilisateur</a>
              </div>
          </div>
          <!-- /.card-body -->
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Suppression d'un utilisateur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="<?= $_SERVER['PHP_SELF']?>">
                <div class="modal-body">
                  <p>Etes-vous sûr de vouloir supprimer l'utilisateur ?</p>
                  <input type="hidden" name="user_id" value="" id="userToDelete">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="Delete" class="btn btn-danger">Supprimer</button>
                </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </div>
      <!-- /.card -->

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

<script>
    $(document).ready(function(){
        $('.delete-btn').on('click', function() {
            var userId = $(this).data('id');
            console.log(userId);
            let inputhidden = $('#userToDelete');
            console.log(inputhidden);
            inputhidden.val(userId);
            console.log(inputhidden);
        });
    });
</script>
</body>
</html>

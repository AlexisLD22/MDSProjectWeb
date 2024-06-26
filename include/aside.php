<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Toilettage Canin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <i class="fas fa-user fa-2x" style="color:#d6d7d8;"></i>
        </div>
        <div class="info">
          <a href="<?= 'profileView.php?id='. $_SESSION['id']?>" class="d-block"><?= $_SESSION['FullName']?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li>
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Inscriptions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inscriptionClient.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter un Client</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inscriptionAnimal.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ajouter un Animal</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li>
            <a href="data.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>DataTables</p>
            </a>
          </li> -->
          <li>
            <a href="calendar.php" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>Calendrier</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Listings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="listingCustomers.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste Clients</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="listingAnimals.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Liste Animaux</p>
                </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="statistiques.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Statistiques</p>
            </a>
          </li>
          <?php if ($_SESSION["is_admin"]): ?>
            <li>
              <a href="admin.php" class="nav-link">
                  <i class="nav-icon fas fa-columns"></i>
                <p>Admin</p>
              </a>
            </li>
          <?php endif ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
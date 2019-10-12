<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img  style="width: 45px; height: 45px;" src="data/uploades/avatarAdmins/<?= $userConnect['image'] ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $userConnect['prenom'] . ' ' . $userConnect['nom'] ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <br>
            <li>
                <a href="acceuilAdmin.php" class="<?php if( isset($link) && $link == 'acceuil' ) echo 'active' ?>">
                    <i class="fa fa-television"></i> <span>Acceuil</span>
                </a>
            </li>
            <li class="header">GESTION DES PATEINTS</li>
            <li class="<?php if( isset($link) && $link == 'patients' ) echo 'active' ?>">
                <a href="patients.php">
                    <i class="fa fa-users"></i><span>Patients</span>
                </a>
            </li>

            <li class="<?php if( isset($link) && $link == 'dossiers' ) echo 'active' ?>">
                <a href="dossiers.php">
                    <i class="fa fa-folder"></i><span>Dossiers medical</span>
                </a>
            </li>

            <li class="<?php if( isset($link) && $link == 'rendezVous' ) echo 'active' ?>">
                <a href="rendezVous.php">
                    <i class="fa fa-calendar"></i><span>Agenda des rendez vous</span>
                </a>
            </li>

            <li class="<?php if( isset($link) && $link == 'paiements' ) echo 'active' ?>">
                <a href="paiements.php">
                    <i class="fa fa-money"></i><span>Paiements</span>
                </a>
            </li>

            <li class="header">STATISTIQUES</li>
            <li class="<?php if( isset($link) && $link == 'statistiques' ) echo 'active' ?>">
                <a href="statistiques.php">
                    <i class="fa fa-line-chart"></i><span>Statistiques</span>
                </a>
            </li>

            <li class="header">GESTION D'UTILISATEURS</li>

            <li class="treeview <?php if( isset($link) && $link == 'admins' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-user-md"></i> <span>Administrateurs</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'admins' ) echo 'active' ?>"><a href="admins.php"><i class="fa fa-circle-o"></i> Liste des Admins</a></li>
                    <li class=" <?php if( isset($subLink) && $subLink == 'addAdmin' ) echo 'active' ?>"><a href="addAdmin.php"><i class="fa fa-circle-o"></i> Ajouter nouveau admin</a></li>
                </ul>
            </li>

            <li class="treeview <?php if( isset($link) && $link == 'gestionnaires' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Gestionnaires</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'gestionnaires' ) echo 'active' ?>"><a href="gestionnaires.php" ><i class="fa fa-circle-o"></i> Liste des gestionnaires</a></li>
                    <li class=" <?php if( isset($subLink) && $subLink == 'addGestionnaire' ) echo 'active' ?>"><a href="addGestionnaire.php"><i class="fa fa-circle-o"></i> Ajouter un gestionnaires</a></li>
                </ul>
            </li>

            <li class="header">REGLAGE</li>
            <li class="treeview <?php if( isset($link) && $link == 'horaires' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Horaires de travail</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'horaires' ) echo 'active' ?>"><a href="horaires.php"><i class="fa fa-circle-o"></i> Liste horaires de travail</a></li>
                    <li class=" <?php if( isset($subLink) && $subLink == 'addHoraire' ) echo 'active' ?>"><a href="addHoraire.php"><i class="fa fa-circle-o"></i> Nouveau horaire de travail</a></li>
                </ul>
            </li>
            <li class="<?php if( isset($link) && $link == 'parametrage' ) echo 'active' ?>">
                <a href="parametrage.php">
                    <i class="fa fa-gears"></i><span>Paramétrage</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
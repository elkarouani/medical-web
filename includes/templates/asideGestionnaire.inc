<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img style="width: 45px; height: 45px;" src="data/uploades/avatarGestionnaires/<?php echo $userConnect['image'] ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p> <?= $userConnect['prenom'] . ' ' . $userConnect['nom'] ?></p>
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
            <li class="treeview <?php if( isset($link) && $link == 'patients' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Patients</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'patients' ) echo 'active' ?>"><a href="patients.php"><i class="fa fa-circle-o"></i> Liste des patients</a></li>
                </ul>
            </li>

            <li class="treeview <?php if( isset($link) && $link == 'rendezVous' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Agenda des rendez vous</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'rendezVous' ) echo 'active' ?>">
                        <a href="rendezVous.php"><i class="fa fa-circle-o"></i> Agenda des rendez vous</a>
                    </li>
                </ul>
            </li>

            <li class="treeview <?php if( isset($link) && $link == 'paiements' ) echo 'menu-open active' ?>">
                <a href="#">
                        <i class="fa fa-money"></i> <span>Paiements</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'paiements' ) echo 'active' ?>"><a href="paiementsGestionnaire.php"><i class="fa fa-circle-o"></i>Gestion du paiements</a></li>
                </ul>
            </li>

            <li class="header">Reglages</li>
            <li class="treeview <?php if( isset($link) && $link == 'horaires' ) echo 'menu-open active' ?>">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Horaires de travail</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=" <?php if( isset($subLink) && $subLink == 'horaires' ) echo 'active' ?>"><a href="addHoraire.php"><i class="fa fa-circle-o"></i> Liste horaires de travail</a></li>
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
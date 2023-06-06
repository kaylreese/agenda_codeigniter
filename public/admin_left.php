<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="<?php echo base_url(); ?>/public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><b>ADMIN SKYNET</b></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo base_url(); ?>/public/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['razonsocial'];?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item menu-open">
          <a href="<?php echo base_url(); ?>home" class="nav-link active">
            <i class="nav-icon fas fa-desktop"></i>
            <p>Inicio</p>
          </a>
        </li>
        <?php
          foreach ($modulos as $value) {
            if (count($value["lista"])>0) { ?>
              <li id="<?php echo $value["descripcion"]?>" class="nav-item">
                <a href="#" class="nav-link">
                  <i class="<?php echo $value["icono"]?>"></i>
                  <p>
                    <?php echo $value["descripcion"]?>
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul id="<?php echo $value["descripcion"]."_SUB";?>" class="nav nav-treeview">
                  <?php 
                    foreach ($value["lista"] as $val) { 
                        if($_SESSION["perfil"] === "Cliente"){
                          $url = $val["urlcliente"];
                        }else{
                          $url = $val["url"];
                        }
                      ?>
                      <li class="nav-item" id="">
                        <a href="<?php echo $url; ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p><?php echo $val["descripcion"]?></p>
                        </a>
                      </li>
                      <?php 
                    } 
                  ?>
                </ul>
              </li>
            <?php }
          } 
        ?>
      </ul>
    </nav>
  </div>
</aside>
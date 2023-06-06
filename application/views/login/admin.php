<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin SkyNet | Inicia Sesión</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/fontawesome-free/css/all.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?php echo base_url(); ?>" class="h1"><b>Admin SkyNet</b></a>
    </div>
    <div class="card-body">
      <div align="center">
          <!-- <h2 style="color: #000;"><b>UNIVERSIDAD NACIONAL DE SAN MARTIN</b></h2> -->
          <img src="<?php echo base_url(); ?>public/images/portada.png" height="100">
          <h4 style="color: #000; font-weight: bold;">BIENVENIDO</h4> <hr>
        </div>
      <form action="<?php echo base_url(); ?>login/control" method="POST">
        <div class="input-group mb-3">
          <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" id="clave" name="clave" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="social-auth-links text-center mt-2 mb-3">
          <button type="submit" class="btn btn-block btn-primary">INICIAR SESIÓN</button>
        </div>
      </form>

      <p class="mb-0" style="text-align: center;">
        Desarrollado por Grupo LyL
      </p>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>public/dist/js/adminlte.min.js"></script>
</body>
</html>

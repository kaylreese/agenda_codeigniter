<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Clientes | Grupo LyL</title>

	    <?php include("public/admin_css.php"); ?>
	</head>

	<body class="hold-transition sidebar-mini layout-fixed">
	    <div class="wrapper">
		    <!-- Navbar -->
		    <?php include("public/admin_header.php"); ?>

		    <aside class="main-sidebar sidebar-dark-primary elevation-4">
			  	<!-- Brand Logo -->
			  	<a href="index3.html" class="brand-link">
				    <img src="<?php echo base_url(); ?>/public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				    <span class="brand-text font-weight-light"><b>ADMIN GRUPO LyL</b></span>
			  	</a>

			  	<!-- Sidebar -->
			  	<div class="sidebar">

				    <!-- Sidebar Menu -->
				    <nav class="mt-2">
				      	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					        <li class="nav-item menu-open">
					          	<a href="<?php echo base_url(); ?>home" class="nav-link active">
					            	<i class="nav-icon fas fa-desktop"></i>
					            	<p>Inicio</p>
					          	</a>
					        </li>
				      	</ul>
				    </nav>
			  	</div>
			</aside>    

		    <div class="content-wrapper">
		    	<div class="content-header">
			      	<div class="container-fluid">
				        <div class="row mb-2">
				          	<div class="col-sm-6">
				            	<h1 class="m-0 titulo" id="titulo">404 Error Page</h1>
				          	</div>
				          	<div class="col-sm-6">
					            <ol class="breadcrumb float-sm-right"></ol>
				          	</div>
				        </div>
			      	</div>
			    </div>

		        <section class="content">
			      	<div class="error-page">
				        <h2 class="headline text-warning"><b>404</b></h2>

				        <div class="error-content">
				          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Pápina no encontrada.</h3>

				          	<p>Estas accediendo a una página donde no tienes permiso - GRUPO LyL</p>

				          	<form class="search-form">
					            <div class="input-group">
					              	<input type="text" name="search" class="form-control" placeholder="Search">

					              	<div class="input-group-append">
						                <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
						                </button>
					              	</div>
					            </div>
				          	</form>
				        </div>
			      	</div>
			    </section>
		    </div>

		    <?php include("public/admin_footer.php"); ?>

		    <aside class="control-sidebar control-sidebar-dark"> </aside>
	    </div>

	    <?php include("public/admin_js.php"); ?>
	</body>
</html>
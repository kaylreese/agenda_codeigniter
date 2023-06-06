<?php
    // $_SESSION['razonsocial'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Home | SkyNet</title>

	    <?php include("public/admin_css.php"); ?>
	</head>

	<body class="hold-transition sidebar-mini layout-fixed">
	    <div class="wrapper">
		    <!-- Navbar -->
		    <?php include("public/admin_header.php"); ?>

		    <?php include("public/admin_left.php"); ?>

		    <div class="content-wrapper">
		        <section class="content">
			      	<div class="container-fluid">
				        <h5 class="mb-2">TAREAS - EVENTOS</h5>
				        <div class="row">
				          	<div class="col-md-3 col-sm-6 col-12">
					            <div class="info-box">
					              	<span class="info-box-icon bg-info"><i class="far fa-calendar-alt"></i></span>
					              	<div class="info-box-content">
					                	<span class="info-box-text">Total</span>
					                	<span class="info-box-number"><?php echo $totaleventos[0]["total"];?></span>
					              	</div>
					            </div>
				          	</div>

				          	<div class="col-md-3 col-sm-6 col-12">
					            <div class="info-box">
					              	<span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

					              	<div class="info-box-content">
					                	<span class="info-box-text">Realizados</span>
					                	<span class="info-box-number"><?php echo $eventosrealizados[0]["realizados"];?></span>
					              	</div>
					            </div>
				          	</div>

				          	<div class="col-md-3 col-sm-6 col-12">
					            <div class="info-box">
					              	<span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

					              	<div class="info-box-content">
					                	<span class="info-box-text">Pendientes</span>
					                	<span class="info-box-number"><?php echo $eventospendientes[0]["pendientes"];?></span>
					              	</div>
					            </div>
				          	</div>

				          	<div class="col-md-3 col-sm-6 col-12">
					            <div class="info-box">
					              	<span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>

					              	<div class="info-box-content">
					                	<span class="info-box-text">Feriados</span>
					                	<span class="info-box-number"><?php echo $feriados[0]["feriados"];?></span>
					              	</div>
					            </div>
				          	</div>
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
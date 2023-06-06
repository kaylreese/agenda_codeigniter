<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Perfiles | Admin SkyNet</title>

	    <?php include("public/admin_css.php"); ?>
	</head>

	<body class="hold-transition sidebar-mini layout-fixed">
	    <div class="wrapper">
		    <!-- Navbar -->
		    <?php include("public/admin_header.php"); ?>

		    <?php include("public/admin_left.php"); ?>     

		    <div class="content-wrapper">
		    	<div class="content-header">
			      	<div class="container-fluid">
				        <div class="row mb-2">
				          	<div class="col-sm-8">
				            	<h1 class="m-0 titulo" id="titulo"></h1>
				          	</div>
				          	<div class="col-sm-4">
					            <ol class="float-sm-right" id="acciones" style="margin-bottom: 0;">
					            	<?php include("acciones.php"); ?>  
					            </ol>
				          	</div>
				        </div>
			      	</div>
			    </div>

		        <section class="content" style="margin-top: 10px;">
		          	<div class="container-fluid" id="contenido">

		          	</div>
		        </section>
		    </div>

		    <?php include("public/admin_footer.php"); ?>

		    <aside class="control-sidebar control-sidebar-dark"> </aside>
	    </div>

	    <?php include("public/admin_js.php"); ?>

	    <script type="text/javascript">
			var campos = ["nombre"];
			var datos = ["codperfil","nombre","descripcion"];
		</script>

		<script src="<?php echo base_url();?>public/js/perfiles.js" type="text/javascript"></script>
	</body>
</html>
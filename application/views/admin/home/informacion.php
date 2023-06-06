<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Información | Grupo LyL</title>

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
				          	<div class="col-sm-6">
				            	<h1 class="m-0 titulo" id="titulo">INFORMACIÓN</h1>
				          	</div>
				        </div>
			      	</div>
			    </div>

		        <section class="content" style="margin-top: 10px;">
		          	<div class="container-fluid" id="contenido">
		          		<form id="FormInformacion" onsubmit="return guardar()">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Institución: </label>
												<div class="controls">
													<textarea name="institucion" id="institucion" class="form-control" required placeholder="Nombre de la Institución" rows="3"><?php echo $info[0]['institucion'];?></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Oficina: </label>
												<div class="controls">
													<textarea name="oficina" id="oficina" class="form-control" required placeholder="Nombre de la Oficina" rows="3"><?php echo $info[0]['oficina'];?></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Dirección: </label>
												<div class="controls">
													<textarea name="direccion" id="direccion" class="form-control" required placeholder="Dirección" rows="1"><?php echo $info[0]['direccion'];?></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Lugar: </label>
												<div class="controls">
													<textarea name="lugar" id="lugar" class="form-control" required placeholder="Lugar" rows="1"><?php echo $info[0]['lugar'];?></textarea>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="email">Email:</label>
												<div class="controls">
													<input type="text" name="email" id="email" class="form-control" value="<?php echo $info[0]['email'];?>" required placeholder="Ingrese Email">
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="telefono">Teléfono:</label>
												<div class="controls">
													<input type="text" name="telefono" id="telefono" value="<?php echo $info[0]['telefono'];?>" class="form-control" required placeholder="Ingrese Teléfono">
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="celular">Celular:</label>
												<div class="controls">
													<input type="text" name="celular" id="celular" value="<?php echo $info[0]['celular'];?>" class="form-control" required placeholder="Ingrese Celular">
												</div>
											</div>
										</div>
									</div>
							        <div class="row">
										<div class="col-md-12">
											<hr>
											<center>
												<button type="button" class="btn btn-success btn-guardar" onclick="guardar();"><span class="fa fa-save"></span> GUARDAR </button> 
											</center>
										</div>
									</div>
								</div>
							</div>
						</form>
		          	</div>
		        </section>
		    </div>

		    <?php include("public/admin_footer.php"); ?>

		    <aside class="control-sidebar control-sidebar-dark"> </aside>
	    </div>

	    <?php include("public/admin_js.php"); ?>
	    <script src="<?php echo base_url();?>public/js/informacion.js" type="text/javascript"></script>
	    <script>
			$(function () {
			  bsCustomFileInput.init();
			});
		</script>
	</body>
</html>
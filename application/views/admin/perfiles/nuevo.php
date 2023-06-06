<div class="card">
    <div class="card-body">
		<form id="FormPerfil" method="POST">
			<input type="hidden" name="codperfil" id="codperfil">
			<div class="col-md-12">
			    <div class="row">
			    	<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="nombre">Nombre:</label>
							<div class="controls">
								<input type="text" name="nombre" id="nombre" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Nombre de Perfil">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
			    	<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="descripcion">Descripción:</label>
							<div class="controls">
								<input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese un descripcion del Perfil">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<hr>
						<center>
							<button type="button" class="btn btn-success btn-guardar" onclick="guardar();"><span class="fa fa-save"></span> GUARDAR </button> 
			            	<button type="button" class="btn btn-danger" onclick="cancelar();"><span class="fa fa-reply"></span> CANCELAR </button> 
						</center>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
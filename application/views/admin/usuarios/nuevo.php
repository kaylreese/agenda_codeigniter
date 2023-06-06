<div class="card">
    <div class="card-body">
		<form id="FormUsuario" method="POST">
			<input type="hidden" name="codusuario" id="codusuario">
			<div class="col-md-12">
			    <div class="row">
			    	<div class="col-md-3">
			    		<div class="form-group">
		                  	<label>DNI:</label>
		                    <div class="input-group">
		                        <input type="text" class="form-control" name="dni" id="dni" data-inputmask="'mask': ['99999999', '99999999']" data-mask/>
		                        <div class="input-group-append" onclick="alert('Buscar por DNI')" title="Buscar en Reniec" >
		                            <div class="input-group-text"><i class="fa fa-search"></i></div>
		                        </div>
		                    </div>
		                </div>
			    	</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="nombres">Nombres:</label>
							<div class="controls">
								<input type="text" name="nombres" id="nombres" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Nombres">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="apellidos">Apellidos:</label>
							<div class="controls">
								<input type="text" name="apellidos" id="apellidos" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Apellidos">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
		                  	<label>Perfil: </label>
		                  	<select class="form-control select2" name="codperfil" id="codperfil" style="width: 100%;">
			                    <option value="">Seleccione. . .</option>
			                    <?php 
	                                foreach ($perfiles as $val) { ?>
	                                    <option value="<?php echo $val["codperfil"];?>"><?php echo $val["nombre"];?></option>
	                                <?php }
	                            ?>
		                  	</select>
		                </div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
                  			<label>	Telefono:</label>
                  			<div class="input-group">
			                    <div class="input-group-prepend">
			                      	<span class="input-group-text"><i class="fas fa-phone"></i></span>
			                    </div>
			                    <input type="text" class="form-control" data-inputmask="'mask': ['999 999 999', '999 999 999']" name="telefono" id="telefono" data-mask>
		                  	</div>
		                </div>
					</div>
					<div class="col-md-2">
			    		<div class="form-group">
							<label for="usuario">Usuario:</label>
							<div class="controls">
								<input type="text" name="usuario" id="usuario" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Usuario">
							</div>
						</div>
			    	</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="clave">Clave:</label>
							<div class="controls">
								<input type="text" name="clave" id="clave" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Contraseña">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email:</label>
							<div class="controls">
								<input type="text" name="email" id="email" class="form-control" required placeholder="Ingrese Email.">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sexo">Sexo:</label>
							<select class="form-control select2" name="sexo" id="sexo" style="width: 100%;">
			                    <option value="">Seleccione. . .</option>
			                    <option value="M">Masculino</option>
			                    <option value="F">Femenino</option>
		                  	</select>
						</div>
					</div>
				</div>

				<div class="row">
			    	<div class="col-md-5">
						<div class="form-group">
							<label for="email">Dirección:</label>
							<div class="controls">
								<input type="text" name="direccion" id="direccion" class="form-control" required placeholder="Ingrese Dirección.">
							</div>
						</div>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<label for="observaciones">Observaciones:</label>
		                  	<textarea class="form-control" name="observaciones" id="observaciones" rows="1" placeholder="Ingrese Observaciones de Usuario"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<hr style="margin-top: 0;">
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

<script>
	$(function () {
	  	bsCustomFileInput.init();
	  	$('[data-mask]').inputmask();

	  	// $('.select2').select2()

	    // //Initialize Select2 Elements
	    // $('.select2bs4').select2({
	    //   theme: 'bootstrap4'
	    // })
	});
</script>
<div class="card">
    <div class="card-body">
		<form id="FormCliente" method="POST">
			<input type="hidden" name="codcliente" id="codcliente">
			<div class="col-md-12">
			    <div class="row">
			    	<div class="col-md-2"></div>
			    	<div class="col-md-4">
			    		<div class="form-group">
		                  	<label>RUC:</label>
		                    <div class="input-group">
		                        <input type="text" class="form-control" placeholder="Ingrese Número de DNI." name="ruc" id="ruc" maxlength="11" />
		                        <div class="input-group-append" onclick="alert('Buscar por DNI')" title="Buscar en Reniec" >
		                            <div class="input-group-text"><i class="fa fa-search"></i></div>
		                        </div>
		                    </div>
		                </div>
			    	</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="nombres">Razón Social:</label>
							<div class="controls">
								<input type="text" name="razonsocial" id="razonsocial" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Razón Social">
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="email">Email:</label>
							<div class="controls">
								<input type="text" name="email" id="email" class="form-control" required placeholder="Ingrese Email.">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
		                  	<label>Perfil: </label>
		                  	<select class="form-control select2" name="codperfil" id="codperfil" style="width: 100%;" disabled>
			                    <option value="">Seleccione. . .</option>
			                    <?php 
	                                foreach ($perfiles as $val) { 
	                                	if($val["nombre"] === "Cliente"){
	                                		$selected = "selected";
	                                	} else {
	                                		$selected = "";
	                                	}?>

	                                    <option value="<?php echo $val["codperfil"];?>" <?php echo $selected;?>><?php echo $val["nombre"];?></option>
	                                	}
	                                <?php }
	                            ?>
		                  	</select>
		                </div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-4">
						<div class="form-group">
                  			<label>	Teléfono:</label>
                  			<div class="input-group">
			                    <div class="input-group-prepend">
			                      	<span class="input-group-text"><i class="fas fa-phone"></i></span>
			                    </div>
			                    <input type="text" class="form-control" data-inputmask='"mask": "(999) 999999"' name="telefono" id="telefono" data-mask>
		                  	</div>
		                </div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
                  			<label>	Celular:</label>
                  			<div class="input-group">
			                    <div class="input-group-prepend">
			                      	<span class="input-group-text"><i class="fas fa-phone"></i></span>
			                    </div>
			                    <input type="text" class="form-control" data-inputmask="'mask': ['999 999 999', '999 999 999']" name="celular" id="celular" data-mask>
		                  	</div>
		                </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-4">
			    		<div class="form-group">
							<label for="usuario">Usuario:</label>
							<div class="controls">
								<input type="text" name="usuario" id="usuario" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Usuario">
							</div>
						</div>
			    	</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="clave">Clave:</label>
							<div class="controls">
								<input type="text" name="clave" id="clave" class="form-control" required data-validation-required-message="Este campo es requerido." placeholder="Ingrese Contraseña">
							</div>
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
	});
</script>
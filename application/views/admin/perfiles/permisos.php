<div class="card">
    <div class="card-body">
		<form id="FormPermisos" onsubmit="return guardar_permisos()">
			<input type="hidden" name="codperfil" id="codperfil">
			<?php 
				foreach ($permisos as $value) { ?>
					<h5><b><i class="<?php echo $value["icono"]; ?>"></i> MÓDULO <?php echo $value["descripcion"]; ?></b></h5>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="table-permisos" style="width: 250px;"> <i class="fa fa-book"></i> SUB MODULO</th>
								<th class="table-permisos"> <center> <i class="fa fa-eye"></i> VER </center> </th>
								<th class="table-permisos"> <center> <i class="fa fa-plus-square"></i> NUEVO </center> </th>
								<th class="table-permisos"> <center> <i class="fa fa-edit"></i> MODIFICAR </center> </th>
								<th class="table-permisos"> <center> <i class="fa fa-trash"></i> ELIMINAR </center> </th>
								<th class="table-permisos"> <center> <i class="fa fa-clipboard"></i> CONSULTAR </center> </th>
								<th class="table-permisos"> <center> <i class="fas fa-circle-down"></i> ANULAR </center> </th>
								<th class="table-permisos"> <center> <i class="fa fa-print"></i> IMPRIMIR </center> </th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if (count($value["lista"])>0) { 
									foreach ($value["lista"] as $val) { 
										$chek_ver = ""; $chek_nuevo = ""; $chek_modificar = ""; $chek_eliminar = ""; $chek_consultar = ""; $chek_anular = ""; $chek_impri = "";

					                   	foreach ($activos as $v) {
			                             	if ($v["codmodulo"]==$val["codmodulo"]) {
			                             		if ($v["ver"]==1) {
			                             			$chek_ver = "checked";
			                             		}
			                             		if ($v["nuevo"]==1) {
			                             			$chek_nuevo = "checked";
			                             		}
			                             		if ($v["modificar"]==1) {
			                             			$chek_modificar = "checked";
			                             		}
			                             		if ($v["eliminar"]==1) {
			                             			$chek_eliminar = "checked";
			                             		}
			                             		if ($v["consultar"]==1) {
			                             			$chek_consultar = "checked";
			                             		}
			                             		if ($v["anular"]==1) {
			                             			$chek_anular = "checked";
			                             		}
			                             		if ($v["imprimir"]==1) {
			                             			$chek_impri = "checked";
			                             		}
			                                    	break;
					                       	}
					                  	} ?>

										<input type="hidden" name="modulos[]" value="<?php echo $val["codmodulo"]; ?>">
										<tr>
											<td><?php echo $val["descripcion"]; ?></td>
											<td align="center">
			                    				<div class="custom-control custom-switch">
							                      	<input type="checkbox" class="custom-control-input" id="check_1<?php echo $val["codmodulo"]; ?>" <?php echo $chek_ver; ?> name="ver[]" value="<?php echo $val["codmodulo"]; ?>">
							                      	<label class="custom-control-label" for="check_1<?php echo $val["codmodulo"]; ?>"></label>
							                    </div>
											</td>
											<td align="center">
			                                	<div class="custom-control custom-switch">
							                      	<input type="checkbox" class="custom-control-input" id="check_2<?php echo $val["codmodulo"]; ?>" <?php echo $chek_nuevo; ?> name="nuevo[]" value="<?php echo $val["codmodulo"]; ?>">
							                      	<label class="custom-control-label" for="check_2<?php echo $val["codmodulo"]; ?>"></label>
							                    </div>
											</td>
											<td align="center">
			                                	<div class="custom-control custom-switch">
							                      	<input type="checkbox" class="custom-control-input" id="check_3<?php echo $val["codmodulo"]; ?>" <?php echo $chek_modificar; ?> name="modificar[]" value="<?php echo $val["codmodulo"]; ?>">
							                      	<label class="custom-control-label" for="check_3<?php echo $val["codmodulo"]; ?>"></label>
							                    </div>
											</td>
											<td align="center">
			                                	<div class="custom-control custom-switch">
							                      	<input type="checkbox" class="custom-control-input" id="check_4<?php echo $val["codmodulo"]; ?>" <?php echo $chek_eliminar; ?> name="eliminar[]" value="<?php echo $val["codmodulo"]; ?>">
							                      	<label class="custom-control-label" for="check_4<?php echo $val["codmodulo"]; ?>"></label>
							                    </div>
											</td>
											<td align="center">
		                                	<div class="custom-control custom-switch">
						                      	<input type="checkbox" class="custom-control-input" id="check_5<?php echo $val["codmodulo"]; ?>" <?php echo $chek_consultar; ?> name="consultar[]" value="<?php echo $val["codmodulo"]; ?>">
						                      	<label class="custom-control-label" for="check_5<?php echo $val["codmodulo"]; ?>"></label>
						                    </div>
										</td>
										<td align="center">
		                                	<div class="custom-control custom-switch">
						                      	<input type="checkbox" class="custom-control-input" id="check_6<?php echo $val["codmodulo"]; ?>" <?php echo $chek_anular; ?> name="anular[]" value="<?php echo $val["codmodulo"]; ?>">
						                      	<label class="custom-control-label" for="check_6<?php echo $val["codmodulo"]; ?>"></label>
						                    </div>
										</td>
											<td align="center">
			                                	<div class="custom-control custom-switch">
							                      	<input type="checkbox" class="custom-control-input" id="check_7<?php echo $val["codmodulo"]; ?>" <?php echo $chek_impri; ?> name="imprimir[]" value="<?php echo $val["codmodulo"]; ?>">
							                      	<label class="custom-control-label" for="check_7<?php echo $val["codmodulo"]; ?>"></label>
							                    </div>
											</td>
										</tr>
									<?php }
								} 
							?>
						</tbody>
					</table>
					<?php
				}
			?>

			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<center>
						<button type="button" class="btn btn-success btn-guardar" onclick="guardarPermisos();"><span class="fa fa-save"></span> GUARDAR </button> 
		            	<button type="button" class="btn btn-danger" onclick="cancelar();"><span class="fa fa-reply"></span> CANCELAR </button> 
					</center>
				</div>
			</div>
		</form>
	</div>
</div>
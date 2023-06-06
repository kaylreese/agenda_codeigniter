<div class="card">
    <div class="card-body">
		<table id="datatable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><center>&nbsp;</center></th>
					<th><center>N°.</center></th>
					<th><center>Perfil</center></th>
					<th><center>Descripción</center></th>
					<th><center>Status</center></th>
				</tr> 
			</thead>
			<tbody>
				<?php $cont = 0;
		            foreach ($perfiles as $value) { $cont = $cont + 1; ?>
		              	<tr>
			                <td><center>
			                	<div class="radio radio-inline" onclick="seleccionPerfil(<?php echo $value["codperfil"]; ?>,'<?php echo $value["nombre"]; ?>')">
                                	<input type="radio" class="form-control-select" name="opcion" id="r_<?php echo $value["codperfil"]; ?>"> 
                            	</div>
			                </center></td>
			                <td><center><?php echo $cont; ?></center></td>
			                <td><center><?php echo $value["nombre"];?></center></td>
		                    <td><center><?php echo $value["descripcion"];?></center></td>
		                    <?php if ($value["estado"] == 1) { ?>
		                    	<td><center><span class="badge bg-success">Activo</span></center></td>
		                    <?php } else { ?>
		                    	<td><center><span class="badge bg-danger">Eliminado</span></center></td>
		                    <?php } ?>
		              	</tr>
		            <?php }
		          ?>
			</tbody>
		</table>
	</div>
</div>
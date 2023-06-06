<div class="card">
    <div class="card-body">
		<table id="datatable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><center>&nbsp;</center></th>
					<th><center>N°.</center></th>
					<th><center>Razón Social</center></th>
					<th><center>Usuario</center></th>
					<th><center>Teléfono</center></th>
					<th><center>Email</center></th>
					<th><center>Dirección</center></th>
					<th><center>Perfil</center></th>
					<th><center>Status</center></th>	
				</tr> 
			</thead>
			<tbody>
				<?php $cont = 0;
		            foreach ($usuarios as $value) { $cont = $cont + 1; ?>
		              	<tr>
			                <td><center>
			                	<div class="radio radio-inline" onclick="seleccion(<?php echo $value["codusuario"]; ?>,)">
                                	<input type="radio" class="form-control-select" name="opcion" id="r_<?php echo $value["codusuario"]; ?>"> 
                            	</div>
			                </center></td>
			                <td><center><?php echo $cont; ?></center></td>
			                <td><center><?php echo $value["razonsocial"];?></center></td>
		                    <td><center><?php echo $value["usuario"];?></center></td>
		                    <td><center><?php echo $value["telefono"];?></center></td>
		                    <td><center><?php echo $value["email"];?></center></td>
		                    <td><center><?php echo $value["direccion"];?></center></td>
		                    <td><center><?php echo $value["perfil"];?></center></td>
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


<div class="card">
    <div class="card-body">
		<table id="datatable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><center>&nbsp;</center></th>
					<th><center>N°.</center></th>
					<th><center>RUC</center></th>
					<th><center>Razón Social</center></th>
					<th><center>Email</center></th>
					<th><center>Teléfono</center></th>
					<?php if ($_SESSION["perfil"] > 0 && $_SESSION["perfil"] <= 3) { ?>
                    	<th><center>Status</center></th>
                    <?php } ?>	
				</tr> 
			</thead>
			<tbody>
				<?php $cont = 0;
		            foreach ($clientes as $value) { $cont = $cont + 1; ?>
		              	<tr>
		              		<td><center>
			                	<div class="radio radio-inline" onclick="seleccion(<?php echo $value["codcliente"]; ?>)">
                                	<input type="radio" class="form-control-select" name="opcion" id="r_<?php echo $value["codcliente"]; ?>"> 
                            	</div>
			                </center></td>
			                <td><center><?php echo $cont; ?></center></td>
			                <td><center><?php echo $value["ruc"];?></center></td>
			                <td><center><?php echo $value["razonsocial"];?></center></td>
		                    <td><center><?php echo $value["email"];?></center></td>
		                    <td><center><?php echo $value["celular"];?></center></td>
		                    <?php if ($_SESSION["perfil"] > 0 && $_SESSION["perfil"] <= 3) { ?>
			                    <?php if ($value["estado"] == 1) { ?>
			                    	<td><center><span class="badge bg-success">Activo</span></center></td>
			                    <?php } else { ?>
			                    	<td><center><span class="badge bg-danger">Eliminado</span></center></td>
			                    <?php }
			                	} 
			                ?>
		              	</tr>
		            <?php }
		          ?>
			</tbody>
		</table>
	</div>
</div>
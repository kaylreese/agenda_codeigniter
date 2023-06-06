<?php 
	if ($acciones[0]["nuevo"]==1) { ?>
	    <button type="button" class="btn btn-success btn-xs" onclick="nuevo();" title="Nuevo"><i class="fa fa-plus" aria-hidden="true" id="btn-nuevo" style="color: white;"></i> Nuevo</button>
	<?php }

	if ($acciones[0]["modificar"]==1) { ?>
		<button type="button" class="btn btn-primary btn-xs" onclick="modificar();" title="Modificar"><i class="fa fa-pen" aria-hidden="true" id="btn-modificar" style="color: white;"></i> Modificar</button>
	<?php }

	if ($acciones[0]["eliminar"]==1) { ?>
		<button type="button" class="btn btn-danger btn-xs" onclick="eliminar();" title="Eliminar"><i class="fa fa-trash" aria-hidden="true" id="btn-eliminar" style="color: white;"></i> Eliminar</button>	
	<?php }

	if ($acciones[0]["consultar"]==1) { ?>
		<button type="button" class="btn btn-info btn-xs" onclick="verpermisos();" title="Permisos"><i class="fas fa-key" aria-hidden="true" id="btn-consultar" style="color: white;"></i> Permisos</button>
	<?php }
?>

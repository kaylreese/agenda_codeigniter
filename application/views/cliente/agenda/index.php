<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Agenda Contable | Admin SkyNet</title>

	    <?php include("public/admin_css.php"); ?>
	</head>

	<body class="hold-transition sidebar-mini layout-fixed">
	    <div class="wrapper">

		    <?php include("public/admin_header.php"); ?>

		    <?php include("public/admin_left.php"); ?>   

		    <div class="content-wrapper">
		        <section class="content" style="margin-top: 10px;">
		          	<div class="container-fluid" id="contenido">
					  	<section class="content">
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="card-body p-0">
											<div id="calendar"></div>
										</div>
									</div>
								</div>
							</div>
						</section>
		          	</div>
		        </section>
		    </div>

		    <?php include("public/admin_footer.php"); ?>

		    <aside class="control-sidebar control-sidebar-dark"> </aside>
	    </div>

		<div class="modal fade" id="myModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="titulo">Registrar Evento</h4>
						
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="FormAgendaCliente" autocomplete="off">
		                    <div class="modal-body">
		                        <div class="row">
		                        	<input type="hidden" id="codagenda" name="codagenda">
		                        	<input type="hidden" id="codcliente" name="codcliente" value="<?php echo $_SESSION['idusuario']; ?>">
		                        	<input type="hidden" id="idusuario" name="idusuario">
		                        	<div class="col-md-12" id="divusuario">
										<div class="form-group">
						                  	<label>Usuario: </label>
						                  	<select class="form-control select2" name="codusuario" id="codusuario" style="width: 100%;" disabled>
							                    <option value="" selected>Seleccione. . .</option>
							                    <?php 
					                                foreach ($usuarios as $val) { ?>
					                                    <option value="<?php echo $val["codusuario"];?>"><?php echo $val["razonsocial"];?></option>
					                                <?php }
					                            ?>
						                  	</select>
						                </div>
					                </div>
					                <div class="col-md-6">
										<div class="form-group">
						                  	<label>Tipo de Evento: </label>
						                  	<select class="form-control select2" name="codtipoagenda" id="codtipoagenda" style="width: 100%;" disabled>
							                    <option value="" selected>Seleccione. . .</option>
							                    <?php 
					                                foreach ($tipoeventos as $val) { ?>
					                                    <option value="<?php echo $val["codtipoagenda"];?>"><?php echo $val["descripcion"];?></option>
					                                <?php }
					                            ?>
						                  	</select>
						                </div>
					                </div>
					                <div class="col-md-6" id="divrealizado">
					                	</br>
					                    <div class="form-group clearfix">
					                    	<label for="realizado">Marcar como Realizado: </label>
						                    <input type="checkbox" id="realizado" name="realizado" onchange="validarRealizado()">
						                    <input type="hidden" name="estado" id="estado">
					                    </div>
					                </div>
		                            <div class="col-md-12">
		                                <div class="form-floating mb-3">
		                                    <label for="title">Evento: </label>
		                                    <input id="title" type="text" class="form-control" name="title" required disabled>
		                                </div>
		                            </div>
		                            <div class="col-md-6">
		                                <div class="form-floating mb-3">
		                                	<label for="" class="form-label">Fecha Inicio: </label>
		                                    <input class="form-control" id="start" type="datetime-local" name="start" disabled>
		                                </div>
		                            </div>
		                            <div class="col-md-6">
		                                <div class="form-floating mb-3">
		                                	<label for="" class="form-label">Fecha Fin: </label>
		                                    <input class="form-control" id="end" type="datetime-local" name="end" disabled>
		                                </div>
		                            </div>
		                            <div class="col-md-12">
		                                <div class="form-floating mb-3">
		                                	<label for="color" class="form-label">Color</label>
		                                    <input class="form-control" id="color" type="color" name="color" disabled>
		                                </div>
		                            </div>
		                            <div class="col-md-12" id="campodocumento">
					                    <div class="form-group">
						                    <label for="urldocumento">Adjunte Documento Referencia:</label>
						                    <div class="input-group">
							                    <div class="custom-file">
							                        <input type="file" class="custom-file-input" name="urldocumento" id="urldocumento">
							                        <label class="custom-file-label" for="logourl">Seleccionar Archivo</label>
							                    </div>
						                    </div>
						                </div>
				                	</div>
				                	<div class="col-md-12" id="descargardocumento">
				                		<div class="form-group">
						                    <label for="urldocumento">Documento Referencia: </label>
						                    <a href="<?php echo base_url();?>public/formato_usuarios.xls" target="_blank" type="button" id="documento" class="btn btn-info" ><i class="fa fa-download" aria-hidden="true"></i> DESCARGAR</a>
						                </div>
				                	</div>
		                        </div>
		                    </div>
		                </form>
					</div>
					<div class="modal-footer justify-content-between">
						<?php 
							if ($acciones[0]["modificar"]==1) { ?>
							    <button type="button" class="btn btn-success" onclick="guardar_documento();" id="btnguardar"><span class="fa fa-save"></span> Registrar</button>
							<?php }
						?>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

	    <?php include("public/admin_js.php"); ?>

	    <script type="text/javascript">
			var campos = ["codtipoagenda","title","start","end"];
		</script>

	    <script src="<?php echo base_url();?>public/js/agenda.js" type="text/javascript"></script>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('calendar');
				let formulario = document.getElementById('FormAgenda');
				let myModal = new bootstrap.Modal(document.getElementById('myModal'));
				let idcliente = $("#codcliente").val();

				$.post(url+'cliente/agenda/eventos/'+idcliente, function(data) {
					var calendar = new FullCalendar.Calendar(calendarEl, {
						locale: 'es',
						headerToolbar: {
							left: 'prev,next today',
							center: 'title',
							right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
						},
						initialDate: new Date(),
						navLinks: true, // can click day/week names to navigate views
						selectable: true,
						// businessHours: true,
						// selectMirror: true,
						editable: true,
						dayMaxEvents: true, // allow "more" link when too many events
						droppable: true,
						events: $.parseJSON(data),
						eventClick: function(info) {
							$('#titulo').text('Actualizar Evento');
							$("#campodocumento").hide();
							$("#descargardocumento").hide();
							$("#divrealizado").show();
							$("#btnguardar").show();
							$("#divusuario").show();
							
							$.get(url+"agenda/modificar/"+info.event.id,function(data){
					            let evento = eval(data);
					            if(evento[0]["estado"]==2) {
					            	$('#titulo').text('Detalle Evento');
					            	$("#divrealizado").hide();
					            	$("#descargardocumento").show();
					         		$('#documento').attr('href', url+'public/documentos/'+evento[0]["urldocumento"]);
					         		$("#btnguardar").hide();
					            }
					            if(evento[0]["codtipoagenda"]==2) {
					            	$("#divrealizado").hide();
					            	$("#btnguardar").hide();
					            	$("#divusuario").hide();
					            }
					            $("#codagenda").val(evento[0]["codagenda"]);
					            $("#idusuario").val(evento[0]["codusuario"]);
					            $("#codusuario option").eq(evento[0]["codusuario"]).prop("selected",true);
					            $("#codtipoagenda option").eq(evento[0]["codtipoagenda"]).prop("selected",true);
								$("#title").val(evento[0]["titulo"]);
								$("#start").val(evento[0]["fechainicio"]);
								
								if (evento[0]["fechafin"] == "1969-12-31 19:00:00") {
									$("#end").val("");
					            } else {
					            	$("#end").val(evento[0]["fechafin"]);
					            }
								
								$("#color").val(evento[0]["color"]);
								myModal.show();
					        });	
						},
					    eventDrop: function(info) {
					    	if(info.event.extendedProps.estado == 2) {
					    		Swal.fire({
								  	title: 'Evento Finalizado!!',
								  	text: "Este evento ya no puede ser modificado.",
								  	icon: 'warning',
								  	confirmButtonColor: '#3085d6',
								  	cancelButtonColor: '#d33',
								  	confirmButtonText: 'OK'
								}).then((result) => {
								  	if (result.isConfirmed) {
									    location.reload();
								  	}
								})
					    	} else {
								Swal.fire({
							        title: 'Atención',
							        text: "No tiene permiso para modificar la fecha del evento!.",
							        icon: 'info',
							        // showCancelButton: true,
							        confirmButtonColor: '#3085d6',
							        cancelButtonColor: '#d33',
							        confirmButtonText: 'OK'
							    }).then((result) => {
							        if (result.isConfirmed) {
							        	location.reload();
								    }
								})
					    	}
						}
					});

					calendar.render();
				});
			});
		</script>

		<script>
			$(function () {
			  bsCustomFileInput.init();
			});
		</script>
	</body>
</html>
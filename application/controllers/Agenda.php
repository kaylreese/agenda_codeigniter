<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); include("Agendabase.php");


class Agenda extends Agendabase {
	function __construct(){
		parent::__construct();
	}

	public function index() {
		if (isset($_SESSION["usuario"])) {
			$data["modulos"] = Agendabase::modulos();
			$data["clientes"] = $this->db->query('SELECT codcliente, razonsocial FROM clientes WHERE estado = 1')->result_array();
			$data["tipoeventos"] = $this->db->query('select * FROM tipoagenda WHERE estado = 1')->result_array();
			$data["usuarios"] = $this->db->query('SELECT codusuario, concat(nombres," ",apellidos) as razonsocial FROM usuarios WHERE estado = 1')->result_array();

			$this->load->view('admin/agenda/index', $data);
		}else{
			header("Location: ".base_url());
		}
	}

	function eventos(){
		if ($this->input->is_ajax_request()){
			$data = $this->db->query('select codagenda as id, titulo as title, fechainicio as start, fechafin as end, color, overlap, display, estado FROM agenda WHERE estado >= 1')->result();

	        echo json_encode($data);
		}else{
			header("Location: ".base_url()."home");
		}
	}

	function agendaCliente($codcliente){
		if ($this->input->is_ajax_request()){
			if($codcliente == 0){
				$data = $this->db->query('select codagenda as id, titulo as title, fechainicio as start, fechafin as end, color, overlap, display, estado FROM agenda WHERE estado >= 1')->result();
			} else {
				$data = $this->db->query('select codagenda as id, titulo as title, fechainicio as start, fechafin as end, color, overlap, display, estado FROM agenda WHERE codcliente='.$codcliente)->result();
			}
			
	        echo json_encode($data);
		}else{
			header("Location: ".base_url()."home");
		}
	}

	function guardar(){
		$this->load->model("Agenda_model");

		if ($this->input->is_ajax_request()){
			if($_POST["estado"]=="") {
				$fechainicio = date("Y-m-d H:i:s", strtotime($_POST['start']));
				$fechafin = date("Y-m-d H:i:s", strtotime($_POST['end']));

				$data = array(
                    "codcliente" => $_POST["codcliente"],
                    "codusuario" => $_POST["codusuario"],
                    "codtipoagenda" => $_POST["codtipoagenda"],
                    "titulo" => $_POST["title"],
                    "fechainicio" => $fechainicio,
                    "fechafin" => $fechafin,
                    "color" => $_POST["color"],
					"codjefatura" => $_SESSION["idusuario"]
                    // "overlap" => $overlap,
                    // "display" => $display
                );

				if($_POST["codcliente"]!=0) {
					$cliente = $this->db->query("select * from clientes where codcliente = ".$_POST["codcliente"])->result_array();
					$usuario = $this->db->query("select * from usuarios where codusuario = ".$_POST["codusuario"])->result_array();

					if($_POST["codagenda"]==""){
						$insertar = $this->db->insert("agenda", $data);

						if ($insertar == 1) {
							$mensaje_cliente = '<div align="center" style="background: #f6f7f9;">
								<table border="0" cellpadding="0" cellspacing="0" width="700px" style="font-family:Roboto,sans-serif;">
									<tbody style="background:#fff;">
										<tr>
											<td style="background:#fff;font-size:20px;padding:40px 40px 0px; text-align: center;">
												<h5 style="color:#0E8138; font-weight:bolder;padding: 0px 10px; margin:0; font-size: 20px">COMUNICADO - AGENDA</h5> <br>
												<h6 style="color:#f43643;font-size:11px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>IMPORTANTE:</b> 
												</h6> <br>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:15px;padding:20px 40px 0px; text-align: center;">
												<p align="justify">Estimado cliente, le comunicamos que de acuerdo a nuestra agenda, tendremos la siguiente actividad a realizar, de acuerdo al siguiente detalle:</p>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:20px;padding:20px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL RESPONSABLE ASIGNADO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>RAZÓN SOCIAL / NOMBRES COMPLETOS:</b> '.$usuario[0]["nombres"].' '.$usuario[0]["apellidos"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b> TELEF. / CELULAR</b> '.$usuario[0]["telefono"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>CORREO ELECTRÓNICO:</b> '.$usuario[0]["email"].'
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 10px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL EVENTO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>ASUNTO:</b> '.$_POST["title"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA INICIO:</b> '.$fechainicio.'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA FIN:</b> '.$fechafin.'
												</h6>
												<h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<i>SI TIENES DUDAS CONSULTAR A '.strtoupper($_SESSION["razonsocial"]).'</i>
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 40px 40px 10px;">
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Gracias,</h5>
												<h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">'.$_SESSION["telefono"].'</h5>
												<br> 
												<span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>';

							$enviar_cliente = $this->Agenda_model->enviar_email($cliente[0]["email"], $cliente[0]["razonsocial"], $mensaje_cliente);

							if($enviar_cliente == 1){
								$estado = 1;
							} else {
								$estado = 0;
							}

							$mensaje_empleado = '<div align="center" style="background: #f6f7f9;">
								<table border="0" cellpadding="0" cellspacing="0" width="700px" style="font-family:Roboto,sans-serif;">
									<tbody style="background:#fff;">
										<tr>
											<td style="background:#fff;font-size:20px;padding:40px 40px 0px; text-align: center;">
												<h5 style="color:#0E8138; font-weight:bolder;padding: 0px 10px; margin:0; font-size: 20px">ESTE EVENTO HA SIDO ASIGNADO POR '.strtoupper($_SESSION["razonsocial"]).' </h5> <br>
												<h6 style="color:#f43643;font-size:11px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>IMPORTANTE:</b> '.strtoupper($usuario[0]["nombres"])." ".strtoupper($usuario[0]["apellidos"]).' DAR SEGUIMIENTO AL EVENTO.
												</h6> <br>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:20px;padding:20px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL CLIENTE</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>RAZÓN SOCIAL / NOMBRES COMPLETOS:</b> '.$cliente[0]["razonsocial"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b> TELEF. / CELULAR</b> '.$cliente[0]["telefono"].' - '.$cliente[0]["celular"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>CORREO ELECTRÓNICO:</b> '.$cliente[0]["email"].'
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 10px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL EVENTO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>ASUNTO:</b> '.$_POST["title"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA INICIO:</b> '.$fechainicio.'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA FIN:</b> '.$fechafin.'
												</h6>
												<h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<i>SI TIENES DUDAS CONSULTAR A '.strtoupper($_SESSION["razonsocial"]).'</i>
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 40px 40px 10px;">
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Gracias,</h5>
												<h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">'.$_SESSION["telefono"].'</h5>
												<br> 
												<span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>';

							$razonsocial = $usuario[0]["nombres"].' '.$usuario[0]["apellidos"];
							$enviar_empleado = $this->Agenda_model->enviar_email($usuario[0]["email"], $razonsocial, $mensaje_empleado);

							if($enviar_empleado == 1){
								$estado = 1;
							} else {
								$estado = 0;
							}
						}else{
							$estado = 0;
						}
					}else{
						$this->db->where("codagenda", $_POST["codagenda"]);
						$actualizar = $this->db->update("agenda", $data);

						if ($actualizar == 1) {
							$mensaje_cliente = '<div align="center" style="background: #f6f7f9;">
								<table border="0" cellpadding="0" cellspacing="0" width="700px" style="font-family:Roboto,sans-serif;">
									<tbody style="background:#fff;">
										<tr>
											<td style="background:#fff;font-size:20px;padding:40px 40px 0px; text-align: center;">
												<h5 style="color:#0E8138; font-weight:bolder;padding: 0px 10px; margin:0; font-size: 20px">COMUNICADO - AGENDA</h5> <br>
												<h6 style="color:#f43643;font-size:11px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>IMPORTANTE:</b> 
												</h6> <br>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:15px;padding:20px 40px 0px; text-align: center;">
												<p align="justify">Estimado cliente, le comunicamos que de acuerdo a nuestra agenda, hubo una <b>modificación</b> en la actividad a realizar, de acuerdo al siguiente detalle:</p>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:20px;padding:20px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL RESPONSABLE ASIGNADO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>RAZÓN SOCIAL / NOMBRES COMPLETOS:</b> '.$usuario[0]["nombres"].' '.$usuario[0]["apellidos"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b> TELEF. / CELULAR</b> '.$usuario[0]["telefono"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>CORREO ELECTRÓNICO:</b> '.$usuario[0]["email"].'
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 10px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL EVENTO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>ASUNTO:</b> '.$_POST["title"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA INICIO:</b> '.$fechainicio.'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA FIN:</b> '.$fechafin.'
												</h6>
												<h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<i>SI TIENES DUDAS CONSULTAR A '.strtoupper($_SESSION["razonsocial"]).'</i>
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 40px 40px 10px;">
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Gracias,</h5>
												<h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">'.$_SESSION["telefono"].'</h5>
												<br> 
												<span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>';


							$enviar_cliente = $this->Agenda_model->enviar_email($cliente[0]["email"], $cliente[0]["razonsocial"], $mensaje_cliente);

							if($enviar_cliente == 1){
								$estado = 1;
							} else {
								$estado = 0;
							}

							$mensaje_empleado = '<div align="center" style="background: #f6f7f9;">
								<table border="0" cellpadding="0" cellspacing="0" width="700px" style="font-family:Roboto,sans-serif;">
									<tbody style="background:#fff;">
										<tr>
											<td style="background:#fff;font-size:20px;padding:40px 40px 0px; text-align: center;">
												<h5 style="color:#0E8138; font-weight:bolder;padding: 0px 10px; margin:0; font-size: 20px">ESTE EVENTO HA SIDO ASIGNADO POR '.strtoupper($_SESSION["razonsocial"]).' </h5> <br>
												<h6 style="color:#f43643;font-size:11px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>IMPORTANTE:</b> '.strtoupper($usuario[0]["nombres"])." ".strtoupper($usuario[0]["apellidos"]).' DAR SEGUIMIENTO AL EVENTO.
												</h6> <br>
											</td>
										</tr>
										<tr>
											<td style="background:#fff;font-size:20px;padding:20px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL CLIENTE</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>RAZÓN SOCIAL / NOMBRES COMPLETOS:</b> '.$cliente[0]["razonsocial"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b> TELEF. / CELULAR</b> '.$cliente[0]["telefono"].' - '.$cliente[0]["celular"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>CORREO ELECTRÓNICO:</b> '.$cliente[0]["email"].'
												</h6>
											</td>
										</tr>

										<tr>
											<td style="padding: 10px 40px;">
												<h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL EVENTO</h5>

												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>ASUNTO:</b> '.$_POST["title"].'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA INICIO:</b> '.$fechainicio.'
												</h6>
												<h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<b>FECHA FIN:</b> '.$fechafin.'
												</h6>
												<h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
													<i>SI TIENES DUDAS CONSULTAR A '.strtoupper($_SESSION["razonsocial"]).'</i>
												</h6>
											</td>
										</tr>
										<tr>
											<td style="padding: 40px 40px 10px;">
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Gracias,</h5>
												<h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
												<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">'.$_SESSION["telefono"].'</h5>
												<br> 
												<span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>';

							$razonsocial = $usuario[0]["nombres"].' '.$usuario[0]["apellidos"];
							$enviar_empleado = $this->Agenda_model->enviar_email($usuario[0]["email"], $razonsocial, $mensaje_empleado);

							if($enviar_empleado == 1){
								$respuesta_empleado = 1;
							} else {
								$respuesta_empleado = $enviar_empleado;
							}
							$estado = 2;
						}else{
							$estado = 0;
						}
					}
				} else {
					if($_POST["codagenda"]==""){
						$insertar = $this->db->insert("agenda", $data);

						if($insertar == 1){
							$estado = 1;
						} else {
							$estado = 0;
						}
					} else {
						$this->db->where("codagenda", $_POST["codagenda"]);
						$actualizar = $this->db->update("agenda", $data);

						if($actualizar == 1){
							$estado = 1;
						} else {
							$estado = 0;
						}
					}
				}  
			} else {
				$file=$_FILES['urldocumento']['name'];
				move_uploaded_file($_FILES["urldocumento"]["tmp_name"],'./public/documentos/'.$file);

				$data = array(
                    "urldocumento" => $file,
                    "estado" => 2
                );
                $this->db->where("codagenda", $_POST["codagenda"]);
                $actualizar = $this->db->update("agenda", $data);

                if ($actualizar == 1) {
            		$estado = 2;
            	}else{
            		$estado = 0;
            	}
			}

			echo $estado;
		}else{
			header("Location: ".base_url());
		}
	}

	function modificar($id) {
		if($this->input->is_ajax_request()) {
			$data = $this->db->query('select * FROM agenda WHERE codagenda ='.$id)->result();
			echo json_encode($data);
		} else {
			return redirect()->to(base_url());
		}
	}

	function update(){
		if ($this->input->is_ajax_request()){

			$fechainicio = date("Y-m-d H:i:s", strtotime($_POST['fechainicio']));

			if (isset($_POST["fechafin"]) != "") {
				$fechafin = date("Y-m-d H:i:s", strtotime($_POST['fechafin']));
			} else {
				$fechafin = $fechainicio;
			}


            $data = array(
                "fechainicio" => $fechainicio,
                "fechafin" => $fechafin
            );

            $this->db->where("codagenda", $_POST["codagenda"]);
            $actualizar = $this->db->update("agenda", $data);

            if ($actualizar === true) {
        		$estado = 1;
        	}else{
        		$estado = 0;
        	}

	        echo $estado;
	    }else{
			return redirect()->to(base_url());
		}
	}

	function eliminar($codevento){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => 0
			);

			$this->db->where("codagenda", $codevento);
            $estado = $this->db->update("agenda", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}
}

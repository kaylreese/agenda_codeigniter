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
			$data["acciones"] = Agendabase::acciones(9);

			$data["tipoeventos"] = $this->db->query('select * FROM tipoagenda WHERE estado = 1')->result_array();
			$data["usuarios"] = $this->db->query('SELECT codusuario, concat(nombres," ",apellidos) as razonsocial FROM usuarios WHERE estado = 1')->result_array();

			$this->load->view('cliente/agenda/index', $data);
		}else{
			header("Location: ".base_url());
		}
	}

	function eventos($codcliente){
		if ($this->input->is_ajax_request()){
			$data = $this->db->query('select codagenda as id, titulo as title, fechainicio as start, fechafin as end, color, overlap, display, estado FROM agenda WHERE (codcliente='.$codcliente.' or codcliente= 0 ) and estado >= 1')->result();

	        echo json_encode($data);
		}else{
			header("Location: ".base_url()."home");
		}
	}

	function guardar_documento(){
		$this->load->model("Agenda_model");

		if ($this->input->is_ajax_request()){

	        $usuario = $this->db->query("select * from usuarios where codusuario = ".$_POST["idusuario"])->result_array();

	        $file=$_FILES['urldocumento']['name'];
			move_uploaded_file($_FILES["urldocumento"]["tmp_name"],'./public/documentos/'.$file);

			$data = array(
                "urldocumento" => $file,
                "estado" => 2
            );
            $this->db->where("codagenda", $_POST["codagenda"]);
            $actualizar = $this->db->update("agenda", $data);  

            $evento = $this->db->query("select * from agenda where codagenda = ".$_POST["codagenda"])->result_array();

            $jefatura = $this->db->query("select * from usuarios where codusuario = ".$evento[0]["codjefatura"])->result_array();

            if ($actualizar == 1) {
            	$mail = new PHPMailer;
	            try {
	                $mail->isSMTP();
	                $mail->SMTPDebug = 2; $mail->Host = 'hs1.ioh.network'; $mail->Port = 25; $mail->SMTPAuth = true;                     
	                $mail->Username = "admin@corpofactperu-temporal.pe"; 
	                $mail->Password = "d2Z24#xpG!E.";
	                $mail->SMTPSecure = 'tls';

	                $mail->setFrom($_SESSION["email"], "AGENDA - GRUPO LyL");
	                $mail->addAddress($usuario[0]["email"], $usuario[0]["nombres"].' '.$usuario[0]["apellidos"]);
	                $mail->Subject = "GRUPO LyL - Mesa de Partes Virtual";
	                $mail->isHTML(true); $mail->CharSet = "utf-8";

	                $mail->Body = '<div align="center" style="background: #f6f7f9;">
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
		                                    <p align="justify">Estimado asistente, se le informa que el cliente <b>'.$_SESSION['razonsocial'].'</b>, acaba de completar la tarea: <b>'.$evento[0]["titulo"].'</b>, la misma que fue asignado a su persona. Acontinuación se detalla el evento y se adjunta una copia del documento de referencia: </p>
		                                </td>
		                            </tr>
		                            <tr>
		                                <td style="padding: 10px 40px;">
		                                    <h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL EVENTO</h5>

		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>ASUNTO:</b> '.$evento[0]["titulo"].'
		                                    </h6>
		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>FECHA INICIO:</b> '.$evento[0]["fechainicio"].'
		                                    </h6>
		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>FECHA FIN:</b> '.$evento[0]["fechafin"].'
		                                    </h6>
		                                    <h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                    </h6>
		                                </td>
		                            </tr>
		                            <tr>
		                                <td style="padding: 40px 40px 10px;">
		                                	<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Atentamente,</h5>
		                                    <h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
		                                    <h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
		                                    <br> 
		                                    <span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
		                                </td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>';

	                $url_archivo  = "public/documentos/".$evento[0]["urldocumento"];
		        	$mail->addAttachment($url_archivo, $$evento[0]['urldocumento']);

	                $mail->smtpConnect = array(
	                    'ssl' => array(
	                    'verify_peer' => false,
	                    'verify_peer_name' => false,
	                    'allow_self_signed' => true
	                    )
	                );

	                $mail->SMTPSecure = false;
	                $mail->SMTPAutoTLS = false;

	                if(!$mail->send()){
	                    $estado = 0;
	                }else{
	                    $estado = 1;
	                }
	            }catch(Exception $e) {
	                $estado = 0;
	            }

                $mail = new PHPMailer;
	            try {
	                $mail->isSMTP();
	                $mail->SMTPDebug = 2; $mail->Host = 'hs1.ioh.network'; $mail->Port = 25; $mail->SMTPAuth = true;                     
	                $mail->Username = "admin@corpofactperu-temporal.pe"; 
	                $mail->Password = "d2Z24#xpG!E.";
	                $mail->SMTPSecure = 'tls';

	                $mail->setFrom($_SESSION["email"], "AGENDA - GRUPO LyL");
	                $mail->addAddress($jefatura[0]["email"], $jefatura[0]["nombres"].' '.$jefatura[0]["apellidos"]);
	                $mail->Subject = "GRUPO LyL - Mesa de Partes Virtual";
	                $mail->isHTML(true); $mail->CharSet = "utf-8";

	                $mail->Body = '<div align="center" style="background: #f6f7f9;">
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
		                                    <p align="justify">Estimado encargado de Jefatura, se le informa que el cliente: <b>'.$_SESSION['razonsocial'].'</b>, acaba de terminar la tarea: <b>'.$evento[0]["titulo"].'</b>. Acontinuación se detalla el evento y se adjunta una copia del documento de referencia:</p>
		                                </td>
		                            </tr>
		                            <tr>
		                                <td style="background:#fff;font-size:20px;padding:20px 40px;">
		                                    <h5 style="color:#00662b; font-weight:bolder;padding: 0px 10px; font-size: 17px">DATOS DEL ASISTENTE</h5>

		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>NOMBRES COMPLETOS:</b> '.$usuario[0]["nombres"].' '.$usuario[0]["apellidos"].'
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
		                                        <b>ASUNTO:</b> '.$evento[0]["titulo"].'
		                                    </h6>
		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>FECHA INICIO:</b> '.$evento[0]["fechainicio"].'
		                                    </h6>
		                                    <h6 style="color:#4f4d51;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                        <b>FECHA FIN:</b> '.$evento[0]["fechafin"].'
		                                    </h6>
		                                    <h6 style="color:#f43643;font-size:12px; padding:5px 10px;margin: 0px; font-weight: 100;">
		                                    </h6>
		                                </td>
		                            </tr>
		                            <tr>
		                                <td style="padding: 40px 40px 10px;">
		                                	<h5 style="margin: 0; color:#4f4d51; padding:0 10px;">Atentamente,</h5>
		                                    <h5 style="color:#4f4d51; padding:0 10px; margin: 0px;"><i>Oficina de Administración</i></h5>
		                                    <h5 style="margin: 0; color:#4f4d51; padding:0 10px;"><i>Grupo LyL</i></h5>
		                                    <br> 
		                                    <span style="color:#4f4d51; padding:5px 10px; font-size: 13px;"><i>Correo enviado de forma automática</i></span>
		                                </td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>';

		            $url_archivo2  = "public/documentos/".$evento[0]["urldocumento"];
		        	$mail->addAttachment($url_archivo2, $$evento[0]['urldocumento']);

	                $mail->smtpConnect = array(
	                    'ssl' => array(
	                    'verify_peer' => false,
	                    'verify_peer_name' => false,
	                    'allow_self_signed' => true
	                    )
	                );

	                $mail->SMTPSecure = false;
	                $mail->SMTPAutoTLS = false;

	                if(!$mail->send()){
	                    $estado = 0;
	                }else{
	                    $estado = 1;
	                }
	            }catch(Exception $e) {
	                $estado = 0;
	            }

        	}else{
        		$estado = 0;
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

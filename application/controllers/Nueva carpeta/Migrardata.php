<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Migrardata extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('excel');
    	$this->dbdefault = $this->load->database('default', TRUE);
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('migrardata/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$usuarios = $this->dbdefault->query("select id, codigo, firstname, lastname, email, department from mdl_user order by id desc limit 1000")->result_array();

			$this->load->view("migrardata/lista", compact("usuarios"));
		}else{
			header("Location: ".base_url()."principal");
		}
	}

	function guardar(){
		if ($this->input->is_ajax_request()){
			if(isset($_FILES["archivo"]["name"])){
				$path = $_FILES["archivo"]["tmp_name"];
				$object = PHPExcel_IOFactory::load($path);

				$cont = 0; $insertar = 0; $cantidad = 0; $repetidos = array();

				foreach($object->getWorksheetIterator() as $hojaexcel){
					$nrofilas = $hojaexcel->getHighestRow();
					$nrocolumnas = $hojaexcel->getHighestColumn();
					
					for($fila=2; $fila<=$nrofilas; $fila++){
						$dni = $hojaexcel->getCellByColumnAndRow(0,$fila)->getValue();
						if ($dni == "") {
							break;
						}else{
							$nombres = $hojaexcel->getCellByColumnAndRow(1,$fila)->getValue();
							$apellidos = $hojaexcel->getCellByColumnAndRow(2,$fila)->getValue();
							$email = $hojaexcel->getCellByColumnAndRow(3,$fila)->getValue();
							$escuela = $hojaexcel->getCellByColumnAndRow(4,$fila)->getValue();
							$tipousuario = $hojaexcel->getCellByColumnAndRow(5,$fila)->getValue();

							$password = password_hash($dni, PASSWORD_DEFAULT, array('cost' => 10));
							$fecha = strtotime("now");

							//print_r($codigo." | ".$nombres." | ".$apellidos." | ".$email." | ".$semestre." | "); 

							$comprobar = $this->dbdefault->query("select * from mdl_user where email = '".$email."'")->result_array();

							if (count($comprobar)) {
								$repetidos[$cont]["correos"] = $email;
								$cont = $cont + 1;
								$cantidad = $cantidad + 1;
							}else{
								$data = array(
					                "auth" => 'manual',
					                "confirmed" => 1,
					                "mnethostid" => 1,
					                "username" => $email,
					                "password" => $password,
					                "idnumber" => $dni,
					                "firstname" => $nombres,
					                "lastname" => $apellidos,
					                "email" => $email,
					                "codigo" => $dni,
					                "institution" => 'UNSM-T',
					                "department" => $escuela,
					                "city" => 'Tarapoto',
					                "country" => 'PE',
					                "lang" => 'es',
					                "description" => $tipousuario,
					                "timecreated" => $fecha
					            );
					            $insertar = $this->dbdefault->insert("mdl_user", $data);
					        }
						}
					}
				}

				if (count($repetidos)) {
        			$data["repetidos"] = 1;
        			$data["cantidad"] = $cantidad;
        			$data["correos"] = $repetidos;
        		}else{
        			$data["repetidos"] = 0;
        		}

	        	if ($insertar!=0) {
	        		$data["estado"] = 1;
	        	}else{
	        		$data["estado"] = $insertar;
	        	} 
			}else{
				$data["estado"] = 2;
			}	
			echo json_encode($data);
		}else{
			header("Location: ".base_url());
		}
	}
}
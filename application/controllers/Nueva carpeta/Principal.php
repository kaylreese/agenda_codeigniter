<?php defined('BASEPATH') OR exit('No direct script access allowed');
session_start();

class Principal extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){	
		if (isset($_SESSION["usuario"])) {
			$this->load->view("sistemaweb/index");
		}else{
			header("Location: ".base_url()."login");
		}
	}

	public function informacion(){	
		if (isset($_SESSION["usuario"])) {
			$info = $this->dbasistencias->query("SELECT * FROM informacion")->result_array();

			$this->load->view("sistemaweb/informacion", compact("info"));
		}else{
			header("Location: ".base_url()."login");
		}
	}

	function guardar(){
		if ($this->input->is_ajax_request()){
        	$data = array(
                "institucion" => $_POST["institucion"],
                "oficina" => $_POST["oficina"],
                "direccion" => $_POST["direccion"],
                "lugar" => $_POST["lugar"],
                "nombreanio" => $_POST["año"]
            );
            $actualizar = $this->dbasistencias->update("informacion", $data);

        	if (count($actualizar)) {
        		$estado = 1;
        	}else{
        		$estado = 0;
        	}

			echo $estado;
		}else{
			header("Location: ".base_url());
		}
	}

	public function cerrarsesion(){
		session_destroy();
		header("Location: ".base_url()."login");
	}
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Temas extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/temas/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$temas = $this->dbasistencias->query("select * from temas where estado=1")->result_array();
			$this->load->view("controlasistencia/temas/lista",compact("temas"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$modulos = $this->dbasistencias->query("select * from temas where padre=0 and estado=1")->result_array();

			$this->load->view("controlasistencia/temas/nuevo",compact("modulos"));
		}else{
			header("Location: ".base_url());
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){
			$data = array(
                "tema" => $_POST["tema"],
                "padre" => $_POST["padre"]
            );

			if( $_POST["codtema"]=="" ){
                $insertar = $this->dbasistencias->insert("temas", $data);

	            if (count($insertar)) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}else{
                $this->dbasistencias->where("codtema", $_POST["codtema"]);
                $actualizar = $this->dbasistencias->update("temas", $data);

            	if (count($actualizar)) {
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

	function modificar($id){
		if ($this->input->is_ajax_request()){
			$info = $this->dbasistencias->query("select * from temas where codtema=".$id)->result();
            echo json_encode($info);
		}else{
			header("Location: ".base_url());
		}
	}

	function eliminar(){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => "0"
			);

			$this->dbasistencias->where("codtema", $_POST["id"]);
			$estado = $this->dbasistencias->update("temas", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}
}
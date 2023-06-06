<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Usuario extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}
	
	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('usuario/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$usuarios = $this->dbasistencias->query("select * from usuarios where estado=1")->result_array();
			$this->load->view("usuario/lista",compact("usuarios"));
		}else{
			header("Location: ".base_url()."principal/login");
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$this->load->view("usuario/nuevo");
		}else{
			header("Location: ".base_url()."principal/login");;
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){ 
	    	if( $_POST["codusuario"]=="" ){
                $data = array(
                    "usuario" => $_POST["usuario"],
                    "clave" => md5($_POST["clave"])
                );
                $insertar = $this->dbasistencias->insert("usuarios", $data);

	            if (count($insertar)) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}

			echo $estado;
		}else{
			header("Location: ".base_url()."principal/login");
		}
	}

	function eliminar(){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => "0"
			);

			$this->dbasistencias->where("codusuario", $_POST["idusuario"]);
			$estado = $this->dbasistencias->update("usuarios", $data);
			echo $estado;
		}else{
			header("Location: ".base_url()."principal/login");
		}		
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Docente extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/docentes/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$docentes = $this->dbasistencias->query("select * from docente as d join escuela as e on e.codescuela = d.codescuela where d.estado=1")->result_array();
			$this->load->view("controlasistencia/docentes/lista",compact("docentes"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$facultades = $this->dbasistencias->query("select * from facultad")->result_array();
			$escuelas = $this->dbasistencias->query("select * from escuela")->result_array();

			$this->load->view("controlasistencia/docentes/nuevo", compact("facultades","escuelas"));
		}else{
			header("Location: ".base_url());
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){
			if( $_POST["coddocente"]=="" ){
                $data = array(
                    "codescuela" => $_POST["codescuela"],
                    "nombres" => $_POST["nombres"],
                    "dni" => $_POST["dni"],
                    "celular" => $_POST["celular"],
                    "email" => $_POST["email"],
                    "estado" => 1
                );
                $insertar = $this->dbasistencias->insert("docente", $data);

	            if (count($insertar)) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}else{
            	$data = array(
                    "codescuela" => $_POST["codescuela"],
                    "nombres" => $_POST["nombres"],
                    "dni" => $_POST["dni"],
                    "celular" => $_POST["celular"],
                    "email" => $_POST["email"]
                );
                $this->dbasistencias->where("coddocente", $_POST["coddocente"]);
                $actualizar = $this->dbasistencias->update("docente", $data);

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
			$info = $this->dbasistencias->query("select * from docente as d join escuela as e on e.codescuela = d.codescuela where d.coddocente=".$id)->result();
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

			$this->dbasistencias->where("coddocente", $_POST["id"]);
			$estado = $this->dbasistencias->update("docente", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}

	function escuelas(){
		$escuelas = $this->dbasistencias->query("select codescuela, nombre_escuela from escuela where codfacultad=".$this->input->post("fac"))->result();
        echo json_encode($escuelas);
	}
}
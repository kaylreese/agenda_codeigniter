<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Asistencias extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/asistencias/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$asistencias = $this->dbasistencias->query("select s.codsesion,s.codevento, e.abreviatura, s.nombre_sesion, s.fecha, s.horainicio, s.horafin, (select count(*) from detalle_sesion where codsesion= s.codsesion) as nrodocentes, (select count(*) from asistencias where codsesion= s.codsesion and estado = 1) as asistentes, s.estado from sesiones as s join evento as e on e.codevento=s.codevento where s.estado = 1 or s.estado = 2")->result_array();
			$this->load->view("controlasistencia/asistencias/lista",compact("asistencias"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function detalle($idsesion){
		if ($this->input->is_ajax_request()){
			$docentes = $this->dbasistencias->query("select ds.codsesion, ds.coddocente, d.nombres, d.dni, d.celular, d.email, e.nombre_escuela, COALESCE((select count(a.coddocente) from asistencias as a where a.coddocente= ds.coddocente and a.codsesion = ".$idsesion."), 0) as estado from detalle_sesion as ds join docente as d on d.coddocente=ds.coddocente join escuela as e on e.codescuela=d.codescuela where ds.codsesion=".$idsesion)->result_array();

			$this->load->view("controlasistencia/asistencias/detalle", compact("docentes"));
		}else{
			header("Location: ".base_url());
		}
	}

	function guardar($idsesion, $iddocente){
		if ($this->input->is_ajax_request()){
			$consulta = $this->dbasistencias->query("select * from asistencias where codsesion=".$idsesion." and coddocente=".$iddocente)->result_array();
			if (count($consulta)) {
				$estado = 2;
			}else{
				$data = array(
	                "codsesion" => $idsesion,
	                "coddocente" => $iddocente,
	                "fecha" => date("Y-m-d H:i:s"),
	                "estado" => 1
	            );
	            $insertar = $this->dbasistencias->insert("asistencias", $data);

	            if (count($insertar)) {
	        		$estado = 1;
	        	}else{
	        		$estado = 0;
	        	}
			}

			echo $estado;
		}else{
			header("Location: ".base_url());
		}
	}

	function finalizar(){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => "2"
			);

			$this->dbasistencias->where("codevento", $_POST["idevento"]);
			$this->dbasistencias->where("codsesion", $_POST["idsesion"]);
			$estado = $this->dbasistencias->update("asistencias", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}
}
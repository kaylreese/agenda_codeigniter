<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); include("Agendabase.php");

class Home extends Agendabase {
	function __construct(){
		parent::__construct();
	}

	public function index(){	
		if (isset($_SESSION["usuario"])) {
			$data["modulos"] = Agendabase::modulos();

			$data["totaleventos"] = $this->db->query('select count(*) as total FROM agenda WHERE codcliente='.$_SESSION["idusuario"].' and (estado = 1 or estado=2)')->result_array();
			$data["eventosrealizados"] = $this->db->query('select count(*) as realizados FROM agenda WHERE codcliente='.$_SESSION["idusuario"].' and estado = 2')->result_array();
			$data["eventospendientes"] = $this->db->query('select count(*) as pendientes FROM agenda WHERE codcliente='.$_SESSION["idusuario"].' and estado = 1')->result_array();
			$data["feriados"] = $this->db->query('select count(*) as feriados FROM agenda WHERE codtipoagenda=2 and estado = 1')->result_array();
			
			$this->load->view("cliente/home/index", $data);
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
                "telefono" => $_POST["telefono"],
                "celular" => $_POST["celular"]
            );
            $actualizar = $this->db->update("informacion", $data);

        	if ($actualizar == 1) {
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
		header("Location: ".base_url());
	}
}

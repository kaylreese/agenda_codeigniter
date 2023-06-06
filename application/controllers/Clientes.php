<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); include("Agendabase.php");

class Clientes extends Agendabase {
	function __construct(){
		parent::__construct();
    	$this->dbdefault = $this->load->database('default', TRUE);
	}

	public function index() {
		if (isset($_SESSION["usuario"])) {
			$data["modulos"] = Agendabase::modulos();
			$data["acciones"] = Agendabase::acciones(4);
			
			$this->load->view("admin/clientes/index", $data);
		}else{
			header("Location: ".base_url()."home");
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$clientes = $this->dbdefault->query("SELECT * from clientes where estado<2")->result_array();
			$this->load->view("admin/clientes/lista",compact("clientes"));
		}else{
			header("Location: ".base_url()."home");
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$data["perfiles"] = $this->db->query('SELECT * from perfiles where estado=1')->result_array();

			$this->load->view("admin/clientes/nuevo", $data);
		}else{
			header("Location: ".base_url()."home");
		}
	}

	function guardar(){
		if ($this->input->is_ajax_request()){
			$data = array(
                "ruc" => $_POST["ruc"],
                "razonsocial" => $_POST["razonsocial"],
                "codperfil" => $_POST["codperfil"],
                "email" => $_POST["email"],
                "telefono" => $_POST["telefono"],
                "celular" => $_POST["celular"],
                "usuario" => $_POST["usuario"],
                "clave" => md5($_POST["clave"])
            );

			if($_POST["codcliente"]==""){
                $insertar = $this->dbdefault->insert("clientes", $data);

	            if ($insertar == 1) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}else{
                $this->dbdefault->where("codcliente", $_POST["codcliente"]);
                $actualizar = $this->dbdefault->update("clientes", $data);

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
			$data = $this->dbdefault->query('SELECT * FROM clientes WHERE codcliente ='.$id)->result();
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

            $this->dbdefault->where("codagenda", $_POST["codagenda"]);
            $actualizar = $this->dbdefault->update("agenda", $data);

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

	function eliminar($codcliente){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => 0
			);

			$this->dbdefault->where("codcliente", $codcliente);
            $estado = $this->dbdefault->update("clientes", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}
}

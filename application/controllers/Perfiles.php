<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); include("Agendabase.php");

class Perfiles extends Agendabase {
	function __construct(){
		parent::__construct();
	}

	public function index() {
		if (isset($_SESSION["usuario"])) {
			$data["modulos"] = Agendabase::modulos();
			$data["acciones"] = Agendabase::acciones(7);

			$this->load->view("admin/perfiles/index", $data);
		}else{
			header("Location: ".base_url()."login");
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$perfiles = $this->db->query("SELECT * FROM perfiles where estado<2")->result_array();
			$this->load->view("admin/perfiles/lista",compact("perfiles"));
		}else{
			header("Location: ".base_url()."login");
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$this->load->view("admin/perfiles/nuevo");
		}else{
			header("Location: ".base_url()."login");
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){ 
	    	if( $_POST["codperfil"]=="" ){
                $data = array(
                    "nombre" => $_POST["nombre"],
                    "descripcion" => $_POST["descripcion"]
                );
                $insertar = $this->db->insert("perfiles", $data);

	            if ($insertar == 1) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	} else {
	     		$this->db->where("codperfil", $_POST["codperfil"]);
                $actualizar = $this->db->update("perfiles", $data);

            	if ($actualizar == 1) {
            		$estado = 2;
            	}else{
            		$estado = 0;
            	}
	     	}

			echo $estado;
		}else{
			header("Location: ".base_url()."login");
		}
	}

	function modificar($codperfil) {
		if($this->input->is_ajax_request()) {
			$data = $this->db->query('SELECT * FROM perfiles WHERE codperfil ='.$codperfil)->result();
			echo json_encode($data);
		} else {
			return redirect()->to(base_url());
		}
	}

	function eliminar($codperfil){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => "0"
			);

			$this->db->where("codperfil", $codperfil);
			$estado = $this->db->update("perfiles", $data);
			echo $estado;
		}else{
			header("Location: ".base_url()."login");
		}		
	}

	// PARA PERMISOS DEL PERFIL DE USUARIO //
	public function verpermisos($codperfil){
		if ($this->input->is_ajax_request()){
			$permisos = $this->db->query("select * from modulos where codpadre=0 and estado=1 order by orden asc")->result_array();
			foreach ($permisos as $key => $value) {
				$mod = $this->db->query("select * from modulos where codpadre=".$value["codmodulo"]." and estado=1")->result_array();
				$permisos[$key]["lista"] = $mod;
			}
			$activos = $this->db->get_where("permisos", array("codperfil" => $codperfil))->result_array();

			$this->load->view("admin/perfiles/permisos", compact("permisos","activos"));
		}else{
			header("Location: ".base_url()."login");
		}
	}

	function guardarpermisos(){
    	if ($this->input->is_ajax_request()){
      		$this->db->where("codperfil", $_POST["codperfil"]);
        	$this->db->delete("permisos");

        	foreach ($_POST["modulos"] as $key => $value) {
            	$data = array(
               		"codperfil" => $_POST["codperfil"],
               		"codmodulo" => $_POST["modulos"][$key],
               		"ver" => 0,
               		"nuevo" => 0,
               		"modificar" => 0,
               		"eliminar" => 0,
               		"consultar" => 0,
               		"anular" => 0,
               		"imprimir" => 0
            	);
            	$estado = $this->db->insert("permisos", $data);
        	}
        	
        	$acciones = ["ver","nuevo","modificar","eliminar","consultar","anular","imprimir"];
        	for ($i=0; $i < 7; $i++) { 
        		if (isset($_POST[$acciones[$i]])) {
            		foreach ($_POST[$acciones[$i]] as $key => $value) {
		                	$data = array($acciones[$i] => 1);
		                	$this->db->where("codperfil", $_POST["codperfil"]);
		                	$this->db->where("codmodulo", $value);
		                	$estado = $this->db->update("permisos", $data);
	            	}
            	}
        	}
        	
        	echo $estado;
    	}else{
        	header("Location: ".base_url()."login");
    	}
    }
}

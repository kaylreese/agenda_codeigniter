<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); include("Agendabase.php");

class Usuarios extends Agendabase {
	function __construct(){
		parent::__construct();
	}

	public function index() {
		if (isset($_SESSION["usuario"])) {
			$data["modulos"] = Agendabase::modulos();
			$data["acciones"] = Agendabase::acciones(8);

			$this->load->view("admin/usuarios/index", $data);
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$usuarios = $this->db->query("SELECT u.codusuario, p.nombre as perfil, CONCAT(u.nombres,' ',u.apellidos) as razonsocial, u.usuario, u.email, u.direccion, u.telefono, u.estado FROM usuarios u join perfiles p on p.codperfil = u.codperfil where u.estado<2")->result_array();
			$this->load->view("admin/usuarios/lista",compact("usuarios"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$data["perfiles"] = $this->db->query('SELECT * from perfiles where estado=1')->result_array();
			$this->load->view("admin/usuarios/nuevo", $data);
		}else{
			header("Location: ".base_url());;
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){ 
			$data = array(
                "dni" => $_POST["dni"],
                "nombres" => $_POST["nombres"],
                "apellidos" => $_POST["apellidos"],
                "codperfil" => $_POST["codperfil"],
                "telefono" => $_POST["telefono"],
                "email" => $_POST["email"],
                "direccion" => $_POST["direccion"],
                "usuario" => $_POST["usuario"],
                "clave" => md5($_POST["clave"]),
                "sexo" => $_POST["sexo"],
                "observaciones" => $_POST["observaciones"]
            );

	    	if( $_POST["codusuario"]=="" ){	
                $insertar = $this->db->insert("usuarios", $data);

	            if ($insertar == 1) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	} else {
	     		$this->db->where("codusuario", $_POST["codusuario"]);
                $actualizar = $this->db->update("usuarios", $data);

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
			$data = $this->db->query('SELECT codusuario, usuario, md5(clave), nombres, apellidos, dni, sexo, direccion, telefono, email, observaciones, codperfil FROM usuarios WHERE codusuario ='.$id)->result();
			echo json_encode($data);
		} else {
			return redirect()->to(base_url());
		}
	}


	function eliminar($codusuario){
		if ($this->input->is_ajax_request()){
			$data = array(
				"estado" => 0
			);

			$this->db->where("codusuario", $codusuario);
			$estado = $this->db->update("usuarios", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}
}

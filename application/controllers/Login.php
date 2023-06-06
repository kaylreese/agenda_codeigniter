<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('login/index');
	}

	public function control(){	
		if (isset($_POST["usuario"])) {
			$usuario = $_POST["usuario"];
	    	$clave = $_POST["clave"];
	    	$usuario = stripslashes($usuario);
	    	$clave = stripslashes($clave);

	    	$arrnou = array("'", "=", "\"", "<", ">", "|", "&", "INSERT", "DELETE", "UPDATE", "TRUNCATE", "SELECT");
	    	$usuario = str_replace($arrnou, "", $usuario);
	    	$clave = str_replace($arrnou, "", $clave);

			$login_cliente = $this->db->query("SELECT c.*, p.nombre as perfil FROM clientes c join perfiles p on p.codperfil = c.codperfil WHERE c.usuario='".$usuario."' and c.clave='".md5($clave)."' and c.estado=1")->result_array();
			
			// print_r($login_cliente); exit();

			if (count($login_cliente)>0) {
				$_SESSION["idusuario"] = $login_cliente[0]["codcliente"];
				$_SESSION["usuario"] = $login_cliente[0]["usuario"];
				$_SESSION["razonsocial"] = $login_cliente[0]["razonsocial"];
				$_SESSION["telefono"] = $login_cliente[0]["telefono"];
				$_SESSION["celular"] = $login_cliente[0]["celular"];
				$_SESSION["email"] = $login_cliente[0]["email"];
				$_SESSION["perfil"] = $login_cliente[0]["perfil"];
				$_SESSION["codperfil"] = $login_cliente[0]["codperfil"];

				header("Location: ".base_url()."cliente/home");
			}else{
				header("Location: ".base_url());
			}
		}else{
			header("Location: ".base_url());
		}
	}
}

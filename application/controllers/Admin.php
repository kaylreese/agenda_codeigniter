<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();

class Admin extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('admin/index');
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

			$login = $this->db->query("SELECT u.*, p.nombre as perfil FROM usuarios u join perfiles p on p.codperfil = u.codperfil WHERE u.usuario='".$usuario."' and u.clave='".md5($clave)."' and u.estado=1")->result_array();

			if (count($login)>0) {
				$_SESSION["idusuario"] = $login[0]["codusuario"];
				$_SESSION["usuario"] = $login[0]["usuario"];
				$_SESSION["razonsocial"] = $login[0]["nombres"]." ".$login[0]["apellidos"];
				$_SESSION["perfil"] = $login[0]["perfil"];
				$_SESSION["codperfil"] = $login[0]["codperfil"];
				$_SESSION["email"] = $login[0]["email"];
				$_SESSION["email_password"] = $login[0]["email_password"];
				$_SESSION["telefono"] = $login[0]["telefono"];

				header("Location: ".base_url()."home");
			}else{
				header("Location: ".base_url()."admin");
			}
		}else{
			header("Location: ".base_url());
		}
	}
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');
session_start();

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){	
		$this->load->view('sistemaweb/login');
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

			$login = $this->dbasistencias->get_where("usuarios", array('usuario' => $usuario,'clave'=> md5($clave), 'estado'=>1))->result_array();

			if (count($login)>0) {
				$_SESSION["idusuario"] = $login[0]["codusuario"];
				$_SESSION["usuario"] = $login[0]["usuario"];

				header("Location: ".base_url()."principal");
			}else{
				header("Location: ".base_url());
			}
		}else{
			header("Location: ".base_url());
		}
	}
}

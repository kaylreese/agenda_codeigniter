<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agendabase extends CI_Controller {

	public function modulos(){
		$modulos = $this->db->query("select * from modulos where codpadre = 0 and estado = 1 order by orden asc")->result_array();

		foreach ($modulos as $key => $value) {
			$mod = $this->db->query("select m.* from modulos m join permisos per on per.codmodulo = m.codmodulo join perfiles p on p.codperfil=per.codperfil where per.codperfil=".$_SESSION["codperfil"]." and m.codpadre=".$value["codmodulo"]." and per.ver=1 and m.estado=1 order by m.orden asc")->result_array();
			$modulos[$key]["lista"] = $mod;
		}
		return $modulos;
	}

	public function acciones($modulo){
		$resultado = $this->db->query("select * from permisos where codperfil=".$_SESSION["codperfil"]." and codmodulo=".$modulo." and ver=1")->result_array();

		return $resultado;
	}
}

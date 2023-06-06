<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Evento extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/eventos/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$eventos = $this->dbasistencias->query("select e.codevento, e.nombre_evento, e.abreviatura, tp.descripcion as tipoevento, (select count(s.codevento) from sesiones as s where s.codevento=e.codevento and s.estado=1) as sesiones from evento as e inner join tipoevento as tp on tp.codtipoevento = e.codtipoevento where e.estado=1")->result_array();
			$this->load->view("controlasistencia/eventos/lista",compact("eventos"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$tipoeventos = $this->dbasistencias->query("select * from tipoevento where estado=1")->result_array();

			$this->load->view("controlasistencia/eventos/nuevo",compact("tipoeventos"));
		}else{
			header("Location: ".base_url());
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){
			$data = array(
                "nombre_evento" => $_POST["nombre_evento"],
                "codtipoevento" => $_POST["codtipoevento"],
                "abreviatura" => $_POST["abreviatura"]
            );

			if( $_POST["codevento"]=="" ){
                
                $insertar = $this->dbasistencias->insert("evento", $data);

	            if (count($insertar)) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}else{
                $this->dbasistencias->where("codevento", $_POST["codevento"]);
                $actualizar = $this->dbasistencias->update("evento", $data);

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
			$info = $this->dbasistencias->query("select * from evento where codevento=".$id)->result();
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

			$this->dbasistencias->where("codevento", $_POST["id"]);
			$estado = $this->dbasistencias->update("evento", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}

	public function ver_sesiones(){
		if ($this->input->is_ajax_request()){
			$this->load->view("controlasistencia/eventos/ver_sesiones");
		}else{
			header("Location: ".base_url());
		}
	}

	public function sesiones($codevento){
		if ($this->input->is_ajax_request()){

			$lista_sesiones = $this->dbasistencias->query("select * from sesiones where codevento=".$codevento." and estado=1")->result_array();

			foreach ($lista_sesiones as $key => $value) {
				$docentes = $this->dbasistencias->query("select d.coddocente, d.nombres, e.nombre_escuela as escuela, d.dni, d.celular, d.email from docente as d join escuela as e on e.codescuela = d.codescuela join detalle_sesion as ds on ds.coddocente=d.coddocente where ds.codsesion=".$value["codsesion"]." and d.estado=1")->result_array();
				$lista_sesiones[$key]["docentes"] = $docentes;
			}

			/*$activos = $this->db->query("select * from e_permisos where codperfil =".$id)->result_array();*/

			$html=""; 
			
			foreach ($lista_sesiones as $key => $value) { 
      			$html .= '<div class="col-md-12">';
				$html .= '	<table class="table table-bordered">';
				$html .= '		<thead>';
				$html .= '			<tr>';
				$html .= '				<th colspan="5" class="table-permisos" style="background: #f4f4f4;"><center>'. $value["nombre_sesion"].'</center></th>';
				$html .= '			</tr>';
				$html .= '			<tr>';
				$html .= '				<th class="table-permisos" style="width: 						20px;"style="background: #f4f4f4;"><center>N°</center></th>';
				$html .= '				<th class="table-permisos" style="width: 						250px;" style="background: #f4f4f4;"><center>Nombres</center></th>';
				$html .= '				<th class="table-permisos" style="width: 						100px;" style="background: #f4f4f4;"><center>Celular</center></th>';
				$html .= '				<th class="table-permisos" style="width: 						250px;" style="background: #f4f4f4;"><center>Email</center></th>';
				$html .= '				<th class="table-permisos" style="width: 						150px;" style="background: #f4f4f4;"><center>Escuela</center></th>';
				$html .= '			</tr>';
				$html .= '		</thead>';
				$html .= '		<tbody>';
					
					$cont = 1;
                    foreach ($value["docentes"] as $val) { 
						$html .= '<tr>';
						$html .= '	<td>'.$cont.'</td>';
						$html .= '	<td>'.$val["nombres"].'</td>';
						$html .= '	<td>'.$val["celular"].'</td>';
						$html .= '	<td>'.$val["email"].'</td>';
						$html .= '	<td>'.$val["escuela"].'</td>';
						$html .= '</tr>';
						$cont = $cont + 1;
					}
				$html .= '		</tbody>';
				$html .= '	</table>';
				$html .= '</div>';
 			}
 			print $html;
		}else{
			header("Location: ".base_url());
		}
	}
}
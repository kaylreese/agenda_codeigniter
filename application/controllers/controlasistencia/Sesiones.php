<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Sesiones extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/sesiones/index', compact("escuelas"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$sesiones = $this->dbasistencias->query("select e.codevento, s.codsesion, e.abreviatura, s.nombre_sesion, s.lugar, s.fecha, s.horainicio, s.horafin, s.estado, (select count(ds.codsesion) from detalle_sesion as ds where ds.codsesion=s.codsesion) as docentes from sesiones as s join evento as e on e.codevento = s.codevento where s.estado=1 or s.estado=2")->result_array();
			$this->load->view("controlasistencia/sesiones/lista",compact("sesiones"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function nuevo(){
		if ($this->input->is_ajax_request()){
			$eventos = $this->dbasistencias->query("select * from evento where estado=1")->result_array();
			$temas = $this->dbasistencias->query("select * from temas where estado=1")->result_array();

			$this->load->view("controlasistencia/sesiones/nuevo", compact("eventos","temas"));
		}else{
			header("Location: ".base_url());
		}
	}
	
	function guardar(){
		if ($this->input->is_ajax_request()){
			$data = array(
                "codevento" => $_POST["codevento"],
                "nombre_sesion" => $_POST["nombre_sesion"],
                "lugar" => $_POST["lugar"],
                "fecha" => $_POST["fecha"],
                "horainicio" => $_POST["horainicio"],
                "horafin" => $_POST["horafin"]
            );

			if( $_POST["codsesion"]=="" ){
                $insertar = $this->dbasistencias->insert("sesiones", $data);
                $codsesion = $this->dbasistencias->insert_id();

                if ($_POST["listacheck"] != "") {
                	foreach ($_POST["listacheck"] as $key => $value) {
	            		$data1 = array(
		               		"codsesion" => $codsesion,
		               		"codtema" => (int)$_POST["listacheck"][$key]
		            	);
		            	$insertar = $this->dbasistencias->insert("detalle_sesion", $data1);
		            }
			}

	            if (count($insertar)) {
            		$estado = 1;
            	}else{
            		$estado = 0;
            	}
	     	}else{
                $this->dbasistencias->where("codsesion", $_POST["codsesion"]);
                $actualizar = $this->dbasistencias->update("sesiones", $data);

                if ($_POST["listacheck"] != "") {
					$this->dbasistencias->where("codsesion", $_POST["codsesion"]);
		        	$this->dbasistencias->delete("detalle_sesion");
		        	
					foreach ($_POST["listacheck"] as $key => $value) {
	            		$data = array(
		               		"codsesion" => $_POST["codsesion"],
		               		"codtema" => $_POST["listacheck"][$key]
		            	);
		            	$actualizar = $this->dbasistencias->insert("detalle_sesion", $data);
					}
				}

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
			$info = $this->dbasistencias->query("select * from sesiones where codsesion=".$id)->result();
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

			$this->dbasistencias->where("codsesion", $_POST["id"]);
			$estado = $this->dbasistencias->update("sesiones", $data);
			echo $estado;
		}else{
			header("Location: ".base_url());
		}		
	}

	function list_temas($idsesion){
		$checklist = $this->dbasistencias->query("select * from temas where estado=1 order by codtema asc")->result_array();

		$activos = $this->dbasistencias->query("select * from detalle_sesion where codsesion =".$idsesion)->result_array();
		
		$html=""; $cont = 1;

		foreach ($checklist as $key => $value) { 
          	$chek = ""; 
          	if(count($activos) > 0){
               	foreach ($activos as $v) {
                	if ($v["codtema"] == $value["codtema"]) {
                 		$chek = "checked";
                        break;
            		}
  				}
  			}

			$html .= '<tr>';
			$html .= '	<td>'.$cont.'</td>';
			$html .= '	<td>'.$value["tema"].'</td>';
			$html .= '	<td align="center">';
			$html .= '	   <input type="checkbox" '.$chek.' name="listacheck[]" id="listacheck_'.$value["codtema"].'" value="'.$value["codtema"].'" class="filled-in chk-col-green"/><label for="listacheck_'.$value["codtema"].'"></label>';
			$html .= '	</td>';
			$html .= '</tr>';
			$cont = $cont + 1;
		}

		print $html;
	}

	public function agregar_docentes(){
		if ($this->input->is_ajax_request()){
			$escuelas = $this->dbasistencias->query("select * from escuela order by codescuela asc")->result_array();

			$this->load->view("controlasistencia/sesiones/add_docente", compact("escuelas"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function docentes($codsesion,$idescuela){
		if ($this->input->is_ajax_request()){

			if ($idescuela==0) {
				$escuela="";
			}else{
				$escuela = "d.codescuela = $idescuela and";
			}

			$lista_docentes = $this->dbasistencias->query("select d.coddocente, d.nombres, e.nombre_escuela as escuela, d.dni, d.celular, d.email from docente as d join escuela as e on e.codescuela = d.codescuela where $escuela d.estado=1")->result_array();

			$activos = $this->dbasistencias->query("select * from detalle_sesion where codsesion=".$codsesion)->result_array();

			$html=""; $cont = 1;
			foreach ($lista_docentes as $key => $value) { 
				$chek = ""; 
              	if(count($activos) > 0){
                   	foreach ($activos as $v) {
                    	if ($v["coddocente"]==$value["coddocente"]) {
                     		$chek = "checked";
                            break;
                		}
      				}
      			}

				$html .= '<tr>';
				$html .= '	<td>'.$cont.'</td>';
				$html .= '	<td>'.$value["nombres"].'</td>';
				$html .= '	<td>'.$value["celular"].'</td>';
				$html .= '	<td>'.$value["email"].'</td>';
				$html .= '	<td>'.$value["escuela"].'</td>';
				$html .= '	<td align="center">';
				$html .= '<input type="checkbox" name="docentes[]" id="docentes_'.$value['coddocente'].'" value="'.$value["coddocente"].'" class="filled-in chk-col-green" '.$chek.'/><label for="docentes_'.$value["coddocente"].'"></label>';
				$html .= '</tr>';
				$cont = $cont + 1;
 			}
 			print $html;
		}else{
			header("Location: ".base_url());
		}
	}

	public function guardar_docentes(){
		if ($this->input->is_ajax_request()){
			if($_POST["codsesion"]!="" ){
				$this->dbasistencias->where("codsesion", $_POST["codsesion"]);
		        $this->dbasistencias->delete("detalle_sesion");

                if (isset($_POST["docentes"]) != "") {
					foreach ($_POST["docentes"] as $key => $value) {
	            		$data = array(
		               		"codsesion" => $_POST["codsesion"],
		               		"coddocente" => $_POST["docentes"][$key],
		               		"fecha" => date("Y-m-d")
		            	);
		            	$insertar = $this->dbasistencias->insert("detalle_sesion", $data);
					}
					if (count($insertar)) {
	            		$estado = 1;
	            	}else{
	            		$estado = 0;
	            	}
				}else{
					$estado = 2;
				}	
	    	}  

			echo $estado;
		}else{
			header("Location: ".base_url());
		}
	}

	function imprimir_lista($idevento,$idsesion){
		$info = $this->dbasistencias->query("SELECT * FROM informacion")->result_array();

      	$this->load->library("Pdf2"); $pdf = new Pdf2(); $pdf->AddPage('L','A4',0);
      	$pdf->pdf_header_asistencia($info[0]["institucion"], $info[0]["oficina"], $info[0]["direccion"], $info[0]["lugar"], $info[0]["nombreanio"]);

        $sesion = $this->dbasistencias->query("select s.codsesion, s.codevento, e.nombre_evento, s.lugar, DATE_FORMAT(s.fecha, '%d/%m/%Y') as fecha, s.horainicio, s.horafin, s.estado from sesiones as s inner join evento as e on e.codevento=s.codevento where s.codsesion=".$idsesion." and s.estado=1")->result_array();

        $fecha = $sesion[0]["fecha"];

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(245,10, utf8_decode("LISTA DE DOCENTES PARA ".$sesion[0]["nombre_evento"]." - ".$sesion[0]["lugar"]),'','','C');
        $pdf->Cell(45,10, utf8_decode("FECHA: ".$fecha),'','','L');
        $pdf->Ln(10);

        $columnas = array("N°","Docente", "Celular","Correo Institucional","Escuela", "Firma");
        $w = array(8,82,20,60,70,35); $pdf->pdf_tabla_head($columnas,$w,10);

        $docentes = $this->dbasistencias->query("select d.coddocente, d.nombres, e.nombre_escuela, d.dni, d.celular, d.email from docente as d join escuela as e on e.codescuela = d.codescuela join detalle_sesion as ds on ds.coddocente=d.coddocente where ds.codsesion=".$idsesion." and d.estado=1")->result_array();

        $pdf->SetFont('Arial','',9);

    	$cont = 1;
        if (count($docentes)) { 
          	foreach ($docentes as $key => $va) {
                $pdf->Cell(8,12,utf8_decode($cont),1,0,'C');
                $pdf->Cell(82,12,utf8_decode($va["nombres"]),1,0,'L');
                $pdf->Cell(20,12,utf8_decode($va["celular"]),1,0,'C');
                $pdf->Cell(60,12,utf8_decode($va["email"]),1,0,'C');
                $pdf->Cell(70,12,utf8_decode($va["nombre_escuela"]),1,0,'C');
                $pdf->Cell(35,12," ",1,0,'C');
                $pdf->Ln(); $cont = $cont + 1;
          	}
        }else{
        	$pdf->Cell(275,8,utf8_decode("Ningun Docente Inscrito en esta sesión"),1,0,'C');
        }

        $pdf->Ln(8); 

      	$pdf->Cell(array_sum($w),0,'','T'); 
      	$pdf->Ln();
      	$pdf->SetTitle("Reporte Control de Asistencia a Capacitaciones"); $pdf->Output();
  	}
}
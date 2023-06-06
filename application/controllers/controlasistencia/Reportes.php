<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Reportes extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);	
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('controlasistencia/reportes/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$eventos = $this->dbasistencias->query("select * from evento where estado = 1")->result_array();

			$this->load->view("controlasistencia/reportes/lista",compact("eventos"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function cargar_docentes($idevento){
		if ($this->input->is_ajax_request()){
			$docentes = $this->dbasistencias->query("select ds.coddocente, d.nombres, d.dni, d.celular, d.email, e.nombre_escuela, COALESCE((select count(a.coddocente) from asistencias as a where a.coddocente= ds.coddocente), 0) as nroasistencias from detalle_sesion as ds join docente as d on d.coddocente=ds.coddocente join escuela as e on e.codescuela=d.codescuela join sesiones as s on s.codsesion = ds.codsesion join evento as ev on ev.codevento=s.codevento where s.codevento=".$idevento)->result();

			echo json_encode($docentes);
		}else{
			header("Location: ".base_url());
		}
	}

	function pdf_detallado($idevento){
        $fecha = date('d-m-Y');

      	$this->load->library("Pdf2"); $pdf = new Pdf2(); $pdf->AddPage();
      	$pdf->pdf_header("ASISTENCIAS DE DOCENTES POR SESIÓN","FECHA",$fecha);

        $evento = $this->dbasistencias->query("select * from evento where codevento=".$idevento." and estado=1")->result_array();

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(0,10, utf8_decode("EVENTO: ".$evento[0]["nombre_evento"]));
        $pdf->Ln(10);
        
        $sesiones = $this->dbasistencias->query("select * from sesiones where codevento=".$idevento." and (estado=1 or estado=2)")->result_array();

        foreach ($sesiones as $key => $value) {
          	
            $pdf->SetFont("Arial","B", 8); 
            $pdf->SetFillColor('224','224','224'); 
            $pdf->Cell(75,5,utf8_decode("SESIÓN: ".$value["nombre_sesion"]),'LTR',0,'L','true');
            $pdf->Cell(35,5,utf8_decode("Lugar: ".$value["lugar"]),'LTR',0,'L','true');
            $pdf->Cell(30,5,utf8_decode("Fecha: ".$value["fecha"]),'LTR',0,'L','true');
            $pdf->Cell(50,5,utf8_decode("Horario: ".$value["horainicio"]." - ".$value["horafin"]),'LTR',0,'L','true');
            $pdf->Ln();

            $columnas = array("N°","Docente", "Celular","Escuela", "Asistió");
            $w = array(8,82,20,65,15); $pdf->pdf_tabla_head($columnas,$w,8);

            $docentes = $this->dbasistencias->query("select ds.codsesion, ds.coddocente, d.nombres, d.dni, d.celular, d.email, e.nombre_escuela, COALESCE((select count(a.coddocente) from asistencias as a where a.coddocente= ds.coddocente and a.codsesion=".$value["codsesion"]."), 0) as estado from detalle_sesion as ds join docente as d on d.coddocente=ds.coddocente join escuela as e on e.codescuela=d.codescuela")->result_array();

        	$pdf->SetFont('Arial','',8);
        	$cont = 1; 
            if (count($docentes)) { 
              	foreach ($docentes as $key => $va) {
	                $pdf->Cell(8,5,utf8_decode($cont),1,0,'C');
	                $pdf->Cell(82,5,utf8_decode($va["nombres"]),1,0,'L');
	                $pdf->Cell(20,5,utf8_decode($va["celular"]),1,0,'C');
	                $pdf->Cell(65,5,utf8_decode($va["nombre_escuela"]),1,0,'C');
	                if ($va["estado"] == 1) {
	                	$pdf->Cell(15,5,"SI",1,0,'C');
	                }else{
	                	$pdf->Cell(15,5,"NO",1,0,'C');
	                }
	                $pdf->Ln(); $cont = $cont + 1;
              	}
            }

            $pdf->Ln(5);    
      	}
      	$pdf->Cell(array_sum($w),0,'','T'); $pdf->Ln();
      	$pdf->SetTitle("Reporte Control de Asistencia a Capacitaciones"); $pdf->Output();
  	}
}
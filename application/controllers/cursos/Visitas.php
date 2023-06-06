<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); 

class Visitas extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->dbdefault = $this->load->database('default', TRUE);
		$this->load->model('Campus_model');
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$escuelas = $this->dbdefault->query("select id, name from mdl_course_categories")->result_array();
			$this->load->view('cursos/visitas/index', compact("escuelas"));
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista($idescuela,$fecinicio,$fecfin){
		if ($this->input->is_ajax_request()){
			$visitas = $this->Campus_model->visitas($idescuela,$fecinicio,$fecfin);

			$this->load->view('cursos/visitas/lista', compact("visitas"));
		}else{
			header("Location: ".base_url()."principal");
		}
	}
	
	function pdf_visitas($fecinicio,$fecfin){
		$estilo = "border-top:1px solid #D5D8DC; font-size: 8px; border-left:1px solid #D5D8DC; border-right:1px solid #D5D8DC;";
		$estilo1 = "border:1px solid #D5D8DC;";

		$html = $this->Campus_model->cabecera_pdf("REPORTE DE VISITAS POR CURSO ",$fecinicio,$fecfin);

		$html .= '<table cellpadding="4" width="100%" style="border:1px solid #D5D8DC;" >';
			$html .= '<tr>';
				$html .= '<th style="width: 25px; '.$estilo.'"><b>N°</b></th>';
				$html .= '<th style="width: 110px; '.$estilo.'"><b>N° USUARIOS</b></th>';
				$html .= '<th style="width: 160px; '.$estilo.'"><b>N° VISITAS</b></th>';
				$html .= '<th style="width: 200px; '.$estilo.'"><b>CURSO</b></th>';
				$html .= '<th style="width: 140px; '.$estilo.'"><b>LINK</b></th>';
				/*$html .= '<th style="width: 60px; '.$estilo.'"><b>SEMESTRE</b></th>';
				$html .= '<th style="width: 50px; '.$estilo.'"><b>ALUMNOS</b></th>';*/
			$html .= '</tr>';

			$visitas = $this->Campus_model->visitas($fecinicio,$fecfin);

			$cont = 0;
			foreach($visitas as $value){
				$cont++;
				$html .= '<tr>';
					$html .= '<td style="'.$estilo.'"> '.$cont.' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["nrousuarios"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["nrovisitas"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["curso"].' </td>';
					$html .= '<td style="'.$estilo.'"> <a target="_new" href="%%WWWROOT%%/course/view.php?id="'.$value["courseid"].'">' .$value["curso"]. '</a> </td>';
					/*$html .= '<td style="'.$estilo.'"> '.$value["fechacreacion"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["estudiantes"].' </td>';*/
				$html .= '</tr>';
			}
		$html .= '</table>';
		
		$pdf = $this->Campus_model->footer_pdf();
		$pdf->writeHTML($html, true, 0, true, 0);
		$nombre_archivo = utf8_decode("Reporte-Visitas-por-Curso-OEAD-UNSM-T-".date('d-m-Y').".pdf");
		$pdf->Output($nombre_archivo, 'I');

		
	}

	function excel_cursoxescuela($idescuela,$fecinicio,$fecfin){
		$this->load->library('Excel'); 

		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('letras');

        $contador = 1;

        // Le aplicamos ancho las columnas //
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'N°');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'Docente');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Docente');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'Curso');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Email');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'Fecha Creación');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'N° Alumnos');

       	if ($idescuela == "todo") {
			$cursos = $this->Campus_model->docenxescuelatodo($fecinicio,$fecfin);
		}else{
			$cursos = $this->Campus_model->docenxescuela($idescuela,$fecinicio,$fecfin);
		}

        $cont = 1;
        //Definimos la data del cuerpo.        
        foreach($cursos as $l){
           //Incrementamos una fila más, para ir a la siguiente.abreviatura
           $contador++;
           //Informacion de las filas de la consulta. 
           $this->excel->getActiveSheet()->setCellValue("A{$contador}", $cont);
           $this->excel->getActiveSheet()->setCellValue("B{$contador}", $l["escuela"]);
           $this->excel->getActiveSheet()->setCellValue("C{$contador}", $l["docente"]);
           $this->excel->getActiveSheet()->setCellValue("D{$contador}", $l["curso"]);
           $this->excel->getActiveSheet()->setCellValue("E{$contador}", $l["email"]);
           $this->excel->getActiveSheet()->setCellValue("F{$contador}", $l["fechacreacion"]);
           $this->excel->getActiveSheet()->setCellValue("G{$contador}", $l["estudiantes"]);
           $cont++;
        } 

        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "Reporte-Cursos-por-Escuela-OEAD-UNSM-T-".date('d-m-Y').".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
	}
}
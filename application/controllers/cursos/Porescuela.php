<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Porescuela extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbdefault = $this->load->database('default', TRUE);
		$this->load->model('Campus_model');
	}

	public function index(){
	    if (isset($_SESSION["usuario"])) {
	      	$this->load->view('cursos/reporxescuelas/index');
	    }else{
	      	header("Location: ".base_url());
	    }
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$escuelas = $this->dbdefault->query("select id, name from mdl_course_categories")->result_array();

			$this->load->view("cursos/reporxescuelas/lista", compact("escuelas"));
		}else{
			header("Location: ".base_url()."principal");
		}
	}
	
	function pdf_cursoxescuela($idescuela,$fecinicio,$fecfin){
		$estilo = "border-top:1px solid #D5D8DC; font-size: 8px; border-left:1px solid #D5D8DC; border-right:1px solid #D5D8DC;";
		$estilo1 = "border:1px solid #D5D8DC;";

		if ($idescuela == 0) {
       	 	$html = $this->Campus_model->cabecera_pdf("REPORTE DE CURSOS DE TODAS LAS ESCUELAS ",$fecinicio,$fecfin);

      	}else{
        	$escuela = $this->dbdefault->query("select abreviatura from mdl_course_categories where id=".$idescuela)->result_array();

        	$html = $this->Campus_model->cabecera_pdf("REPORTE DE CURSOS POR DE LA ESCUELA: ".$escuela[0]["abreviatura"],$fecinicio,$fecfin);
      	}

		$html .= '<table cellpadding="4" width="100%" style="border:1px solid #D5D8DC;" >';
			$html .= '<tr>';
				$html .= '<th style="width: 25px; '.$estilo.'"><b>N°</b></th>';
				if ($idescuela == 0) {
					$html .= '<th style="width: 80px; '.$estilo.'"><b>ESCUELA</b></th>';
					$html .= '<th style="width: 170px; '.$estilo.'"><b>DOCENTE</b></th>';
					$html .= '<th style="width: 240px; '.$estilo.'"><b>CURSO</b></th>';
					$html .= '<th style="width: 50px; '.$estilo.'"><b>FECHA</b></th>';
					$html .= '<th style="width: 30px; '.$estilo.'"><b>ALU.</b></th>';
					$html .= '<th style="width: 30px; '.$estilo.'"><b>VIS.</b></th>';
					$html .= '<th style="width: 30px; '.$estilo.'"><b>ACT.</b></th>';
				}else{
					$html .= '<th style="width: 180px; '.$estilo.'"><b>DOCENTE</b></th>';
					$html .= '<th style="width: 250px; '.$estilo.'"><b>CURSO</b></th>';
					$html .= '<th style="width: 55px; '.$estilo.'"><b>FECHA</b></th>';
					$html .= '<th style="width: 40px; '.$estilo.'"><b>ALUM</b></th>';
					$html .= '<th style="width: 40px; '.$estilo.'"><b>VISIT.</b></th>';
					$html .= '<th style="width: 40px; '.$estilo.'"><b>ACTIV.</b></th>';
				}			
					
			$html .= '</tr>';

			$cursos = $this->Campus_model->docenxescuela($idescuela,$fecinicio,$fecfin);

			$codescuela = $idescuela;

			$cont = 0;
			foreach($cursos as $value){
				$cont++;
				$html .= '<tr>';
					$html .= '<td style="'.$estilo.'"> '.$cont.' </td>';
					if ($codescuela == 0) {
						$html .= '<td style="'.$estilo.'"> '.$value["escuela"].' </td>';
					}
					$html .= '<td style="'.$estilo.'"> '.$value["docente"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["curso"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["fechacreacion"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["estudiantes"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["visitas"].' </td>';
					$html .= '<td style="'.$estilo.'"> '.$value["actividades"].' </td>';
				$html .= '</tr>';
			}
		$html .= '</table>';
		
		$pdf = $this->Campus_model->footer_pdf();
		$pdf->writeHTML($html, true, 0, true, 0);
		$nombre_archivo = utf8_decode("Reporte-Cursos-por-Escuela-OEAD-UNSM-T-".date('d-m-Y').".pdf");
		$pdf->Output($nombre_archivo, 'I');
	}

  	function pdf_actividad_cursos($idescuela,$fecinicio,$fecfin){
      	if ($idescuela == 0) {
       	 	$escuela[0] = array('abreviatura'=>'TODAS');
       	 	$esc_id = "";
      	}else{
        	$escuela = $this->dbdefault->query("select abreviatura from mdl_course_categories where id=".$idescuela)->result_array();
        	$esc_id = " and cc.id = $idescuela ";
      	}

      	$this->load->library("Pdf2"); $pdf = new Pdf2(); $pdf->AddPage();
      	$pdf->pdf_header("ACTIVIDAD DE DOCENTES POR CURSO","ESCUELA", $escuela[0]["abreviatura"]);

      	if ($idescuela == 0) {
          	$docentes = $this->dbdefault->query("SELECT u.id as coddocente, CONCAT(u.firstname,' ',u.lastname) AS docente 
          		FROM mdl_user u 
          		INNER JOIN mdl_role_assignments ra ON ra.userid = u.id
            	INNER JOIN mdl_context ct ON ct.id = ra.contextid
            	INNER JOIN mdl_course c ON c.id = ct.instanceid
            	INNER JOIN mdl_course_categories as cc on cc.id=c.category
            	INNER JOIN mdl_role r ON r.id = ra.roleid
            	where r.id = 3 and FROM_UNIXTIME(c.timecreated) >= '".$fecinicio."' and FROM_UNIXTIME(c.timecreated) <= '".$fecfin."' group by coddocente order by coddocente")->result_array();
      	}else{
          	$docentes = $this->dbdefault->query("SELECT u.id as coddocente, CONCAT(u.firstname,' ',u.lastname) AS docente
          		FROM mdl_user u
          		INNER JOIN mdl_role_assignments ra ON ra.userid = u.id
          		INNER JOIN mdl_context ct ON ct.id = ra.contextid
          		INNER JOIN mdl_course c ON c.id = ct.instanceid
          		INNER JOIN mdl_course_categories as cc on cc.id=c.category
          		INNER JOIN mdl_role r ON r.id = ra.roleid
          		where cc.id =".$idescuela." AND r.id = 3 and FROM_UNIXTIME(c.timecreated) >= '".$fecinicio."' and FROM_UNIXTIME(c.timecreated) <= '".$fecfin."' group by u.id order by u.id")->result_array();
      	}

      	$cont = 1;
      	foreach ($docentes as $key => $val) {
	        $pdf->SetFont('Arial','B',8);
	        $pdf->Cell(0,10, utf8_decode($cont.". Docente: ".$val["docente"]));
	        $pdf->Ln(10);
        
	        $cursos = $this->dbdefault->query("SELECT u.id, CONCAT(u.firstname,' ',u.lastname) AS docente, c.id as codcurso, c.fullname as curso, cc.id, cc.name as escuela, u.email as email, FROM_UNIXTIME(c.timecreated, '%d-%m-%Y') AS fechacreacion
	            FROM mdl_user u
	            INNER JOIN mdl_role_assignments ra ON ra.userid = u.id
	            INNER JOIN mdl_context ct ON ct.id = ra.contextid
	            INNER JOIN mdl_course c ON c.id = ct.instanceid
	            INNER JOIN mdl_course_categories as cc on cc.id=c.category
	            INNER JOIN mdl_role r ON r.id = ra.roleid
	            where r.id = 3 and u.id=".$val["coddocente"]." $esc_id and FROM_UNIXTIME(c.timecreated) >= '".$fecinicio."' and FROM_UNIXTIME(c.timecreated) <= '".$fecfin."' order by u.id")->result_array();

        	foreach ($cursos as $key => $value) {
	          	$estudiantes = $this->dbdefault->query("SELECT count(*) as alumnos FROM mdl_course
		            INNER JOIN mdl_context ON mdl_context.instanceid = mdl_course.id
		            INNER JOIN mdl_role_assignments ON mdl_context.id = mdl_role_assignments.contextid
		            INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid
		            INNER JOIN mdl_user ON mdl_user.id = mdl_role_assignments.userid
		            WHERE mdl_role.id = 5 AND mdl_course.id = ".$value["codcurso"])->result_array();

          		$visitas = $this->dbdefault->query("SELECT COUNT(l.courseid) AS nrovisitas FROM mdl_logstore_standard_log AS l JOIN mdl_course AS c ON c.id = l.courseid WHERE l.courseid = ".$value["codcurso"]." AND FROM_UNIXTIME(l.timecreated) >= '".$fecinicio."' AND FROM_UNIXTIME(l.timecreated) <= '".$fecfin."'")->result_array();

	          	$cursos[$key]["estudiantes"] = $estudiantes[0]["alumnos"];
	          	$cursos[$key]["visitas"] = $visitas[0]["nrovisitas"];
        	}
        
	        foreach ($cursos as $key => $value) {
	            $pdf->SetFont("Arial","B", 8); $activ = 0;
	            $pdf->SetFillColor('224','224','224'); 
	            $pdf->Cell(140,5,utf8_decode("CURSO: ".$value["curso"]),'LTR',0,'L','true');
	            $pdf->Cell(50,5,utf8_decode("Fecha Creación: ".$value["fechacreacion"]),'LTR',0,'L','true');
	            $pdf->Ln();
	            $pdf->Cell(110,5,utf8_decode("Escuela: ".$value["escuela"]),'LTR',0,'L','true');
	            $pdf->Cell(30,5,utf8_decode("N° T. Visitas: ".$value["visitas"]),'LTR',0,'L','true');
	            $pdf->Cell(50,5,utf8_decode("N° Alum. Matriculados: ".$value["estudiantes"]),'LTR',0,'L','true');
	            $pdf->Ln();

	            $columnas = array("Tipo Actividad","Actividad", "Fecha Creación");
	            $w = array(40,120,30); $pdf->pdf_tabla_head($columnas,$w,8);

	            $tareas = $this->dbdefault->query("SELECT 'Tarea' as tipoactividad, name as actividad, FROM_UNIXTIME(allowsubmissionsfromdate, '%d-%m-%Y') as fecha FROM mdl_assign WHERE course =".$value["codcurso"])->result_array();
	            if (count($tareas)) {
	              	foreach ($tareas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $libros = $this->dbdefault->query("SELECT 'Libro' as tipoactividad, name as actividad, FROM_UNIXTIME(timecreated, '%d-%m-%Y') as fecha FROM mdl_book WHERE course=".$value["codcurso"])->result_array();
	            if (count($libros)) {
	              	foreach ($libros as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $chats = $this->dbdefault->query("SELECT 'Chat' as tipoactividad, name as actividad, FROM_UNIXTIME(chattime, '%d-%m-%Y') as fecha FROM mdl_chat WHERE course=".$value["codcurso"])->result_array();
	            if (count($chats)) {
	              	foreach ($chats as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $consultas = $this->dbdefault->query("SELECT 'Consulta' as tipoactividad, name as actividad, FROM_UNIXTIME(timeopen, '%d-%m-%Y') as fecha FROM mdl_choice WHERE course=".$value["codcurso"])->result_array();
	            if (count($consultas)) {
		            foreach ($consultas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $basedatos = $this->dbdefault->query("SELECT 'Base de datos' as tipoactividad, name as actividad, FROM_UNIXTIME(timeopen, '%d-%m-%Y') as fecha FROM mdl_choice WHERE course=".$value["codcurso"])->result_array();
	            if (count($basedatos)) {
	              	foreach ($basedatos as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $encuespredefinidas = $this->dbdefault->query("SELECT 'Encuesta predefinida' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_feedback WHERE course=".$value["codcurso"])->result_array();
	            if (count($encuespredefinidas)) {
	              	foreach ($encuespredefinidas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $carpetas = $this->dbdefault->query("SELECT 'Carpeta' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_folder WHERE course=".$value["codcurso"])->result_array();
	            if (count($carpetas)) {
	              	foreach ($carpetas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $foros = $this->dbdefault->query("SELECT 'Foro' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_forum WHERE course=".$value["codcurso"]." and type!='news'")->result_array();
	            if (count($foros)) {
	              	foreach ($foros as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $glosarios = $this->dbdefault->query("SELECT 'Glosario' as tipoactividad, name as actividad, FROM_UNIXTIME(timecreated, '%d-%m-%Y') as fecha FROM mdl_glossary WHERE course=".$value["codcurso"])->result_array();
	            if (count($glosarios)) {
	              	foreach ($glosarios as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $etiquetas = $this->dbdefault->query("SELECT 'Etiqueta' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_label WHERE course=".$value["codcurso"])->result_array();
	            if (count($etiquetas)) {
	              	foreach ($etiquetas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $lecciones = $this->dbdefault->query("SELECT 'Lección' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_lesson WHERE course=".$value["codcurso"])->result_array();
	            if (count($lecciones)) {
	              	foreach ($lecciones as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $herramientas = $this->dbdefault->query("SELECT 'Herramienta Externa' as tipoactividad, name as actividad, FROM_UNIXTIME(timecreated, '%d-%m-%Y') as fecha FROM mdl_lti WHERE course=".$value["codcurso"])->result_array();
	            if (count($herramientas)) {
	              	foreach ($herramientas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $pagweb = $this->dbdefault->query("SELECT 'Pág. Web' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_page WHERE course=".$value["codcurso"])->result_array();
	            if (count($pagweb)) {
	              	foreach ($pagweb as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $cuestionarios = $this->dbdefault->query("SELECT 'Cuestionario' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_quiz WHERE course=".$value["codcurso"])->result_array();
	            if (count($cuestionarios)) {
	              	foreach ($cuestionarios as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $recursos = $this->dbdefault->query("SELECT 'Recurso' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_resource WHERE course=".$value["codcurso"])->result_array();
	            if (count($recursos)) {
	              	foreach ($recursos as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $scorm = $this->dbdefault->query("SELECT 'Scorm' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_scorm WHERE course=".$value["codcurso"])->result_array();
	            if (count($scorm)) {
	              	foreach ($scorm as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $encuestas = $this->dbdefault->query("SELECT 'Encuesta' as tipoactividad, name as actividad, FROM_UNIXTIME(timecreated, '%d-%m-%Y') as fecha FROM mdl_survey WHERE course=".$value["codcurso"])->result_array();
	            if (count($encuestas)) {
	              	foreach ($encuestas as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $url = $this->dbdefault->query("SELECT 'Url' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_url WHERE course=".$value["codcurso"])->result_array();
	            if (count($url)) {
	              	foreach ($url as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $wiki = $this->dbdefault->query("SELECT 'Wiki' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_wiki WHERE course=".$value["codcurso"])->result_array();
	            if (count($wiki)) {
	              	foreach ($wiki as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $taller = $this->dbdefault->query("SELECT 'Taller' as tipoactividad, name as actividad, FROM_UNIXTIME(timemodified, '%d-%m-%Y') as fecha FROM mdl_workshop WHERE course=".$value["codcurso"])->result_array();
	            if (count($taller)) {
	              	foreach ($taller as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            $videoconferencia = $this->dbdefault->query("SELECT 'Videoconferencia' as tipoactividad, name as actividad, FROM_UNIXTIME(timecreated, '%d-%m-%Y') as fecha FROM mdl_bigbluebuttonbn WHERE course=".$value["codcurso"])->result_array();
	            if (count($videoconferencia)) {
	              	foreach ($videoconferencia as $key => $va) {
		                $pdf->Cell(40,5,utf8_decode($va["tipoactividad"]),1,0,'C');
		                $pdf->Cell(120,5,utf8_decode($va["actividad"]),1,0,'L');
		                $pdf->Cell(30,5,utf8_decode($va["fecha"]),1,0,'C');
		                $pdf->Ln(); $activ = 1;
	              	}
	            }

	            if ($activ == 0) {
		            $pdf->SetTextColor(255, 1, 12);
		            $pdf->Cell(190,5,'Ninguna Actividad para mostrar',1,0,'C');
		            $pdf->Ln();
		            $pdf->SetTextColor(0, 0, 0);
	            }

	            $pdf->Ln(5);
	        }     
	        $cont = $cont + 1;
      	}
      	$pdf->Cell(array_sum($w),0,'','T'); $pdf->Ln();
      	$pdf->SetTitle("OEAD - Reporte Actividad - Cursos"); $pdf->Output();
  	}

	public function reporte_excel($idescuela,$fecinicio,$fecfin){
      	if ($idescuela == 0) {
          	$parametro[0] = array('codescuela'=> 0);
      	}else{
      		$escuela = $this->dbdefault->query("select abreviatura from mdl_course_categories where id=".$idescuela)->result_array();

      		$parametro[0] = array('codescuela'=>$idescuela, 'escuela'=>$escuela[0]["abreviatura"]);
      	}

		$cursos = $this->Campus_model->docenxescuela($idescuela,$fecinicio,$fecfin);

		$this->load->view("cursos/reporxescuelas/reporte",compact("cursos","parametro"));
	}
}
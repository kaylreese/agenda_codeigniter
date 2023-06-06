<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Campus_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	// Configuracion Base del PDF //
	function cabecera_pdf($titulo,$fecinicio,$fecfin){
		$html = '<h4 align="center"> OFICINA DE EDUCACIÓN A DISTANCIA - UNSM-T</h4>';
		$html .= '<h6 align="center"> Av. Universitaria N° 334 - Ciudad Universitaria - MORALES</h6>';

		$html .= '<h5 align="center"> FECHA | DESDE '.$fecinicio.' - HASTA '.$fecfin.' </h5>';

		$html .= '<h5 align="center">'.$titulo.'</h2> <h5>';
		return $html;
	}

	function footer_pdf(){
		$this->load->library('Pdf');
		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('oead@unsm.edu.pe');
		$pdf->SetTitle('REPORTE OEAD - UNSM');
		$pdf->SetSubject('CAMPUS VIRTUAL');
		$pdf->SetKeywords('REPORTE,CAMPUS VIRTUAL');

		$subtitulobebe = "REPORTE CAMPUS VIRTUAL";
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->setFontSubsetting(true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->AddPage("A");

		return $pdf;
	}

	function docenxescuela($idescuela,$fecinicio,$fecfin){
		if ($idescuela == 0) {
			$codescuela = "";
			$escuela ="cc.abreviatura as escuela,";
		}else{
			$codescuela = "cc.id =$idescuela and";
			$escuela ="";
		}
		$cursos = $this->dbdefault->query("SELECT cc.id, c.id as codcurso, $escuela CONCAT(u.firstname,' ',u.lastname) AS docente, c.fullname as curso, u.email as email, FROM_UNIXTIME(c.timecreated, '%d-%m-%y') AS fechacreacion
				FROM mdl_user u
				INNER JOIN mdl_role_assignments ra ON ra.userid = u.id
				INNER JOIN mdl_context ct ON ct.id = ra.contextid
				INNER JOIN mdl_course c ON c.id = ct.instanceid
				INNER JOIN mdl_course_categories as cc on cc.id=c.category
				INNER JOIN mdl_role r ON r.id = ra.roleid
				where $codescuela r.id = 3 and FROM_UNIXTIME(c.timecreated) >= '".$fecinicio."' and FROM_UNIXTIME(c.timecreated) <= '".$fecfin."' order by cc.id")->result_array();

			foreach ($cursos as $key => $value) {
				$actividades = 0;

				$estudiantes = $this->dbdefault->query("SELECT count(*) as alumnos FROM mdl_course
					INNER JOIN mdl_context ON mdl_context.instanceid = mdl_course.id
					INNER JOIN mdl_role_assignments ON mdl_context.id = mdl_role_assignments.contextid
					INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid
					INNER JOIN mdl_user ON mdl_user.id = mdl_role_assignments.userid
					WHERE mdl_role.id = 5 AND mdl_course.id = ".$value["codcurso"])->result_array();

				$visitas = $this->dbdefault->query("SELECT COUNT(l.courseid) AS nrovisitas FROM mdl_logstore_standard_log AS l JOIN mdl_course AS c ON c.id = l.courseid WHERE l.courseid = ".$value["codcurso"]." AND FROM_UNIXTIME(l.timecreated) >= '".$fecinicio."' AND FROM_UNIXTIME(l.timecreated) <= '".$fecfin."'")->result_array();

				$tareas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_assign WHERE course =".$value["codcurso"])->result_array();

	            $libros = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_book WHERE course=".$value["codcurso"])->result_array();

	            $chats = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_chat WHERE course=".$value["codcurso"])->result_array();

	            $consultas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_choice WHERE course=".$value["codcurso"])->result_array();

	            $basedatos = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_choice WHERE course=".$value["codcurso"])->result_array();

	            $encuespredefinidas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_feedback WHERE course=".$value["codcurso"])->result_array();

	            $carpetas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_folder WHERE course=".$value["codcurso"])->result_array();

	            $foros = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_forum WHERE course=".$value["codcurso"]." and type!='news'")->result_array();

	            $glosarios = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_glossary WHERE course=".$value["codcurso"])->result_array();

	            $etiquetas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_label WHERE course=".$value["codcurso"])->result_array();

	            $lecciones = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_lesson WHERE course=".$value["codcurso"])->result_array();

	            $herramientas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_lti WHERE course=".$value["codcurso"])->result_array();

	            $pagweb = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_page WHERE course=".$value["codcurso"])->result_array();

	            $cuestionarios = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_quiz WHERE course=".$value["codcurso"])->result_array();

	            $recursos = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_resource WHERE course=".$value["codcurso"])->result_array();

	            $scorm = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_scorm WHERE course=".$value["codcurso"])->result_array();

	            $encuestas = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_survey WHERE course=".$value["codcurso"])->result_array();

	            $url = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_url WHERE course=".$value["codcurso"])->result_array();

	            $wiki = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_wiki WHERE course=".$value["codcurso"])->result_array();

	            $taller = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_workshop WHERE course=".$value["codcurso"])->result_array();

	            $videoconferencia = $this->dbdefault->query("SELECT count(*) as cantidad FROM mdl_bigbluebuttonbn WHERE course=".$value["codcurso"])->result_array();

	            $actividades = $tareas[0]["cantidad"]+$libros[0]["cantidad"]+$chats[0]["cantidad"]+$consultas[0]["cantidad"]+$basedatos[0]["cantidad"]+$encuespredefinidas[0]["cantidad"]+$carpetas[0]["cantidad"]+$foros[0]["cantidad"]+$glosarios[0]["cantidad"]+$etiquetas[0]["cantidad"]+$lecciones[0]["cantidad"]+$herramientas[0]["cantidad"]+$pagweb[0]["cantidad"]+$cuestionarios[0]["cantidad"]+$recursos[0]["cantidad"]+$scorm[0]["cantidad"]+$encuestas[0]["cantidad"]+$url[0]["cantidad"]+$wiki[0]["cantidad"]+$taller[0]["cantidad"]+$videoconferencia[0]["cantidad"];
				

				$cursos[$key]["estudiantes"] = $estudiantes[0]["alumnos"];
				$cursos[$key]["visitas"] = $visitas[0]["nrovisitas"];
				$cursos[$key]["actividades"] = $actividades;
			}
		return $cursos;
	}

	function visitas($idescuela,$fecinicio,$fecfin){
		if ($idescuela == 0) {
			$categoria = "";
		}else{
			$categoria = "cc.id = $idescuela AND";
		}

		$visitas = $this->dbdefault->query("SELECT l.courseid, c.fullname AS curso, COUNT(DISTINCT userid) AS nrousuarios, COUNT(l.courseid) AS nrovisitas, cc.name as escuela FROM mdl_logstore_standard_log AS l JOIN mdl_course AS c ON c.id = l.courseid JOIN mdl_course_categories as cc on cc.id=c.category WHERE l.courseid > 0 AND $categoria FROM_UNIXTIME(c.timecreated) >= '".$fecinicio."' AND FROM_UNIXTIME(c.timecreated) <= '".$fecfin."' GROUP BY l.courseid ORDER BY nrovisitas desc")->result_array();
		return $visitas;
	}

	function usuarios_portipo($tipouser){
		$usuarios = $this->dbdefault->query("SELECT count(u.id) as usuarios
            FROM mdl_user u
            INNER JOIN mdl_role_assignments ra ON ra.userid = u.id
            INNER JOIN mdl_context ct ON ct.id = ra.contextid
            INNER JOIN mdl_course c ON c.id = ct.instanceid
            INNER JOIN mdl_course_categories as cc on cc.id=c.category
            INNER JOIN mdl_role r ON r.id = ra.roleid
            where r.id =".$tipouser." group by u.id")->result_array();

		return $usuarios;
	}
} ?>
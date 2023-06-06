<?php defined('BASEPATH') OR exit('No direct script access allowed');
session_start();

class Indicadores extends CI_Controller {
	function __construct(){
		parent::__construct();
    	$this->dbdefault = $this->load->database('default', TRUE);
    	$this->dbasistencias = $this->load->database('control_asistencia', TRUE);
    	$this->load->model('Campus_model');	
	}

	public function index(){
        if (isset($_SESSION["usuario"])) {
            $cursos = $this->dbdefault->query("select count(*) as nrocursos from mdl_course")->result_array();
            $usuarios = $this->dbdefault->query("select count(*) as nrousuarios from mdl_user where deleted = 0")->result_array();  
            $doc = $this->Campus_model->usuarios_portipo(3);
            $docentes = (int)count($doc);
            $alumnos = (int)$usuarios[0]["nrousuarios"] - $docentes;

            $escuelaschart = $this->dbdefault->query("select cc.id, cc.abreviatura as escuela, count(c.category) as cantidad from mdl_course c inner join mdl_course_categories as cc on cc.id=c.category group by cc.id order by cantidad desc")->result();

            $usuarioschart = $this->dbdefault->query("select DATE_FORMAT(FROM_UNIXTIME(timecreated), '%Y') AS anio, count(id) as cantidad from mdl_user where FROM_UNIXTIME(timecreated) >='2000-01-01' group by anio order by anio asc")->result();
            $visitaschart = $this->dbdefault->query("select DATE_FORMAT(FROM_UNIXTIME(timecreated), '%Y') AS anio, count(id) as cantidad from mdl_logstore_standard_log group by anio order by anio asc")->result();

            $tareas = $this->dbdefault->query("select 'Tarea' as tipoactividad, count(*) as cantidad from mdl_assign")->result_array();

            $libros = $this->dbdefault->query("select 'Libro' as tipoactividad, count(*) as cantidad from mdl_book")->result_array();

            $chats = $this->dbdefault->query("select 'Chat' as tipoactividad, count(*) as cantidad from mdl_chat")->result_array();

            $consultas = $this->dbdefault->query("select 'Consulta' as tipoactividad, count(*) as cantidad from mdl_choice")->result_array();

            $basedatos = $this->dbdefault->query("select 'Base de datos' as tipoactividad, count(*) as cantidad from mdl_data")->result_array();

            $encuespredefinidas = $this->dbdefault->query("select 'Encue. predef.' as tipoactividad, count(*) as cantidad from mdl_feedback")->result_array();

            $carpetas = $this->dbdefault->query("select 'Carpeta' as tipoactividad, count(*) as cantidad from mdl_folder")->result_array();

            $foros = $this->dbdefault->query("select 'Foro' as tipoactividad, count(*) as cantidad from mdl_forum")->result_array();

            $glosarios = $this->dbdefault->query("select 'Glosario' as tipoactividad, count(*) as cantidad from mdl_glossary")->result_array();

            $etiquetas = $this->dbdefault->query("select 'Etiqueta' as tipoactividad, count(*) as cantidad from mdl_label")->result_array();

            $lecciones = $this->dbdefault->query("select 'Lección' as tipoactividad, count(*) as cantidad from mdl_lesson")->result_array();

            $herramientas = $this->dbdefault->query("select 'Herram. Externa' as tipoactividad, count(*) as cantidad from mdl_lti")->result_array();

            $pagweb = $this->dbdefault->query("select 'Pág. Web' as tipoactividad, count(*) as cantidad from mdl_page")->result_array();

            $cuestionarios = $this->dbdefault->query("select 'Cuestionario' as tipoactividad, count(*) as cantidad from mdl_quiz")->result_array();

            $recursos = $this->dbdefault->query("select 'Recurso' as tipoactividad, count(*) as cantidad from mdl_resource")->result_array();

            $scorm = $this->dbdefault->query("select 'Scorm' as tipoactividad, count(*) as cantidad from mdl_scorm")->result_array();

            $encuestas = $this->dbdefault->query("select 'Encuesta' as tipoactividad, count(*) as cantidad from mdl_survey")->result_array();

            $url = $this->dbdefault->query("select 'Url' as tipoactividad, count(*) as cantidad from mdl_url")->result_array();

            $wiki = $this->dbdefault->query("select 'Wiki' as tipoactividad, count(*) as cantidad from mdl_wiki")->result_array();

            $taller = $this->dbdefault->query("select 'Taller' as tipoactividad, count(*) as cantidad from mdl_workshop")->result_array();

            $videoconferencia = $this->dbdefault->query("select 'Videoconf.' as tipoactividad, count(*) as cantidad from mdl_bigbluebuttonbn")->result_array();

            $tipoeventoschart = $this->dbasistencias->query("select te.descripcion as tipoevento, count(e.codtipoevento) as cantidad from tipoevento as te inner join evento as e where te.codtipoevento = e.codtipoevento and te.estado=1 and e.estado=1 group by te.descripcion")->result();

            $compasistencias = $this->dbasistencias->query("select s.codevento, te.descripcion, (select count(*) from detalle_sesion as ds where ds.codsesion= s.codsesion) as nrodocentes, (select count(*) from asistencias where codsesion= s.codsesion and estado = 1) as asistieron from sesiones as s join evento as e on e.codevento=s.codevento join tipoevento as te on te.codtipoevento = e.codtipoevento where s.estado = 1 or s.estado = 2 group by codevento")->result_array();

            foreach ($compasistencias as $key => $value) {
                $compasistencias[$key]["faltaron"] = (int)($value["nrodocentes"] - $value["asistieron"]);
            }

            $docxescuela = $this->dbasistencias->query("select d.codescuela, e.abreviatura as escuela, count(d.coddocente) as cantidad from docente as d join escuela as e on e.codescuela = d.codescuela group by d.codescuela order by d.codescuela")->result_array();

            $this->load->view("sistemaweb/indicadores", compact("cursos","usuarios","docentes","alumnos","escuelaschart","usuarioschart","visitaschart","tareas","libros","chats","consultas","basedatos","encuespredefinidas","carpetas","foros","glosarios","etiquetas","lecciones","herramientas","pagweb","cuestionarios","recursos","scorm","encuestas","url","wiki","taller","videoconferencia", "tipoeventoschart","compasistencias","docxescuela"));
        }else{
            header("Location: ".base_url());
        }
	}

}

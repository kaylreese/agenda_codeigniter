<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class Migrardb extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('excel');
    	$this->dbactual = $this->load->database('default', TRUE);
    	$this->dbnueva = $this->load->database('moodle', TRUE);
	}

	public function index(){
		if (isset($_SESSION["usuario"])) {
			$this->load->view('migrardb/index');
		}else{
			header("Location: ".base_url());
		}
	}

	public function lista(){
		if ($this->input->is_ajax_request()){
			$this->load->view("migrardb/lista");
		}else{
			header("Location: ".base_url()."principal");
		}
	}

	public function categorias(){
		$categorias = $this->dbactual->query("select * from mdl_course_categories")->result_array();

		foreach ($categorias as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"name" => $value["name"],
            	"abreviatura" => $value["abreviatura"],
            	"description" => $value["description"],
            	"descriptionformat" => 1,
            	"sortorder" => $value["sortorder"],
            	"coursecount" => $value["coursecount"],
            	"timemodified" => $value["timemodified"],
            	"path" => $value["path"]
			);
			$estado = $this->dbnueva->insert("mdl_course_categories", $data);
			$incremento = $value["id"];
		}

		$this->dbnueva->query("ALTER TABLE mdl_course_categories AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function cursos(){
		$cursos = $this->dbactual->query("select * from mdl_course where id>1")->result_array();

		foreach ($cursos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"category" => $value["category"],
            	"sortorder" => $value["sortorder"],
            	"fullname" => $value["fullname"],
            	"shortname" => $value["shortname"],
            	"idnumber" => $value["idnumber"],
            	"summary" => $value["summary"],
            	"summaryformat" => $value["summaryformat"],
            	"format" => $value["format"],
            	"showgrades" => $value["showgrades"],
            	"newsitems" => $value["newsitems"],
            	"startdate" => $value["startdate"],
            	"enddate" => $value["enddate"],
            	"groupmode" => $value["groupmode"],
            	"groupmodeforce" => $value["groupmodeforce"],
            	"lang" => $value["lang"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"groupmodeforce" => $value["groupmodeforce"],
            	"cacherev" => $value["cacherev"]
			);
			$estado = $this->dbnueva->insert("mdl_course", $data);
			$incremento = $value["id"];
		}

		$this->dbnueva->query("ALTER TABLE mdl_course AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function secciones(){
		$secciones = $this->dbactual->query("select * from mdl_course_sections")->result_array();

		foreach ($secciones as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"section" => $value["section"],
            	"name" => $value["name"],
            	"summary" => $value["summary"],
            	"summaryformat" => $value["summaryformat"],
            	"sequence" => $value["sequence"],
            	"visible" => $value["visible"],
            	"availability" => $value["availability"]
			);
			$estado = $this->dbnueva->insert("mdl_course_sections", $data);
			$incremento = $value["id"];
		}

		$this->dbnueva->query("ALTER TABLE mdl_course_sections AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function contexto(){
		//ELIMINAR REGISTROS
		/*$eliminar = $this->dbactual->query("delete from mdl_context where id>=1")->result_array();*/

		$contexto = $this->dbactual->query("select * from mdl_context where id>40500")->result_array();

		foreach ($contexto as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"contextlevel" => $value["contextlevel"],
            	"instanceid" => $value["instanceid"],
            	"path" => $value["path"],
            	"depth" => $value["depth"]
			);
			$estado = $this->dbnueva->insert("mdl_context", $data);
			$incremento = $value["id"];
		}

		$this->dbnueva->query("ALTER TABLE mdl_context AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function modulos(){
		$modulos = $this->dbactual->query("select * from mdl_course_modules")->result_array();

		foreach ($modulos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"module" => $value["module"],
            	"instance" => $value["instance"],
            	"section" => $value["section"],
            	"added" => $value["added"],
            	"visible" => $value["visible"],
            	"visibleold" => $value["visibleold"]
			);
			$estado = $this->dbnueva->insert("mdl_course_modules", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_course_modules AUTO_INCREMENT ".$incremento);

		$modulos_comp = $this->dbactual->query("select * from mdl_course_modules_completion")->result_array();

		foreach ($modulos_comp as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"coursemoduleid" => $value["coursemoduleid"],
            	"userid" => $value["userid"],
            	"completionstate" => $value["completionstate"],
            	"viewed" => $value["viewed"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_course_modules_completion", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_course_modules_completion AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	//ELIMINAR USUARIOS DEFINITIVAMENTE DE LA DB
	public function eliminar_usuarios(){
		$usuarios = $this->dbactual->query("select * from mdl_user where id>2 and deleted=1")->result_array();

		foreach ($usuarios as $key => $value) {
			$estado = $this->dbactual->delete('mdl_user', array('id' => $value["id"]));
		}

		echo $estado;
	}

	public function usuarios1(){
		$usuarios = $this->dbactual->query("select * from mdl_user where id>2")->result_array();

		//echo "<pre>";
		//print_r($usuarios); exit();

		foreach ($usuarios as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"auth" => $value["auth"],
            	"confirmed" => $value["confirmed"],
            	"deleted" => $value["deleted"],
            	"mnethostid" => $value["mnethostid"],
            	"username" => $value["username"],
            	"password" => $value["password"],
            	"firstname" => $value["firstname"],
            	"lastname" => $value["lastname"],
            	"email" => $value["email"],
            	"city" => $value["city"],
            	"country" => 'PE',
            	"lang" => $value["lang"],
            	"firstaccess" => $value["firstaccess"],
            	"lastaccess" => $value["lastaccess"],
            	"lastlogin" => $value["lastlogin"],
            	"currentlogin" => $value["currentlogin"],
            	"lastip" => $value["lastip"],
            	"picture" => $value["picture"],
            	"url" => $value["url"],
            	"description" => $value["description"],
            	"descriptionformat" => $value["descriptionformat"],
            	"mailformat" => $value["mailformat"],
            	"maildisplay" => $value["maildisplay"],
            	"autosubscribe" => $value["autosubscribe"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_user", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_user AUTO_INCREMENT ".$incremento);

		$this->dbactual->delete('mdl_user', array('id' => 6001));
		$accesos = $this->dbactual->query("select * from mdl_user_lastaccess")->result_array();
		foreach ($accesos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"courseid" => $value["courseid"],
            	"timeaccess" => $value["timeaccess"]
			);
			$estado = $this->dbnueva->insert("mdl_user_lastaccess", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_user_lastaccess AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function usuarios2(){
		$usuarios = $this->dbactual->query("select * from mdl_user_enrolments where id>=13000 and id<=13500")->result_array();

		foreach ($usuarios as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"enrolid" => $value["enrolid"],
            	"userid" => $value["userid"],
            	"modifierid" => $value["modifierid"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_user_enrolments", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_user_enrolments AUTO_INCREMENT ".$incremento); 


		/*$password_resets = $this->dbactual->query("select * from mdl_user_password_resets")->result_array();
		foreach ($password_resets as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"timerequested" => $value["timerequested"],
            	"timererequested" => $value["timererequested"],
            	"token" => $value["token"]
			);
			$estado = $this->dbnueva->insert("mdl_user_password_resets", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_user_password_resets AUTO_INCREMENT ".$incremento);*/

		echo $estado;
	}


	public function usuarios3(){
		$preferences = $this->dbactual->query("select * from mdl_user_preferences where id>16 and id<=1000")->result_array();

		foreach ($preferences as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"name" => $value["name"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_user_preferences", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_user_preferences AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function asignaciones_bloque1(){
		$asignaciones = $this->dbactual->query("select * from mdl_assign")->result_array();

		foreach ($asignaciones as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"alwaysshowdescription" => $value["alwaysshowdescription"],
            	"submissiondrafts" => $value["submissiondrafts"],
            	"sendnotifications" => $value["sendnotifications"],
            	"duedate" => $value["duedate"],
            	"allowsubmissionsfromdate" => $value["allowsubmissionsfromdate"],
            	"grade" => $value["grade"],
            	"timemodified" => $value["timemodified"],
            	"requiresubmissionstatement" => $value["requiresubmissionstatement"],
            	"completionsubmit" => $value["completionsubmit"],
            	"cutoffdate" => $value["cutoffdate"],
            	"teamsubmission" => $value["teamsubmission"],
            	"attemptreopenmethod" => $value["attemptreopenmethod"],
            	"maxattempts" => $value["maxattempts"],
            	"sendstudentnotifications" => $value["sendstudentnotifications"]
			);
			$estado = $this->dbnueva->insert("mdl_assign", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign AUTO_INCREMENT ".$incremento);


		$asign_grades = $this->dbactual->query("select * from mdl_assign_grades")->result_array();
		foreach ($asign_grades as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"grader" => $value["grader"],
            	"grade" => $value["grade"]
			);
			$estado = $this->dbnueva->insert("mdl_assign_grades", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign_grades AUTO_INCREMENT ".$incremento);


		$plugin = $this->dbactual->query("select * from mdl_assign_plugin_config")->result_array();
		foreach ($plugin as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"plugin" => $value["plugin"],
            	"subtype" => $value["subtype"],
            	"name" => $value["name"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_assign_plugin_config", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign_plugin_config AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function asignaciones_bloque2(){
		$submission = $this->dbactual->query("select * from mdl_assign_submission")->result_array();

		foreach ($submission as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"status" => $value["status"],
            	"latest" => $value["latest"]
			);
			$estado = $this->dbnueva->insert("mdl_assign_submission", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign_submission AUTO_INCREMENT ".$incremento);


		$asign_flags = $this->dbactual->query("select * from mdl_assign_user_flags")->result_array();
		foreach ($asign_flags as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"assignment" => $value["assignment"],
            	"mailed" => $value["mailed"],
            	"extensionduedate" => $value["extensionduedate"]
			);
			$estado = $this->dbnueva->insert("mdl_assign_user_flags", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign_user_flags AUTO_INCREMENT ".$incremento);


		$asign_mapping = $this->dbactual->query("select * from mdl_assign_user_mapping")->result_array();
		foreach ($asign_mapping as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"userid" => $value["userid"]
			);
			$estado = $this->dbnueva->insert("mdl_assign_user_mapping", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assign_user_mapping AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function asignaciones_bloque3(){
		$comments = $this->dbactual->query("select * from mdl_assignfeedback_comments")->result_array();

		foreach ($comments as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"grade" => $value["grade"],
            	"commenttext" => $value["commenttext"],
            	"commentformat" => $value["commentformat"]
			);
			$estado = $this->dbnueva->insert("mdl_assignfeedback_comments", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignfeedback_comments AUTO_INCREMENT ".$incremento);


		$editpdf_annot = $this->dbactual->query("select * from mdl_assignfeedback_editpdf_annot")->result_array();
		foreach ($editpdf_annot as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"gradeid" => $value["gradeid"],
            	"x" => $value["x"],
            	"y" => $value["y"],
            	"endx" => $value["endx"],
            	"endy" => $value["endy"],
            	"path" => $value["path"],
            	"type" => $value["type"],
            	"colour" => $value["colour"],
            	"draft" => $value["draft"]
			);
			$estado = $this->dbnueva->insert("mdl_assignfeedback_editpdf_annot", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignfeedback_editpdf_annot AUTO_INCREMENT ".$incremento);


		$editpdf_cmnt = $this->dbactual->query("select * from mdl_assignfeedback_editpdf_cmnt")->result_array();
		foreach ($editpdf_cmnt as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"gradeid" => $value["gradeid"],
            	"x" => $value["x"],
            	"y" => $value["y"],
            	"width" => $value["width"],
            	"rawtext" => $value["rawtext"],
            	"pageno" => $value["pageno"],
            	"colour" => $value["colour"],
            	"draft" => $value["draft"]
			);
			$estado = $this->dbnueva->insert("mdl_assignfeedback_editpdf_cmnt", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignfeedback_editpdf_cmnt AUTO_INCREMENT ".$incremento);


		$editpdf_queue = $this->dbactual->query("select * from mdl_assignfeedback_editpdf_queue")->result_array();
		foreach ($editpdf_queue as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"submissionid" => $value["submissionid"],
            	"submissionattempt" => $value["submissionattempt"]
			);
			$estado = $this->dbnueva->insert("mdl_assignfeedback_editpdf_queue", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignfeedback_editpdf_queue AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function asignaciones_bloque4(){
		$editpdf_quick = $this->dbactual->query("select * from mdl_assignfeedback_editpdf_quick")->result_array();

		foreach ($editpdf_quick as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"rawtext" => $value["rawtext"],
            	"width" => $value["width"],
            	"colour" => $value["colour"]
			);
			$estado = $this->dbnueva->insert("mdl_assignfeedback_editpdf_quick", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignfeedback_editpdf_quick AUTO_INCREMENT ".$incremento);


		$submission_file = $this->dbactual->query("select * from mdl_assignsubmission_file")->result_array();
		foreach ($submission_file as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"submission" => $value["submission"],
            	"numfiles" => $value["numfiles"]
			);
			$estado = $this->dbnueva->insert("mdl_assignsubmission_file", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignsubmission_file AUTO_INCREMENT ".$incremento);


		$onlinetext = $this->dbactual->query("select * from mdl_assignsubmission_onlinetext")->result_array();
		foreach ($onlinetext as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"assignment" => $value["assignment"],
            	"submission" => $value["submission"],
            	"onlinetext" => $value["onlinetext"],
            	"onlineformat" => $value["onlineformat"]
			);
			$estado = $this->dbnueva->insert("mdl_assignsubmission_onlinetext", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_assignsubmission_onlinetext AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function backups(){
		$controllers = $this->dbactual->query("select * from mdl_backup_controllers")->result_array();

		foreach ($controllers as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"backupid" => $value["backupid"],
            	"operation" => $value["operation"],
            	"type" => $value["type"],
            	"itemid" => $value["itemid"],
            	"format" => $value["format"],
            	"interactive" => $value["interactive"],
            	"purpose" => $value["purpose"],
            	"userid" => $value["userid"],
            	"status" => $value["status"],
            	"execution" => $value["execution"],
            	"checksum" => $value["checksum"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_backup_controllers", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_backup_controllers AUTO_INCREMENT ".$incremento);


		//INSIGNIAS
		$badge = $this->dbactual->query("select * from mdl_badge")->result_array();
		foreach ($badge as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"name" => $value["name"],
            	"description" => $value["description"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"usercreated" => $value["usercreated"],
            	"usermodified" => $value["usermodified"],
            	"issuername" => $value["issuername"],
            	"issuerurl" => $value["issuerurl"],
            	"issuercontact" => $value["issuercontact"],
            	"type" => $value["type"],
            	"courseid" => $value["courseid"],
            	"message" => $value["message"],
            	"messagesubject" => $value["messagesubject"],
            	"attachment" => $value["attachment"],
            	"status" => $value["status"]
			);
			$estado = $this->dbnueva->insert("mdl_badge", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_badge AUTO_INCREMENT ".$incremento);


		$badge_criteria = $this->dbactual->query("select * from mdl_badge_criteria")->result_array();
		foreach ($badge_criteria as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"badgeid" => $value["badgeid"],
            	"criteriatype" => $value["criteriatype"],
            	"method" => $value["method"],
            	"descriptionformat" => $value["descriptionformat"]
			);
			$estado = $this->dbnueva->insert("mdl_badge_criteria", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_badge_criteria AUTO_INCREMENT ".$incremento);


		$criteria_param = $this->dbactual->query("select * from mdl_badge_criteria_param")->result_array();
		foreach ($criteria_param as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"critid" => $value["critid"],
            	"name" => $value["name"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_badge_criteria_param", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_badge_criteria_param AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function videoconferencia(){
		$videoconf = $this->dbactual->query("select * from mdl_bigbluebuttonbn")->result_array();

		foreach ($videoconf as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"type" => $value["type"],
            	"introformat" => $value["introformat"],
            	"meetingid" => $value["meetingid"],
            	"moderatorpass" => $value["moderatorpass"],
            	"viewerpass" => $value["viewerpass"],
            	"record" => $value["record"],
            	"welcome" => $value["welcome"],
            	"openingtime" => $value["openingtime"],
            	"closingtime" => $value["closingtime"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"participants" => $value["participants"],
            	"recordings_html" => $value["recordings_html"],
            	"recordings_deleted" => $value["recordings_deleted"],
            	"recordings_preview" => $value["recordings_preview"]
			);
			$estado = $this->dbnueva->insert("mdl_bigbluebuttonbn", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_bigbluebuttonbn AUTO_INCREMENT ".$incremento);


		$video_logs = $this->dbactual->query("select * from mdl_bigbluebuttonbn_logs")->result_array();
		foreach ($video_logs as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"courseid" => $value["courseid"],
            	"bigbluebuttonbnid" => $value["bigbluebuttonbnid"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"],
            	"meetingid" => $value["meetingid"],
            	"log" => $value["log"],
            	"meta" => $value["meta"]
			);
			$estado = $this->dbnueva->insert("mdl_bigbluebuttonbn_logs", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_bigbluebuttonbn_logs AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	/*public function eliminar_bloques(){
		$bloques = $this->dbnueva->query("select * from mdl_block_instances where id>28")->result_array();

		foreach ($bloques as $key => $value) {
			$estado = $this->dbnueva->delete('mdl_block_instances', array('id' => $value["id"]));
		}

		echo $estado;
	}

	public function bloques(){
		$block_instances = $this->dbactual->query("select * from mdl_block_instances where id>28")->result_array();

		foreach ($block_instances as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"blockname" => $value["blockname"],
            	"parentcontextid" => $value["parentcontextid"],
            	"pagetypepattern" => $value["pagetypepattern"],
            	"subpagepattern" => $value["subpagepattern"],
            	"defaultregion" => $value["defaultregion"],
            	"defaultweight" => $value["defaultweight"]
			);
			$estado = $this->dbnueva->insert("mdl_block_instances", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_block_instances AUTO_INCREMENT ".$incremento);


		$block_positions = $this->dbactual->query("select * from mdl_block_positions")->result_array();
		foreach ($block_positions as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"blockinstanceid" => $value["blockinstanceid"],
            	"contextid" => $value["contextid"],
            	"pagetype" => $value["pagetype"],
            	"subpage" => $value["subpage"],
            	"visible" => $value["visible"],
            	"region" => $value["region"],
            	"weight" => $value["weight"]
			);
			$estado = $this->dbnueva->insert("mdl_block_positions", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_block_positions AUTO_INCREMENT ".$incremento);

		echo $estado;
	}*/


	public function libros(){
		$libros = $this->dbactual->query("select * from mdl_book")->result_array();

		foreach ($libros as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"numbering" => $value["numbering"],
            	"navstyle" => $value["navstyle"],
            	"revision" => $value["revision"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_book", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_book AUTO_INCREMENT ".$incremento);


		$book_chapters = $this->dbactual->query("select * from mdl_book_chapters")->result_array();
		foreach ($book_chapters as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"bookid" => $value["bookid"],
            	"pagenum" => $value["pagenum"],
            	"title" => $value["title"],
            	"content" => $value["content"],
            	"contentformat" => $value["contentformat"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]

			);
			$estado = $this->dbnueva->insert("mdl_book_chapters", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_book_chapters AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function chats(){
		$chat = $this->dbactual->query("select * from mdl_chat")->result_array();
		foreach ($chat as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"chattime" => $value["chattime"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_chat", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_chat AUTO_INCREMENT ".$incremento);


		$mensajes = $this->dbactual->query("select * from mdl_chat_messages")->result_array();
		foreach ($mensajes as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"chatid" => $value["chatid"],
            	"userid" => $value["userid"],
            	"issystem" => $value["system"],
            	"message" => $value["message"],
            	"timestamp" => $value["timestamp"]
			);
			$estado = $this->dbnueva->insert("mdl_chat_messages", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_chat_messages AUTO_INCREMENT ".$incremento);


		$messages_current = $this->dbactual->query("select * from mdl_chat_messages_current")->result_array();
		foreach ($messages_current as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"chatid" => $value["chatid"],
            	"userid" => $value["userid"],
            	"issystem" => $value["system"],
            	"message" => $value["message"],
            	"timestamp" => $value["timestamp"]
			);
			$estado = $this->dbnueva->insert("mdl_chat_messages_current", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_chat_messages_current AUTO_INCREMENT ".$incremento);

		//HERRAMIENTAS
		$lti = $this->dbactual->query("select * from mdl_lti")->result_array();
		foreach ($lti as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"introformat" => $value["introformat"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"toolurl" => $value["toolurl"],
            	"instructorchoicesendname" => $value["instructorchoicesendname"],
            	"instructorchoicesendemailaddr" => $value["instructorchoicesendemailaddr"],
            	"instructorchoiceacceptgrades" => $value["instructorchoiceacceptgrades"],
            	"grade" => $value["grade"],
            	"launchcontainer" => $value["launchcontainer"],
            	"showtitlelaunch" => $value["showtitlelaunch"],
            	"servicesalt" => $value["servicesalt"]
			);
			$estado = $this->dbnueva->insert("mdl_lti", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lti AUTO_INCREMENT ".$incrementomd);
		
		echo $estado;
	}

	public function consulta(){
		$consulta = $this->dbactual->query("select * from mdl_choice")->result_array();
		foreach ($consulta as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"display" => $value["display"],
            	"timeopen" => $value["timeopen"],
            	"timeclose" => $value["timeclose"],
            	"showpreview" => $value["showpreview"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_choice", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_choice AUTO_INCREMENT ".$incremento);


		$choice_answers = $this->dbactual->query("select * from mdl_choice_answers")->result_array();
		foreach ($choice_answers as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"choiceid" => $value["choiceid"],
            	"userid" => $value["userid"],
            	"optionid" => $value["optionid"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_choice_answers", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_choice_answers AUTO_INCREMENT ".$incremento);


		$messages_current = $this->dbactual->query("select * from mdl_choice_options")->result_array();
		foreach ($messages_current as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"choiceid" => $value["choiceid"],
            	"text" => $value["text"],
            	"maxanswers" => $value["maxanswers"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_choice_options", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_choice_options AUTO_INCREMENT ".$incremento);


		$comentarios = $this->dbactual->query("select * from mdl_comments")->result_array();
		foreach ($comentarios as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"contextid" => $value["contextid"],
            	"component" => $value["component"],
            	"commentarea" => $value["commentarea"],
            	"itemid" => $value["itemid"],
            	"content" => $value["content"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"]
			);
			$estado = $this->dbnueva->insert("mdl_comments", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_comments AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function basedatos(){
		$basedatos = $this->dbactual->query("select * from mdl_data")->result_array();
		foreach ($basedatos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"manageapproved" => $value["manageapproved"],
            	"scale" => $value["scale"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_data", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_data AUTO_INCREMENT ".$incremento);


		$editor = $this->dbactual->query("select * from mdl_editor_atto_autosave")->result_array();
		foreach ($editor as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"elementid" => $value["elementid"],
            	"contextid" => $value["contextid"],
            	"pagehash" => $value["pagehash"],
            	"userid" => $value["userid"],
            	"drafttext" => $value["drafttext"],
            	"draftid" => $value["draftid"],
            	"pageinstance" => $value["pageinstance"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_editor_atto_autosave", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_editor_atto_autosave AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function inscripciones(){
		$enrol = $this->dbactual->query("select * from mdl_enrol")->result_array();
		foreach ($enrol as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"enrol" => $value["enrol"],
            	"status" => $value["status"],
            	"courseid" => $value["courseid"],
            	"sortorder" => $value["sortorder"],
            	"expirythreshold" => $value["expirythreshold"],
            	"roleid" => $value["roleid"],
            	"customint4" => $value["customint4"],
            	"customint6" => $value["customint6"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_enrol", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_enrol AUTO_INCREMENT ".$incremento);

		//EVENTOS
		$eventos = $this->dbactual->query("select * from mdl_event")->result_array();
		foreach ($eventos as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"name" => $value["name"],
            	"description" => $value["description"],
            	"format" => $value["format"],
            	"courseid" => $value["courseid"],
            	"userid" => $value["userid"],
            	"modulename" => $value["modulename"],
            	"instance" => $value["instance"],
            	"eventtype" => $value["eventtype"],
            	"timestart" => $value["timestart"],
            	"timeduration" => $value["timeduration"],
            	"visible" => $value["visible"],
            	"sequence" => $value["sequence"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_event", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_event AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function encuesta(){
		$encuesta = $this->dbactual->query("select * from mdl_feedback")->result_array();
		foreach ($encuesta as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"anonymous" => $value["anonymous"],
            	"email_notification" => $value["email_notification"],
            	"autonumbering" => $value["autonumbering"],
            	"page_after_submit" => $value["page_after_submit"],
            	"page_after_submitformat" => $value["page_after_submitformat"],
            	"publish_stats" => $value["publish_stats"],
            	"timeopen" => $value["timeopen"],
            	"timeclose" => $value["timeclose"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_feedback", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_feedback AUTO_INCREMENT ".$incremento);

		//ENCUESTAS COMPLETADAS
		$completadas = $this->dbactual->query("select * from mdl_feedback_completed")->result_array();
		foreach ($completadas as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"feedback" => $value["feedback"],
            	"userid" => $value["userid"],
            	"timemodified" => $value["timemodified"],
            	"random_response" => $value["random_response"],
            	"anonymous_response" => $value["anonymous_response"]
			);
			$estado = $this->dbnueva->insert("mdl_feedback_completed", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_feedback_completed AUTO_INCREMENT ".$incremento);

		//PREGUNTAS DE LAS ENCUESTAS
		$items = $this->dbactual->query("select * from mdl_feedback_item")->result_array();
		foreach ($items as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"feedback" => $value["feedback"],
            	"name" => $value["name"],
            	"label" => $value["label"],
            	"presentation" => $value["presentation"],
            	"typ" => $value["typ"],
            	"hasvalue" => $value["hasvalue"],
            	"position" => $value["position"],
            	"options" => $value["options"]
			);
			$estado = $this->dbnueva->insert("mdl_feedback_item", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_feedback_item AUTO_INCREMENT ".$incremento);

		//VALORES DE LAS PREGUNTAS
		$items = $this->dbactual->query("select * from mdl_feedback_value")->result_array();
		foreach ($items as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"course_id" => $value["course_id"],
            	"item" => $value["item"],
            	"completed" => $value["completed"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_feedback_value", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_feedback_value AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function archivos(){
		$archivos = $this->dbactual->query("select * from mdl_files where id<=16000")->result_array();
		foreach ($archivos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"contenthash" => $value["contenthash"],
            	"pathnamehash" => $value["pathnamehash"],
            	"contextid" => $value["contextid"],
            	"component" => $value["component"],
            	"filearea" => $value["filearea"],
            	"itemid" => $value["itemid"],
            	"filepath" => $value["filepath"],
            	"filename" => $value["filename"],
            	"userid" => $value["userid"],
            	"filesize" => $value["filesize"],
            	"mimetype" => $value["mimetype"],

            	"source" => $value["source"],
            	"author" => $value["author"],
            	"license" => $value["license"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_files", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_files AUTO_INCREMENT ".$incremento);

		//CARPETAS
		$carpetas = $this->dbactual->query("select * from mdl_folder")->result_array();
		foreach ($carpetas as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"revision" => $value["revision"],
            	"timemodified" => $value["timemodified"],
            	"showexpanded" => $value["showexpanded"],
            	"showdownloadfolder" => $value["showdownloadfolder"]
			);
			$estado = $this->dbnueva->insert("mdl_folder", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_folder AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function foros(){
		$items = $this->dbactual->query("select * from mdl_forum")->result_array();
		foreach ($items as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"type" => $value["type"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"assessed" => $value["assessed"],
            	"scale" => $value["scale"],
            	"maxbytes" => $value["maxbytes"],
            	"maxattachments" => $value["maxattachments"],
            	"forcesubscribe" => $value["forcesubscribe"],
            	"trackingtype" => $value["trackingtype"],
            	"timemodified" => $value["timemodified"],
            	"warnafter" => $value["warnafter"],
            	"blockafter" => $value["blockafter"],
            	"blockperiod" => $value["blockperiod"],
            	"displaywordcount" => $value["displaywordcount"]
			);
			$estado = $this->dbnueva->insert("mdl_forum", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum AUTO_INCREMENT ".$incremento);


		$forum_digests = $this->dbactual->query("select * from mdl_forum_digests")->result_array();
		foreach ($forum_digests as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"forum" => $value["forum"],
            	"maildigest" => $value["maildigest"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_digests", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_digests AUTO_INCREMENT ".$incremento);


		$discussion_subs = $this->dbactual->query("select * from mdl_forum_discussion_subs")->result_array();
		foreach ($discussion_subs as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"forum" => $value["forum"],
            	"userid" => $value["userid"],
            	"discussion" => $value["discussion"],
            	"preference" => $value["preference"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_discussion_subs", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_discussion_subs AUTO_INCREMENT ".$incremento);


		$discussion = $this->dbactual->query("select * from mdl_forum_discussions")->result_array();
		foreach ($discussion as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"forum" => $value["forum"],
            	"name" => $value["name"],
            	"firstpost" => $value["firstpost"],
            	"userid" => $value["userid"],
            	"groupid" => $value["groupid"],
            	"timemodified" => $value["timemodified"],
            	"usermodified" => $value["usermodified"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_discussions", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_discussions AUTO_INCREMENT ".$incremento);
		

		$posts = $this->dbactual->query("select * from mdl_forum_posts")->result_array();
		foreach ($posts as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"discussion" => $value["discussion"],
            	"parent" => $value["parent"],
            	"userid" => $value["userid"],
            	"created" => $value["created"],
            	"modified" => $value["modified"],
            	"subject" => $value["subject"],
            	"message" => $value["message"],
            	"messageformat" => $value["messageformat"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_posts", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_posts AUTO_INCREMENT ".$incremento);


		$forum_read = $this->dbactual->query("select * from mdl_forum_read")->result_array();
		foreach ($forum_read as $key => $value) {
			$data5 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"forumid" => $value["forumid"],
            	"discussionid" => $value["discussionid"],
            	"postid" => $value["postid"],
            	"firstread" => $value["firstread"],
            	"lastread" => $value["lastread"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_read", $data5);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_read AUTO_INCREMENT ".$incremento);


		$subscriptions = $this->dbactual->query("select * from mdl_forum_subscriptions")->result_array();
		foreach ($subscriptions as $key => $value) {
			$data6 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"forum" => $value["forum"]
			);
			$estado = $this->dbnueva->insert("mdl_forum_subscriptions", $data6);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_forum_subscriptions AUTO_INCREMENT ".$incremento);
		
		echo $estado;
	}

	public function glosario(){
		$glosario = $this->dbactual->query("select * from mdl_glossary")->result_array();
		foreach ($glosario as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"displayformat" => $value["displayformat"],
            	"mainglossary" => $value["mainglossary"],
            	"showspecial" => $value["showspecial"],
            	"showalphabet" => $value["showalphabet"],
            	"showall" => $value["showall"],
            	"allowcomments" => $value["allowcomments"],
            	"allowprintview" => $value["allowprintview"],
            	"usedynalink" => $value["usedynalink"],
            	"defaultapproval" => $value["defaultapproval"],
            	"approvaldisplayformat" => $value["approvaldisplayformat"],
            	"entbypage" => $value["entbypage"],
            	"assesstimestart" => $value["assesstimestart"],
            	"assesstimefinish" => $value["assesstimefinish"],
            	"scale" => $value["scale"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_glossary", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_glossary AUTO_INCREMENT ".$incremento);


		$entries = $this->dbactual->query("select * from mdl_glossary_entries")->result_array();
		foreach ($entries as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"glossaryid" => $value["glossaryid"],
            	"userid" => $value["userid"],
            	"concept" => $value["concept"],
            	"definition" => $value["definition"],
            	"definitionformat" => $value["definitionformat"],
            	"attachment" => $value["attachment"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"approved" => $value["approved"]
			);
			$estado = $this->dbnueva->insert("mdl_glossary_entries", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_glossary_entries AUTO_INCREMENT ".$incremento);
		
		echo $estado;
	}

	public function grados(){
		$categorias = $this->dbactual->query("select * from mdl_grade_categories")->result_array();
		foreach ($categorias as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"courseid" => $value["courseid"],
            	"depth" => $value["depth"],
            	"path" => $value["path"],
            	"fullname" => $value["fullname"],
            	"aggregation" => $value["aggregation"],
            	"aggregateonlygraded" => $value["aggregateonlygraded"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_grade_categories", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_grade_categories AUTO_INCREMENT ".$incremento);


		$history = $this->dbactual->query("select * from mdl_grade_categories_history")->result_array();
		foreach ($history as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"action" => $value["action"],
            	"oldid" => $value["oldid"],
            	"source" => $value["source"],
            	"timemodified" => $value["timemodified"],
            	"loggeduser" => $value["loggeduser"],
            	"courseid" => $value["courseid"],
            	"depth" => $value["depth"],
            	"path" => $value["path"],
            	"fullname" => $value["fullname"],
            	"aggregation" => $value["aggregation"],
            	"aggregateonlygraded" => $value["aggregateonlygraded"]
			);
			$estado = $this->dbnueva->insert("mdl_grade_categories_history", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_grade_categories_history AUTO_INCREMENT ".$incremento); 


		$item = $this->dbactual->query("select * from mdl_grade_items_history")->result_array();
		foreach ($item as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"action" => $value["action"],
            	"oldid" => $value["oldid"],
            	"source" => $value["source"],
            	"timemodified" => $value["timemodified"],
            	"loggeduser" => $value["loggeduser"],
            	"courseid" => $value["courseid"],
            	"categoryid" => $value["categoryid"],
            	"itemname" => $value["itemname"],
            	"itemtype" => $value["itemtype"],
            	"itemmodule" => $value["itemmodule"],
            	"iteminstance" => $value["iteminstance"],
            	"idnumber" => $value["idnumber"],
            	"gradetype" => $value["gradetype"],
            	"grademax" => $value["grademax"],
            	"multfactor" => $value["multfactor"],
            	"aggregationcoef2" => $value["aggregationcoef2"],
            	"sortorder" => $value["sortorder"],
            	"needsupdate" => $value["needsupdate"]
			);
			$estado = $this->dbnueva->insert("mdl_grade_items_history", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_grade_items_history AUTO_INCREMENT ".$incremento); 
 

		$settings = $this->dbactual->query("select * from mdl_grade_settings")->result_array();
		foreach ($settings as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"courseid" => $value["courseid"],
            	"name" => $value["name"],
            	"itemtype" => $value["itemtype"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_grade_settings", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_grade_settings AUTO_INCREMENT ".$incremento); 


		$areas = $this->dbactual->query("select * from mdl_grading_areas")->result_array();
		foreach ($areas as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"contextid" => $value["contextid"],
            	"component" => $value["component"],
            	"areaname" => $value["areaname"]
			);
			$estado = $this->dbnueva->insert("mdl_grading_areas", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_grading_areas AUTO_INCREMENT ".$incremento);
		
		echo $estado;
	}


	public function grupos(){
		$grupos = $this->dbactual->query("select * from mdl_groups")->result_array();
		foreach ($grupos as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"courseid" => $value["courseid"],
            	"idnumber" => $value["idnumber"],
            	"name" => $value["name"],
            	"description" => $value["description"],
            	"descriptionformat" => $value["descriptionformat"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_groups", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_groups AUTO_INCREMENT ".$incremento);


		$miembros = $this->dbactual->query("select * from mdl_groups_members")->result_array();
		foreach ($miembros as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"groupid" => $value["groupid"],
            	"userid" => $value["userid"],
            	"timeadded" => $value["timeadded"]
			);
			$estado = $this->dbnueva->insert("mdl_groups_members", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_groups_members AUTO_INCREMENT ".$incremento);


		$etiqueta = $this->dbactual->query("select * from mdl_label")->result_array();
		foreach ($etiqueta as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_label", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_label AUTO_INCREMENT ".$incremento);
		
		echo $estado;
	}


	public function lecciones(){
		$lecciones = $this->dbactual->query("select * from mdl_lesson")->result_array();
		foreach ($lecciones as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"grade" => $value["grade"],
            	"custom" => $value["custom"],
            	"maxanswers" => $value["maxanswers"],
            	"maxattempts" => $value["maxattempts"],
            	"minquestions" => $value["minquestions"],
            	"maxpages" => $value["maxpages"],
            	"timelimit" => $value["timelimit"],
            	"mediaheight" => $value["mediaheight"],
            	"mediawidth" => $value["mediawidth"],
            	"width" => $value["width"],
            	"height" => $value["height"],
            	"bgcolor" => $value["bgcolor"],
            	"available" => $value["available"],
            	"deadline" => $value["deadline"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson AUTO_INCREMENT ".$incremento);


		$preguntas = $this->dbactual->query("select * from mdl_lesson_answers")->result_array();
		foreach ($preguntas as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"pageid" => $value["pageid"],
            	"jumpto" => $value["jumpto"],
            	"timecreated" => $value["timecreated"],
            	"answer" => $value["answer"],
            	"answerformat" => $value["answerformat"],
            	"response" => $value["response"],
            	"responseformat" => $value["responseformat"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_answers", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_answers AUTO_INCREMENT ".$incremento);


		$attempts = $this->dbactual->query("select * from mdl_lesson_attempts")->result_array();
		foreach ($attempts as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"pageid" => $value["pageid"],
            	"userid" => $value["userid"],
            	"answerid" => $value["answerid"],
            	"correct" => $value["correct"],
            	"useranswer" => $value["useranswer"],
            	"timeseen" => $value["timeseen"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_attempts", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_attempts AUTO_INCREMENT ".$incremento);
		

		$branch = $this->dbactual->query("select * from mdl_lesson_branch")->result_array();
		foreach ($branch as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"userid" => $value["userid"],
            	"pageid" => $value["pageid"],
            	"timeseen" => $value["timeseen"],
            	"nextpageid" => $value["nextpageid"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_branch", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_branch AUTO_INCREMENT ".$incremento);
		

		$grades = $this->dbactual->query("select * from mdl_lesson_grades")->result_array();
		foreach ($grades as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"userid" => $value["userid"],
            	"grade" => $value["grade"],
            	"completed" => $value["completed"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_grades", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_grades AUTO_INCREMENT ".$incremento);


		$pages = $this->dbactual->query("select * from mdl_lesson_pages")->result_array();
		foreach ($pages as $key => $value) {
			$data5 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"prevpageid" => $value["prevpageid"],
            	"nextpageid" => $value["nextpageid"],
            	"qtype" => $value["qtype"],
            	"qoption" => $value["qoption"],
            	"layout" => $value["layout"],
            	"display" => $value["display"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"title" => $value["title"],
            	"contents" => $value["contents"],
            	"contentsformat" => $value["contentsformat"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_pages", $data5);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_pages AUTO_INCREMENT ".$incremento);

		
		$timer = $this->dbactual->query("select * from mdl_lesson_timer")->result_array();
		foreach ($timer as $key => $value) {
			$data6 = array(
            	"id" => $value["id"],
            	"lessonid" => $value["lessonid"],
            	"userid" => $value["userid"],
            	"starttime" => $value["starttime"],
            	"lessontime" => $value["lessontime"],
            	"completed" => $value["completed"]
			);
			$estado = $this->dbnueva->insert("mdl_lesson_timer", $data6);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_lesson_timer AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function log(){
		$log = $this->dbactual->query("select * from mdl_logstore_standard_log where id>1107")->result_array();
		foreach ($log as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"eventname" => $value["eventname"],
            	"component" => $value["component"],
            	"action" => $value["action"],
            	"target" => $value["target"],
            	"objecttable" => $value["objecttable"],
            	"objectid" => $value["objectid"],
            	"crud" => $value["crud"],
            	"edulevel" => $value["edulevel"],
            	"contextid" => $value["contextid"],
            	"contextlevel" => $value["contextlevel"],
            	"contextinstanceid" => $value["contextinstanceid"],
            	"userid" => $value["userid"],
            	"courseid" => $value["courseid"],
            	"relateduserid" => $value["relateduserid"],
            	"other" => $value["other"],
            	"timecreated" => $value["timecreated"],
            	"origin" => $value["origin"],
            	"ip" => $value["ip"]
			);
			$estado = $this->dbnueva->insert("mdl_logstore_standard_log", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_logstore_standard_log AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	public function mensajes(){
		$mensajes = $this->dbactual->query("select * from mdl_message")->result_array();
		foreach ($mensajes as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"useridfrom" => $value["useridfrom"],
            	"useridto" => $value["useridto"],
            	"subject" => $value["subject"],
            	"fullmessage" => $value["fullmessage"],
            	"fullmessageformat" => $value["fullmessageformat"],
            	"fullmessagehtml" => $value["fullmessagehtml"],
            	"smallmessage" => $value["smallmessage"],
            	"timecreated" => $value["timecreated"],
            	"component" => $value["component"],
            	"eventtype" => $value["eventtype"]
			);
			$estado = $this->dbnueva->insert("mdl_message", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_message AUTO_INCREMENT ".$incremento);
		

		$contactos = $this->dbactual->query("select * from mdl_message_contacts")->result_array();
		foreach ($contactos as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"contactid" => $value["contactid"]
			);
			$estado = $this->dbnueva->insert("mdl_message_contacts", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_message_contacts AUTO_INCREMENT ".$incremento); 


		/*$members = $this->dbactual->query("select * from mdl_message_conversation_members")->result_array();
		foreach ($members as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"conversationid" => $value["conversationid"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"]
			);
			$estado = $this->dbnueva->insert("mdl_message_conversation_members", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_message_conversation_members AUTO_INCREMENT ".$incremento);


		$conversations = $this->dbactual->query("select * from mdl_message_conversations")->result_array();
		foreach ($conversations as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"type" => $value["type"],
            	"convhash" => $value["convhash"],
            	"enabled" => $value["userid"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_message_conversations", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_message_conversations AUTO_INCREMENT ".$incremento); */


		$read = $this->dbactual->query("select * from mdl_message_read")->result_array();
		foreach ($read as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"useridfrom" => $value["useridfrom"],
            	"useridto" => $value["useridto"],
            	"subject" => $value["subject"],
            	"fullmessage" => $value["fullmessage"],
            	"fullmessageformat" => $value["fullmessageformat"],
            	"fullmessagehtml" => $value["fullmessagehtml"],
            	"smallmessage" => $value["smallmessage"],
            	"notification" => $value["smallmessage"],
            	"contexturl" => $value["contexturl"],
            	"contexturl" => $value["contexturl"],
            	"timecreated" => $value["timecreated"],
            	"timeread" => $value["timeread"],
            	"component" => $value["component"],
            	"eventtype" => $value["eventtype"]
			);
			$estado = $this->dbnueva->insert("mdl_message_read", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_message_read AUTO_INCREMENT ".$incremento);

		echo $estado;
	}

	
	public function paginas(){
		$mypages = $this->dbactual->query("select * from mdl_my_pages")->result_array();
		foreach ($mypages as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"name" => $value["name"],
            	"private" => $value["private"]
			);
			$estado = $this->dbnueva->insert("mdl_my_pages", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_my_pages AUTO_INCREMENT ".$incremento);
		

		$paginas = $this->dbactual->query("select * from mdl_page")->result_array();
		foreach ($paginas as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"content" => $value["content"],
            	"contentformat" => $value["contentformat"],
            	"display" => $value["display"],
            	"displayoptions" => $value["displayoptions"],
            	"revision" => $value["revision"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_page", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_page AUTO_INCREMENT ".$incremento); 


		$post = $this->dbactual->query("select * from mdl_post")->result_array();
		foreach ($post as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"module" => $value["module"],
            	"userid" => $value["userid"],
            	"courseid" => $value["courseid"],
            	"content" => $value["content"],
            	"format" => $value["format"],
            	"publishstate" => $value["publishstate"],
            	"lastmodified" => $value["lastmodified"],
            	"created" => $value["created"],
            	"usermodified" => $value["usermodified"]
			);
			$estado = $this->dbnueva->insert("mdl_post", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_post AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}


	public function otros1(){
		$qtype_essay = $this->dbactual->query("select * from mdl_qtype_essay_options")->result_array();
		foreach ($qtype_essay as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"questionid" => $value["questionid"],
            	"responseformat" => $value["responseformat"],
            	"responserequired" => $value["responserequired"],
            	"responsefieldlines" => $value["responsefieldlines"],
            	"graderinfo" => $value["graderinfo"],
            	"graderinfoformat" => $value["graderinfoformat"],
            	"responsetemplateformat" => $value["responsetemplateformat"]
			);
			$estado = $this->dbnueva->insert("mdl_qtype_essay_options", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_qtype_essay_options AUTO_INCREMENT ".$incremento);
		

		$qtype_match = $this->dbactual->query("select * from mdl_qtype_match_subquestions")->result_array();
		foreach ($qtype_match as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"questionid" => $value["questionid"],
            	"questiontext" => $value["questiontext"],
            	"questiontextformat" => $value["questiontextformat"],
            	"answertext" => $value["answertext"]
			);
			$estado = $this->dbnueva->insert("mdl_qtype_match_subquestions", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_qtype_match_subquestions AUTO_INCREMENT ".$incremento); 


		$multichoice = $this->dbactual->query("select * from mdl_qtype_multichoice_options")->result_array();
		foreach ($multichoice as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"questionid" => $value["questionid"],
            	"single" => $value["single"],
            	"shuffleanswers" => $value["shuffleanswers"],
            	"correctfeedback" => $value["correctfeedback"],
            	"correctfeedbackformat" => $value["correctfeedbackformat"],
            	"partiallycorrectfeedback" => $value["partiallycorrectfeedback"],
            	"partiallycorrectfeedbackformat" => $value["partiallycorrectfeedbackformat"],
            	"incorrectfeedback" => $value["incorrectfeedback"],
            	"incorrectfeedbackformat" => $value["incorrectfeedbackformat"],
            	"answernumbering" => $value["answernumbering"],
            	"shownumcorrect" => $value["shownumcorrect"]
			);
			$estado = $this->dbnueva->insert("mdl_qtype_multichoice_options", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_qtype_multichoice_options AUTO_INCREMENT ".$incremento); 


		$shortanswer = $this->dbactual->query("select * from mdl_qtype_shortanswer_options")->result_array();
		foreach ($shortanswer as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"questionid" => $value["questionid"],
            	"usecase" => $value["usecase"]
			);
			$estado = $this->dbnueva->insert("mdl_qtype_shortanswer_options", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_qtype_shortanswer_options AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}


	public function preguntas1(){
		$preguntas = $this->dbactual->query("select * from mdl_question")->result_array();
		foreach ($preguntas as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"category" => $value["category"],
            	"name" => $value["name"],
            	"questiontext" => $value["questiontext"],
            	"questiontextformat" => $value["questiontextformat"],
            	"generalfeedback" => $value["generalfeedback"],
            	"generalfeedbackformat" => $value["generalfeedbackformat"],
            	"defaultmark" => $value["defaultmark"],
            	"penalty" => $value["penalty"],
            	"qtype" => $value["qtype"],
            	"length" => $value["length"],
            	"stamp" => $value["stamp"],
            	"version" => $value["version"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"createdby" => $value["createdby"],
            	"modifiedby" => $value["modifiedby"]
			);
			$estado = $this->dbnueva->insert("mdl_question", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question AUTO_INCREMENT ".$incremento);
		

		$answers = $this->dbactual->query("select * from mdl_question_answers")->result_array();
		foreach ($answers as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"question" => $value["question"],
            	"answer" => $value["answer"],
            	"answerformat" => $value["answerformat"],
            	"fraction" => $value["fraction"],
            	"feedbackformat" => $value["feedbackformat"]
			);
			$estado = $this->dbnueva->insert("mdl_question_answers", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_answers AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}

	public function preguntas2(){
		$step_data = $this->dbactual->query("select * from mdl_question_attempt_step_data")->result_array();
		foreach ($step_data as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"attemptstepid" => $value["attemptstepid"],
            	"name" => $value["name"],
            	"value" => $value["value"]
			);
			$estado = $this->dbnueva->insert("mdl_question_attempt_step_data", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_attempt_step_data AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}

	public function preguntas3(){
		$steps = $this->dbactual->query("select * from mdl_question_attempt_steps")->result_array();
		foreach ($steps as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"questionattemptid" => $value["questionattemptid"],
            	"sequencenumber" => $value["sequencenumber"],
            	"state" => $value["state"],
            	"fraction" => $value["fraction"],
            	"timecreated" => $value["timecreated"],
            	"userid" => $value["userid"]
			);
			$estado = $this->dbnueva->insert("mdl_question_attempt_steps", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_attempt_steps AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}


	public function preguntas4(){
		$attempts = $this->dbactual->query("select * from mdl_question_attempts")->result_array();
		foreach ($attempts as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"questionusageid" => $value["questionusageid"],
            	"slot" => $value["slot"],
            	"behaviour" => $value["behaviour"],
            	"questionid" => $value["questionid"],
            	"variant" => $value["variant"],
            	"maxmark" => $value["maxmark"],
            	"minfraction" => $value["minfraction"],
            	"maxfraction" => $value["maxfraction"],
            	"questionsummary" => $value["questionsummary"],
            	"rightanswer" => $value["rightanswer"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_question_attempts", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_attempts AUTO_INCREMENT ".$incremento); 

		echo $estado;
	}


	public function preguntas5(){
		$categories = $this->dbactual->query("select * from mdl_question_categories")->result_array();
		foreach ($categories as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"name" => $value["name"],
            	"contextid" => $value["contextid"],
            	"info" => $value["info"],
            	"infoformat" => $value["infoformat"],
            	"stamp" => $value["stamp"],
            	"sortorder" => $value["sortorder"]
			);
			$estado = $this->dbnueva->insert("mdl_question_categories", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_categories AUTO_INCREMENT ".$incremento);


		$ddwtos = $this->dbactual->query("select * from mdl_question_ddwtos")->result_array();
		foreach ($ddwtos as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"questionid" => $value["questionid"],
            	"shuffleanswers" => $value["shuffleanswers"],
            	"shuffleanswers" => $value["shuffleanswers"],
            	"correctfeedback" => $value["correctfeedback"],
            	"correctfeedbackformat" => $value["correctfeedbackformat"],
            	"partiallycorrectfeedback" => $value["partiallycorrectfeedback"],
            	"partiallycorrectfeedbackformat" => $value["partiallycorrectfeedbackformat"],
            	"incorrectfeedback" => $value["incorrectfeedback"],
            	"incorrectfeedbackformat" => $value["incorrectfeedbackformat"],
            	"shownumcorrect" => $value["shownumcorrect"]
			);
			$estado = $this->dbnueva->insert("mdl_question_ddwtos", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_ddwtos AUTO_INCREMENT ".$incremento);


		$numerical = $this->dbactual->query("select * from mdl_question_numerical")->result_array();
		foreach ($numerical as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"question" => $value["question"],
            	"answer" => $value["answer"]
			);
			$estado = $this->dbnueva->insert("mdl_question_numerical", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_numerical AUTO_INCREMENT ".$incremento);


		$options = $this->dbactual->query("select * from mdl_question_numerical_options")->result_array();
		foreach ($options as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"question" => $value["question"],
            	"showunits" => $value["showunits"],
            	"unitpenalty" => $value["unitpenalty"]
			);
			$estado = $this->dbnueva->insert("mdl_question_numerical_options", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_numerical_options AUTO_INCREMENT ".$incremento);


		$analysis = $this->dbactual->query("select * from mdl_question_response_analysis")->result_array();
		foreach ($analysis as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"hashcode" => $value["hashcode"],
            	"whichtries" => $value["whichtries"],
            	"timemodified" => $value["timemodified"],
            	"questionid" => $value["questionid"],
            	"variant" => $value["variant"],
            	"subqid" => $value["subqid"],
            	"aid" => $value["aid"],
            	"response" => $value["response"],
            	"credit" => $value["credit"]
			);
			$estado = $this->dbnueva->insert("mdl_question_response_analysis", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_response_analysis AUTO_INCREMENT ".$incremento);


		$count = $this->dbactual->query("select * from mdl_question_response_count")->result_array();
		foreach ($count as $key => $value) {
			$data5 = array(
            	"id" => $value["id"],
            	"analysisid" => $value["analysisid"],
            	"rcount" => $value["rcount"]
			);
			$estado = $this->dbnueva->insert("mdl_question_response_count", $data5);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_response_count AUTO_INCREMENT ".$incremento);


		$count = $this->dbactual->query("select * from mdl_question_statistics")->result_array();
		foreach ($count as $key => $value) {
			$data6 = array(
            	"id" => $value["id"],
            	"hashcode" => $value["hashcode"],
            	"timemodified" => $value["timemodified"],
            	"questionid" => $value["questionid"],
            	"slot" => $value["slot"],
            	"s" => $value["s"],
            	"effectiveweight" => $value["effectiveweight"],
            	"discriminationindex" => $value["discriminationindex"],
            	"discriminativeefficiency" => $value["discriminativeefficiency"],
            	"sd" => $value["sd"],
            	"facility" => $value["facility"],
            	"maxmark" => $value["maxmark"],
            	"positions" => $value["positions"],
            	"randomguessscore" => $value["randomguessscore"]
			);
			$estado = $this->dbnueva->insert("mdl_question_statistics", $data6);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_statistics AUTO_INCREMENT ".$incremento);


		$truefalse = $this->dbactual->query("select * from mdl_question_truefalse")->result_array();
		foreach ($truefalse as $key => $value) {
			$data7 = array(
            	"id" => $value["id"],
            	"question" => $value["question"],
            	"trueanswer" => $value["trueanswer"],
            	"falseanswer" => $value["falseanswer"]
			);
			$estado = $this->dbnueva->insert("mdl_question_truefalse", $data7);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_truefalse AUTO_INCREMENT ".$incremento);


		$usages = $this->dbactual->query("select * from mdl_question_usages")->result_array();
		foreach ($usages as $key => $value) {
			$data8 = array(
            	"id" => $value["id"],
            	"contextid" => $value["contextid"],
            	"component" => $value["component"],
            	"preferredbehaviour" => $value["preferredbehaviour"]
			);
			$estado = $this->dbnueva->insert("mdl_question_usages", $data8);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_question_usages AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function examenes(){
		$examenes = $this->dbactual->query("select * from mdl_quiz")->result_array();
		foreach ($examenes as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"timeopen" => $value["timeopen"],
            	"timeclose" => $value["timeclose"],
            	"timelimit" => $value["timelimit"],
            	"overduehandling" => $value["overduehandling"],
            	"preferredbehaviour" => $value["preferredbehaviour"],
            	"attempts" => $value["attempts"],
            	"grademethod" => $value["grademethod"],
            	"decimalpoints" => $value["decimalpoints"],
            	"questiondecimalpoints" => $value["questiondecimalpoints"],
            	"reviewattempt" => $value["reviewattempt"],
            	"reviewcorrectness" => $value["reviewcorrectness"],
            	"reviewmarks" => $value["reviewmarks"],
            	"reviewspecificfeedback" => $value["reviewspecificfeedback"],
            	"reviewgeneralfeedback" => $value["reviewgeneralfeedback"],
            	"reviewrightanswer" => $value["reviewrightanswer"],
            	"reviewoverallfeedback" => $value["reviewoverallfeedback"],
            	"questionsperpage" => $value["questionsperpage"],
            	"navmethod" => $value["navmethod"],
            	"shuffleanswers" => $value["shuffleanswers"],
            	"sumgrades" => $value["sumgrades"],
            	"grade" => $value["grade"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz AUTO_INCREMENT ".$incremento);


		$attempts = $this->dbactual->query("select * from mdl_quiz_attempts")->result_array();
		foreach ($attempts as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"quiz" => $value["quiz"],
            	"userid" => $value["userid"],
            	"attempt" => $value["attempt"],
            	"uniqueid" => $value["uniqueid"],
            	"layout" => $value["layout"],
            	"preview" => $value["preview"],
            	"state" => $value["state"],
            	"timestart" => $value["timestart"],
            	"timefinish" => $value["timefinish"],
            	"timemodified" => $value["timemodified"],
            	"sumgrades" => $value["sumgrades"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz_attempts", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz_attempts AUTO_INCREMENT ".$incremento);


		$feedback = $this->dbactual->query("select * from mdl_quiz_feedback")->result_array();
		foreach ($feedback as $key => $value) {
			$data2 = array(
            	"id" => $value["id"],
            	"quizid" => $value["quizid"],
            	"feedbacktext" => $value["feedbacktext"],
            	"feedbacktextformat" => $value["feedbacktextformat"],
            	"mingrade" => $value["mingrade"],
            	"maxgrade" => $value["maxgrade"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz_feedback", $data2);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz_feedback AUTO_INCREMENT ".$incremento);


		$grades = $this->dbactual->query("select * from mdl_quiz_grades")->result_array();
		foreach ($grades as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"quiz" => $value["quiz"],
            	"userid" => $value["userid"],
            	"grade" => $value["grade"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz_grades", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz_grades AUTO_INCREMENT ".$incremento);


		$analysis = $this->dbactual->query("select * from mdl_quiz_slots")->result_array();
		foreach ($analysis as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"slot" => $value["slot"],
            	"quizid" => $value["quizid"],
            	"page" => $value["page"],
            	"questionid" => $value["questionid"],
            	"maxmark" => $value["maxmark"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz_slots", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz_slots AUTO_INCREMENT ".$incremento); 


		/*$statistics = $this->dbactual->query("select * from mdl_quiz_statistics")->result_array();
		foreach ($statistics as $key => $value) {
			$data5 = array(
            	"id" => $value["id"],
            	"hashcode" => $value["hashcode"],
            	"whichattempts" => $value["whichattempts"],
            	"timemodified" => $value["timemodified"],
            	"firstattemptscount" => $value["firstattemptscount"],
            	"highestattemptscount" => $value["highestattemptscount"],
            	"lastattemptscount" => $value["lastattemptscount"],
            	"allattemptscount" => $value["allattemptscount"],
            	"firstattemptsavg" => $value["firstattemptsavg"],
            	"lastattemptsavg" => $value["lastattemptsavg"],
            	"allattemptsavg" => $value["allattemptsavg"],
            	"median" => $value["median"],
            	"skewness" => $value["skewness"],
            	"kurtosis" => $value["kurtosis"],
            	"cic" => $value["cic"],
            	"errorratio" => $value["errorratio"],
            	"standarderror" => $value["standarderror"]
			);
			$estado = $this->dbnueva->insert("mdl_quiz_statistics", $data5);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_quiz_statistics AUTO_INCREMENT ".$incremento);
*/

		echo $estado;
	}


	public function recurso(){
		$recurso = $this->dbactual->query("select * from mdl_resource")->result_array();
		foreach ($recurso as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"displayoptions" => $value["displayoptions"],
            	"revision" => $value["revision"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_resource", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_resource AUTO_INCREMENT ".$incremento);


		$rol = $this->dbactual->query("select * from mdl_role_assignments")->result_array();
		foreach ($rol as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"roleid" => $value["roleid"],
            	"contextid" => $value["contextid"],
            	"userid" => $value["userid"],
            	"timemodified" => $value["timemodified"],
            	"modifierid" => $value["modifierid"]
			);
			$estado = $this->dbnueva->insert("mdl_role_assignments", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_role_assignments AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function sesiones(){
		$sesiones = $this->dbactual->query("select * from mdl_sessions")->result_array();
		foreach ($sesiones as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"sid" => $value["sid"],
            	"userid" => $value["userid"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"],
            	"firstip" => $value["firstip"],
            	"lastip" => $value["lastip"]
			);
			$estado = $this->dbnueva->insert("mdl_sessions", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_sessions AUTO_INCREMENT ".$incremento);


		$survey = $this->dbactual->query("select * from mdl_survey_answers")->result_array();
		foreach ($survey as $key => $value) {
			$data1 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"survey" => $value["survey"],
            	"question" => $value["question"],
            	"time" => $value["time"],
            	"answer2" => $value["answer2"]
			);
			$estado = $this->dbnueva->insert("mdl_survey_answers", $data1);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_survey_answers AUTO_INCREMENT ".$incremento);

		
		$tag = $this->dbactual->query("select * from mdl_tag")->result_array();
		foreach ($tag as $key => $value) {
			$data3 = array(
            	"id" => $value["id"],
            	"userid" => $value["userid"],
            	"tagcollid" => $value["tagcollid"],
            	"name" => $value["name"],
            	"rawname" => $value["rawname"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_tag", $data3);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_tag AUTO_INCREMENT ".$incremento);


		$instance = $this->dbactual->query("select * from mdl_tag_instance")->result_array();
		foreach ($instance as $key => $value) {
			$data4 = array(
            	"id" => $value["id"],
            	"tagid" => $value["tagid"],
            	"component" => $value["component"],
            	"itemtype" => $value["itemtype"],
            	"itemid" => $value["itemid"],
            	"contextid" => $value["contextid"],
            	"ordering" => $value["ordering"],
            	"timecreated" => $value["timecreated"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_tag_instance", $data4);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_tag_instance AUTO_INCREMENT ".$incremento);


		$adhoc = $this->dbactual->query("select * from mdl_task_adhoc")->result_array();
		foreach ($adhoc as $key => $value) {
			$data5 = array(
            	"id" => $value["id"],
            	"classname" => $value["classname"],
            	"nextruntime" => $value["nextruntime"],
            	"customdata" => $value["customdata"]
			);
			$estado = $this->dbnueva->insert("mdl_task_adhoc", $data5);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_task_adhoc AUTO_INCREMENT ".$incremento);


		$tool = $this->dbactual->query("select * from mdl_tool_recyclebin_category")->result_array();
		foreach ($tool as $key => $value) {
			$data6 = array(
            	"id" => $value["id"],
            	"categoryid" => $value["categoryid"],
            	"shortname" => $value["shortname"],
            	"fullname" => $value["fullname"],
            	"timecreated" => $value["timecreated"]
			);
			$estado = $this->dbnueva->insert("mdl_tool_recyclebin_category", $data6);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_tool_recyclebin_category AUTO_INCREMENT ".$incremento);

		echo $estado;
	}


	public function url(){
		$url = $this->dbactual->query("select * from mdl_url")->result_array();
		foreach ($url as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"externalurl" => $value["externalurl"],
            	"display" => $value["display"],
            	"displayoptions" => $value["displayoptions"],
            	"parameters" => $value["parameters"],
            	"timemodified" => $value["timemodified"]
			);
			$estado = $this->dbnueva->insert("mdl_url", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_url AUTO_INCREMENT ".$incremento);

		$taller = $this->dbactual->query("select * from mdl_workshop")->result_array();
		foreach ($taller as $key => $value) {
			$data = array(
            	"id" => $value["id"],
            	"course" => $value["course"],
            	"name" => $value["name"],
            	"intro" => $value["intro"],
            	"introformat" => $value["introformat"],
            	"instructauthors" => $value["instructauthors"],
            	"instructauthorsformat" => $value["instructauthorsformat"],
            	"instructreviewers" => $value["instructreviewers"],
            	"instructreviewersformat" => $value["instructreviewersformat"],
            	"timemodified" => $value["timemodified"],
            	"phase" => $value["phase"],
            	"useexamples" => $value["useexamples"],
            	"usepeerassessment" => $value["usepeerassessment"],
            	"useselfassessment" => $value["useselfassessment"],
            	"grade" => $value["grade"],
            	"gradinggrade" => $value["gradinggrade"],
            	"strategy" => $value["strategy"],
            	"evaluation" => $value["evaluation"],
            	"gradedecimals" => $value["gradedecimals"],
            	"nattachments" => $value["nattachments"],
            	"latesubmissions" => $value["latesubmissions"],
            	"maxbytes" => $value["maxbytes"],
            	"submissionstart" => $value["submissionstart"],
            	"submissionend" => $value["submissionend"],
            	"assessmentstart" => $value["assessmentstart"],
            	"assessmentend" => $value["assessmentend"]
			);
			$estado = $this->dbnueva->insert("mdl_workshop", $data);
			$incremento = $value["id"];
		}
		$this->dbnueva->query("ALTER TABLE mdl_workshop AUTO_INCREMENT ".$incremento);

		echo $estado;
	}
}
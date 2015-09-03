<?php
/*
 * **************************************************************************
 *
 * Created on
 2015-5-8 1:16:35
 *
 * File:
 citamedica.php
 *
 *
 *
 * Created for project:
 Admin
 *
 * Time of creation:
 2015-5-8 1:16:35
 *
 * **************************************************************************
 * **************************************************************************
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Citamedica extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_citamedica");
		$this -> load -> model("model_medico");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_consultorio");
		$this -> load -> model("model_fecha");
		$this -> load -> model("model_hora");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1] = 'medico_id';
		$this -> fields[2] = 'consultorio_id';
		$this -> fields[3] = 'especialidad_id';
		$this -> fields[4] = 'consultorio_id';
		$this -> fields[5] = 'fecha_id';
		$this -> fields[6] = 'hora_id';
		$this -> fields[7] = 'paciente_id';
		$this -> fields[8] = 'citamedica';
		$this -> fields[9] = 'informacion';
		$this -> fields[10] = 'prescripcion';
		$this -> fields[11] = 'medicacion';
		$this -> fields[12] = 'descripcion';
		$this -> fields[13] = 'data';

		$this -> searchfields = array();
		$this -> searchfields[7] = 'citamedica';
		$this -> searchfields[8] = 'informacion';
		$this -> searchfields[9] = 'prescripcion';
		$this -> searchfields[10] = 'medicacion';
		$this -> searchfields[11] = 'descripcion';
		$this -> searchfields[12] = 'data';

		$this -> load -> library('session');
		$this -> name = "citamedicaid";
		$this -> searchname = "citamedicasearch";
	}

	public function combo() {
		$answer = array();

		$medico_data = $this -> model_medico -> getAll();
		$medicos = array();
		foreach ($medico_data as $key => $value) {
			$medicos[$value['id']] = array();
			$medicos[$value['id']] = $value;
		}

		$consultorio_data = $this -> model_consultorio -> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}

		$especialidad_data = $this -> model_especialidad -> getAll();
		$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}

		$consultorio_data = $this -> model_consultorio -> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}

		$fecha_data = $this -> model_fecha -> getAll();
		$fechas = array();
		foreach ($fecha_data as $key => $value) {
			$fechas[$value['id']] = array();
			$fechas[$value['id']] = $value;
		}

		$hora_data = $this -> model_hora -> getAll();
		$horas = array();
		foreach ($hora_data as $key => $value) {
			$horas[$value['id']] = array();
			$horas[$value['id']] = $value;
		}

		$citamedica_data = $this -> model_citamedica -> getAll();
		$citamedicas = array();
		foreach ($citamedica_data as $key => $value) {
			$citamedicas[$value['id']] = array();
			$citamedicas[$value['id']] = $value;
		}

		foreach ($citamedicas as $key => $value) {
			$answer[] = array("id" => $value['id'], "citamedica" => $value['citamedica']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function all() {
		$answer = array();

		$medico_data = $this -> model_medico -> getAll();
		$medicos = array();
		foreach ($medico_data as $key => $value) {
			$medicos[$value['id']] = array();
			$medicos[$value['id']] = $value;
		}

		$paciente_data = $this -> model_paciente -> getAll();
		$pacientes = array();
		foreach ($paciente_data as $key => $value) {
			$pacientes[$value['id']] = array();
			$pacientes[$value['id']] = $value;
		}

		$consultorio_data = $this -> model_consultorio -> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}

		$especialidad_data = $this -> model_especialidad -> getAll();
		$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}

		$consultorio_data = $this -> model_consultorio -> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}

		$fecha_data = $this -> model_fecha -> getAll();
		$fechas = array();
		foreach ($fecha_data as $key => $value) {
			$fechas[$value['id']] = array();
			$fechas[$value['id']] = $value;
		}

		$hora_data = $this -> model_hora -> getAll();
		$horas = array();
		foreach ($hora_data as $key => $value) {
			$horas[$value['id']] = array();
			$horas[$value['id']] = $value;
		}

		$citamedica_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$citamedica_info = $this -> model_citamedica -> like($data);
				if(isset($citamedica_info['data'])==true){
					foreach ($citamedica_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$citamedica_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$citamedica_data = $this -> model_citamedica -> getAll();
		}
		
		$citamedicas = array();
		foreach ($citamedica_data as $key => $value) {
			$citamedicas[$value['id']] = array();
			$citamedicas[$value['id']] = $value;
			if(isset($medicos[$value['medico_id']]['medico'])==false){
				$medicos[$value['medico_id']]['medico']='No asignado';
			}
			$citamedicas[$value['id']]['medico'] = $medicos[$value['medico_id']]['medico'];
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']="";
			}
			$citamedicas[$value['id']]['paciente'] = $pacientes[$value['paciente_id']]['paciente'];
			if(isset($consultorios[$value['consultorio_id']]['consultorio'])==false){
				$consultorios[$value['consultorio_id']]['consultorio']='No asignado';
			}
			$citamedicas[$value['id']]['consultorio'] = $consultorios[$value['consultorio_id']]['consultorio'];
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No asignado';
			}
			$citamedicas[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			if(isset($fechas[$value['fecha_id']]['fecha'])==false){
				$fechas[$value['fecha_id']]['fecha']='No asignado';
			}
			$citamedicas[$value['id']]['fecha'] = $fechas[$value['fecha_id']]['fecha'];
			if(isset($horas[$value['hora_id']]['hora'])==false){
				$horas[$value['hora_id']]['hora']='No asignado';
			}
			$citamedicas[$value['id']]['hora'] = $horas[$value['hora_id']]['hora'];
		}
		foreach ($citamedicas as $key => $value) {
			$answer[] = $value;
		}
		$_SESSION[$this -> searchname] = "";
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function save() {
		$answer = array("success" => "false");
		$data = array();
		$flag = true;
		if (isset($_POST['informacion']) == true) {
			$_POST['citamedica'] = $_POST['informacion'];
		}
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_citamedica -> create($data);
			$answer = array("success" => "true");
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function update() {
		$answer = array("success" => "false");
		$id = $this -> input -> get_post("id", TRUE);
		$data = array();
		$flag = true;
		if (isset($_POST['informacion']) == true) {
			$_POST['citamedica'] = $_POST['informacion'];
		}
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_citamedica -> update($id, $data);
			$answer = array("success" => "true");
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function delete() {
		$answer = array("success" => "true");
		$id = "";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$this -> model_citamedica -> delete($id);
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function session($data) {
		$answer = array("success" => "true");
		$data = trim($data);
		if (strlen($data) > 0) {
			$_SESSION[$this -> name] = $data;
		}
		$answer = array("success" => "true", "session" => $_SESSION[$this -> name]);
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function data() {
		$answer = array("success" => "true");
		$id = "";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$data = $this -> model_citamedica -> getOne($id);
		if (isset($data['data']) == true) {
			$data = $data['data'];
		} else {
			$data = array();
		}
		foreach ($data as $key => $value) {
			$answer[$key] = $value;
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function search() {
		$answer = array("success" => "true");
		$data = trim($_GET['q']);
		if (strlen($data) > 0) {
			$_SESSION[$this -> searchname] = $data;
		}
		$answer = array("success" => "true", "session" => $_SESSION[$this -> searchname]);
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

}

/* End of file citamedica.php */
/* Location: ./application/controllers/citamedica.php */
?>

<?php
/*
 * **************************************************************************
 *
 * Created on
 2015-5-8 1:16:35
 *
 * File:
 horario.php
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
class Horario extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_horario");
		$this -> load -> model("model_hora");
		$this -> load -> model("model_consultorio");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1] = 'horario';
		$this -> fields[2] = 'hora_inicio_id';
		$this -> fields[3] = 'hora_fin_id';
		$this -> fields[4] = 'consultorio_id';
		$this -> fields[5] = 'descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1] = 'horario';
		$this -> searchfields[5] = 'descripcion';
		
		$this -> load -> library('session');
		$this -> name = "horarioid";
		$this -> searchname = "horariosearch";
	}

	public function combo() {
		$answer = array();
		$horario_data = $this -> model_horario -> getAll();
		$horarios = array();
		foreach ($horario_data as $key => $value) {
			$horarios[$value['id']] = array();
			$horarios[$value['id']] = $value;
		}
		foreach ($horarios as $key => $value) {
			$answer[] = array("id" => $value['id'], "horario" => $value['horario']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function all() {
		$answer = array();

		$hora_data = $this -> model_hora -> getAll();
		$horas = array();
		foreach ($hora_data as $key => $value) {
			$horas[$value['id']] = array();
			$horas[$value['id']] = $value;
		}

		$consultorio_data = $this -> model_consultorio -> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}

		$horario_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$horario_info = $this -> model_horario -> like($data);
				if(isset($horario_info['data'])==true){
					foreach ($horario_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$horario_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$horario_data = $this -> model_horario -> getAll();
		}
		$horarios = array();
		foreach ($horario_data as $key => $value) {
			$horarios[$value['id']] = array();
			$horarios[$value['id']] = $value;
			$horarios[$value['id']]['hora_inicio'] = $horas[$value['hora_inicio_id']]['hora'];
			$horarios[$value['id']]['hora_fin']    = $horas[$value['hora_fin_id']]['hora'];
			$horarios[$value['id']]['consultorio'] = $consultorios[$value['consultorio_id']]['consultorio'];
		}
		foreach ($horarios as $key => $value) {
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
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_horario -> create($data);
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
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_horario -> update($id, $data);
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
		$this -> model_horario -> delete($id);
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
		$data = $this -> model_horario -> getOne($id);
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

/* End of file horario.php */
/* Location: ./application/controllers/horario.php */
?>

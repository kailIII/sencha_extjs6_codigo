<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		turno.php
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
class Turno extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_turno");
		$this -> load -> model("model_hora");
		$this -> fields = array();
		$this -> fields[1]='turno';
		$this -> fields[2]='hora_inicio_id';
		$this -> fields[3]='hora_fin_id';
		$this -> fields[4]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='turno';
		$this -> searchfields[4]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "turnoid";
		$this -> searchname = "turnosearch";
	}
	public function combo() {
		$answer = array();
		$turno_data = $this -> model_turno-> getAll();
		$turnos = array();
		foreach ($turno_data as $key => $value) {
			$turnos[$value['id']] = array();
			$turnos[$value['id']] = $value;
		}
		foreach ($turnos as $key => $value) {
			$answer[] = array("id" => $value['id'], "turno" => $value['turno']);
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
		
		$turno_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$turno_info = $this -> model_turno -> like($data);
				if(isset($turno_info['data'])==true){
					foreach ($turno_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$turno_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$turno_data = $this -> model_turno -> getAll();
		}
    	$turnos = array();
		foreach ($turno_data as $key => $value) {
			$turnos[$value['id']] = array();
			$turnos[$value['id']] = $value;
			
			if(isset($horas[$value['hora_inicio_id']]['hora'])==false){
				$horas[$value['hora_inicio_id']]['hora']='No asignado';
			}
			$turnos[$value['id']]['hora_inicio'] = $horas[$value['hora_inicio_id']]['hora'];
			if(isset($horas[$value['hora_fin_id']]['hora'])==false){
				$horas[$value['hora_fin_id']]['hora']='No asignado';
			}
			$turnos[$value['id']]['hora_fin'] = $horas[$value['hora_fin_id']]['hora'];
		}

		foreach ($turnos as $key => $value) {
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
			$this -> model_turno -> create($data);
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
			$this -> model_turno -> update($id, $data);
			$answer = array("success" => "true");
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function delete() {
		$answer = array("success" => "true");
		$id="";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$this -> model_turno -> delete($id);
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
	public function data(){
		$answer = array("success" => "true");
		$id="";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$data=$this -> model_turno -> getOne($id);
		if(isset($data['data'])==true){
			$data=$data['data'];
		}else{
			$data=array();
		}
		foreach ($data as $key => $value) {
			$answer[$key]=$value;
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

/* End of file turno.php */
/* Location: ./application/controllers/turno.php */
?>

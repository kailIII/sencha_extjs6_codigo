<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		especialidad.php
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
class Especialidad extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;	
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_especialidad");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='especialidad';
		$this -> fields[2]='costo';
		$this -> fields[3]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='especialidad';
		$this -> searchfields[2]='costo';
		$this -> searchfields[3]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "especialidadid";
		$this -> searchname = "especialidadsearch";
	}
	public function combo() {
		$answer = array();
		$especialidad_data = $this -> model_especialidad-> getAll();
		$especialidads = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidads[$value['id']] = array();
			$especialidads[$value['id']] = $value;
		}
		foreach ($especialidads as $key => $value) {
			$answer[] = array("id" => $value['id'], "especialidad" => $value['especialidad']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$especialidad_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$especialidad_info = $this -> model_especialidad -> like($data);
				if(isset($especialidad_info['data'])==true){
					foreach ($especialidad_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$especialidad_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$especialidad_data = $this -> model_especialidad -> getAll();
		}
    	$especialidads = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidads[$value['id']] = array();
			$especialidads[$value['id']] = $value;
		}
		foreach ($especialidads as $key => $value) {
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
			$this -> model_especialidad -> create($data);
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
			$this -> model_especialidad -> update($id, $data);
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
		$this -> model_especialidad -> delete($id);
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
		$data=$this -> model_especialidad -> getOne($id);
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

/* End of file especialidad.php */
/* Location: ./application/controllers/especialidad.php */
?>

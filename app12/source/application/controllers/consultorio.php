<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		consultorio.php
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
class Consultorio extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_consultorio");
		$this -> load -> model("model_institucion");
		$this -> load -> model("model_especialidad");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='institucion_id';
		$this -> fields[2]='especialidad_id';
		$this -> fields[3]='consultorio';
		$this -> fields[4]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[3]='consultorio';
		$this -> searchfields[4]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "consultorioid";
		$this -> searchname = "consultoriosearch";
	}
	public function combo() {
		$answer = array();
		$consultorio_data = $this -> model_consultorio-> getAll();
		$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
		}
		foreach ($consultorios as $key => $value) {
			$answer[] = array("id" => $value['id'], "consultorio" => $value['consultorio']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$institucion_data = $this -> model_institucion -> getAll();
    	$instituciones = array();
		foreach ($institucion_data as $key => $value) {
			$instituciones[$value['id']] = array();
			$instituciones[$value['id']] = $value;
		}
		
		$especialidad_data = $this -> model_especialidad -> getAll();
    	$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}
		
		
		$consultorio_data = array();

		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$consultorio_info = $this -> model_consultorio -> like($data);
				if(isset($consultorio_info['data'])==true){
					foreach ($consultorio_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$consultorio_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$consultorio_data = $this -> model_consultorio -> getAll();
		}
		
    	$consultorios = array();
		foreach ($consultorio_data as $key => $value) {
			$consultorios[$value['id']] = array();
			$consultorios[$value['id']] = $value;
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No asignado';
			}
			if(isset($instituciones[$value['institucion_id']]['institucion'])==false){
				$instituciones[$value['institucion_id']]['institucion']='No asignado';
			}
			$consultorios[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			$consultorios[$value['id']]['institucion'] = $instituciones[$value['institucion_id']]['institucion'];
		}
		foreach ($consultorios as $key => $value) {
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
			$this -> model_consultorio -> create($data);
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
			$this -> model_consultorio -> update($id, $data);
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
		$this -> model_consultorio -> delete($id);
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
		$data=$this -> model_consultorio -> getOne($id);
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

/* End of file consultorio.php */
/* Location: ./application/controllers/consultorio.php */
?>

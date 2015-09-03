<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		institucion.php
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
class Institucion extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_institucion");		
		$this -> load -> model("model_departamento");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='institucion';
		$this -> fields[2]='departamento_id';
		$this -> fields[3]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='institucion';
		$this -> searchfields[3]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "institucionid";
		$this -> searchname = "institucionsearch";
	}
	public function combo() {
		$answer = array();
		$institucion_data = $this -> model_institucion-> getAll();
		$institucions = array();
		foreach ($institucion_data as $key => $value) {
			$institucions[$value['id']] = array();
			$institucions[$value['id']] = $value;
		}
		foreach ($institucions as $key => $value) {
			$answer[] = array("id" => $value['id'], "institucion" => $value['institucion']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$departamento_data = $this -> model_departamento-> getAll();
		$departamentos = array();
		foreach ($departamento_data as $key => $value) {
			$departamentos[$value['id']] = array();
			$departamentos[$value['id']] = $value;
		}
		
		$institucion_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$institucion_info = $this -> model_institucion -> like($data);
				if(isset($institucion_info['data'])==true){
					foreach ($institucion_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$institucion_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$institucion_data = $this -> model_institucion -> getAll();
		}
    	$institucions = array();
		foreach ($institucion_data as $key => $value) {
			$institucions[$value['id']] = array();
			$institucions[$value['id']] = $value;
			if(isset($departamentos[$value['departamento_id']]['departamento'])==false){
				$departamentos[$value['departamento_id']]['departamento']='No asignado';
			}
			$institucions[$value['id']]['departamento'] = $departamentos[$value['departamento_id']]['departamento'];
		}
		foreach ($institucions as $key => $value) {
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
			$this -> model_institucion -> create($data);
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
			$this -> model_institucion -> update($id, $data);
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
		$this -> model_institucion -> delete($id);
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
		$data=$this -> model_institucion -> getOne($id);
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

/* End of file institucion.php */
/* Location: ./application/controllers/institucion.php */
?>

<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		reportepaciente.php
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
class Reportepaciente extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_departamento");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='paciente';
		$this -> fields[2]='nombres';
		$this -> fields[3]='apellidos';
		$this -> fields[4]='dni';
		$this -> fields[5]='telefono';
		$this -> fields[6]='celular';
		$this -> fields[7]='departamento_id';
		$this -> fields[8]='nacimiento';
		$this -> fields[9]='domicilio';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='paciente';
		$this -> searchfields[2]='nombres';
		$this -> searchfields[3]='apellidos';
		$this -> searchfields[4]='dni';
		$this -> searchfields[5]='telefono';
		$this -> searchfields[6]='celular';
		$this -> searchfields[8]='nacimiento';
		$this -> searchfields[9]='domicilio';
				
		$this -> load -> library('session');
		$this -> name = "reportepacienteid";
		$this -> searchname = "reportepacientesearch";
	}
	public function combo() {
		$answer = array();
		$paciente_data = $this -> model_paciente-> getAll();
		$pacientes = array();
		foreach ($paciente_data as $key => $value) {
			$pacientes[$value['id']] = array();
			$pacientes[$value['id']] = $value;
		}
		foreach ($pacientes as $key => $value) {
			$answer[] = array("id" => $value['id'], "paciente" => $value['paciente']);
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
		
		$paciente_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$paciente_info = $this -> model_paciente -> like($data);
				if(isset($paciente_info['data'])==true){
					foreach ($paciente_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$paciente_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$paciente_data = $this -> model_paciente -> getAll();
		}
    	$pacientes = array();
		foreach ($paciente_data as $key => $value) {
			$pacientes[$value['id']] = array();
			$pacientes[$value['id']] = $value;
			
			if(isset($departamentos[$value['departamento_id']]['departamento'])==false){
				$departamentos[$value['departamento_id']]['departamento']='No asignado';
			}
			$pacientes[$value['id']]['departamento'] = $departamentos[$value['departamento_id']]['departamento'];
		}
		foreach ($pacientes as $key => $value) {
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
		$_POST['paciente']=$_POST['nombres'].", ".$_POST['apellidos'];
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_paciente -> create($data);
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
		$_POST['paciente']=$_POST['nombres'].", ".$_POST['apellidos'];
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_paciente -> update($id, $data);
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
		$this -> model_paciente -> delete($id);
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
		$data=$this -> model_paciente -> getOne($id);
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

/* End of file reportepaciente.php */
/* Location: ./application/controllers/reportepaciente.php */
?>

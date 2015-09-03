<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		pagoregistro.php
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
class Pagoregistro extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_pagoregistro");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_consulta");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[2]='paciente_id';
		$this -> fields[4]='pago';
		$this -> fields[5]='fecha';
		$this -> fields[6]='hora';
		$this -> fields[7]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[2]='pago';
		$this -> searchfields[3]='fecha';
		$this -> searchfields[4]='hora';
		$this -> searchfields[5]='descripcion';
				
		$this -> load -> library('session');
		$this -> name = "pagoregistroid";
		$this -> searchname = "pagoregistrosearch";
	}
	public function combo() {
		$answer = array();
		$pagoregistro_data = $this -> model_pagoregistro-> getAll();
		$pagoregistros = array();
		foreach ($pagoregistro_data as $key => $value) {
			$pagoregistros[$value['id']] = array();
			$pagoregistros[$value['id']] = $value;
		}
		foreach ($pagoregistros as $key => $value) {
			$answer[] = array("id" => $value['id'], "pagoregistro" => $value['pagoregistro']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$paciente_data = $this -> model_paciente -> getAll();
    	$pacientes = array();
		foreach ($paciente_data as $key => $value) {
			$pacientes[$value['id']] = array();
			$pacientes[$value['id']] = $value;
		}

		$pagoregistro_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$pagoregistro_info = $this -> model_pagoregistro -> like($data);
				if(isset($pagoregistro_info['data'])==true){
					foreach ($pagoregistro_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$pagoregistro_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$pagoregistro_data = $this -> model_pagoregistro -> getAll();
		}
    	$pagoregistros = array();
		foreach ($pagoregistro_data as $key => $value) {
			$pagoregistros[$value['id']] = array();
			$pagoregistros[$value['id']] = $value;
			
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']='No asignado';
			}
			$pagoregistros[$value['id']]['paciente'] = $pacientes[$value['paciente_id']]['paciente'];
		}
		foreach ($pagoregistros as $key => $value) {
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
			$this -> model_pagoregistro -> create($data);
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
			$this -> model_pagoregistro -> update($id, $data);
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
		$this -> model_pagoregistro -> delete($id);
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
		$data=$this -> model_pagoregistro -> getOne($id);
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

/* End of file pagoregistro.php */
/* Location: ./application/controllers/pagoregistro.php */
?>

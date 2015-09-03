<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		pagoconsulta.php
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
class Pagoconsulta extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_pagoconsulta");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_consulta");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='concepto';
		$this -> fields[2]='consulta_id';
		$this -> fields[3]='especialidad_id';
		$this -> fields[4]='pago';
		$this -> fields[5]='fecha';
		$this -> fields[6]='hora';
		$this -> fields[7]='descripcion';
		$this -> fields[8]='paciente_id';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='concepto';
		$this -> searchfields[2]='pago';
		$this -> searchfields[3]='fecha';
		$this -> searchfields[4]='hora';
		$this -> searchfields[5]='descripcion';
				
		$this -> load -> library('session');
		$this -> name = "pagoconsultaid";
		$this -> searchname = "pagoconsultasearch";
	}
	public function combo() {
		$answer = array();
		$pagoconsulta_data = $this -> model_pagoconsulta-> getAll();
		$pagoconsultas = array();
		foreach ($pagoconsulta_data as $key => $value) {
			$pagoconsultas[$value['id']] = array();
			$pagoconsultas[$value['id']] = $value;
		}
		foreach ($pagoconsultas as $key => $value) {
			$answer[] = array("id" => $value['id'], "pagoconsulta" => $value['concepto']);
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
		
		$especialidad_data = $this -> model_especialidad -> getAll();
    	$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}
		
		$consulta_data = $this -> model_consulta -> getAll();
    	$consultas = array();
		foreach ($consulta_data as $key => $value) {
			$consultas[$value['id']] = array();
			$consultas[$value['id']] = $value;
		}
		
		$pagoconsulta_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$pagoconsulta_info = $this -> model_pagoconsulta -> like($data);
				if(isset($pagoconsulta_info['data'])==true){
					foreach ($pagoconsulta_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$pagoconsulta_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$pagoconsulta_data = $this -> model_pagoconsulta -> getAll();
		}
    	$pagoconsultas = array();
		foreach ($pagoconsulta_data as $key => $value) {
			$pagoconsultas[$value['id']] = array();
			$pagoconsultas[$value['id']] = $value;
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']='No asignado';
			}
			$pagoconsultas[$value['id']]['paciente'] = $pacientes[$value['paciente_id']]['paciente'];
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No asignado';
			}
			$pagoconsultas[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			if(isset($consultas[$value['consulta_id']]['consulta'])==false){
				$consultas[$value['consulta_id']]['consulta']='No asignado';
			}
			$pagoconsultas[$value['id']]['consulta'] = $consultas[$value['consulta_id']]['consulta'];
		}
		foreach ($pagoconsultas as $key => $value) {
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
			$this -> model_pagoconsulta -> create($data);
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
			$this -> model_pagoconsulta -> update($id, $data);
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
		$this -> model_pagoconsulta -> delete($id);
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
		$data=$this -> model_pagoconsulta -> getOne($id);
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

/* End of file pagoconsulta.php */
/* Location: ./application/controllers/pagoconsulta.php */
?>

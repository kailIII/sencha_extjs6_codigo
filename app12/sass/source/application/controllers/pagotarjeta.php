<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		pagotarjeta.php
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
class Pagotarjeta extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_pagotarjeta");
		$this -> load -> model("model_pagoregistro");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_consulta");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='concepto';
		$this -> fields[2]='consulta_id';
		$this -> fields[3]='especialidad_id';
		$this -> fields[4]='paciente_id';
		$this -> fields[5]='pago';
		$this -> fields[6]='fecha';
		$this -> fields[7]='hora';
		$this -> fields[8]='tarjeta';
		$this -> fields[9]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='concepto';
		$this -> searchfields[3]='pago';
		$this -> searchfields[4]='fecha';
		$this -> searchfields[5]='hora';
		$this -> searchfields[6]='tarjeta';
		$this -> searchfields[8]='descripcion';
				
		$this -> load -> library('session');
		$this -> name = "pagotarjetaid";
		$this -> searchname = "pagotarjetasearch";
	}
	public function combo() {
		$answer = array();
		$pagotarjeta_data = $this -> model_pagotarjeta-> getAll();
		$pagotarjetas = array();
		foreach ($pagotarjeta_data as $key => $value) {
			$pagotarjetas[$value['id']] = array();
			$pagotarjetas[$value['id']] = $value;
		}
		foreach ($pagotarjetas as $key => $value) {
			$answer[] = array("id" => $value['id'], "pagotarjeta" => $value['concepto']);
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
		
		$pagotarjeta_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$pagotarjeta_info = $this -> model_pagotarjeta -> like($data);
				if(isset($pagotarjeta_info['data'])==true){
					foreach ($pagotarjeta_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$pagotarjeta_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$pagotarjeta_data = $this -> model_pagotarjeta -> getAll();
		}
    	$pagotarjetas = array();
		foreach ($pagotarjeta_data as $key => $value) {
			$pagotarjetas[$value['id']] = array();
			$pagotarjetas[$value['id']] = $value;
			
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']='No asignado';
			}
			$pagotarjetas[$value['id']]['paciente'] = $pacientes[$value['paciente_id']]['paciente'];
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No asignado';
			}
			$pagotarjetas[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			if(isset($consultas[$value['consulta_id']]['consulta'])==false){
				$consultas[$value['consulta_id']]['consulta']='No asignado';
			}
			$pagotarjetas[$value['id']]['consulta'] = $consultas[$value['consulta_id']]['consulta'];
		}
		foreach ($pagotarjetas as $key => $value) {
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
			$this -> model_pagotarjeta -> create($data);
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
			$this -> model_pagotarjeta -> update($id, $data);
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
		$this -> model_pagotarjeta -> delete($id);
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
		$data=$this -> model_pagotarjeta -> getOne($id);
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

/* End of file pagotarjeta.php */
/* Location: ./application/controllers/pagotarjeta.php */
?>

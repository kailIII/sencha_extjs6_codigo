<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		consulta.php
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
class Consulta extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_consulta");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_medico");
		$this -> load -> model("model_fecha");
		$this -> load -> model("model_pagotarjeta");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='paciente_id';
		$this -> fields[2]='especialidad_id';
		$this -> fields[3]='medico_id';
		$this -> fields[4]='fecha_id';
		$this -> fields[5]='tema';
		$this -> fields[6]='consulta';
		$this -> fields[7]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[5]='tema';
		$this -> searchfields[6]='consulta';
		$this -> searchfields[7]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "consultaid";
		$this -> searchname = "consultasearch";
	}
	public function consultar(){
		$answer = array();
		
		$dni=trim($this->input->get_post("dni"));
		$especialidad_id=$this->input->get_post("especialidad_id");
		$tema=trim($this->input->get_post("tema"));
		$consulta=trim($this->input->get_post("consulta"));
		$descripcion=trim($this->input->get_post("descripcion"));
		$tarjeta=trim($this->input->get_post("tarjeta"));
		$ccv=trim($this->input->get_post("ccv"));
		
		$search=array(
			array(
				"data"=>$dni,
				"field"=>"dni"
			)
		);
		
		$usuario_data=$this->model_paciente->where($search);
		$usuario=array();
		if(isset($usuario_data['data'])==true){
			$usuario_data=$usuario_data['data'];
			if(isset($usuario_data[0])==true){
				$usuario=$usuario_data[0];	
			}			
		}else{
			$usuario_data=array();
		}
		
		
		$especialidad_data=$this->model_especialidad->getOne($especialidad_id);
		$especialidad=array();
		if(isset($especialidad_data['data'])==true){
			$especialidad=$especialidad_data['data'];
		}
		
		$search=array(
			array(
				"data"=>$especialidad_id,
				"field"=>"especialidad_id"
			)
		);
		
		$medico_data=$this->model_medico->where($search);
		$medico=array();
		
		if(isset($medico_data['data'])==true){
			$medico_data=$medico_data['data'];
			$count=count($medico_data);
			mt_srand((double)microtime()*1000000);
			$number= mt_rand(0, $count-1);
			if(isset($medico_data[$number])==true){
				$medico=$medico_data[$number];	
			}			
		}else{
			$medico_data=array();
		}
		
		$fecha=date("Y-m-d");
		
		$search=array(
			array(
				"data"=>$fecha,
				"field"=>"fecha"
			)
		);
		
		$fecha_data=$this->model_fecha->where($search);
		$fecha=array();
		
		if(isset($fecha_data['data'])==true){
			$fecha_data=$fecha_data['data'];		
			if(isset($fecha_data[0])==true){
				$fecha=$fecha_data[0];	
			}			
		}else{
			$fecha_data=array();
		}
		
		if(isset($fecha['id'])==false){
			$fecha_data=$this->model_fecha->create(array("fecha"=>date("Y-m-d"),"descripcion"=>date("Y-m-d")));
			if(isset($fecha_data['id'])==true){
				$fecha['id']=$fecha_data['id'];
			}	
		}	
		
		$consulta_data=array();
		if(strlen($tema)>0 && strlen($consulta)>0 && strlen($descripcion)>0){
			$data=array(
				"paciente_id"=>$usuario['id'],
				"especialidad_id"=>$medico['especialidad_id'],
				"medico_id"=>$medico['id'],
				"fecha_id"=>$fecha['id'],
				"tema"=>$tema,
				"consulta"=>$consulta,
				"descripcion"=>$descripcion			
			);
			
			$consulta_data=$this->model_consulta->create($data);	
		}
		
		$pagotarjeta_data=array();
		
		if(isset($consulta_data['id'])==true && strlen($tarjeta)>0 && strlen($ccv)>0){
			$data=array(
				"concepto"=>"consulta web",
				"consulta_id"=>$consulta_data['id'],
				"especialidad_id"=>$medico['especialidad_id'],
				"paciente_id"=>$usuario['id'],
				"pago"=>$especialidad['costo'],
				"fecha"=>date("Y-m-d"),
				"hora"=>date("h:i:s"),
				"tarjeta"=>$tarjeta,
				"descripcion"=>$ccv
			);
			
			$pagotarjeta_data=$this->model_pagotarjeta->create($data);	
		}
		
		
		if(isset($pagotarjeta_data['id'])==true){
			$answer=array("success"=>"true","message"=>"Transaccion exitosa");
			
			//mail de medico
			$to = $medico['email'];
			$subject = "Consulta Web";
			$txt = "Consulta Web"."\n".
					"Usuario : ".$usuario['nombres'].", ".$usuario['apellidos']."\n".
					"Celular : ".$usuario['celular']."\n".
					"Consulta: ".$consulta."\n".
					"Tema: ".$tema."\n".
					"Descripcion: ".$descripcion."\n";
					
			$headers = "From: system@system.com"; 
			
			mail($to,$subject,$txt,$headers);
		}else{
			$answer=array("success"=>"false","message"=>"Datos Incompletos");
		}
		
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function combo() {
		$answer = array();
		$consulta_data = $this -> model_consulta-> getAll();
		$consultas = array();
		foreach ($consulta_data as $key => $value) {
			$consultas[$value['id']] = array();
			$consultas[$value['id']] = $value;
		}
		foreach ($consultas as $key => $value) {
			$answer[] = array("id" => $value['id'], "consulta" => $value['consulta']);
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
		
		$fecha_data = $this -> model_fecha -> getAll();
		$fechas = array();
		foreach ($fecha_data as $key => $value) {
			$fechas[$value['id']] = array();
			$fechas[$value['id']] = $value;
		}
		
		$especialidad_data = $this -> model_especialidad -> getAll();
		$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}
		
		$medico_data = $this -> model_medico -> getAll();
		$medicos = array();
		foreach ($medico_data as $key => $value) {
			$medicos[$value['id']] = array();
			$medicos[$value['id']] = $value;
		}
		
		$consulta_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$consulta_info = $this -> model_consulta -> like($data);
				if(isset($consulta_info['data'])==true){
					foreach ($consulta_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$consulta_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$consulta_data = $this -> model_consulta -> getAll();
		}

    	$consultas = array();
		foreach ($consulta_data as $key => $value) {
			$consultas[$value['id']] = array();
			$consultas[$value['id']] = $value;
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']='No asignado';
			}
			$consultas[$value['id']]['paciente'] = $pacientes[$value['paciente_id']]['paciente'];
			
			if(isset($medicos[$value['medico_id']]['medico'])==false){
				$medicos[$value['medico_id']]['medico']='No asignado';
			}
			$consultas[$value['id']]['medico'] = $medicos[$value['medico_id']]['medico'];
			
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No asignado';
			}
			$consultas[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			
			if(isset($fechas[$value['fecha_id']]['fecha'])==false){
				$fechas[$value['fecha_id']]['fecha']='No asignado';
			}
			$consultas[$value['id']]['fecha'] = $fechas[$value['fecha_id']]['fecha'];
		}
		foreach ($consultas as $key => $value) {
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
			$this -> model_consulta -> create($data);
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
			$this -> model_consulta -> update($id, $data);
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
		$this -> model_consulta -> delete($id);
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
		$data=$this -> model_consulta -> getOne($id);
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

/* End of file consulta.php */
/* Location: ./application/controllers/consulta.php */
?>

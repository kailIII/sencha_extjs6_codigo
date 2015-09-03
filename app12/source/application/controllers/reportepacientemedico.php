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
class Reportepacientemedico extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_departamento");
		$this -> load -> model("model_medico");
		$this -> load -> model("model_fecha");
		$this -> load -> model("model_especialidad");
		$this -> load -> model("model_consulta");
		$this -> load -> model("model_historiaclinica");
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

	public function all() {
		$answer = array();
		
		$historiaclinica_data = $this -> model_historiaclinica-> getAll();
		$historiaclinicas = array();
		foreach ($historiaclinica_data as $key => $value) {
			$historiaclinicas[$value['paciente_id']] = array();
			$historiaclinicas[$value['paciente_id']] = $value;
		}
		
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
			
			$consultas[$value['id']]['atencion']="En proceso";
			
			if($historiaclinicas[$value['paciente_id']]['fecha_id']==$value['fecha_id']){
				$consultas[$value['id']]['atencion']="Atencion procesada";				
			}
		}
		foreach ($consultas as $key => $value) {
			$answer[] = $value;
		}
		$_SESSION[$this -> searchname] = "";
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

/* End of file reportepacientemedico.php */
/* Location: ./application/controllers/reportepacientemedico.php */
?>

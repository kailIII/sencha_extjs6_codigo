<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		historiaclinica.php
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
class Historiaclinica extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_historiaclinica");
		$this -> load -> model("model_paciente");
		$this -> load -> model("model_fecha");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='historiaclinica';
		$this -> fields[2]='paciente_id';
		$this -> fields[3]='fecha_id';
		$this -> fields[4]='tema';
		$this -> fields[5]='consulta';
		$this -> fields[6]='antecedentes';
		$this -> fields[7]='diagnostico';
		$this -> fields[8]='indicaciones';
		$this -> fields[9]='observacion';
		$this -> fields[10]='descripcion';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='historiaclinica';
		$this -> searchfields[4]='tema';
		$this -> searchfields[5]='consulta';
		$this -> searchfields[6]='antecedentes';
		$this -> searchfields[7]='diagnostico';
		$this -> searchfields[8]='indicaciones';
		$this -> searchfields[9]='observacion';
		$this -> searchfields[10]='descripcion';
		
		$this -> load -> library('session');
		$this -> name = "historiaclinicaid";
		$this -> searchname = "historiaclinicasearch";
	}
	public function combo() {
		$answer = array();
		$historiaclinica_data = $this -> model_historiaclinica-> getAll();
		$historiaclinicas = array();
		foreach ($historiaclinica_data as $key => $value) {
			$historiaclinicas[$value['id']] = array();
			$historiaclinicas[$value['id']] = $value;
		}
		foreach ($historiaclinicas as $key => $value) {
			$answer[] = array("id" => $value['id'], "historiaclinica" => $value['historiaclinica']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$fecha_data = $this -> model_fecha -> getAll();
		$fechas = array();
		foreach ($fecha_data as $key => $value) {
			$fechas[$value['id']] = array();
			$fechas[$value['id']] = $value;
		}

		$paciente_data = $this -> model_paciente -> getAll();
		$pacientes = array();
		foreach ($paciente_data as $key => $value) {
			$pacientes[$value['id']] = array();
			$pacientes[$value['id']] = $value;
		}
		
		$historiaclinica_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$historiaclinica_info = $this -> model_historiaclinica -> like($data);
				if(isset($historiaclinica_info['data'])==true){
					foreach ($historiaclinica_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$historiaclinica_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$historiaclinica_data = $this -> model_historiaclinica -> getAll();
		}
    	$historiaclinicas = array();
		foreach ($historiaclinica_data as $key => $value) {
			$historiaclinicas[$value['id']] = array();
			$historiaclinicas[$value['id']] = $value;
			
			if(isset($pacientes[$value['paciente_id']]['paciente'])==false){
				$pacientes[$value['paciente_id']]['paciente']='No asignado';
			}
			$historiaclinicas[$value['id']]['paciente']=$pacientes[$value['paciente_id']]['paciente'];
			
			if(isset($fechas[$value['fecha_id']]['fecha'])==false){
				$fechas[$value['fecha_id']]['fecha']='No asignado';
			}
			$historiaclinicas[$value['id']]['fecha']=$fechas[$value['fecha_id']]['fecha'];
		}
		foreach ($historiaclinicas as $key => $value) {
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
			$this -> model_historiaclinica -> create($data);
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
			$this -> model_historiaclinica -> update($id, $data);
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
		$this -> model_historiaclinica -> delete($id);
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
		$data=$this -> model_historiaclinica -> getOne($id);
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

/* End of file historiaclinica.php */
/* Location: ./application/controllers/historiaclinica.php */
?>

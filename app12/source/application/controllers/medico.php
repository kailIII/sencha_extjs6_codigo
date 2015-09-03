<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		medico.php
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
class Medico extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_medico");
		$this -> load -> model("model_departamento");
		$this -> load -> model("model_institucion");
		$this -> load -> model("model_especialidad");
		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1]='medico';
		$this -> fields[2]='nombres';
		$this -> fields[3]='apellidos';
		$this -> fields[4]='dni';
		$this -> fields[5]='telefono';
		$this -> fields[6]='celular';
		$this -> fields[7]='especialidad_id';
		$this -> fields[8]='institucion_id';
		$this -> fields[9]='departamento_id';
		$this -> fields[10]='horario_id';
		$this -> fields[11]='turno_id';
		$this -> fields[12]='descripcion';
		$this -> fields[13]='cmp';
		$this -> fields[14]='email';
		
		$this -> searchfields = array();
		$this -> searchfields[1]='medico';
		$this -> searchfields[2]='nombres';
		$this -> searchfields[3]='apellidos';
		$this -> searchfields[4]='dni';
		$this -> searchfields[5]='telefono';
		$this -> searchfields[6]='celular';
		$this -> searchfields[12]='descripcion';
		$this -> searchfields[13]='email';
		$this -> searchfields[14]='cmp';
		
		
		$this -> load -> library('session');
		$this -> name = "medicoid";
		$this -> searchname = "medicosearch";
	}
	public function combo() {
		$answer = array();
		$medico_data = $this -> model_medico-> getAll();
		$medicos = array();
		foreach ($medico_data as $key => $value) {
			$medicos[$value['id']] = array();
			$medicos[$value['id']] = $value;
		}
		foreach ($medicos as $key => $value) {
			$answer[] = array("id" => $value['id'], "medico" => $value['medico']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {
		$answer = array();
		
		$departamento_data = $this -> model_departamento -> getAll();
    	$departamentos = array();
		foreach ($departamento_data as $key => $value) {
			$departamentos[$value['id']] = array();
			$departamentos[$value['id']] = $value;
		}
		
		$especialidad_data = $this -> model_especialidad -> getAll();
    	$especialidades = array();
		foreach ($especialidad_data as $key => $value) {
			$especialidades[$value['id']] = array();
			$especialidades[$value['id']] = $value;
		}
		
		$institucion_data = $this -> model_institucion -> getAll();
    	$instituciones = array();
		foreach ($institucion_data as $key => $value) {
			$instituciones[$value['id']] = array();
			$instituciones[$value['id']] = $value;
		}
		
		$medico_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$medico_info = $this -> model_medico -> like($data);
				if(isset($medico_info['data'])==true){
					foreach ($medico_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$medico_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$medico_data = $this -> model_medico -> getAll();
		}
    	$medicos = array();
		foreach ($medico_data as $key => $value) {
			$medicos[$value['id']] = array();
			$medicos[$value['id']] = $value;
			if(isset($especialidades[$value['especialidad_id']]['especialidad'])==false){
				$especialidades[$value['especialidad_id']]['especialidad']='No Asignado';
			}
			$medicos[$value['id']]['especialidad'] = $especialidades[$value['especialidad_id']]['especialidad'];
			if(isset($instituciones[$value['institucion_id']]['institucion'])==false){
				$instituciones[$value['institucion_id']]['institucion']='No asignado';
			}
			$medicos[$value['id']]['institucion'] = $instituciones[$value['institucion_id']]['institucion'];
			if(isset($departamentos[$value['departamento_id']]['departamento'])==false){
				$departamentos[$value['departamento_id']]['departamento']='No asignado';
			}
			$medicos[$value['id']]['departamento'] = $departamentos[$value['departamento_id']]['departamento']; 
		}
		foreach ($medicos as $key => $value) {
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
		$_POST['medico']=$_POST['nombres'].", ".$_POST['apellidos'];
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_medico -> create($data);
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
		$_POST['medico']=$_POST['nombres'].", ".$_POST['apellidos'];
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_medico -> update($id, $data);
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
		$this -> model_medico -> delete($id);
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
		$data=$this -> model_medico -> getOne($id);
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

/* End of file medico.php */
/* Location: ./application/controllers/medico.php */
?>

<?php
/*
 * **************************************************************************
 *
 * Created on
 2015-5-8 1:16:35
 *
 * File:
 acceso.php
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
class Acceso extends CI_Controller {
	private $fields;
	private $searchfields;
	private $name;
	private $searchname;
	private $sessionname;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_acceso");

		$this -> fields = array();
		//$this -> fields[0]='id';
		$this -> fields[1] = 'login';
		$this -> fields[2] = 'password';
		$this -> fields[3] = 'email';
		$this -> fields[4] = 'creacion';

		$this -> searchfields = array();
		$this -> searchfields[1] = 'login';
		$this -> searchfields[2] = 'email';
		$this -> searchfields[3] = 'creacion';

		$this -> load -> library('session');
		$this -> name = "accesoid";
		$this -> searchname = "accesosearch";
		
		$this -> sessionname = "accesosession";
	}
	
	public function verificar() {
		$json	 = array();
		
		$access="false";
		
		if(
			isset($_SESSION[$this->sessionname])==true && 
			strlen($_SESSION[$this->sessionname])>0
		) {
			$data=$_SESSION[$this->sessionname];
			$access="true";	
		}else{
			$access="false";	
		}
		$login="";
		if($access=="true"){
			$search=array(
				array(
					"data"=>$data,
					"field"=>"login"
				)
			);
			$acceso_data = $this -> model_acceso-> where($search);
			if(isset($acceso_data['data'])==true){
				$data=$acceso_data['data'];
				if(isset($data[0]['login'])==true){
					$login=$data[0]['login'];	
				}else{
					$login="";
				}				
				$access="true";
			}else{
				$data=array();
				$access="false";	
			}				
		}
		$json=array();
		if($access=="true"){
			$json=array("access"=>"true","login"=>$login);	
		}else{
			$json=array("access"=>"false","login"=>$login);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($json);
	}

	public function ingresar() {
		$json	 = array();
		
		$login = $this -> input -> get_post("login", TRUE);
		$password = $this -> input -> get_post("password", TRUE);		
		
		$access="false";
		
		$search=array(
			array(
				"data"=>$login,
				"field"=>"login"
			)
		);
		
		$acceso_data = $this -> model_acceso-> where($search);
		if(isset($acceso_data['data'])==true){
			$data=$acceso_data['data'];
			if(isset($data[0]['password'])==true){
				$password_data=trim($data[0]['password']);	
			}else{
				$password_data="";
			}				
			if($password==$password_data){
				$access="true";
			}else{
				$access="false";
			}
		}else{
			$data=array();
			$access="false";	
		}
		
		if($access=="true"){
			$_SESSION[$this->sessionname]=$login;
		}else{
			$_SESSION[$this->sessionname]="";
		}
		$json=array();
		if($access=="true"){
			$json=array("access"=>"true");	
		}else{
			$json=array("access"=>"false");
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($json);
	}
	
	public function salir() {
		$json	 = array();
		$_SESSION[$this->sessionname]="";
		session_destroy();
		session_start();
		$json=array("access"=>"true");
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($json);
	}

	public function combo() {
		$answer = array();

		$acceso_data = $this -> model_acceso-> getAll();
		$accesos = array();
		foreach ($acceso_data as $key => $value) {
			$accesos[$value['id']] = array();
			unset($value['password']);
			$accesos[$value['id']] = $value;
		}
		
		foreach ($accesos as $key => $value) {
			$answer[] = array("id" => $value['id'], "acceso" => $value['login']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function all() {
		$answer = array();

		

		$acceso_data = array();
		if (isset($_SESSION[$this -> searchname]) == true && strlen($_SESSION[$this -> searchname]) > 0) {
			$search = $_SESSION[$this -> searchname];
			foreach ($this->searchfields as $i => $j) {
				$data = array( array("field" => $j, "data" => $search));
				$acceso_info = $this -> model_acceso -> like($data);
				if(isset($acceso_info['data'])==true){
					foreach ($acceso_info['data'] as $index => $info) {
						if(isset($info['id'])==true){
							$acceso_data[$info['id']] = $info;
						}
					}	
				}				
			}
		} else {
			$acceso_data = $this -> model_acceso -> getAll();
		}
		
		$accesos = array();
		foreach ($acceso_data as $key => $value) {
			$accesos[$value['id']] = array();
			unset($value['password']);
			$accesos[$value['id']] = $value;
		}
		foreach ($accesos as $key => $value) {
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
		$_POST['creacion']=date("Y-m-d");
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_acceso -> create($data);
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
		$_POST['creacion']=date("Y-m-d");
		foreach ($this->fields as $key => $value) {
			$data[$value] = $this -> input -> get_post($value, TRUE);
			if ($data[$value] == '') {
				$flag = false;
			}
		}
		if ($flag) {
			$this -> model_acceso -> update($id, $data);
			$answer = array("success" => "true");
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}

	public function delete() {
		$answer = array("success" => "true");
		$id = "";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$this -> model_acceso -> delete($id);
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

	public function data() {
		$answer = array("success" => "true");
		$id = "";
		if (isset($_SESSION[$this -> name]) == true) {
			$id = $_SESSION[$this -> name];
		}
		$data = $this -> model_acceso -> getOne($id);
		if (isset($data['data']) == true) {
			$data = $data['data'];
		} else {
			$data = array();
		}
		foreach ($data as $key => $value) {
			$answer[$key] = $value;
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

/* End of file acceso.php */
/* Location: ./application/controllers/acceso.php */
?>

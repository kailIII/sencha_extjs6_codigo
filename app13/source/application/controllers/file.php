<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-7-16 14:9:57
 * 
 * File:
		file.php
 * 
 * 
 * 
 * Created for project:
		Crud
 * 
 * Time of creation:
		2015-7-16 14:9:57
 * 
 * ************************************************************************** 
 * ************************************************************************** 
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class File extends CI_Controller {
	private $fields;
	private $name;
	public function __construct() {
		parent::__construct();
		$this -> load -> model("model_file");
		$this -> fields = array();
		$this -> fields[1]='name';
		$this -> fields[2]='file';
		$this -> load -> library('session');
		$this -> name = "fileid";
	}
	public function upload(){//subida de imagen
		$answer = array("success"=>"false");		
		$flag = true;		
		$error=array();
		$info=array();
		$config=array();		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '10000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload("imagen")) //imagen desde formulario
		{
			$error = array('error' => $this->upload->display_errors());			
		}
		else
		{			
			$info=$this->upload->data();
			$name= $info['file_name'];
			$tmp= $info['full_path'];	
			$sql= "insert into  file (id,name,data) values (NULL,'".$name."', load_file('".$tmp."') );";		
			$answer = array("success"=>"true","file"=>$name);			
			$this->model_file->execute($sql);					
		}		
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);		
	}
	public function combo() {
		$answer = array();
		$file_data = $this -> model_file-> getAll();
		$files = array();
		foreach ($file_data as $key => $value) {
			$files[$value['id']] = array();
			$files[$value['id']] = $value;
		}
		foreach ($files as $key => $value) {
			$answer[] = array("id" => $value['id'], "file" => $value['file']);
		}
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
	public function all() {//lectura de datos 
		$answer = array();
		$file_data = $this -> model_file -> getAll();
    	$files = array();
		foreach ($file_data as $key => $value) {
			$files[$value['id']] = array();
			if(isset($value['data'])==true){
				unset($value['data']);	
			}				
			$files[$value['id']] = $value;
			$files[$value['id']]['url'] = "source/index.php/file/imagen/".$value['id'];
		}
		foreach ($files as $key => $value) {
			$answer[] = $value;
		}
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
			$this -> model_file -> create($data);
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
			$this -> model_file -> update($id, $data);
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
		$this -> model_file -> delete($id);
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
		$data=$this -> model_file -> getOne($id);
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
	
	public function imagen($id){//muestra imagen en la salida
		header('Content-Type: image/png');		
		$data=array(
			array(
				"field"=>"id",
				"data"=>$id
			)			
		);		
		$all = $this->model_file->where($data);		
		if(isset($all['data'][0]['data'])==true){
			echo $all['data'][0]['data'];	
		}		
		exit(0);	
		
	}
}

/* End of file file.php */
/* Location: ./application/controllers/file.php */
?>

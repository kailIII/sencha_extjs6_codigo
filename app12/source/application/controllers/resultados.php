<?php
/*
 * ************************************************************************** 
 * 
 * Created on
		2015-5-8 1:16:35
 * 
 * File:
		resultados.php
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
class Resultados extends CI_Controller {
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

	}
	
	public function save() {
		$answer = array("success" => "true");

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($answer);
	}
}

/* End of file resultados.php */
/* Location: ./application/controllers/resultados.php */
?>

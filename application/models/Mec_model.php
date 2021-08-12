<?php

class Mec_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function addMec($encabezados = [], $data = [])
	{
		$newData = array_merge($encabezados, $data);
		$this->db->insert('mec_mec', $newData);
	}

	public function addMec2($mecs2 = [], $mec = [])
	{
		foreach ($mecs2 as $mec2) {
			$newData = array_merge($mec, $mec2);
			$this->db->insert('mec_mec2', $newData);
		}

	}

	public function get_all()
	{
		return $this->db->get('mec_mec')->result();
	}

	public function mec_2($mec_cod, $mec_sec, $mec_afec)
	{
		$this->db->where('mec_mec_doc', $mec_cod);
		$this->db->where('mec_mec_sec', $mec_sec);
		$this->db->where('mec_mec_afec', $mec_afec);
		return $this->db->get('mec_mec2')->result();
	}


	public function truncateTablesIfNotEmpty()
	{
		if($this->countRows('mec_mec') > 0) $this->truncateTable('mec_mec');
		if($this->countRows('mec_mec2') > 0) $this->truncateTable('mec_mec2');
	}

	public function countRows($table)
	{
		return $this->db->count_all_results($table);
	}

	public function truncateTable($table)
	{
		$this->db->truncate($table);
	}

}
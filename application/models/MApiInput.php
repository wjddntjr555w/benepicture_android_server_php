<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MApiInput extends MY_Model
{

	public $table = 't_api_input';
	public $primary_key = 'f_idx';

	function __construct()
	{
		parent::__construct();
	}

	public function select_max_sort($api_idx)
	{
		$this->db->select_max('f_sort');
		$this->db->where('f_api', $api_idx);
		$qry = $this->db->get($this->table);
		$result = $qry->row_array();
		return $result['f_sort'];
	}

	public function update_sort($api_idx, $ai_sort)
	{
		$this->db->where('f_api', $api_idx);
		$this->db->where('f_sort >=', $ai_sort);
		$this->db->set('f_sort', 'f_sort+1', false);
		$result = $this->db->update($this->table);
		return $result;
	}

	public function delete_all($column, $value)
	{
		$this->db->where($column, $value);
		return $this->db->delete($this->table);
	}

	public function get_list($column = 'f_name', $keyword = '')
	{
		return $this->db->where($column, $keyword)->order_by('f_sort', 'ASC')->get($this->table)->result_array();
	}

}

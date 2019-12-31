<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MApiList extends MY_Model
{

	public $table = 't_api_list';
	public $primary_key = 'f_idx';

	function __construct()
	{
		parent::__construct();
	}

	public function get_list($column = 'f_name', $keyword = '')
	{
		if (!empty($keyword))
			$this->db->like($column, $keyword);

		return $this->db->order_by('f_idx', 'ASC')->get($this->table)->result_array();
	}

}

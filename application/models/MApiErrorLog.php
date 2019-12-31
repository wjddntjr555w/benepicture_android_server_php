<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class MApiErrorLog extends MY_Model
{

	public $table = 't_api_error_log';

	function __construct()
	{
		parent::__construct();
	}

}

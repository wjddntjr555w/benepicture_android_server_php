<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Term extends AdminController
{
    public $_page_index = 5;
    public $_page_title = ["약관관리"];

    protected $models = ['MTerm'];

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_page_sub_index = 0;

        $service_term = '';
        $privacy_info = '';

        $term_model = $this->MTerm->where('f_type', 1)->get();
		if ($term_model != false) {
			$service_term = $term_model->f_content;
		}
		$term_model = $this->MTerm->where('f_type', 2)->get();
		if ($term_model != false) {
			$privacy_info = $term_model->f_content;
		}

        $this->render('term/index', [
        	'service' => $service_term,
			'privacy' => $privacy_info
		]);
    }

    public function save()
    {
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		$service = $this->input->post('service');
		$privacy = $this->input->post('privacy');

		$term_model = $this->MTerm->where('f_type', 1)->get();
		if ($term_model == false) {
			$insert_result = $this->db->insert('t_term', [
				'f_reg_time' => _get_current_time(),
				'f_type' => 1,
				'f_content' => $service
			]);
			if ($insert_result == false) {
				echo _server_err_msg();
				return;
			}
		} else {
			$update_result = $this->db->where('f_idx', $term_model->f_idx)->update('t_term', [
				'f_content' => $service
			]);
			if ($update_result == false) {
				echo _server_err_msg();
				return;
			}
		}

		$term_model = $this->MTerm->where('f_type', 2)->get();
		if ($term_model == false) {
			$insert_result = $this->db->insert('t_term', [
				'f_reg_time' => _get_current_time(),
				'f_type' => 2,
				'f_content' => $privacy
			]);
			if ($insert_result == false) {
				echo _server_err_msg();
				return;
			}
		} else {
			$update_result = $this->db->where('f_idx', $term_model->f_idx)->update('t_term', [
				'f_content' => $privacy
			]);
			if ($update_result == false) {
				echo _server_err_msg();
				return;
			}
		}

		echo 'success';
    }

}

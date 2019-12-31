<?php

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Api 문서보기 controller 입니다.
 */
class Apidoc extends AdminController
{
	protected $isCheckPrivilegeController = false;

	protected $models = [];
	protected $helpers = ['array'];
	protected $api_list_model = 'MApiList';

	function __construct()
	{
		parent::__construct();

		$this->db_check();

		$this->load->model(['MApiErrorLog', 'MApiInput', 'MApiOutput', 'MApiList']);
	}

	/**
	 * api 관련 테이블들이 존재하는지 체크, 없다면 생성
	 */
	private function db_check()
	{
		$this->load->database();

		if (!$this->db->table_exists('t_api_input')) {
			$this->db->query("
            CREATE TABLE `t_api_input` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_api` int(11) NOT NULL COMMENT 'api 아이디 (t_api_list 테이블의 아이디)',
              `f_name` varchar(50) NOT NULL COMMENT '파라미터명',
              `f_type` varchar(15) NOT NULL COMMENT '변수타입',
              `f_ness` varchar(1) NOT NULL COMMENT '필수여부 (N-필수, ''-빈값, D-개발시)',
              `f_exp` text COMMENT '파라미터설명',
              `f_sort` int(3) NOT NULL COMMENT '순서',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 요청파라미터테이블';");
		}

		if (!$this->db->table_exists('t_api_output')) {
			$this->db->query("
            CREATE TABLE `t_api_output` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_api` int(11) NOT NULL COMMENT 'a[i 아이디 (t_api_list테이블의 아이디)',
              `f_name` varchar(50) NOT NULL COMMENT '파라미터명',
              `f_type` varchar(15) NOT NULL COMMENT '파라미터 변수타입',
              `f_ness` varchar(1) NOT NULL COMMENT '필수여부 (N-필수, ''-빈값, D-개발시)',
              `f_exp` text COMMENT '파라미터설명',
              `f_sort` int(3) NOT NULL COMMENT '순서',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 응답파라미터테이블';");
		}

		if (!$this->db->table_exists('t_api_list')) {
			$this->db->query("
            CREATE TABLE `t_api_list` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_reg_time` datetime NOT NULL COMMENT '저장일짜',
              `f_name` varchar(255) NOT NULL COMMENT 'api 명',
              `f_exp` text COMMENT 'api 설명',
              `f_url` varchar(255) NOT NULL COMMENT '요청URL (서브도메인)',
              `f_method` varchar(10) NOT NULL COMMENT '요청메서드',
              `f_use` tinyint(1) NOT NULL COMMENT '사용여부 (1-사용, 0-미사용)',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 테이블';");
		}

		if (!$this->db->table_exists('t_api_error_log')) {
			$this->db->query("
            CREATE TABLE `t_api_error_log` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_time` datetime NOT NULL COMMENT '저장일짜',
              `f_ip` varchar(255) NOT NULL COMMENT 'ip주소',
              `f_self` varchar(255) NOT NULL COMMENT 'New Column3',
              `f_vars` text COMMENT 'New Column4',
              `f_result` varchar(255) NOT NULL COMMENT 'New Column5',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 에러로그테이블';");
		}
	}

	/**
	 * 메인페이지입니다 메소드입니다
	 */
	public function index()
	{
//		if (!DEV_MODE) {
//			show_404();
//		}

		$result = array();
		$api_list = $this->{$this->api_list_model}->get_list();
		$result['api_list'] = $api_list;
		$this->render_api('apis/doc/index', $result);
	}

	/**
	 * 뷰페이지입니다 메소드입니다
	 */
	public function view($pid = 0)
	{
//		if (!DEV_MODE) {
//			show_404();
//		}

		$view = array();
		$view['view'] = array();

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		if ($pid) {
			$pid = (int)$pid;
			if (empty($pid) OR $pid < 1) {
				show_404();
			}
		}

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$api_data = array();
		if ($pid) {
			$api_data = $this->{$this->api_list_model}->as_array()->get($pid);
			$input = $this->MApiInput->get_list('f_api', $pid);
			$output = $this->MApiOutput->get_list('f_api', $pid);
		} else {
			show_404();
		}

		$view['view']['data'] = $api_data;
		$view['view']['input'] = $input;
		$view['view']['output'] = $output;

		$this->render_api('apis/doc/view', $view);
	}

	public function emptypage()
	{
		echo '실행하여주세요';
	}

	public function xml($api_idx)
	{
		if (!$api_idx)
			show_404();
		$api_data = $this->{$this->api_list_model}->as_array()->get($api_idx);

		if (!element('f_idx', $api_data))
			show_404();

		echo $this->apilib->call($api_data, 'xml');
	}

	public function json($api_idx)
	{
		if (!$api_idx)
			show_404();
		$api_data = $this->{$this->api_list_model}->as_array()->get($api_idx);

		if (!element('f_idx', $api_data))
			show_404();

		define('API_RESPONSE_PRETTY', true);
		echo $this->apilib->call($api_data, 'json');
	}

	public function json2($api_idx)
	{
		if (!$api_idx)
			show_404();
		$api_data = $this->{$this->api_list_model}->as_array()->get($api_idx);

		if (!element('f_idx', $api_data))
			show_404();

		echo $this->apilib->call($api_data, 'json2');
	}
}

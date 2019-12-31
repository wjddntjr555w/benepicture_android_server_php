<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-02
 * Time: 오전 9:49
 */

use app\controllers\fend\ApiController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends ApiController
{

	protected $models = ['MNotice', 'MUser', 'MCert', 'MTerm'];

	function __construct()
	{
		parent::__construct();
		$this->load->helper('nusoap_tongkni');
	}

	public function get_key()
	{
		$this->_check_params(__FUNCTION__, __CLASS__);

		$user_model = $this->MUser->where('f_phone', $this->api_params->phone)->get();
		if ($user_model != false && $user_model->f_sess_token != $this->api_params->token) {
			$this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 핸드폰번호입니다.');
		}

		$cert_key = mt_rand(100000, 999999);
		$cert_model = $this->MCert->where('f_phone', $this->api_params->phone)->get();
		if ($cert_model == false) {
			$insert_result = $this->MCert->insert([
				'f_reg_time' => _get_current_time(),
				'f_phone' => $this->api_params->phone,
				'f_cert' => $cert_key
			]);
			if ($insert_result == false) {
				$this->_response_error(API_RES_ERR_DB);
			}
		} else {
			$update_result = $this->MCert->update(['f_cert' => $cert_key], $cert_model->f_idx);
			if ($update_result == false) {
				$this->_response_error(API_RES_ERR_DB);
			}
		}


		// 통큰아이 전송
		$arrPhone = explode(' ', $this->api_params->phone);
		if (count($arrPhone) > 1) {
			$ret = $this->sendSMS($arrPhone[1], '베네픽쳐 인증번호는 [' . $cert_key . '] 입니다.');
		} else {
			$ret = $this->sendSMS($arrPhone[0], '베네픽쳐 인증번호는 [' . $cert_key . '] 입니다.');
		}

		$this->_response_success([
			'cert_key' => DEV_MODE ? $cert_key : ''
		]);
	}

	/**
	 * 통큰아이 전송
	 * @param $p_receiver
	 * @param $p_content
	 * @return string
	 */
	function sendSMS($p_receiver, $p_content)
	{
		$snd_number = "023579339";
		$rcv_number = $p_receiver;//"01086018905";
		$sms_content = $p_content;

		/******고객님 접속 정보************/
		$sms_id = "tfkint";            //고객님께서 부여 받으신 sms_id
		$sms_pwd = "Avhcbs1004!!";       //고객님께서 부여 받으신 sms_pwd
		/**********************************/
		$callbackURL = "www.tongkni.co.kr";
		$userdefine = $sms_id;                            //예약취소를 위해 넣어주는 구분자 정의값, 사용자 임의로 지정해주시면 됩니다. 영문으로 넣어주셔야 합니다. 사용자가 구분할 수 있는 값을 넣어주세요.
		$canclemode = "1";                //예약 취소 모드 1: 사용자정의값에 의한 삭제.  현제는 무조건 1을 넣어주시면 됩니다.

		//구축 테스트 주소와 일반 웹서비스 선택
//        if (substr($sms_id,0,3) == "bt_"){
//            $webService = "http://webservice.tongkni.co.kr/sms.3.bt/ServiceSMS_bt.asmx?WSDL";
//        }
//        else{
		$webService = "http://webservice.tongkni.co.kr/sms.3/ServiceSMS.asmx?WSDL";
//        }
		$sms = new SMS($webService); //SMS 객체 생성

		/*즉시 전송으로 구성하실경우*/
		$result = $sms->SendSMS($sms_id, $sms_pwd, $snd_number, $rcv_number, $sms_content);// 5개의 인자로 함수를 호출합니다.

		return $result;

	}

	public function check_key()
	{
		$this->_check_params(__FUNCTION__, __CLASS__);

		$cert_model = $this->MCert->where('f_phone', $this->api_params->phone)->get();
		if ($cert_model == false) {
			$this->_response_error(API_RES_ERR_INFO_NO_EXIST, '인증번호가 잘못되었습니다.');
		}
		if ($cert_model->f_cert != $this->api_params->cert_key) {
			$this->_response_error(API_RES_ERR_INCORRECT, '인증번호가 잘못되었습니다.');
		}

		$this->_response_success();
	}

	public function term()
	{
		$term = '';
		$privacy = '';

		$term_model = $this->MTerm->where('f_type', 1)->get();
		if ($term_model != false) {
			$term = $term_model->f_content;
		}

		$privacy_model = $this->MTerm->where('f_type', 2)->get();
		if ($privacy_model != false) {
			$privacy = $privacy_model->f_content;
		}

		$this->_response_success([
			'term' => $term,
			'privacy' => $privacy
		]);
	}

	public function notice_list()
	{
		$this->_check_params(__FUNCTION__, __CLASS__);

		$limit = LIMIT_20;
		$offset = _get_page_offset($this->api_params->page_num, $limit);

		$total_cnt = $this->db->where('f_type', $this->api_params->type)->count_all_results('t_notice');
		$notice_list = $this->db
			->select('f_idx id, DATE_FORMAT(f_reg_time, "%Y.%m.%d") reg_time, f_title title, f_content content, f_img img')
			->where('f_type', $this->api_params->type)
			->order_by('f_reg_time', 'DESC')
			->limit($limit, $offset)
			->get('t_notice')
			->result();

		foreach ($notice_list as $notice) {
			$notice->img = _get_file_url($notice->img);
		}

		$this->_response_success([
			'page_cnt' => _get_page_count($total_cnt, $limit),
			'notice' => $notice_list
		]);
	}

	public function file_uploads()
	{
		$this->_check_params(__FUNCTION__, __CLASS__);

		$user_model = $this->db->where('f_sess_token', $this->api_params->token)->get('t_user')->row();
		if (is_null($user_model)) {
			$this->_response_error(API_RES_ERR_PRIVILEGE);
		}

//		foreach ($this->api_params->files as &$file) {
//			$file = _get_file_url($file);
//		}

		$this->_response_success([
			'url' => $this->api_params->files
		]);
	}

}

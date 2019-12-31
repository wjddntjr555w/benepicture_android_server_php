<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-16
 * Time: 오후 5:27
 */

namespace app\controllers\fend;

use MY_Controller;
use stdClass;

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiController extends MY_Controller
{
	protected $isCheckPrivilegeController = false;

	protected $libraries = ['session'];
	protected $helpers = ['common', 'bene'];

	protected $api_name;
	protected $api_method;
	protected $api_params;

	function __construct()
	{
		parent::__construct();
		$this->api_params = new stdClass();
	}


	/**
	 * 해당 api 호출시 호출파라미터가 정확히 다 올라왔는가를 체크하는 메서드
	 *
	 * @param string $api_name api 명
	 * @param string $api_url api URL
	 * @return void
	 */
	public function _check_params($api_name, $api_url = '')
	{
		// 해당 api 에 대한 정보를 블러 온다.
		$this->db->where('f_name', strtolower($api_name));
		if (!empty($api_url)) {
			$this->db->where('f_url', strtolower($api_url));
		}

		$api_list = $this->db->get('t_api_list')->result_array();

		if (_is_array($api_list)) {
			$api_data = $api_list[0];

			// api 에 해당한 요청파라메터목록을 블러온다.
			$input_param_list = $this->db
				->where('f_api', element('f_idx', $api_data))
				->get('t_api_input')
				->result_array();


			// 모든 api 들에서 공통으로 요구되는 필수파라미터들을 로드한다.
			/*if (strtoupper(trim(element('f_method', $api_data))) == 'POST') {
				$this->api_params->lang = $this->input->post('lang');
			} else if (strtoupper(trim(element('f_method', $api_data))) == 'GET') {
				$this->api_params->lang = $this->input->get('lang');
			}

			if (is_null($this->api_params->lang) || trim($this->api_params->lang) == '') {
				$this->_response_error(API_RES_ERR_PARAMETER, 'lang');
			}

			// 언어파일을 로드한다.
			$this->load_lang($this->api_params->lang);*/


			// 요청으로 올라온 파라미터들을 로드한다.
			if (_is_array($input_param_list)) {

				foreach ($input_param_list as $param) {

					switch (strtolower(trim(element('f_type', $param)))) {
						case 'string(varchar)':
						case 'integer':
						case 'float':
						case 'boolean':
						case 'string(text)':
							if (strtoupper(trim(element('f_method', $api_data))) == 'POST') {
								$this->api_params->{trim($param['f_name'])} = $this->input->post(trim($param['f_name']));
							} else if (strtoupper(trim(element('f_method', $api_data))) == 'GET') {
								$this->api_params->{trim($param['f_name'])} = $this->input->get(trim($param['f_name']));
							}
							break;
						case 'object':
						case 'object arr':
							if (strtoupper(trim(element('f_method', $api_data))) == 'POST') {
								$this->api_params->{trim($param['f_name'])} = json_decode(stripslashes($this->input->post(trim($param['f_name']))), true);
							} else if (strtoupper(trim(element('f_method', $api_data))) == 'GET') {
								$this->api_params->{trim($param['f_name'])} = json_decode(stripslashes($this->input->get(trim($param['f_name']))), true);
							}
							break;
						case 'file':
							$this->api_params->{trim($param['f_name'])} = $this->_file_upload(date('Y/m/d'), trim($param['f_name']), false);
							break;
						case 'multi files':
							$this->api_params->{trim($param['f_name'])} = $this->_multi_file_upload(date('Y/m/d'), trim($param['f_name']), false);
							break;
					}

				}

			}

			// 필수 파라미터가 다 등록되었는지를 확인한다.
			if (_is_array($input_param_list)) {

				foreach ($input_param_list as $param) {

					if (trim(element('f_ness', $param)) == 'N') {
						if (is_null($this->api_params->{trim($param['f_name'])})) {
							$this->_response_error(API_RES_ERR_PARAMETER, $param['f_name']);
						}

						switch (strtolower(trim(element('f_type', $param)))) {
							case 'string(varchar)':
							case 'integer':
							case 'float':
							case 'boolean':
							case 'string(text)':
							case 'file':
								if (trim($this->api_params->{trim($param['f_name'])}) == '') {
									$this->_response_error(API_RES_ERR_PARAMETER, $param['f_name']);
								}
								break;
							case 'object':
							case 'object arr':
							case 'multi files':
								if (empty($this->api_params->{trim($param['f_name'])})) {
									$this->_response_error(API_RES_ERR_PARAMETER, $param['f_name']);
								}
								break;
						}
					}

				}

			}

		} else {

			$this->_response_error(API_RES_ERR_PARAMETER, 'no api exist');

		}

	}

	/**
	 * 서버요청에 대한 처리결과를 귀환하는 메서드
	 *
	 * @param integer $error_code
	 * @param string $msg
	 * @return array
	 */
	public function _response_error($error_code, $msg = '')
	{
		if (empty($msg)) {
			switch ($error_code) {
				default:
				case API_RES_SUCCESS:
					$msg = 'Success';
					break;
				case API_RES_ERR_UNKNOWN:
					$msg = '서버오류입니다';
					break;
				case API_RES_ERR_PARAMETER:
					$msg = '파라미터누락오류 ';
					break;
				case API_RES_ERR_DB:
					$msg = '데이터베이스 오류입니다';
					break;
				case API_RES_ERR_DUPLICATE:
					$msg = '이미 등록되어 있습니다';
					break;
				case API_RES_ERR_INFO_NO_EXIST:
					$msg = '결과가 없습니다';
					break;
				case API_RES_ERR_PRIVILEGE:
					$msg = '조작이 불가합니다';
					break;
				case API_RES_ERR_FILE_UPLOAD:
					$msg = '파일업로드에 실패하였습니다';
					break;
			}
			if ($error_code == API_RES_ERR_PARAMETER) {
				$msg = '파라미터누락오류 (' . $msg . ')';
			}
		}

		return $this->_make_response($error_code, $msg, array());
	}

	private function _make_response($code, $msg, $data)
	{
		$response = [
			'res_code' => $code,
			'res_msg' => $msg
		];

		foreach ($data as $key => $value) {
			$response[$key] = $value;
		}

		if (isset($_REQUEST['pretty']) && $_REQUEST['pretty'] == 'TRUE') {
			$this->output
				->set_status_header(200)
				->set_content_type('text/html', 'utf-8')
				->set_output('<textarea readonly style="width: 100%; height: 99%; border: none; resize: none">' . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</textarea>')
				->_display();
		} else {
			$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response))
				->_display();
		}

		exit();
	}

	public function _response_success($data = array())
	{
		return $this->_make_response(API_RES_SUCCESS, 'Success', $data);
	}

	public function correctImageOrientation($filename)
	{
		if (function_exists('exif_read_data')) {
			$exif = exif_read_data($filename);
			if ($exif && isset($exif['Orientation'])) {
				$orientation = $exif['Orientation'];
				if ($orientation != 1) {
					$img = imagecreatefromjpeg($filename);
					$deg = 0;
					switch ($orientation) {
						case 3:
							$deg = 180;
							break;
						case 6:
							$deg = 270;
							break;
						case 8:
							$deg = 90;
							break;
					}
					if ($deg) {
						$img = imagerotate($img, $deg, 0);
					}
					// then rewrite the rotated image back to the disk as $filename
					imagejpeg($img, $filename, 95);
				} // if there is some rotation necessary
			} // if have the exif orientation info
		} // if function exists
	}

	private function load_lang($api_lang)
	{
		$lang_map = [
			'en' => 'english',
			'ko' => 'korean'
		];

		$this->lang->load('api', $lang_map[strtolower(trim($api_lang))]);
	}
}

<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-02
 * Time: 오전 9:49
 */

use app\controllers\fend\ApiController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends ApiController
{

    protected $models = ['MVersion', 'MCert', 'MTerm', 'MUser'];
    protected $helpers = ['nusoap_tongkni'];

    function __construct()
    {
        parent::__construct();
    }

    public function version()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        if (_is_empty($this->api_params->device)) {
            $this->_response_error(API_RES_ERR_PARAMETER, 'device');
        }

        $version_code = 1;
        $version_model = $this->MVersion->where('f_device', $this->api_params->device)->get();
        if ($version_model != false) {
            $version_code = $version_model->f_version;
        }

        $this->_response_success([
            'version_code' => $version_code,
            'version_name' => '',
            'market_url' => '',
            'force_update' => 1,
        ]);
    }

    public function term()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);


        $useterm = '';
        $privacy = '';
        $adver = '';
        $cost = 0;
        $winner = 0;

        $term_model = $this->MTerm->where('f_type', 1)->get();
        if ($term_model != false) {
            $useterm = $term_model->f_content;
        }

        $term_model = $this->MTerm->where('f_type', 2)->get();
        if ($term_model != false) {
            $privacy = $term_model->f_content;
        }

        $term_model = $this->MTerm->where('f_type', 3)->get();
        if ($term_model != false) {
            $adver = $term_model->f_content;
        }

        $term_model = $this->MTerm->where('f_type', 4)->get();
        if ($term_model != false) {
            $cost = $term_model->f_content;
        }

        $term_model = $this->MTerm->where('f_type', 5)->get();
        if ($term_model != false) {
            $winner = $term_model->f_content;
        }

        $this->_response_success([
            'useterm' => $useterm,
            'privacy' => $privacy,
            'adver' => $adver,
            'cost' => $cost,
            'winner' => $winner
        ]);
    }

    public function get_key()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->where(['f_phone' => $this->api_params->phone, 'f_status' => USER_COMMON])->get();

        if ($this->api_params->check == 0) {
            if ($user_model != false && $user_model->f_sess_token != $this->api_params->token) {
                $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 번호입니다.');
            }
        } else {
            if ($user_model == false) {
                $this->_response_error(API_RES_ERR_INFO_NO_EXIST, '가입되지 않은 번호입니다.');
            }
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


    /**
     * 통큰아이 전송
     * @param $p_receiver
     * @param $p_content
     * @return string
     */
    function sendSMS($p_receiver, $p_content)
    {
        if (DEV_MODE) {
            return true;
        }

        $snd_number = "05050420819";
        $rcv_number = $p_receiver;//"01048268224";
        $sms_content = $p_content;

        /******고객님 접속 정보************/
        $sms_id = "dayongleemessage";            //고객님께서 부여 받으신 sms_id
        $sms_pwd = "qlalf12!@";       //고객님께서 부여 받으신 sms_pwd
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
}

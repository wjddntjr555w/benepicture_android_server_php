<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-02
 * Time: 오전 9:49
 */

use app\controllers\fend\ApiController;

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends ApiController
{

    protected $models = ['MUser', 'MCert', 'MGift', 'MIntroduce', 'MAdvertise', 'MAnswer', 'MStatistics'];

    function __construct()
    {
        parent::__construct();
        $this->load->helper('nusoap_tongkni');
    }

    public function signin()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        if ($this->api_params->type == LOGIN_ID) {
            if (_is_empty($this->api_params->pwd)) {
                $this->_response_error(API_RES_ERR_PARAMETER, 'pwd');
            }
        }


        $user_model = $this->MUser->where($this->api_params->type == LOGIN_ID ? 'f_id' : 'f_kt_id', $this->api_params->usr_id)->where('f_status', USER_NORMAL)->get();
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST, '아이디를 확인해주세요');
        }
        if ($user_model->f_status == USER_EXIT) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST, '아이디를 확인해주세요');
        }
        if ($this->api_params->type == LOGIN_ID && !_verify_pwd($this->api_params->pwd, $user_model->f_pwd)) {
            $this->_response_error(API_RES_ERR_INCORRECT, '비밀번호를 확인해주세요.');
        }

        // 보안토큰을 발급한다.
        $security_token = crypt('BenePicture' . mt_rand(1000, 9999) . $user_model->f_idx . time(), mt_rand(1000, 9999));

        $update_data = [
            'f_device' => $this->api_params->device,
            'f_fcm_token' => $this->api_params->token,
            'f_sess_token' => $security_token
        ];

        $this->MUser->update($update_data, $user_model->f_idx);

        // 로그인로그를 저장한다.
        $log_model = $this->db->where(['f_user' => $user_model->f_idx, 'f_reg_time' => _get_current_date()])->get('t_login')->row();
        if (is_null($log_model)) {
            $this->db->insert('t_login', ['f_reg_time' => _get_current_date(), 'f_user' => $user_model->f_idx]);
        }

        $this->_response_success([
            'id' => $user_model->f_idx,
            'usr_id' => $user_model->f_id,
            'token' => $security_token
        ]);
    }

    public function check()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);


        $user_model = $this->db->where(['f_id' => $this->api_params->usr_id, 'f_status' => USER_COMMON])->get('t_user')->row();
        if (!is_null($user_model)) {
            $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 아이디입니다.');
        }

        $this->_response_success();
    }

    public function signup()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

//        if (_is_empty($this->api_params->phone)) {
//            $this->_response_error(API_RES_ERR_PARAMETER, 'phone');
//        }
//        if (_is_empty($this->api_params->auth)) {
//            $this->_response_error(API_RES_ERR_PARAMETER, 'cert_key');
//        }
//        if (_is_empty($this->api_params->birthday)) {
//            $this->_response_error(API_RES_ERR_PARAMETER, 'birthday');
//        }

        if ($this->api_params->type == LOGIN_ID) {
            if (_is_empty($this->api_params->pwd)) {
                $this->_response_error(API_RES_ERR_PARAMETER, 'pwd');
            }

            $user_model = $this->MUser->where(['f_id' => $this->api_params->usr_id, 'f_status' => USER_COMMON])->get();
            if ($user_model != false) {
                $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 아이디입니다.');
            }
        } else {
            $kt_id_cnt = $this->db->where(['f_kt_id' => $this->api_params->usr_id, 'f_status' => USER_COMMON])->count_all_results('t_user');
            if ($kt_id_cnt > 0) {
                $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 아이디입니다.');
            }
        }

        $user_model = $this->MUser->where(['f_phone' => $this->api_params->phone, 'f_status' => USER_COMMON])->get();
        if ($user_model != false) {
            $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 번호입니다.');
        }
        $cert_model = $this->MCert->where(['f_phone' => $this->api_params->phone, 'f_cert' => $this->api_params->auth])->get();
        if ($cert_model == false) {
            $this->_response_error(API_RES_ERR_INCORRECT, '인증번호를 확인해주세요.');
        }

        $insert_data = [
            'f_reg_time' => _get_current_time(),
            'f_login_type' => $this->api_params->type,
            'f_id' => $this->api_params->usr_id, //$this->api_params->type == LOGIN_ID ? $this->api_params->usr_id : '',
            'f_kt_id' => $this->api_params->type == LOGIN_KAKAO ? $this->api_params->kakao_id : '',
//            'f_pwd' => $this->api_params->type == LOGIN_ID ? _hash_pwd($this->api_params->pwd) : '',
            'f_pwd' => _hash_pwd($this->api_params->pwd),
            'f_name' => $this->api_params->name,
            'f_birthday' => $this->api_params->birthday,
            'f_phone' => $this->api_params->phone,
            'f_gender' => $this->api_params->gender == 1 ? 'M' : 'F',
            'f_market_status' => $this->api_params->adver,
            'f_watch_cnt' => 10,                        // 회원가입시 광고시청갯수가 10개 적립된다.
            'f_device' => $this->api_params->device,
            'f_fcm_token' => $this->api_params->token
        ];

        $inserted_id = $this->MUser->insert($insert_data);
        if ($inserted_id === false) {
            $this->_response_error(API_RES_ERR_DB);
        }

        $this->_response_success();
    }

    public function get_profile()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false || $user_model->f_status == USER_EXIT) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }
        if ($user_model->f_sess_token != $this->api_params->token) {
            $this->_response_error(API_RES_ERR_PRIVILEGE);
        }

        // 수령신청가능한 금액
        $subscribe_cost = $this->db->query("
                SELECT
                    IFNULL(SUM(W.f_cost), 0) cost
                FROM
                    t_winner W
                JOIN t_answer A ON W.f_answer = A.f_idx
                JOIN t_user U ON U.f_idx = A.f_user
                LEFT JOIN t_withdraw D ON D.f_winner = W.f_idx
                WHERE U.f_idx = {$user_model->f_idx} AND D.f_idx IS NULL
                ")
            ->row('cost');

        $this->_response_success([
            'info' => [
                'id' => $user_model->f_idx,
                'nickname' => $user_model->f_name,
                'usr_id' => $user_model->f_id,
                'pwd' => '',
                'birth' => $user_model->f_birthday,
                'phone' => $user_model->f_phone,
                'gender' => $user_model->f_gender == 'M' ? 1 : 2,
                'bank' => is_null($user_model->f_account) ? '' : $user_model->f_account,
                'bank_name' => is_null($user_model->f_bank) ? '' : $user_model->f_bank,
                'depositor' => is_null($user_model->f_depositor) ? '' : $user_model->f_depositor,
                'bank_status' => $user_model->f_account_status,
                'kt_id' => $user_model->f_kt_id,
                'sess_token' => $user_model->f_sess_token,
                'login_type' => $user_model->f_login_type,
                'whole_winning_money' => (float)$user_model->f_reward, //총상금
                'subscribe_cost' => $subscribe_cost,
                'available_ad_count' => (int)$user_model->f_watch_cnt, //볼수 있는 광고갯수
                'subscribe_count' => (int)$this->db->where(['f_user' => $user_model->f_idx, 'f_status' => 1])->count_all_results('t_answer'), //응모권갯수
                'agree_gamead' => (int)$user_model->f_game_alarm,//게임광고 설정? 0: 아니, 1:예
                'agree_videoad' => (int)$user_model->f_video_alarm,//영상광고 설정? 0: 아니, 1:예
                'agree_adkeyword' => (int)$user_model->f_keyword_alarm,//맞춤키워드 설정? 0: 아니, 1:예
                'agree_vibrate' => (int)$user_model->f_vibrate_alarm,//진동 설정? 0: 아니, 1:예
                'alarm_wining_result' => (int)$user_model->f_result_alarm,//당첨결과 알람설정? 0: 아니, 1:예
                'alarm_friend_msg' => (int)$user_model->f_friend_alarm,//친구 메시지 설정? 0: 아니, 1:예
                'alarm_notice' => (int)$user_model->f_notice_alarm,//공지사항알람 설정? 0: 아니, 1:예
                'alarm_puzzle' => (int)$user_model->f_puzzle_alarm,//잔여퍼즐 알람 설정? 0: 아니, 1:예
                'puzzle_time' => (int)$user_model->f_puzzle_time//퍼즐알림시간
            ]]);
    }

    public function reset_pwd()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->where(['f_phone' => $this->api_params->phone, 'f_status' => USER_COMMON])->get();
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

//        $this->load->helper('string');
//        $new_pwd = random_string('nozero', 6);

        $update_result = $this->MUser->update(['f_pwd' => _hash_pwd($this->api_params->pwd)], $user_model->f_idx);
        if ($update_result === false) {
            $this->_response_error(API_RES_ERR_DB);
        }

//        $arrPhone = explode(' ', $this->api_params->id);
//        if (count($arrPhone) > 1) {
//            $this->sendSMS($arrPhone[1], '회원님의 베네 임시 비밀번호는 [' . $new_pwd . '] 입니다.');
//        } else {
//            $this->sendSMS($arrPhone[0], '회원님의 Bloggy 임시 비밀번호는 [' . $new_pwd . '] 입니다.');
//        }

        $this->_response_success([
//            'pwd' => DEV_MODE ? $new_pwd : ''
        ]);
    }

    public function find_id()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->where(['f_phone' => $this->api_params->phone, 'f_status' => USER_COMMON])->get();
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST, '가입되지 않은 번호입니다.');
        }
        $cert_model = $this->MCert->where(['f_phone' => $this->api_params->phone, 'f_cert' => $this->api_params->cert_key])->get();
        if ($cert_model == false) {
            $this->_response_error(API_RES_ERR_INCORRECT, '인증번호를 확인해주세요.');
        }

        switch (strlen($user_model->f_id)) {
            case 2:
                $user_model->f_id = substr($user_model->f_id, 0, 1) . '*';
                break;
            case 3:
                $user_model->f_id = substr($user_model->f_id, 0, 1) . '*' . substr($user_model->f_id, 2);
                break;
            case 4:
                $user_model->f_id = substr($user_model->f_id, 0, 2) . '**';
                break;
            case 5:
                $user_model->f_id = substr($user_model->f_id, 0, 3) . '**';
                break;
            case 6:
                $user_model->f_id = substr($user_model->f_id, 0, 3) . '**' . substr($user_model->f_id, 5);
                break;
            default:
                $user_model->f_id = substr($user_model->f_id, 0, 3) . str_repeat('*', strlen($user_model->f_id) - 6) . substr($user_model->f_id, strlen($user_model->f_idx) - 3);
                break;
        }

        $this->_response_success([
            'usr_id' => $user_model->f_id
        ]);
    }

    public function get_adver()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $response = [
            'id' => 0,
            'name' => '',
            'cost' => 0,
            'ad_type' => 1,
            'ad_image' => '',
            'ad_video' => '',
            'log' => 0,
            'url' => ''
        ];


        $where_base = '1 = 1';

        // 회원이 게임알람을 OFF 한 경우 비디오 광고만 노출
        $where_type = $where_base;
        if ($user_model->f_game_alarm == 0) {
            $where_type .= ' AND A.f_type = "V"';
        }
        // 회원이 영상알람을 OFF 한 경우 게임 광고만 노출
        if ($user_model->f_video_alarm == 0) {
            $where_type .= ' AND A.f_type = "G"';
        }


        // 타깃 성별 필터링
        $where_gender = $where_type;
        $where_gender .= ' AND (A.f_target_gender = "N" OR A.f_target_gender = "' . $user_model->f_gender . '")';


        // 타깃 연령대 필터링
        $where_age = $where_gender;
        $where_age .= ' AND ((A.f_from = "" AND A.f_to = "") OR (A.f_from <= "' . substr($user_model->f_birthday, 0, 4) . '" AND A.f_to >= "' . substr($user_model->f_birthday, 0, 4) . '"))';


        // 맞춤 키워드 필터링
        $where_keyword = $where_age;

        // 회원이 선택한 맞춤키워드리스트틀 블러 온다.
        $user_keyword_list = $this->db
            ->get('t_keyword_like')
            ->result();

        if (count($user_keyword_list) > 0) {
            $where_keyword .= ' AND (1 <> 1';
            foreach ($user_keyword_list as $keyword) {
                $where_keyword .= ' OR A.f_keyword LIKE "%{' . $keyword->f_keyword . '}%"';
            }
            $where_keyword .= ' )';
        }


        // 만일 필터링한 결과가 없다면 임의로 광고하나를 선택
        $ad_list = $this->db
            ->join('t_advertise A', 'A.f_idx = V.id')
            ->where($where_keyword)
            ->order_by('V.ratio', 'DESC')
            ->limit(20, 0)
            ->get('v_advertise V')
            ->result();
        if (count($ad_list) < 1) {
            $ad_list = $this->db
                ->join('t_advertise A', 'A.f_idx = V.id')
                ->where($where_age)
                ->order_by('V.ratio', 'DESC')
                ->limit(20, 0)
                ->get('v_advertise V')
                ->result();
        }
        if (count($ad_list) < 1) {
            $ad_list = $this->db
                ->join('t_advertise A', 'A.f_idx = V.id')
                ->where($where_gender)
                ->order_by('V.ratio', 'DESC')
                ->limit(20, 0)
                ->get('v_advertise V')
                ->result();
        }
        if (count($ad_list) < 1) {
            $ad_list = $this->db
                ->join('t_advertise A', 'A.f_idx = V.id')
                ->where($where_type)
                ->order_by('V.ratio', 'DESC')
                ->limit(20, 0)
                ->get('v_advertise V')
                ->result();
        }
        if (count($ad_list) < 1) {
            $ad_list = $this->db
                ->join('t_advertise A', 'A.f_idx = V.id')
                ->where($where_base)
                ->order_by('V.ratio', 'DESC')
                ->limit(20, 0)
                ->get('v_advertise V')
                ->result();
        }

        if (count($ad_list) > 0) {
            shuffle($ad_list);
            $ad_model = $ad_list[0];

            // 광고노출로그저장
            $log_result = $this->MStatistics->insert([
                'f_reg_time' => _get_current_time(),
                'f_user' => $user_model->f_idx,
                'f_advertise' => $ad_model->f_idx,
            ]);

            $response['id'] = $ad_model->f_idx;
            $response['name'] = $ad_model->f_name;
            $response['cost'] = $ad_model->f_visible_cost;
            $response['ad_type'] = $ad_model->f_type == 'G' ? ($ad_model->f_game_type == 'S' ? 2 : 3) : 1;
            $response['ad_image'] = _get_file_url($ad_model->f_media);
            $response['ad_video'] = _get_file_url($ad_model->f_media);
            $response['log'] = $log_result;
            $response['url'] = $ad_model->f_link == null ? '' : $ad_model->f_link;
        }


        $this->_response_success([
            'info' => $response
        ]);
    }

    public function view_ad()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);


        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $ad_model = $this->MAdvertise->get($this->api_params->ad);
        if ($ad_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $statistics_model = $this->db->where('f_idx', $this->api_params->log)->get('t_statistics')->row();
        if (is_null($statistics_model)) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $this->_response_success();
    }

    public function ad_complete()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);


        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $ad_model = $this->MAdvertise->get($this->api_params->ad_id);
        if ($ad_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $statistics_model = $this->db->where('f_idx', $this->api_params->log)->get('t_statistics')->row();
        if (is_null($statistics_model)) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }


        // 회원의 광고시청갯수 1감소
        $this->db->where('f_idx', $user_model->f_idx)->set('f_watch_cnt', 'f_watch_cnt-1', false)->update('t_user');


        // added by Gambler 2019-10-15
        // 회원의 맞춤키워드알람이 ON 인 경우 광고의 키워드를 자동획득한다.
        // 보러가기를 누른 경우에만 맞춤키워드 자동 설정, 2019.11.04
        if ($user_model->f_keyword_alarm == 1 && !empty($ad_model->f_keyword) && $this->api_params->link == 1) {
            $arr_ad_keyword = _get_array($ad_model->f_keyword);

            if (_is_array($arr_ad_keyword)) {
                $arr_insert = [];

                foreach ($arr_ad_keyword as $item) {
                    $keyword_model = $this->db->where(['f_user' => $user_model->f_idx, 'f_keyword' => $item])->get('t_keyword_like')->row();
                    if (is_null($keyword_model)) {
                        array_push($arr_insert, ['f_user' => $user_model->f_idx, 'f_keyword' => $item]);
                    }
                }

                if (_is_array($arr_insert)) {
                    $this->db->insert_batch('t_keyword_like', $arr_insert);
                }
            }
        }


        // 회원의 상금에 1회노출시 적립금을 부가한다.
        $this->db->where('f_idx', $user_model->f_idx)->update('t_user', ['f_reward' => $user_model->f_reward + $ad_model->f_visible_cost]);


        // 광고 URL 클릭여부 반영
        $this->db->where('f_idx', $statistics_model->f_idx)->update('t_statistics', ['f_url_cnt' => $this->api_params->link]);


        // 관리자의 상금누적로그를 저장한다.
        $admin_cost_model = $this->db->where('f_reg_time', _get_current_date())->get('t_admin_cost')->row();
        if (is_null($admin_cost_model)) {
            $this->db->insert('t_admin_cost', [
                'f_reg_time' => _get_current_date(),
                'f_cost' => $ad_model->f_admin_cost
            ]);
        } else {
            $this->db->where('f_idx', $admin_cost_model->f_idx)->set('f_cost', 'f_cost+' . $ad_model->f_admin_cost, false)->update('t_admin_cost');
        }


        // 응모권을 발행한다.
        $this->MAnswer->insert([
            'f_reg_time' => _get_current_time(),
            'f_user' => $user_model->f_idx,
            'f_advertise' => $ad_model->f_idx,
            'f_statistics' => $statistics_model->f_idx,
            'f_no' => _get_random_string(6, 'alpha_c'),
            'f_round' => $this->db->query('SELECT IFNULL(MAX(f_round), 0) `round` FROM t_answer WHERE f_status = 0')->row('round') + 1
        ]);


        $this->_response_success();
    }

    public function change_account()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false || $user_model->f_status == USER_EXIT) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $update_data = [
            'f_bank' => $this->api_params->bank,
            'f_account' => $this->api_params->account,
            'f_depositor' => $this->api_params->depositor,
            'f_account_status' => $this->api_params->bank_status,
        ];

        $this->MUser->update($update_data, $user_model->f_idx);

        $this->_response_success();
    }

    public function change_profile()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false || $user_model->f_status == USER_EXIT) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }
//        if ($user_model->f_sess_token != $this->api_params->token) {
//            $this->_response_error(API_RES_ERR_PRIVILEGE);
//        }

        $other_user_model = $this->MUser->where(['f_id' => $this->api_params->usr_id, 'f_status' => USER_COMMON])->get();
        if ($other_user_model != false && $other_user_model->f_idx != $user_model->f_idx) {
            $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 아이디입니다.');
        }
        $other_user_model = $this->MUser->where(['f_phone' => $this->api_params->phone, 'f_status' => USER_COMMON])->get();
        if ($other_user_model != false && $other_user_model->f_idx != $user_model->f_idx) {
            $this->_response_error(API_RES_ERR_DUPLICATE, '이미 가입된 번호입니다.');
        }
        if ($user_model->f_phone != $this->api_params->phone) {
            $cert_model = $this->MCert->where(['f_phone' => $this->api_params->phone, 'f_cert' => $this->api_params->auth])->get();
            if ($cert_model == false) {
                $this->_response_error(API_RES_ERR_INCORRECT, '인증번호를 확인해주세요.');
            }
        }


        $update_data = [
            'f_id' => $this->api_params->usr_id,
            'f_name' => $this->api_params->name,
            'f_birthday' => $this->api_params->birthday,
            'f_gender' => $this->api_params->gender == 1 ? 'M' : 'F',
            'f_phone' => $this->api_params->phone,
            'f_account' => $this->api_params->bank,
            'f_account_status' => $this->api_params->bank_status,
            'f_device' => $this->api_params->device,
            'f_fcm_token' => $this->api_params->token
        ];
        if (!empty($this->api_params->pwd)) {
            $update_data['f_pwd'] = _hash_pwd($this->api_params->pwd);
        }

        $this->MUser->update($update_data, $user_model->f_idx);

        $user_model = $this->MUser->get($this->api_params->id);

        $this->_response_success([
            'info' => [
                'id' => $user_model->f_idx,
                'nickname' => $user_model->f_name,
                'usr_id' => $user_model->f_id,
                'pwd' => '',
                'birth' => $user_model->f_birthday,
                'phone' => $user_model->f_phone,
                'gender' => $user_model->f_gender == 'M' ? 1 : 2,
                'bank' => is_null($user_model->f_account) ? '' : $user_model->f_account,
                'bank_status' => $user_model->f_account_status,
                'kt_id' => $user_model->f_kt_id,
                'sess_token' => $user_model->f_sess_token,
                'login_type' => $user_model->f_login_type,
                'whole_winning_money' => (int)$user_model->f_reward, //총상금
                'available_ad_count' => (int)$user_model->f_watch_cnt, //볼수 있는 광고갯수
                'subscribe_count' => (int)$this->db->where('f_user', $user_model->f_idx)->count_all_results('t_answer'), //응모권갯수
                'agree_gamead' => (int)$user_model->f_game_alarm,//게임광고 설정? 0: 아니, 1:예
                'agree_videoad' => (int)$user_model->f_video_alarm,//영상광고 설정? 0: 아니, 1:예
                'agree_adkeyword' => (int)$user_model->f_keyword_alarm,//맞춤키워드 설정? 0: 아니, 1:예
                'agree_vibrate' => (int)$user_model->f_vibrate_alarm,//진동 설정? 0: 아니, 1:예
                'alarm_wining_result' => (int)$user_model->f_result_alarm,//당첨결과 알람설정? 0: 아니, 1:예
                'alarm_friend_msg' => (int)$user_model->f_friend_alarm,//친구 메시지 설정? 0: 아니, 1:예
                'alarm_notice' => (int)$user_model->f_notice_alarm,//공지사항알람 설정? 0: 아니, 1:예
                'alarm_puzzle' => (int)$user_model->f_puzzle_alarm,//잔여퍼즐 알람 설정? 0: 아니, 1:예
            ]]);
    }

    public function alarm_set()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        switch ($this->api_params->kind) {
            case 'gamead':
                $column = 'f_game_alarm';
                break;
            case 'videoad':
                $column = 'f_video_alarm';
                break;
            case 'adkeyword':
                $column = 'f_keyword_alarm';
                break;
            case 'vibrate':
                $column = 'f_vibrate_alarm';
                break;
            case 'winningresult':
                $column = 'f_result_alarm';
                break;
            case 'friendmsg':
                $column = 'f_friend_alarm';
                break;
            case 'notice':
                $column = 'f_notice_alarm';
                break;
            case 'puzzle':
                $column = 'f_puzzle_alarm';
                break;
            case 'puzzle_time':
                $column = 'f_puzzle_time';
                break;
            default:
                $column = '';
        }

        $this->MUser->update([$column => $this->api_params->value], $user_model->f_idx);

        $this->_response_success();
    }

    public function signout()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST, '회원이 존재하지 않습니다.');
        }
        if (!_verify_pwd($this->api_params->pwd, $user_model->f_pwd)) {
            $this->_response_error(API_RES_ERR_INCORRECT, '비밀번호를 확인해주세요.');
        }

        $this->MUser->update(['f_status' => 2], $user_model->f_idx);

        $this->_response_success();
    }

    public function friends_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        $kakao_friend_list = explode(',', $this->api_params->friend);
//        $kakao_friend_list = explode(',', '');// 테스트용 임시 수정 2019.10.31

        if ($this->api_params->type == 0 && count($kakao_friend_list) < 1) {
            $this->_response_success([
                'page_cnt' => 0,
                'list' => []
            ]);
        }

        if ($this->api_params->type == 0) {
            // 친구 리스트얻기
            $friend_list = $this->db
                ->select('U.f_idx id, U.f_name name, U.f_kt_id face, 
                (SELECT COUNT(*) FROM t_gift G WHERE G.f_reg_time = "' . _get_current_date() . '" AND G.f_from = ' . $user_model->f_idx . ' AND G.f_to = U.f_idx) cnt_sendgift, 
                0 cnt_introduce', false)
                ->where('U.f_status', USER_COMMON)
                ->like('U.f_name', $this->api_params->search)
                ->where_in('U.f_kt_id', $kakao_friend_list)
                ->limit($limit, $offset)
                ->get('t_user U')
                ->result();
            $total_cnt = $this->db
                ->where('f_status', USER_COMMON)
                ->like('f_name', $this->api_params->search)
                ->where_in('f_kt_id', $kakao_friend_list)
                ->count_all_results('t_user');
        } else {
            // 초대하기 리스트얻기
            $friend_list = $this->db
                ->select('GROUP_CONCAT(U.f_kt_id) face', false)
                ->where('U.f_status', USER_COMMON)
//                ->like('U.f_name', $this->api_params->search)
                ->where_in('U.f_kt_id', $kakao_friend_list)
//                ->limit($limit, $offset)
                ->get('t_user U')
                ->row();
            $total_cnt = 0;


            // 친구가 아닌 카톡아이디 필터
            $my_kakao_friend_list = explode(',', $friend_list->face);
            $kakao_friend_list = array_filter($kakao_friend_list, function ($friend) use ($my_kakao_friend_list) {
                return !in_array($friend, $my_kakao_friend_list);
            });


            // 내가 오늘 초대한 카톡친구 리스트 블러 오기
            $today_invite_list = $this->db
                ->select('GROUP_CONCAT(f_kt_id) invite', false)
                ->where(['f_reg_time' => _get_current_date(), 'f_user' => $user_model->f_idx])
                ->get('t_introduce')
                ->row();

            $friend_list = [];
            $invite_list = explode(',', $today_invite_list->invite);

            foreach ($kakao_friend_list as $kakao) {
                array_push($friend_list, [
                    'id' => 0,
                    'name' => '',
                    'face' => $kakao,
                    'cnt_sendgift' => 0,
                    'cnt_introduce' => in_array($kakao, $invite_list) ? 1 : 0,
                    'profile' => ''
                ]);
            }
        }

        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $friend_list
        ]);

    }

    public function friend_msg_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        $gift_list = $this->db
            ->select('G.f_idx id, U.f_idx friend_id, U.f_name name, U.f_kt_id face, 1 receivegift_status')
            ->join('t_user U', 'U.f_idx = G.f_from')
            ->where(['G.f_to' => $user_model->f_idx, 'G.f_reg_time' => _get_current_date()])
            ->limit($limit, $offset)
            ->get('t_gift G')
            ->result();
        $total_cnt = $this->db
            ->where(['G.f_to' => $user_model->f_idx, 'G.f_reg_time' => _get_current_date()])
            ->count_all_results('t_gift G');

        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $gift_list
        ]);
    }

    public function req_friend_action()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $friend_model = $this->MUser->get($this->api_params->friend);
        if ($friend_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }


        if ($this->api_params->action == 'present') {

            // 선물하기
            $gift_model = $this->MGift->where(['f_reg_time' => _get_current_date(), 'f_from' => $user_model->f_idx, 'f_to' => $friend_model->f_idx])->get();
            if ($gift_model != false) {
                $this->_response_error(API_RES_ERR_DUPLICATE, '선물하기는 1일 1회 가능합니다.');
            }

            $this->MGift->insert([
                'f_reg_time' => _get_current_date(),
                'f_from' => $user_model->f_idx,
                'f_to' => $friend_model->f_idx,
                'f_status' => 1
            ]);


            // 푸시알림전송
            if ($friend_model->f_friend_alarm == 1) {
                $this->_send_push($friend_model->f_device, $friend_model->f_fcm_token, [
                    'id' => $user_model->f_idx,
                    'type' => 1,
                    'title' => '베네픽쳐 친구메시지 알림',
                    'message' => '친구에게 선물이 도착했습니다.'
                ]);
            }


            // 친구가 오늘 받은 선물 갯수, 친구 초대한 횟수를 블러 온다.
            $friend_take_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_to' => $friend_model->f_idx])->count_all_results('t_gift');
            $friend_invite_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_user' => $friend_model->f_idx])->count_all_results('t_introduce');

            if (($friend_take_cnt + $friend_invite_cnt) <= 90) {
                // 퍼즐 1개 획득
                $this->db->where('f_idx', $friend_model->f_idx)->set('f_watch_cnt', 'f_watch_cnt+1', false)->update('t_user');
            }


            // 오늘 내가 선물한 갯수, 친구 초대한 횟수를 블러 온다.
            $today_gift_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_from' => $user_model->f_idx])->count_all_results('t_gift');
            $today_invite_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_user' => $user_model->f_idx])->count_all_results('t_introduce');

            if (($today_gift_cnt + $today_invite_cnt) <= 90) {
                // 퍼즐 1개 획득
                $this->db->where('f_idx', $user_model->f_idx)->set('f_watch_cnt', 'f_watch_cnt+1', false)->update('t_user');
            } else {
                $this->_response_error(API_RES_ERR_INCORRECT, '하루 퍼즐획득수를 초과하였습니다.');
            }


        } else if ($this->api_params->action == 'introduce') {

            // 초대하기
            $invite_model = $this->MIntroduce->where(['f_reg_time' => _get_current_date(), 'f_user' => $user_model->f_idx, 'f_kt_id' => $this->api_params->kakao])->get();
            if ($invite_model != false) {
                $this->_response_error(API_RES_ERR_DUPLICATE, '친구초대는 1일 1회 가능합니다.');
            }

            $this->MIntroduce->insert([
                'f_reg_time' => _get_current_date(),
                'f_user' => $user_model->f_idx,
                'f_kt_id' => $this->api_params->kakao
            ]);

            // 오늘 내가 선물한 갯수, 친구 초대한 횟수를 블러 온다.
            $today_gift_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_from' => $user_model->f_idx])->count_all_results('t_gift');
            $today_invite_cnt = $this->db->where(['f_reg_time' => _get_current_date(), 'f_user' => $user_model->f_idx])->count_all_results('t_introduce');

            if (($today_gift_cnt + $today_invite_cnt) <= 90) {
                // 퍼즐 1개 획득
                $this->db->where('f_idx', $user_model->f_idx)->set('f_watch_cnt', 'f_watch_cnt+1', false)->update('t_user');
            } else {
                $this->_response_error(API_RES_ERR_INCORRECT, '하루 퍼즐획득수를 초과하였습니다.');
            }

        } else {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $this->_response_success();
    }

    public function get_whole_reward(){
        $whole_reward = $this->db->query("
                select sum(f_reward) as whole_reward from t_user where f_status = 1
                ")
            ->row('whole_reward');

        $this->_response_success([
            'whole_reward' => $whole_reward
        ]);
    }

    public function set_token()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);
        $this->db->where('f_idx', $this->api_params->id)->update('t_user', ['f_fcm_token' => $this->api_params->token]);
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
}

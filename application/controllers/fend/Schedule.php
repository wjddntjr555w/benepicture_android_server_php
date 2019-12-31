<?php

use app\controllers\fend\ApiController;

/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2019-08-02
 * Time: 오전 9:49
 */

///////////////////////////////////////////////////////////////////
//
// cron tab 에 저장할 스크립트
//
// 0 0 * * * curl -X GET http://15.164.112.250/api/schedule/format_watch_cnt
// 0 19 * * * curl -X GET http://15.164.112.250/api/schedule/notify_puzzle_left
// 0 */1 * * * curl -X GET http://15.164.112.250/api/schedule/delete_notice
// 0 0 * * 0 curl -X GET http://15.164.112.250/api/schedule/select_winner
///////////////////////////////////////////////////////////////////

class Schedule extends ApiController
{

    protected $models = ['MUser', 'MWinner', 'MNotice'];


    function __construct()
    {
        parent::__construct();
    }


    /**
     * 매일 자정에 모든 회원들의 일일 광고시청가능횟수를 10개로 초기화
     */
    public function format_watch_cnt()
    {
        // 이 부분때문에 404 에러가 계속 뜸
//        if (!$this->input->is_cli_request()) {
//            show_404();
//            return;
//        }

        // 회원들의 광고시청가능횟수를 10개로 초기화
        $this->db->where('f_status', USER_COMMON)->update('t_user', ['f_watch_cnt' => 10]);
    }


    /**
     * 1시간에 한번씩 회원이 설정한 퍼즐시간에 소진되지 않은 퍼즐갯수를 알림
     */
    public function notify_puzzle_left()
    {
//        if (!$this->input->is_cli_request()) {
//            show_404();
//            return;
//        }

        // 퍼즐알람이 ON 이고 퍼즐잔여수가 1이상인 회원리스트를 블러 온다.
        $curHour = date('G');
        $arr_user = $this->db->where(['f_puzzle_alarm' => 1, 'f_puzzle_time' => $curHour, 'f_watch_cnt>' => 0, 'f_status' => USER_COMMON])->get('t_user')->result();

        if (_is_array($arr_user)) {
            foreach ($arr_user as $user) {

                $this->MNotice->insert([
                    'f_reg_time' => _get_current_time(),
                    'f_user' => $user->f_idx,
                    'f_title' => '관리자메세지',
                    'f_content' => '오늘 사용하지 않은 Puzzle이 ' . $user->f_watch_cnt . '개 남았습니다!'
                ]);

                if ($user->f_device == DEVICE_ANDROID) {
                    $this->_send_push(DEVICE_ANDROID, $user->f_fcm_token, [
                        'id' => 0,
                        'type' => PUSH_TYPE_PUZZLE,
                        'title' => '베네픽쳐에서 알림니다.',
                        'message' => '오늘 사용하지 않은 Puzzle이 ' . $user->f_watch_cnt . '개 남았습니다!'
                    ]);
                } else {
                    $this->_send_push(DEVICE_IOS, $user->f_fcm_token, [
                        'id' => 0,
                        'type' => PUSH_TYPE_PUZZLE,
                        'title' => '베네픽쳐에서 알림니다.',
                        'message' => '오늘 사용하지 않은 Puzzle이 ' . $user->f_watch_cnt . '개 남았습니다!'
                    ]);
                }
            }
        }
    }


    /**
     * 1시간에 한번씩 생성후 24시간이 지난 공지삭제
     */
    public function delete_notice()
    {
//        if (!$this->input->is_cli_request()) {
//            show_404();
//            return;
//        }

//        $this->db->where('f_reg_time <= "' . _minus_days(_get_current_time(), 1) . '"', false)->delete('t_notice');
        $this->db->where('f_reg_time <= "' . _minus_days(_get_current_time(), 1) . '"')->delete('t_notice');
    }

    public function test_winner(){
        $total_reward =  27701.00;
        $cost = ($total_reward * 0.8 + ($total_reward * 0.2 % 5000));

        echo $cost;
    }

    /**
     * 매주 일요일 0시에 당첨자선택
     * 매주 일요일 0시에 당첨자선택
     */
    public function select_winner()
    {
//        if (!$this->input->is_cli_request()) {
//            show_404();
//            return;
//        }


        // 현재 진행하고 있는 회차수를 블러 온다.
        $round = $this->db->query('SELECT IFNULL(MAX(f_round), 0) `round` FROM t_answer WHERE f_status = 0')->row('round') + 1;


        // 총 모금액을 블러 온다.
        $total_reward = $this->db->select_sum('f_reward')->where('f_status', 1)->get('t_user')->row('f_reward');


        // 관리자가 이번 회차에 설정한 1등 당첨자가 있는지를 체크한다.
        $winner_model = $this->db->where('f_round', $round)->get('t_winner_default')->row();


        if (!is_null($winner_model)) {

            // 관리자가 1등으로 설정한 회원이 있다면
            $insert_data = [];

            $answer_model = $this->db->where(['f_status' => 1, 'f_user' => $winner_model->f_user, 'f_round' => $round])->order_by('f_reg_time', 'DESC')->get('t_answer')->row();

            array_push($insert_data, [
                'f_reg_time' => _get_current_time(),
                'f_answer' => is_null($answer_model) ? 0 : $answer_model->f_idx,
//                'f_user' => $winner_model->f_user,
                'f_round' => $round,
                'f_order' => 1,
                'f_cost' => $total_reward <= 25000 ? $total_reward : floor($total_reward * 0.8 + ($total_reward * 0.2 % 5000))
            ]);

            if ($total_reward > 25000) {
                $total_reward = $total_reward * 0.2;
            } else {
                $total_reward = 0;
            }

            // 2등 ~ 랜덤으로 선출
            $winner_list = $this->db
                ->where(['f_round' => $round, 'f_status' => 1, 'f_user<>' => $winner_model->f_user])
                ->order_by('RAND()')
                ->group_by('f_user')
                ->limit(floor($total_reward / 5000), 0)
                ->get('t_answer')
                ->result();


            for ($ind = 0; $ind < count($winner_list); $ind++) {
                $answer_model = $this->db->where(['f_status' => 1, 'f_user' => $winner_list[$ind]->f_user, 'f_round' => $round])->order_by('f_reg_time', 'DESC')->get('t_answer')->row();

                array_push($insert_data, [
                    'f_reg_time' => _get_current_time(),
                    'f_answer' => is_null($answer_model) ? 0 : $answer_model->f_idx,
//                    'f_user' => $winner_list[$ind]->f_user,
                    'f_round' => $round,
                    'f_order' => $ind + 2,
                    'f_cost' => 5000
                ]);
            }

            $this->MWinner->insert($insert_data);

        } else {

            // 없다면 랜덤으로 최대 10명을 당첨자로 설정
            $gold_cost = $total_reward <= 25000 ? $total_reward : floor($total_reward * 0.8 + ($total_reward * 0.2 % 5000));
            $limit = $total_reward <= 25000 ? 0 : floor($total_reward * 0.2 / 5000);


            $winner_list = $this->db
                ->where(['f_round' => $round, 'f_status' => 1])
                ->order_by('RAND()')
                ->group_by('f_user')
                ->limit($limit + 1, 0)
                ->get('t_answer')
                ->result();

            $insert_data = [];
            for ($ind = 0; $ind < count($winner_list); $ind++) {
                $answer_model = $this->db->where(['f_status' => 1, 'f_user' => $winner_list[$ind]->f_user, 'f_round' => $round])->order_by('f_reg_time', 'DESC')->get('t_answer')->row();

                array_push($insert_data, [
                    'f_reg_time' => _get_current_time(),
                    'f_answer' => is_null($answer_model) ? 0 : $answer_model->f_idx,
//                    'f_user' => $winner_list[$ind]->f_user,
                    'f_round' => $round,
                    'f_order' => $ind + 1,
                    'f_cost' => $ind == 0 ? $gold_cost : 5000
                ]);
            }

            $this->MWinner->insert($insert_data);

        }


        // 응모권을 삭제상태로 변경
        $this->db->where('f_status', 1)->set('f_status', 0)->update('t_answer');


        // 회원들의 총 상금을 0으로 리셋
        $this->db->where('f_status', 1)->set('f_reward', 0)->update('t_user');


        // 당첨자발표알림푸시전송
        $arr_user = $this->db->where(['f_status' => 1, 'f_result_alarm' => 1])->get('t_user')->result();
        if (_is_array($arr_user)) {
            $android_user = [];
            $ios_user = [];

            foreach ($arr_user as $user) {
                if ($user->f_device == DEVICE_ANDROID) {
                    array_push($android_user, $user->f_fcm_token);
                } else {
                    array_push($ios_user, $user->f_fcm_token);
                }
            }

            if (_is_array($android_user)) {
                $this->_send_push(DEVICE_ANDROID, $android_user, [
                    'id' => 0,
                    'type' => PUSH_TYPE_WIN,
                    'title' => '베네픽쳐알림',
                    'message' => '당첨자가 발표 났습니다.'
                ]);
            }
            if (_is_array($ios_user)) {
                $this->_send_push(DEVICE_IOS, $ios_user, [
                    'id' => 0,
                    'type' => PUSH_TYPE_WIN,
                    'title' => '베네픽쳐알림',
                    'message' => '당첨자가 발표 났습니다.'
                ]);
            }
        }
    }
}

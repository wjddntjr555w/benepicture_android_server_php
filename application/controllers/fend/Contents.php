<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-02
 * Time: 오전 9:49
 */

use app\controllers\fend\ApiController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends ApiController
{

    protected $models = ['MUser', 'MNotice', 'MNoticeCheck', 'MAnswer', 'MAdvertise', 'MWithdraw', 'MKeywordLike'];

    function __construct()
    {
        parent::__construct();
        $this->load->helper('nusoap_tongkni');
    }

    public function msg_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        switch ($this->api_params->type) {
            case 0:
                $where = '`read` = 0';
                break;
            case 1:
                $where = '`read` = 1';
                break;
            default:
                $where = '1 = 1';
                break;
        }

        $notice_list = $this->db
            ->select('N.f_idx id, N.f_reg_time datetime, N.f_title title, N.f_content content, IF(ISNULL(C.f_idx), 0, 1) `read`, N.f_user msg_type', false)
            ->join('t_notice_check C', 'C.f_user = ' . $user_model->f_idx . ' AND C.f_notice = N.f_idx', 'left')
            ->where_in('N.f_user', [0, $user_model->f_idx])
            ->having($where)
            ->order_by('N.f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_notice N')
            ->result();
        $total_cnt = $this->db
            ->select('N.f_idx id, N.f_reg_time datetime, N.f_title title, N.f_content content, IF(ISNULL(C.f_idx), 0, 1) `read`, N.f_user msg_type', false)
            ->join('t_notice_check C', 'C.f_user = ' . $user_model->f_idx . ' AND C.f_notice = N.f_idx', 'left')
            ->where_in('N.f_user', [0, $user_model->f_idx])
            ->having($where)
            ->order_by('N.f_reg_time', 'DESC')
            ->get('t_notice N')
            ->num_rows();


        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $notice_list
        ]);
    }

    public function subscribe_info_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        // 현재 진행하고 있는 회차수를 블러 온다.
        $round = $this->db->query('SELECT IFNULL(MAX(f_round), 0) `round` FROM t_answer WHERE f_status = 0')->row('round') + 1;

        $subscribe_list = $this->db
            ->select('A.f_idx id, DATE_FORMAT(A.f_reg_time, "%Y/%m/%d %H:%i") create_datetime, S.f_name subscribe_adname,
            A.f_no subscribe_number, 0 is_admin, DATE_FORMAT(A.f_reg_time, "%Y/%m/%d %H:%i") fund_datetime,
            IFNULL(DATE_FORMAT(W.f_reg_time, "%Y/%m/%d %H:%i"), "") take_datetime,
            IF(W.f_idx IS NULL, 0, 1) is_winning,
            A.f_round round, 0 current_round,
            IFNULL(W.f_cost, 0) winning_money')
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->join('t_winner W', 'A.f_idx = W.f_answer', 'LEFT')
            ->where(['A.f_user' => $user_model->f_idx/*, 'A.f_status' => 1*/])
            ->order_by('A.f_round', 'DESC')
            ->order_by('A.f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_answer A')
            ->result();
        $total_cnt = $this->db
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->join('t_winner W', 'A.f_idx = W.f_answer', 'LEFT')
            ->where(['A.f_user' => $user_model->f_idx/*, 'A.f_status' => 1*/])
            ->count_all_results('t_answer A');

        foreach ($subscribe_list as &$subscribe)
            $subscribe->current_round = $round;

        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $subscribe_list
        ]);
    }

    public function take_history_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        $history_list = $this->db
            ->select("  A.f_idx id,
                        DATE_FORMAT(W.f_reg_time, '%Y/%m/%d %H:%i') create_datetime,
                        S.f_name subscribe_adname,
                        A.f_no subscribe_number,
                        0 is_admin,
                        DATE_FORMAT(W.f_reg_time, '%Y/%m/%d %H:%i') take_datetime,
                        W.f_cost winning_money,
                        IF(ISNULL(D.f_idx), -1, D.f_status) `status`", false)
            ->join('t_answer A', 'A.f_idx = W.f_answer AND A.f_user = ' . $user_model->f_idx)
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->join('t_withdraw D', 'D.f_winner = W.f_idx', 'LEFT')
            ->order_by('W.f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->having($this->api_params->type == 1 ? '1 = 1' : '`status` = -1')
            ->get('t_winner W')
            ->result();
        $total_cnt = $this->db
            ->select("  A.f_idx id,
                        DATE_FORMAT(W.f_reg_time, '%Y/%m/%d %H:%i') create_datetime,
                        S.f_name subscribe_adname,
                        A.f_no subscribe_number,
                        0 is_admin,
                        DATE_FORMAT(W.f_reg_time, '%Y/%m/%d %H:%i') take_datetime,
                        W.f_cost winning_money,
                        IF(ISNULL(D.f_idx), -1, D.f_status) `status`", false)
            ->join('t_answer A', 'A.f_idx = W.f_answer AND A.f_user = ' . $user_model->f_idx)
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->join('t_withdraw D', 'D.f_winner = W.f_idx', 'LEFT')
            ->having($this->api_params->type == 1 ? '1 = 1' : '`status` = -1')
            ->get('t_winner W')
            ->num_rows();

        foreach ($history_list as &$history) {
            $history->status += 1;
        }

        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $history_list
        ]);
    }

    public function review_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);

        $where = $this->api_params->lottery == 0 ? '' : ' AND W.f_round = ' . $this->api_params->lottery;

        $review_list = $this->db
            ->select("
                        W.f_idx id,
                        DATE_FORMAT(W.f_content_time, '%Y.%m.%d') create_date,
                        S.f_name subscribe_adname,
                        A.f_no subscribe_number,
                        IF (U.f_login_type = 1, U.f_id, U.f_kt_id) user_id,
                        0 is_admin,
                        W.f_round round,
                        W.f_content content,
                        W.f_cost winning_money")
            ->join('t_answer A', 'A.f_idx = W.f_answer')
            ->join('t_user U', 'A.f_user = U.f_idx')
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->where('W.f_public = 1 OR (W.f_public = 0 AND A.f_user = ' . $user_model->f_idx . ') AND W.f_content IS NOT NULL' . $where)
            ->order_by('W.f_cost', 'DESC')
            ->order_by('W.f_content_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_winner W')
            ->result();
        $total_cnt = $this->db
            ->join('t_answer A', 'A.f_idx = W.f_answer')
            ->join('t_user U', 'A.f_user = U.f_idx')
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->where('W.f_public = 1 OR (W.f_public = 0 AND A.f_user = ' . $user_model->f_idx . ') AND W.f_content IS NOT NULL' . $where)
            ->count_all_results('t_winner W');

        foreach ($review_list as &$review) {
            switch (strlen($review->user_id)) {
                case 2:
                    $review->user_id = substr($review->user_id, 0, 1) . '*';
                    break;
                case 3:
                    $review->user_id = substr($review->user_id, 0, 1) . '*' . substr($review->user_id, 2);
                    break;
                case 4:
                    $review->user_id = substr($review->user_id, 0, 2) . '**';
                    break;
                case 5:
                    $review->user_id = substr($review->user_id, 0, 3) . '**';
                    break;
                case 6:
                    $review->user_id = substr($review->user_id, 0, 3) . '**' . substr($review->user_id, 5);
                    break;
                default:
                    $review->user_id = substr($review->user_id, 0, 3) . str_repeat('*', strlen($review->user_id) - 6) . substr($review->user_id, strlen($review->user_id) - 3);
                    break;
            }
        }

        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $review_list
        ]);
    }

    public function take_reward()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $answer_model = $this->MAnswer->where(['f_user' => $user_model->f_idx, 'f_no' => $this->api_params->number])->get();
        if ($answer_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $winner_model = $this->db->where('f_answer', $answer_model->f_idx)->get('t_winner')->row();
        if (is_null($winner_model)) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        if ($user_model->f_account_status == 0) {
            // 회원의 계좌인증이 미인증이라면 계좌정보변경
            $this->MUser->update([
                'f_bank' => $this->api_params->bank,
                'f_account' => $this->api_params->account,
                'f_account_status' => 1
            ], $user_model->f_idx);
        }

        // 후기내용을 저장
        $this->db
            ->where(['f_answer' => $answer_model->f_idx])
            ->update('t_winner', [
                'f_content' => $this->api_params->review,
                'f_content_time' => _get_current_time(),
                'f_public' => 1
            ]);

        $this->MWithdraw->insert([
            'f_reg_time' => _get_current_time(),
            'f_user' => $user_model->f_idx,
            'f_answer' => $answer_model->f_idx,
            'f_winner' => $winner_model->f_idx,
            'f_name' => $this->api_params->name,
            'f_bank' => $user_model->f_account_status == 1 ? $user_model->f_bank : $this->api_params->bank,
            'f_account' => $user_model->f_account_status == 1 ? $user_model->f_account : $this->api_params->account,
            'f_cost' => $this->api_params->cost,
            'f_status' => 0
        ]);

        $this->_response_success();
    }


    public function lottery()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $lottery_list = $this->db
            ->select('W.f_round id, "" title, DATE_FORMAT(W.f_reg_time, "%Y.%m.%d") `date`, 3 period, A.f_user winner, 0 `status`', false)
            ->join('t_answer A', 'A.f_idx = W.f_answer')
            ->where('W.f_order', 1)
            ->order_by('W.f_round', 'ASC')
            ->get('t_winner W')
            ->result();

        foreach ($lottery_list as &$lotter) {
            if ($lotter->winner == $user_model->f_idx) {
                $lotter->status = 3;
            } else {
                $win_model = $this->db
                    ->join('t_answer A', 'A.f_idx = W.f_answer AND A.f_user = ' . $user_model->f_idx)
                    ->where('W.f_round', $lotter->id)
                    ->get('t_winner W')
                    ->row();

                if (is_null($win_model)) {
                    $lotter->status = 1;
                } else {
                    $lotter->status = 2;
                }
            }
        }

        $this->_response_success([
            'list' => $lottery_list
        ]);
    }


    public function msg_read()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $notice_model = $this->MNotice->get($this->api_params->msg);
        if ($notice_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $check_model = $this->db->where(['f_user' => $user_model->f_idx, 'f_notice' => $notice_model->f_idx])->get('t_notice_check')->row();
        if (is_null($check_model)) {
            $this->MNoticeCheck->insert([
                'f_user' => $user_model->f_idx,
                'f_notice' => $notice_model->f_idx
            ]);
        }

        $this->_response_success();
    }

    public function msg_delete()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->usr_id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $notice_model = $this->MNotice->get($this->api_params->msg_id);
        if ($notice_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $this->db->where(['f_user' => $user_model->f_idx, 'f_idx' => $notice_model->f_idx])->delete('t_notice');

        $this->_response_success();
    }

    public function register()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $this->MAdvertise->insert([
            'f_reg_time' => _get_current_time(),
            'f_user' => $user_model->f_idx,
            'f_from' => $this->api_params->from,
            'f_to' => $this->api_params->to,
            'f_name' => $this->api_params->title,
            'f_visible_cnt' => $this->api_params->count,
            'f_type' => $this->api_params->type == 1 ? 'G' : 'V',
            'f_link' => $this->api_params->url,
            'f_media' => $this->api_params->file,
            'f_request' => 0
        ]);

        $this->_response_success();
    }

    public function keyword_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $parent_key_list = $this->db
            ->select('K.f_idx id, K.f_name name, 0 parent, IF(ISNULL(L.f_idx), 0, 1) `like`', false)
            ->join('t_keyword_like L', 'L.f_keyword = K.f_idx AND L.f_user = ' . $user_model->f_idx, 'LEFT')
            ->where(['K.f_parent' => 0])
            ->order_by('K.f_reg_time', 'asc')
            ->get('t_keyword K')
            ->result();

        foreach ($parent_key_list as &$parent) {
            $child_key_list = $this->db
                ->select('K.f_idx id, K.f_name name, K.f_parent parent, IF(ISNULL(L.f_idx), 0, 1) `like`', false)
                ->join('t_keyword_like L', 'L.f_keyword = K.f_idx AND L.f_user = ' . $user_model->f_idx, 'LEFT')
                ->where(['K.f_parent' => $parent->id])
                ->order_by('K.f_reg_time', 'asc')
                ->get('t_keyword K')
                ->result();
            $parent->child = $child_key_list;
        }

        $this->_response_success([
            'list' => $parent_key_list
        ]);
    }

    public function save_keyword()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $this->db->where('f_user', $user_model->f_idx)->delete('t_keyword_like');

        $keywordList = explode(',', $this->api_params->keyword);
        if (_is_array($keywordList)) {
            $insert = [];


            foreach ($keywordList as $item) {
                array_push($insert, [
                    'f_keyword' => $item,
                    'f_user' => $user_model->f_idx
                ]);
            }

            $this->MKeywordLike->insert($insert);
        }

        $this->_response_success();
    }

    public function winner_list()
    {
        $this->_check_params(__FUNCTION__, __CLASS__);

        $user_model = $this->MUser->get($this->api_params->id);
        if ($user_model == false) {
            $this->_response_error(API_RES_ERR_INFO_NO_EXIST);
        }

        $limit = LIMIT_20;
        $offset = _get_page_offset($this->api_params->page_num, $limit);


        $subscribe_list = $this->db
            ->select('A.f_idx id, 
                        A.f_no no,
                        IF (U.f_login_type = 1, U.f_id, U.f_kt_id) usr_id,
                        S.f_name ad_name,
                        SUBSTRING(U.f_phone FROM LENGTH(U.f_phone) - 3 FOR 4) phone,
                        W.f_cost cost', false)
            ->join('t_answer A', 'A.f_idx = W.f_answer')
            ->join('t_advertise S', 'S.f_idx = A.f_advertise')
            ->join('t_user U', 'U.f_idx = A.f_user')
            ->where(['W.f_round' => $this->api_params->round])
            ->order_by('W.f_order', 'ASC')
            ->limit($limit, $offset)
            ->get('t_winner W')
            ->result();
        $total_cnt = $this->db->where(['f_round' => $this->api_params->round])->count_all_results('t_winner');


        $this->_response_success([
            'page_cnt' => _get_page_count($total_cnt, $limit),
            'list' => $subscribe_list
        ]);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends AdminController
{
    public $_page_index = 0;
    public $_page_title = ["Dash Board"];
    public $_page_privilege = [];

    protected $models = ['MLogin', 'MNotice', 'MAdvertise'];

    function __construct()
    {
        parent::__construct();
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];
    }

    public function index()
    {
        $this->_page_sub_index = 0;


        //어제 가입자수
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $yesterday_login_cnt = $this->db
            ->where('f_reg_time LIKE "' . $yesterday . '%"')
            ->count_all_results('t_user');
//            ->where('f_reg_time', $yesterday)
//            ->count_all_results('t_login');


        //전체 가입자수
        $all_user_cnt = $this->db->where('f_status', USER_COMMON)->count_all_results('t_user');


        //미처리비용
        $non_deal_cost = $this->db->select_sum('f_cost')
            ->from('t_withdraw')
            ->where('DATEDIFF(NOW(),f_reg_time) <= 60 and f_status = ' . STATUS_FALSE)
            ->get()->row('f_cost');


        //미처리건
        $non_deal_cnt = $this->db
            ->where('DATEDIFF(NOW(),f_reg_time) <= 60 and f_status = ' . STATUS_FALSE)
            ->count_all_results('t_withdraw');


        //처리완료
        $done_deal_cost = $this->db->select_sum('f_cost')
            ->from('t_withdraw')
            ->where('DATEDIFF(NOW(),f_reg_time) <= 60 and f_status = ' . STATUS_TRUE)
            ->get()->row('f_cost');


        //이번 달 수익
        $this_month_reward = $this->db
            ->select_sum('f_cost')
            ->where(['f_reg_time>=' => _get_current_date('Y-m-01'), 'f_reg_time<=' => _get_current_date('Y-m-31')])
            ->get('t_admin_cost')
            ->row('f_cost');


        //지난 6달 수익
        $half_year_reward = $this->db
            ->select_sum('f_cost')
            ->where(['f_reg_time>=' => date('Y-m-d', strtotime('-6 months')), 'f_reg_time<=' => _get_current_date()])
            ->get('t_admin_cost')
            ->row('f_cost');


        // 실시간 상금누적
        $total_reward = $this->db->select_sum('f_cost')->where('f_reg_time', _get_current_date())->get('t_admin_cost')->row('f_cost');


        //신청대기중인 광고
        $request_advertise_cnt = $this->db->where(['f_request' => STATUS_WAITING])->count_all_results('t_advertise');


        //진행중인 광고
        $doing_advertise_cnt = $this->db
            ->where(['f_status' => 1, 'f_request' => 1, 'f_from <= ' => _get_current_date(), 'f_to >= ' => _get_current_date()])
            ->count_all_results('t_advertise');


        //완료된 광고
        $complete_advertise_cnt = $this->db
            ->where(['f_status' => 1, 'f_request' => 1, 'f_from > ' => _get_current_date(), 'f_to < ' => _get_current_date()])
            ->count_all_results('t_advertise');


        $this->render('main/index', $this->_page_privilege, [
            'yesterday_login_cnt' => $yesterday_login_cnt,
            'all_user_cnt' => $all_user_cnt,
            'non_deal_cost' => $non_deal_cost,
            'non_deal_cnt' => $non_deal_cnt,
            'done_deal_cost' => $done_deal_cost,
            'total_reward' => $total_reward,
            'this_month_reward' => $this_month_reward,
            'half_year_reward' => $half_year_reward,
            'doing_advertise_cnt' => $doing_advertise_cnt,
            'request_advertise_cnt' => $request_advertise_cnt,
            'complete_advertise_cnt' => $complete_advertise_cnt,
        ]);
    }

    public function table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $limit = $this->input->get('length');
        $offset = $this->input->get('start');

        $arr_banners = $this->db
            ->where('f_user', 0)
            ->order_by('f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_notice')
            ->result();

        $totalFiltered = $this->db
            ->where('f_user', 0)
            ->count_all_results('t_notice');

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        foreach ($arr_banners as $banner) {
            $nestedData = [];
            $nestedData[0] = $nestedData['no'] = $index;
            $nestedData[1] = $nestedData['content'] = $banner->f_content;
            $nestedData[2] = $nestedData['title'] = $banner->f_title;
            $nestedData[3] = $nestedData['id'] = $banner->f_idx;
            $nestedData[4] = $nestedData['reg_time'] = $banner->f_reg_time;

            $index--;
            array_push($data, $nestedData);
        }

        $json_data = array(
            "draw" => intval($this->input->get('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function delete()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $banner_id = $this->input->get('id');

        $this->db->where('f_idx', $banner_id)->delete('t_notice');
        $this->db->where('f_notice', $banner_id)->delete('t_notice_check');

        echo 'success';
    }

    public function save()
    {
        $banner = $this->input->post('Notice');

        $banner_model = $this->MNotice->get($banner['f_idx']);

        if ($banner_model == false) {
            unset($banner['f_idx']);

            $banner['f_reg_time'] = _get_current_time();
            $banner['f_user'] = 0;

            $insert_result = $this->MNotice->insert($banner);
            if ($insert_result == false) {
                echo 'fail';
                return;
            }

            // 공지사항 푸시알림전송
            $arr_user = $this->db->where(['f_status' => USER_COMMON, 'f_notice_alarm' => 1, 'f_fcm_token<>' => ''])->get('t_user')->result();
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
                        'id' => $insert_result,
                        'type' => PUSH_TYPE_NOTICE,
                        'title' => '베네픽쳐에서 알림니다.',
                        'message' => $banner['f_title']
                    ]);
                }
                if (_is_array($ios_user)) {
                    $this->_send_push(DEVICE_IOS, $ios_user, [
                        'id' => $insert_result,
                        'type' => PUSH_TYPE_NOTICE,
                        'title' => '베네픽쳐에서 알림니다.',
                        'message' => $banner['f_title']
                    ]);
                }
            }

            echo 'success';
        } else {
            $banner['f_reg_time'] = _get_current_time();

            unset($banner['f_idx']);
            $this->MNotice->update($banner, $banner_model->f_idx);
            echo 'success';
        }
    }

}

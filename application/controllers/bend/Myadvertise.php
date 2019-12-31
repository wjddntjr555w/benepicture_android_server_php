<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Myadvertise extends AdminController
{
    public $_page_index = 1;
    public $_page_title = ["나의광고"];
    public $_page_privilege = [];

    protected $models = ['MAdvertise', 'MUser', 'MKeyword', 'MStatistics'];

    function __construct()
    {
        parent::__construct();
        $this->_page_privilege = [ADMIN_ADVERTISE];
    }

    public function index()
    {
        $this->_page_sub_index = 0;

        $this->render('my_advertise/index', $this->_page_privilege);
    }

    public function table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $value = $this->input->get('search')['value'];

        $where = '1 = 1';
        $where .= ' And f_request = ' . STATUS_ALLOW;
        $where .= ' And f_user = ' . $_SESSION[ADMIN_IDX];

        if (!empty($value)) {
            $where .= ' AND f_name LIKE "%' . $value . '%"';
        }

        $arr_advertises = $this->db
            ->select('*')
            ->where($where)
            ->order_by('f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_advertise')
            ->result();

        $totalFiltered = $this->db
            ->where($where)
            ->count_all_results('t_advertise');

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        if (!empty($arr_advertises)) {
            foreach ($arr_advertises as $advertise) {
                $nestedData = [];
                $nestedData[0] = $nestedData['name'] = $advertise->f_name;
                $nestedData[1] = $nestedData['visible_cnt'] = $advertise->f_visible_cnt;
                $nestedData[2] = $nestedData['target_gender'] = $advertise->f_target_gender;
                $nestedData[3] = $nestedData['age_period'] = $advertise->f_age_from . '~' . $advertise->f_age_to;
                $nestedData[4] = $nestedData['visible_cost'] = $advertise->f_visible_cost;
                $nestedData[5] = $nestedData['admin_cost'] = $advertise->f_admin_cost;
                $nestedData[6] = $nestedData['type'] = $advertise->f_type;

                $current_time = date('Y-m-d');

                $status = '';
                if ($advertise->f_status == STATUS_FALSE)
                    $status = '일시정지';
                else if ($current_time >= $advertise->f_from && $current_time <= $advertise->f_to)
                    $status = '진행중';
                else if ($current_time <= $advertise->f_from)
                    $status = '대기중';
                else if ($current_time >= $advertise->f_to)
                    $status = '완료';

                $nestedData[7] = $nestedData['status'] = $status;
                $nestedData[8] = $nestedData['id'] = $advertise->f_idx;
                $nestedData[9] = $nestedData['link'] = $advertise->f_link;
                $nestedData[10] = $nestedData['game_type'] = $advertise->f_game_type;
                $nestedData[11] = $nestedData['media_url'] = _get_file_url($advertise->f_media);
                $nestedData[11] = $nestedData['media'] = $advertise->f_media;
                $nestedData[12] = $nestedData['from'] = $advertise->f_from;
                $nestedData[13] = $nestedData['to'] = $advertise->f_to;
                $nestedData[14] = $nestedData['age_from'] = $advertise->f_age_from;
                $nestedData[15] = $nestedData['age_to'] = $advertise->f_age_to;
                $nestedData[16] = $nestedData['user'] = $advertise->f_user;
                $nestedData[17] = $nestedData['keyword'] = _get_array($advertise->f_keyword);
                $nestedData[18] = $nestedData['request'] = _get_array($advertise->f_request);
                $nestedData[19] = $nestedData['f_status'] = $advertise->f_status;

                $index--;
                array_push($data, $nestedData);
            }
        }

        $json_data = array(
            "draw" => intval($this->input->get('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "memberCnt" => number_format(intval($totalFiltered)),
            "data" => $data
        );

        echo json_encode($json_data);
    }


    public function detail()
    {
        $this->_page_sub_index = 0;
        $id = $this->input->get('id');

        if ($id == false) {
            show_error('파라메터 오류');
            return;
        }

        //기본정보부분
        $advertise_info = $this->MAdvertise->get($id);
        $user_model = $this->MUser->get($advertise_info->f_user);
        $advertise_info->f_user = $user_model->f_name;      //광고주

        $keyword_arr = _get_array($advertise_info->f_keyword);
        $keyword_str = implode(',', $keyword_arr);
        if ($keyword_str != '')
            $advertise_info->f_keyword = $this->db->query('select CONCAT(f_name) as keyword_string from t_keyword where f_idx in (' . $keyword_str . ')')->row('keyword_string'); //키워드

        $advertise_info->f_media = _get_file_url($advertise_info->f_media);

        $current_time = _get_current_date();

        $status = '';
        if ($advertise_info->f_status == STATUS_FALSE)
            $status = '일시정지';
        else if ($current_time >= $advertise_info->f_from && $current_time <= $advertise_info->f_to)
            $status = '진행중';
        else if ($current_time <= $advertise_info->f_from)
            $status = '대기중';
        else if ($current_time >= $advertise_info->f_to)
            $status = '완료';
        $advertise_info->f_status = $status;

        // 통계부분
        $page_num = 1;
        $page_length = 7;
        $day_step = $page_num * $page_length;
//        $start_day = date_sub($current_day, date_interval_create_from_date_string($day_step.' days'));
//        $start_day = $this->db->query('select DATE_SUB(' . "'$current_day'" . ', INTERVAL ' . $day_step . ' DAY) as start_day')->row('start_day');
        $start_day = date('Y-m-d', strtotime('-' . $day_step . ' days', time()));
        $total_cnt = $this->db->where('f_advertise', $id)->count_all_results('t_statistics');

        // chart정보
        $category = array();
        $chart_visit_info = array();
        $chart_click_info = array();
        $statistics_info = array();
        for ($i = 1; $i <= 7; $i++) {
//            $moment_day = date_add($start_day, date_interval_create_from_date_string($i.' days'));
//            $moment_day = $this->db->query('select DATE_ADD(' . "'$start_day'" . ', INTERVAL ' . $i . ' DAY) as moment_day')->row('moment_day');
            $moment_day = date('Y-m-d', strtotime($i . ' days', strtotime($start_day)));
            $total_visit_cnt = $this->db->where('f_advertise', $id)->where('f_reg_time', $moment_day)->count_all_results('t_statistics');
            $total_click_cnt = $this->db->where('f_advertise', $id)->where('f_url_cnt', STATUS_TRUE)->where('f_reg_time', $moment_day)->count_all_results('t_statistics');

            $info_row = array(
                'day' => date('y.m.d', strtotime($moment_day)),
                'total_visit_cnt' => $total_visit_cnt,
                'url_click_cnt' => $total_click_cnt
            );

            array_push($category, "'$moment_day'");
            array_push($chart_visit_info, $total_visit_cnt);
            array_push($chart_click_info, $total_click_cnt);
            array_push($statistics_info, $info_row);
        }

        $all_visit_cnt = $this->db->where('f_advertise', $id)->count_all_results('t_statistics');
        $all_click_cnt = $this->db->where('f_advertise', $id)->where('f_url_cnt', STATUS_TRUE)->count_all_results('t_statistics');

        $age_statistics = array();
        for ($age = 10; $age <= 60; $age += 10) {
            $age_statistics_row = array(
                'ageM' => $this->db
                    ->select('*')
                    ->from('t_statistics as S')
                    ->join('t_user as A', 'S.f_user=A.f_idx', 'left')
                    ->where('S.f_advertise', $id)
                    ->where('A.f_gender', 'M')
                    ->where('A.f_birthday<=', date('Y', strtotime('-' . ($age - 10) . ' years')) . '.12.31')
                    ->where('A.f_birthday>=', date('Y', strtotime('-' . ($age - 1) . ' years')) . '.01.01')
                    ->count_all_results(),
                'ageF' => $this->db
                    ->select('*')
                    ->from('t_statistics as S')
                    ->join('t_user as A', 'S.f_user=A.f_idx', 'left')
                    ->where('S.f_advertise', $id)
                    ->where('A.f_gender', 'F')
                    ->where('A.f_birthday<=', date('Y', strtotime('-' . ($age - 10) . ' years')) . '.12.31')
                    ->where('A.f_birthday>=', date('Y', strtotime('-' . ($age - 1) . ' years')) . '.01.01')
                    ->count_all_results(),
            );

            array_push($age_statistics, $age_statistics_row);
        }

        array_push($age_statistics, array(
            'ageM' => $this->db
                ->select('*')
                ->from('t_statistics as S')
                ->join('t_user as A', 'S.f_user=A.f_idx', 'left')
                ->where('S.f_advertise', $id)
                ->where('A.f_gender', 'M')
                ->where('A.f_birthday<=', date('Y', strtotime('-' . 60 . ' years')) . '.12.31')
                ->count_all_results(),
            'ageF' => $this->db
                ->select('*')
                ->from('t_statistics as S')
                ->join('t_user as A', 'S.f_user=A.f_idx', 'left')
                ->where('S.f_advertise', $id)
                ->where('A.f_gender', 'F')
                ->where('A.f_birthday<=', date('Y', strtotime('-' . 60 . ' years')) . '.12.31')
                ->count_all_results(),
        ));

        $this->render('my_advertise/detail', $this->_page_privilege, [
            'statistics_info' => $statistics_info,
            'advertise_info' => $advertise_info,
            'all_visit_cnt' => $all_visit_cnt,
            'all_click_cnt' => $all_click_cnt,
            'age_statistics' => $age_statistics,
            'total_page' => ($total_cnt % $page_length) ? ($total_cnt / $page_length + 1) : ($total_cnt / $page_length),
            'page_num' => $page_num,
            'category' => $category,
            'total_visit_info' => $chart_visit_info,
            'total_click_info' => $chart_click_info
        ]);

    }

    public function get_page_data()
    {
        $advertise_id = $this->input->post('advertise_id');
        $page_num = $this->input->post('page_num');
        $page_length = 7;
        $current_day = date('Y-m-d');
        $day_step = $page_num * 7;
//        $start_day = date_sub($current_day, date_interval_create_from_date_string($day_step.' days'));
        $start_day = $this->db->query('select DATE_SUB(' . "'$current_day'" . ', INTERVAL ' . $day_step . ' DAY) as start_day')->row('start_day');

        $total_cnt = $this->db->where('f_advertise', $advertise_id)->count_all_results('t_statistics');

        //chart정보
        $category = array();
        $chart_visit_info = array();
        $chart_click_info = array();
        $statistics_info = array();
        for ($i = 1; $i <= 7; $i++) {
//            $moment_day = date_add($start_day, date_interval_create_from_date_string($i.' days'));
            $moment_day = $this->db->query('select DATE_ADD(' . "'$start_day'" . ', INTERVAL ' . $i . ' DAY) as moment_day')->row('moment_day');
            $total_visit_cnt = $this->db->where('f_advertise', $advertise_id)->like('f_reg_time', $moment_day, 'both')->count_all_results('t_statistics');
            $total_click_cnt = $this->db->where('f_advertise', $advertise_id)->where('f_url_cnt', STATUS_TRUE)->like('f_reg_time', $moment_day, 'both')->count_all_results('t_statistics');
            $info_row = array(
                'day' => $moment_day,
                'total_visit_cnt' => $total_visit_cnt,
                'url_click_cnt' => $total_click_cnt
            );
            array_push($category, "'$moment_day'");
            array_push($chart_visit_info, $total_visit_cnt);
            array_push($chart_click_info, $total_click_cnt);
            array_push($statistics_info, $info_row);
        }

        $data = array(
            'statistics_info' => $statistics_info,
            'page_num' => $page_num,
            'total_page' => ($total_cnt % $page_length) ? ($total_cnt / $page_length + 1) : ($total_cnt / $page_length),
            'category' => $category,
            'total_visit_info' => $chart_visit_info,
            'total_click_info' => $chart_click_info,
        );
        $this->load->view('my_advertise/chart', $data);
    }
}

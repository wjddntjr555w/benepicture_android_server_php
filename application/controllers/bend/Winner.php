<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Winner extends AdminController
{
    public $_page_index = 4;
    public $_page_title = ["당첨조회"];
    public $_page_privilege = [];

    protected $models = ['MWinner', 'MUser', 'MAdvertise', 'MAnswer', 'MWithdraw', 'MWinnerDefault'];

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_page_sub_index = 0;
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];

        $max_round = $this->db->select_max('f_round')->get('t_winner')->row('f_round');
        if (empty($max_round)) {
            $max_round = 1;
        }

        $this->render('winner/index', $this->_page_privilege, [
            'max_round' => $max_round
        ]);
    }

    public function winner_table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $value = $this->input->get('search')['value'];
        $round = $this->input->get('round');

        $winner_cost_sum = $this->db->select_sum('f_cost')->where('f_round', $round)->get('t_winner')->row('f_cost');

        $sql = <<<sql
            SELECT
                U.f_id, U.f_phone, S.f_name, A.f_no, W.f_order, W.f_cost, 
                IF(ISNULL(D.f_idx), 0, 1) withdraw
            FROM
                `t_winner` W
            JOIN t_answer A ON A.f_idx = W.f_answer
            JOIN t_user U ON A.f_user = U.f_idx
            JOIN t_advertise S ON S.f_idx = A.f_advertise
            LEFT JOIN t_withdraw D ON D.f_winner = W.f_idx
            WHERE 1 = 1
sql;

        // 이부분때문에 2회차 이상인 당첨자들이 관리자에서 보이지 않는다.2019.10.21
        $sql .= ' AND W.f_round = ' . $round;
        if (!empty($value)) {
            $sql .= ' AND (U.f_id LIKE "%' . $value . '%" OR S.f_name LIKE "%' . $value . '%" OR A.f_no LIKE "%' . $value . '%")';
        }

        $arr_winners = $this->db
            ->query($sql)->result();

        $totalFiltered = $this->db
            ->query($sql)->num_rows();

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        if (!empty($arr_winners)) {
            foreach ($arr_winners as $winner) {
                $nestedData[0] = $nestedData['user_id'] = $winner->f_id;
                $nestedData[1] = $nestedData['phone'] = $winner->f_phone;
                $nestedData[2] = $nestedData['advertise'] = $winner->f_name;
                $nestedData[3] = $nestedData['no'] = $winner->f_name . '-' . $winner->f_no;
                $nestedData[4] = $nestedData['order'] = $winner->f_order;
                $nestedData[5] = $nestedData['cost'] = number_format($winner->f_cost, 2);
                $nestedData[6] = $nestedData['status'] = $winner->withdraw;

                $index--;
                array_push($data, $nestedData);
            }
        }

        $json_data = array(
            "draw" => intval($this->input->get('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "memberCnt" => number_format($winner_cost_sum, 2),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function after()
    {
        $this->_page_sub_index = 1;
        $this->_page_title = ['후기관리'];
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];

        $this->render('winner/after', $this->_page_privilege);
    }

    public function after_table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $value = $this->input->get('search')['value'];

        $sql = <<<sql
            SELECT
                W.f_idx, U.f_id, U.f_phone, S.f_name, A.f_no, W.f_order, W.f_round, W.f_content, W.f_public
            FROM
                `t_winner` W
            JOIN t_answer A ON A.f_idx = W.f_answer
            JOIN t_user U ON A.f_user = U.f_idx
            JOIN t_advertise S ON S.f_idx = A.f_advertise
            LEFT JOIN t_withdraw D ON D.f_winner = W.f_idx
            WHERE 1 = 1 AND W.f_content IS NOT NULL 
sql;

        if (!empty($value)) {
            $sql .= ' AND (U.f_id LIKE "%' . $value . '%" OR S.f_name LIKE "%' . $value . '%" OR A.f_no LIKE "%' . $value . '%")';
        }

        $arr_winners = $this->db
            ->query($sql)->result();

        $totalFiltered = $this->db
            ->query($sql)->num_rows();

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        if (!empty($arr_winners)) {
            foreach ($arr_winners as $winner) {
                $nestedData = [];

                $nestedData[0] = $nestedData['user_id'] = $winner->f_id;
                $nestedData[1] = $nestedData['phone'] = $winner->f_phone;
                $nestedData[2] = $nestedData['advertise'] = $winner->f_name;
                $nestedData[3] = $nestedData['no'] = $winner->f_name . '-' . $winner->f_no;
                $nestedData[4] = $nestedData['round'] = $winner->f_round;
                $nestedData[5] = $nestedData['content'] = $winner->f_content;
                $nestedData[6] = $nestedData['public'] = $winner->f_public;
                $nestedData[7] = $nestedData['idx'] = $winner->f_idx;

                $index--;
                array_push($data, $nestedData);
            }
        }

        $json_data = array(
            "draw" => intval($this->input->get('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function change_public()
    {
        $public_status = $this->input->post('status');
        $idx = $this->input->post('idx');

        $result = $this->db->update('t_winner', array('f_public' => $public_status), array('f_idx' => $idx));
        if ($result)
            die('success');
        else
            die('fail');
    }

    public function default_winner()
    {
        $this->_page_title = ['당첨설정'];
        $this->_page_sub_index = 2;
        $this->_page_privilege = [ADMIN_SUPER];

        $max_round = $this->db->select_max('f_round')->get('t_winner')->row('f_round');
        if (empty($max_round)) {
            $max_round = 1;
        } else {
            $max_round++;
        }

        $this->render('winner/default_winner', $this->_page_privilege, array(
//            'default_winner' => $this->db->get('t_winner')->row(),
            'round' => $max_round,
            'user_list' => $this->db->get_where('t_user', array('f_status' => STATUS_TRUE))->result()
        ));
    }

    public function save_default_winner()
    {
        $winner = $this->input->post('winner');
        $round = $this->input->post('round');

        $default_winner_model = $this->db->get_where('t_winner_default', array('f_round' => $round))->row();
        if ($default_winner_model == null) {
            $result = $this->db->insert('t_winner_default', array('f_round' => $round, 'f_user' => $winner));
        } else
            $result = $this->db->update('t_winner_default', array('f_user' => $winner), array('f_round' => $round));

        if ($result)
            die('success');
        else
            die('fail');
    }

    public function cancel_default_winner()
    {
        $round = $this->input->post('round');
        if ($this->db->delete('t_winner_default', array('f_round' => $round)))
            die('success');
        else
            die('fail');
    }
}

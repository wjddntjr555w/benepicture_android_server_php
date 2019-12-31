<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends AdminController
{
    public $_page_index = 5;
    public $_page_title = ["출금요청"];
    public $_page_privilege = [];

    protected $models = ['MWithdraw', 'MUser', 'MAnswer'];

    function __construct()
    {
        parent::__construct();
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];
    }

    public function index()
    {
        $this->_page_sub_index = 0;

        $this->render('withdraw/index', $this->_page_privilege);
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

        $sql = <<<sql
            SELECT
                W.f_idx,
                U.f_id,
                U.f_phone,
                U.f_name f_user_name,
                U.f_birthday,
                U.f_gender,
                S.f_name,
                A.f_no,
                W.f_name user_name,
                W.f_cost,
                W.f_bank,
                W.f_account,
                W.f_status
            FROM
                t_withdraw W
            JOIN t_user U ON U.f_idx = W.f_user
            JOIN t_answer A ON A.f_idx = W.f_answer
            JOIN t_advertise S ON S.f_idx = A.f_advertise 
            WHERE 1 = 1
sql;

        if (!empty($value)) {
            $sql .= ' AND (U.f_id LIKE "%' . $value . '%" OR W.f_bank LIKE "%' . $value . '%" OR W.f_account LIKE "%' . $value . '%")';
        }

        $arr_withdraws = $this->db
            ->query($sql)->result();

        $totalFiltered = $this->db
            ->query($sql)->num_rows();

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        if (!empty($arr_withdraws)) {
            foreach ($arr_withdraws as $withdraw) {
                $nestedData = [];

                $nestedData[0] = $nestedData['user_id'] = $withdraw->f_id;
                $nestedData[1] = $nestedData['user_phone'] = $withdraw->f_phone;
                $nestedData[2] = $nestedData['cost'] = number_format($withdraw->f_cost, 2);
                $nestedData[3] = $nestedData['bank'] = $withdraw->f_bank;
                $nestedData[4] = $nestedData['account'] = $withdraw->f_account;
                $nestedData[5] = $nestedData['status'] = $withdraw->f_status;
                $nestedData[6] = $nestedData['status'] = $withdraw->f_status;

                $nestedData[7] = $nestedData['answer_no'] = $withdraw->f_no;
                $nestedData[8] = $nestedData['user_birthday'] = $withdraw->f_birthday;
                $nestedData[9] = $nestedData['user_name'] = $withdraw->f_user_name;
                $nestedData[10] = $nestedData['user_gender'] = $withdraw->f_gender;
                $nestedData[11] = $nestedData['idx'] = $withdraw->f_idx;
                $nestedData[12] = $nestedData['name'] = $withdraw->user_name;
                $nestedData[13] = $nestedData['ad_name'] = $withdraw->f_name;

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

    public function change_status_all()
    {
        $id_str = $this->input->post('id_str');
        $result = $this->db->set('f_status', STATUS_TRUE)->where_in('f_idx', explode(',', $id_str))->update('t_withdraw');
        if ($result)
            die('success');
        else
            die('error');
    }

    public function change_status()
    {
        $idx = $this->input->post('idx');
        $status = $this->input->post('status');
        $result = $this->db->update('t_withdraw', array('f_status' => $status), array('f_idx' => $idx));
        if ($result)
            die('success');
        else
            die('error');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Mymain extends AdminController
{
    public $_page_index = 0;
    public $_page_title = ["Dash Board"];
    public $_page_privilege = [];

    protected $models = ['MLogin', 'MNotice'];

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_page_sub_index = 0;
        $this->_page_privilege = [ADMIN_ADVERTISE];

        $reward = 0;

        $user_model = $this->db->where('f_idx', $_SESSION[ADMIN_IDX])->get('t_user')->row();
        if (is_null($user_model)) {
            $reward = $user_model->f_reward;
        }

        $this->render('my_main/index', $this->_page_privilege, [
            'reward' => $reward
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
            ->where('f_user = 0 OR f_user = ' . $_SESSION[ADMIN_IDX])
            ->order_by('f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_notice')
            ->result();

        $totalFiltered = $this->db
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

}

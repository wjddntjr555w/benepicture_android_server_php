<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Keyword extends AdminController
{
    public $_page_index = 3;
    public $_page_title = ["키워드 설정"];
    public $_page_privilege = [];

    protected $models = ['MKeyword'];

    function __construct()
    {
        parent::__construct();
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];
    }

    public function index()
    {
        $this->_page_sub_index = 0;
        $page_num = 1;
        $length = 10;
        $total_records = $this->db->get_where('t_keyword', array('f_parent' => STATUS_FALSE))->num_rows();
        $keyword_list = $this->db->select('*')
            ->where('f_parent', STATUS_FALSE)
            ->limit($length, ($page_num - 1) * $length)
            ->order_by('f_reg_time', 'DESC')
            ->get('t_keyword')
            ->result_object();

        $this->render('keyword/index', $this->_page_privilege, array(
            'page_num' => $page_num,
            'total_page' => ($total_records % $length) ? ($total_records / $length + 1) : ($total_records / $length),
            'keyword_list' => $keyword_list
        ));
    }

    public function table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $length = $this->input->post('length');
        $page_num = $this->input->post('page_num');
        $search_keyword = $this->input->post('search_keyword');
        $parent = $this->input->post('parent');

        $where = '1 = 1';
        if (!empty($search_keyword)) {
            $where .= ' AND f_name LIKE "%' . $search_keyword . '%"';
        }

        $where .= ' And f_parent = ' . $parent;


        $arr_keywords = $this->db
            ->select('*')
            ->where($where)
            ->order_by('f_reg_time', 'DESC')
            ->limit($length, ($page_num - 1) * $length)
            ->get('t_keyword')
            ->result();

        $totalFiltered = $this->db
            ->select('*')
            ->where($where)
            ->get('t_keyword')
            ->num_rows();

        $index = $totalFiltered;

        $data = [];
        foreach ($arr_keywords as $keyword) {
            $nestedData = [];
            $nestedData[0] = $nestedData['name'] = $keyword->f_name;
            $nestedData[1] = $nestedData['idx'] = $keyword->f_idx;

            $index--;
            array_push($data, $nestedData);
        }

        $json_data = array(
            "draw" => intval($this->input->get('draw')),
            "recordsTotal" => intval($totalFiltered),
            "page_num" => $page_num,
            'total_page' => ($totalFiltered % $length) ? ($totalFiltered / $length + 1) : ($totalFiltered / $length),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function save()
    {
        $parent = $this->input->post('parent');
        $name = $this->input->post('name');
        $idx = $this->input->post('id');

        $existing_item = $this->db->get_where('t_keyword', array('f_name' => $name, 'f_parent' => $parent))->row();
        if ($existing_item) {
            die('duplicate');
        } else {
            $update_item = $this->db->get_where('t_keyword', array('f_idx' => $idx))->row();
            if ($update_item) {
                $result = $this->db->update('t_keyword', array(
                    'f_reg_time' => _get_current_time(),
                    'f_name' => $name
                ), array('f_idx' => $idx));
            } else {
                $result = $this->db->insert('t_keyword', array(
                    'f_reg_time' => _get_current_time(),
                    'f_name' => $name,
                    'f_parent' => $parent
                ));
            }

            if ($result)
                die('success');
            else
                die('fail');
        }
    }

    public function delete()
    {
        $id = $this->input->get('id');

        $this->db->where('f_idx', $id)->or_where('f_parent', $id)->delete('t_keyword');
        $this->db->where('f_keyword', $id)->delete('t_keyword_like');
        $this->db->set('f_keyword', 'REPLACE(f_keyword, "{' . $id . '}", "")', false)->update('t_advertise');

        echo 'success';
    }

}

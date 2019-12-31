<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends AdminController
{
    public $_page_index = 1;
    public $_page_title = ["일반회원관리"];
    public $_page_privilege = [];

    protected $models = ['MUser', 'MAdvertise', 'MAdmin', 'MNotice'];

    function __construct()
    {
        parent::__construct();
        $this->_page_privilege = [ADMIN_SUPER, ADMIN_COMMON];
    }

    public function index()
    {
        $this->_page_sub_index = 1;
        $this->render('member/index', $this->_page_privilege);
    }

    public function table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
//        $column = $this->input->get('column');
        $value = $this->input->get('search')['value'];

        $user_type = $this->input->get('user_type');
        $user_status = $this->input->get('user_status');

        $where = '1 = 1';

        if ($user_type != 0)
            $where .= ' AND f_user_type = ' . $user_type;

        $where .= ' AND f_status = ' . $user_status;

        if (!empty($value)) {
            $where .= ' AND (f_id LIKE "%' . $value . '%" OR f_name LIKE "%' . $value . '%" OR f_phone LIKE "%' . $value . '%")';
        }

        $arr_users = $this->db
            ->select('*')
            ->where($where)
            ->order_by('f_reg_time', 'DESC')
            ->limit($limit, $offset)
            ->get('t_user')
            ->result();

        $totalFiltered = $this->db
            ->where($where)
            ->count_all_results('t_user');

        if ($limit != -1) {
            $index = $totalFiltered - $offset;
        } else {
            $index = $totalFiltered;
        }

        $data = [];
        if (!empty($arr_users)) {
            foreach ($arr_users as $user) {
                $nestedData = [];
                $nestedData[0] = $nestedData['no'] = $index;
                $nestedData[1] = $nestedData['id'] = $user->f_id;//$user->f_login_type == LOGIN_ID ? $user->f_id : $user->f_kt_id;
                $nestedData[2] = $nestedData['name'] = $user->f_name;
                $nestedData[3] = $nestedData['gender'] = $user->f_gender;
                $nestedData[4] = $nestedData['birthday'] = $user->f_birthday;
                $nestedData[5] = $nestedData['phone'] = $user->f_phone;

                $nestedData[6] = $nestedData['market_status'] = $user->f_market_status;
                $nestedData[7] = $nestedData['account_status'] = $user->f_account_status;
                $nestedData[8] = $nestedData['idx'] = $user->f_idx;
                $nestedData[8] = $nestedData['pwd'] = $user->f_pwd;
                $nestedData[9] = $nestedData['depositor_phone'] = $user->f_depositor_phone;
                $nestedData[10] = $nestedData['bank'] = $user->f_bank;
                $nestedData[11] = $nestedData['depositor'] = $user->f_depositor;
                $nestedData[12] = $nestedData['account'] = $user->f_account;

                $nestedData[13] = $nestedData['company_name'] = $user->f_company_name;
                $nestedData[14] = $nestedData['company_owner'] = $user->f_company_owner;
                $nestedData[15] = $nestedData['owner_phone'] = $user->f_owner_phone;
                $nestedData[16] = $nestedData['addr'] = $user->f_addr;
                $nestedData[17] = $nestedData['business'] = $user->f_business;
                $nestedData[18] = $nestedData['business_type'] = $user->f_business_type;
                $nestedData[19] = $nestedData['email'] = $user->f_email;


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

    public function download()
    {
        $this->load->library('PHPExcel');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '유저번호')
            ->setCellValue('B1', 'ID')
            ->setCellValue('C1', '이름')
            ->setCellValue('D1', '성별')
            ->setCellValue('E1', '생년월일')
            ->setCellValue('F1', '연락처')
            ->setCellValue('G1', '마케팅동의')
            ->setCellValue('H1', '계좌인증');

        $user_list = $this->db->where(['f_status' => STATUS_TRUE, 'f_user_type' => USER_COMMON])->get('t_user')->result();

        foreach ($user_list as $i => $row) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($i + 2), ($i + 1))
                ->setCellValue('B' . ($i + 2), $row->f_id)
                ->setCellValue('C' . ($i + 2), $row->f_name)
                ->setCellValue('D' . ($i + 2), (trim(strtolower($row->f_gender)) == 'm' ? '남' : '여'))
                ->setCellValue('E' . ($i + 2), $row->f_birthday)
                ->setCellValue('F' . ($i + 2), $row->f_phone)
                ->setCellValue('G' . ($i + 2), $row->f_market_status == STATUS_TRUE ? '동의' : '미동의')
                ->setCellValue('H' . ($i + 2), $row->f_account_status == STATUS_TRUE ? '인증완료' : '미인증');
        }

        $objPHPExcel->getActiveSheet()->setTitle('베네픽쳐 회원목록');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . date('Ymd') . '_benepicture.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }

    public function advertise_manager()
    {
        $this->_page_sub_index = 0;
        $this->_page_title = ['광고주'];

        $this->render('member/advertise_manager', $this->_page_privilege, array(
            'advertise_list' => $this->db->get_where('t_advertise', array('f_user' => null))->result()
        ));
    }

    public function exit_member()
    {
        $this->_page_sub_index = 2;
        $this->_page_title = ['탈퇴회원'];
        $this->render('member/exit_member', $this->_page_privilege);
    }

    public function save_info()
    {
        $user = $this->input->post('Member');

        if (isset($user['f_id'])) {
            $user_model = $this->MUser->where(['f_id' => $user['f_id'], 'f_status' => USER_COMMON])->get();
            if ($user_model != false && $user_model->f_idx != $user['f_idx']) {
                echo('이미 가입된 아이디입니다.');
                return;
            }
        }
        if (isset($user['f_phone'])) {
            $user_model = $this->MUser->where(['f_phone' => $user['f_phone'], 'f_status' => USER_COMMON])->get();
            if ($user_model != false && $user_model->f_idx != $user['f_idx']) {
                echo('이미 가입된 번호입니다.');
                return;
            }
        }

        $user_model = $this->MUser->get($user['f_idx']);
        if ($user_model == false) {
            unset($user['f_idx']);
            $user['f_reg_time'] = _get_current_time();

            $insert_result = $this->MUser->insert($user);
            if ($insert_result == false) {
                echo 'fail';
                exit;
            }
            echo 'success';
        } else {
//            $banner['f_reg_time'] = _get_current_time();

            unset($user['f_idx']);

            if (empty($user['f_pwd'])) {
                unset($user['f_pwd']);
            } else {
                $user['f_pwd'] = _hash_pwd($user['f_pwd']);
            }

            $this->MUser->update($user, $user_model->f_idx);

            echo 'success';
        }
    }

    public function send_message()
    {
        $id = $this->input->post('id');
        $message = $this->input->post('message');

        $user_model = $this->MUser->get($id);
        if ($user_model != false) {
            $insert_result = $this->MNotice->insert([
                'f_reg_time' => _get_current_time(),
                'f_user' => $user_model->f_idx,
                'f_title' => '관리자메세지',
                'f_content' => $message
            ]);

            $this->_send_push($user_model->f_device, $user_model->f_fcm_token, [
                'id' => $insert_result,
                'title' => '베네픽쳐 알림',
                'message' => $message,
                'type' => PUSH_TYPE_ADMIN
            ]);
        }

        echo 'success';
    }

    public function do_exit()
    {
        $id = $this->input->post('id');
        $result = $this->db->update('t_user', array('f_status' => USER_EXIT), array('f_idx' => $id));
        if ($result)
            die('success');
        else
            die('fail');
    }

    public function change_type()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $result = $this->db->update('t_user', array('f_user_type' => $type), array('f_idx' => $id));
        if ($result) {
            $del_result = true;
            if ($type == USER_COMMON) {
                $this->db->update('t_advertise', array('f_user' => null), array('f_user' => $id));
            }
            if ($del_result)
                die('success');
            else
                die('fail');
        } else
            die('fail');
    }

    public function matching_advertise()
    {
        $user_id = $this->input->post('id');
        $advertise_arr = $this->input->post('advertise');

        if (count($advertise_arr) > 0) {
            for ($i = 0; $i < count($advertise_arr); $i++) {
                $flag = $this->db->update('t_advertise', array('f_user' => $user_id), array('f_idx' => $advertise_arr[$i]));
                if (!$flag) {
                    echo 'fail';
                    exit;
                }
            }
        }

        echo 'success';
    }

    public function change_pwd()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('member/index');
        }

        $pwd = $this->input->post('pwd');
        $privilege = $this->input->post('privilege');

        $hash_pwd = _hash_pwd($pwd);

        $existing_row = $this->MAdmin->get(['f_type' => $privilege]);
        if ($existing_row != false)
            $this->db->update('t_admin', array('f_pwd' => $hash_pwd), array('f_type' => $privilege));

        echo 'success';
    }
}

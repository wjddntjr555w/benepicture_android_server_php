<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-17
 * Time: 오후 8:33
 */

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends AdminController
{
    protected $isCheckPrivilegeController = false;

    public $_page_title = ['베네픽쳐 로그인'];

    protected $models = ['MAdmin', 'MUser'];

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->render_login('login');
    }

    public function login()
    {
        $id = $this->input->post("username");
        $password = $this->input->post("password");

        if (empty($id) || empty($password)) {
            $this->_show_res_msg('아이디와 비밀번호를 입력해주세요.', 'error', '오류');
            redirect('login');
        }

        $admin = $this->MAdmin->get(['f_id' => $id]);
        if ($admin == false) {
            $advertise_admin = $this->MUser->get(['f_id' => $id, 'f_user_type' => USER_ADVERTISE, 'f_status' => STATUS_TRUE]);

            if ($advertise_admin == false) {
                $this->_show_res_msg('아이디와 비밀번호를 확인해주세요.', 'error', '오류');
                redirect('login');
            }
            if (!_verify_pwd($password, $advertise_admin->f_pwd)) {
                $this->_show_res_msg('아이디와 비밀번호를 확인해주세요.', 'error', '오류');
                redirect('login');
            }

            $sess_data = array(
                ADMIN_PRIVILEGE => ADMIN_ADVERTISE,
                ADMIN_ID => $advertise_admin->f_id,
                ADMIN_IDX => $advertise_admin->f_idx
            );

            $this->session->set_userdata($sess_data);
            redirect('Mymain');
        } else {
            if (!_verify_pwd($password, $admin->f_pwd)) {
                $this->_show_res_msg('아이디와 비밀번호를 확인해주세요.', 'error', '오류');
                redirect('login');
            }

            $sess_data = array(
                ADMIN_PRIVILEGE => $admin->f_type,
                ADMIN_ID => $admin->f_id,
                ADMIN_IDX => 0
            );

            $this->session->set_userdata($sess_data);
            redirect('main');
        }


    }

    public function logout()
    {
        $this->session->unset_userdata(ADMIN_ID);
        $this->session->unset_userdata(ADMIN_IDX);
        $this->session->unset_userdata(ADMIN_PRIVILEGE);
        $this->session->sess_destroy();
        redirect('login');
    }

}

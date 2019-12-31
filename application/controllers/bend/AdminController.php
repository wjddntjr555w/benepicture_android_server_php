<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-16
 * Time: 오후 5:26
 */

namespace app\controllers\bend;

use MY_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends MY_Controller
{

    /**
     * View page information
     */
    public $_page_title = ['베네픽쳐'];    // page title
    public $_page_index = -1;            // active main menu index
    public $_page_sub_index = -1;        // active sub menu index

    protected $helpers = ['form'];
    protected $libraries = ['session', 'form_validation'];

    public function __construct()
    {
        parent::__construct();

        if (!$this->isCheckPrivilegeController)
            return;

        $this->load->library('session');
        if (!$this->session->has_userdata(ADMIN_ID)) {
            if (!$this->input->is_ajax_request()) {
                redirect('login');
            } else {
                die('로그인을 진행해주세요.');
            }
        }

    }


    protected function render($template_name, $privilege_data = array(), $main_data = array(), $header_data = array(), $menu_data = array())
    {
        if (!in_array($_SESSION[ADMIN_PRIVILEGE], $privilege_data)) {
            redirect('login');
        }

        if (!array_key_exists("title", $main_data)) {
            $main_data['page_title'] = $this->_page_title;
        }

        if (!array_key_exists("page_index", $header_data)) {
            $header_data['page_index'] = $this->_page_index;
            $header_data['page_sub_index'] = $this->_page_sub_index;
        }

        $data["header"] = $this->load->view("layout/header", $header_data, true);
        $data["menu"] = $this->load->view("layout/menu", $menu_data, true);
        $data["main"] = $this->load->view($template_name, $main_data, true);

        if ($this->session->has_userdata('error')) {
            $data['error'] = $this->session->userdata('error');
            $this->session->unset_userdata('error');
        } else {
            $data['error'] = array('error_flag' => false);
        }

        $this->load->view("layout/layout_admin", $data);
    }

    protected function render_login($template_name, $main_data = array())
    {
        $data["main"] = $this->load->view($template_name, $main_data, true);

        if (!array_key_exists("title", $data)) {
            $data['title'] = $this->_page_title[0];
        }

        if ($this->session->has_userdata('error')) {
            $data['error'] = $this->session->userdata('error');
            $this->session->unset_userdata('error');
        } else {
            $data['error'] = array('error_flag' => false);
        }

        $this->load->view("layout/layout_client", $data);
    }

    protected function render_api($template_name, $main_data = array())
    {
        $main_data['page_title'] = $this->_page_title[0];
        $data["main"] = $this->load->view($template_name, $main_data, true);
        $this->load->view("layout/layout_api", $data);
    }
}

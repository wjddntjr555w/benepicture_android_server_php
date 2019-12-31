<?php

use app\controllers\bend\AdminController;

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Api 관리 Controller
 */
class Apimng extends AdminController
{

    public $_page_index = 6;
    public $_page_title = ['Api 관리'];
    public $_page_privilege = [ADMIN_SUPER, ADMIN_COMMON, ADMIN_ADVERTISE];

    protected $helpers = ['array'];
    protected $libraries = ['querystring'];
    protected $models = ['MApiErrorLog', 'MApiInput', 'MApiOutput', 'MApiList'];

    function __construct()
    {
        parent::__construct();

        $this->db_check();

    }

    /**
     * api 관련 테이블들이 존재하는지 체크, 없다면 생성
     */
    private function db_check()
    {
        if (!$this->db->table_exists('t_api_input')) {
            $this->db->query("
            CREATE TABLE `t_api_input` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_api` int(11) NOT NULL COMMENT 'api 아이디 (t_api_list 테이블의 아이디)',
              `f_name` varchar(50) NOT NULL COMMENT '파라미터명',
              `f_type` varchar(15) NOT NULL COMMENT '변수타입',
              `f_ness` varchar(1) NOT NULL COMMENT '필수여부 (N-필수, ''-빈값, D-개발시)',
              `f_exp` text COMMENT '파라미터설명',
              `f_sort` int(3) NOT NULL COMMENT '순서',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 요청파라미터테이블';");
        }

        if (!$this->db->table_exists('t_api_output')) {
            $this->db->query("
            CREATE TABLE `t_api_output` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_api` int(11) NOT NULL COMMENT 'a[i 아이디 (t_api_list테이블의 아이디)',
              `f_name` varchar(50) NOT NULL COMMENT '파라미터명',
              `f_type` varchar(15) NOT NULL COMMENT '파라미터 변수타입',
              `f_ness` varchar(1) NOT NULL COMMENT '필수여부 (N-필수, ''-빈값, D-개발시)',
              `f_exp` text COMMENT '파라미터설명',
              `f_sort` int(3) NOT NULL COMMENT '순서',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 응답파라미터테이블';");
        }

        if (!$this->db->table_exists('t_api_list')) {
            $this->db->query("
            CREATE TABLE `t_api_list` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_reg_time` datetime NOT NULL COMMENT '저장일짜',
              `f_name` varchar(255) NOT NULL COMMENT 'api 명',
              `f_exp` text COMMENT 'api 설명',
              `f_url` varchar(255) NOT NULL COMMENT '요청URL (서브도메인)',
              `f_method` varchar(10) NOT NULL COMMENT '요청메서드',
              `f_use` tinyint(1) NOT NULL COMMENT '사용여부 (1-사용, 0-미사용)',
              `f_bigo` text COMMENT '비고',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 테이블';");
        }

        if (!$this->db->table_exists('t_api_error_log')) {
            $this->db->query("
            CREATE TABLE `t_api_error_log` (
              `f_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '아이디',
              `f_time` datetime NOT NULL COMMENT '저장일짜',
              `f_ip` varchar(255) NOT NULL COMMENT 'ip주소',
              `f_self` varchar(255) NOT NULL COMMENT 'New Column3',
              `f_vars` text COMMENT 'New Column4',
              `f_result` varchar(255) NOT NULL COMMENT 'New Column5',
              PRIMARY KEY (`f_idx`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Api 에러로그테이블';");
        }
    }

    /**
     * Api 전체 리스트를 블러오는 메소드
     */
    public function index()
    {
        $column = $this->input->get('col') ? 'f_' . $this->input->get('col') : 'f_name';
        $keyword = $this->input->get('keyword') ? $this->input->get('keyword') : '';

        $api_list = $this->MApiList->get_list($column, $keyword);

        $this->render('apis/manage/index', $this->_page_privilege, [
            'api_list' => $api_list,
            'keyword' => $keyword,
            'column' => str_replace('f_', '', $column)
        ]);
    }

    /**
     * Api 추가 / 편집 페이지를 블러오는 메소드
     *
     * @param int $pid api 아이디
     */
    public function write($pid = 0)
    {
        // 프라이머리키에 숫자형이 입력되지 않으면 에러처리
        if (!is_numeric($pid)) {
            show_404();
        }

        $primary_key = $this->MApiList->primary_key;

        // 수정 페이지일 경우 기존 데이터를 블러 온다.
        $api_data = [];
        if ($pid) {
            $api_data = $this->MApiList->as_array()->get($pid);
        }

        // Validation 라이브러리를 블러 온다.
        $this->load->library('form_validation');

        // 전송된 데이터 유효성 체크
        $config = array(
            array(
                'field' => 'f_name',
                'label' => 'API 이름',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'f_method',
                'label' => '호출방식',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'f_url',
                'label' => '서브 URL',
                'rules' => 'trim',
            ),
            array(
                'field' => 'f_exp',
                'label' => '설명',
                'rules' => 'trim',
            ),
            array(
                'field' => 'f_use',
                'label' => '사용여부',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'f_bigo',
                'label' => '비고',
                'rules' => 'trim',
            ),
        );
        $this->form_validation->set_rules($config);


        // 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우
        // 즉 글쓰기나 수정 페이지를 보고 있는 경우
        if ($this->form_validation->run() === false) {
            $this->render('apis/manage/write', $this->_page_privilege, [
                'api_data' => $api_data,
                'primary_key' => $primary_key
            ]);
        } else {
            // 유효성 검사를 통과한 경우,
            // 즉 데이터의 insert 나 update 의 처리가 필요한 상황이다.
            $update_data = array(
                'f_name' => $this->input->post('f_name'),
                'f_method' => $this->input->post('f_method'),
                'f_url' => $this->input->post('f_url'),
                'f_exp' => $this->input->post('f_exp'),
                'f_use' => $this->input->post('f_use'),
                'f_bigo' => $this->input->post('f_bigo')
            );

            if ($this->input->post($primary_key)) {
                // 게시물을 수정하는 경우
                $api_model = $this->MApiList->where(['f_name' => $this->input->post('f_name'), 'f_url' => $this->input->post('f_url')])->get();
                if ($api_model != false && $api_model->{$primary_key} != $this->input->post($primary_key)) {
                    $this->_show_res_msg($this->input->post('f_name') . ' 으로 된 API가 이미 존재합니다.', 'error', '알림');
                    redirect('apimng/write/' . $this->input->post($primary_key));
                }

                $this->MApiList->update($update_data, $this->input->post($primary_key));
                $this->session->set_flashdata(
                    'message', '정상적으로 수정되었습니다'
                );
            } else {
                // 게시물을 새로 입력하는 경우
                $api_model = $this->MApiList->where(['f_name' => $this->input->post('f_name'), 'f_url' => $this->input->post('f_url')])->get();
                if ($api_model != false) {
                    $this->_show_res_msg($this->input->post('f_name') . ' 으로 된 API가 이미 존재합니다.', 'error', '알림');
                    redirect('apimng/write');
                }

                $update_data['f_reg_time'] = _get_current_time();
                $this->MApiList->insert($update_data);
                $this->session->set_flashdata(
                    'message', '정상적으로 입력되었습니다'
                );
            }

            // 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동
            $param = &$this->querystring;
            redirect(base_url('apimng?' . $param->output()));
        }
    }

    /**
     * Api 리스트페이지에서 선택삭제를 하는 메서드
     */
    public function delete()
    {
        $chk = $this->input->post('chk');

        if ($chk && is_array($chk)) {
            foreach ($chk as $val) {
                $this->MApiList->delete($val);

                // Input, Output 파라미터 삭제
                $this->MApiInput->where('f_api', $val)->delete();
                $this->MApiOutput->where('f_api', $val)->delete();
            }
        }

        $this->session->set_flashdata(
            'message', '정상적으로 삭제되었습니다'
        );

        redirect(base_url('apimng'));
    }

    /**
     * Api Request/Response 파라미터리스트를 블러오는 메서드
     *
     * @param string $type output/input 의 문짜
     * @param string $f_idx api 아이디
     */
    public function param_list($type = '', $f_idx = '')
    {
        if ($type != 'output')
            $type = 'input';

        if (!is_numeric($f_idx))
            show_404();

        $api_data = $this->MApiList->as_array()->get($f_idx);

        $param = &$this->querystring;
        $model_name = $type == 'output' ? 'MApiOutput' : 'MApiInput';

        $column = $this->input->get('column') ? $this->input->get('column') : 'f_name';
        $keyword = $this->input->get('keyword') ? $this->input->get('keyword') : '';

        if (empty($keyword)) {
            $result = $this->{$model_name}->get_list('f_api', element('f_idx', $api_data));
        } else {
            $result = $this->db
                ->where('f_api', element('f_idx', $api_data))
                ->like($column, $keyword)
                ->order_by('f_sort', 'ASC')
                ->get($this->{$model_name}->_table)->result_array();
        }

        $this->render('apis/manage/argumentlist', $this->_page_privilege, [
            'type' => $type,
            'api_data' => $api_data,
            'data' => $result,
            'primary_key' => $this->{$model_name}->primary_key,
            'keyword' => $keyword,
            'column' => $column,
            'list_delete_url' => base_url('apimng/argumentlistdelete/' . $type . '/' . $f_idx . '?' . $param->output())
        ]);
    }

    /**
     * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
     *
     * @param string $type output/input 문짜
     * @param string $f_idx api 아이디
     * @param int $pid 파라미터아이디
     */
    public function argumentwrite($type = '', $f_idx = '', $pid = 0)
    {
        if ($type != 'output')
            $type = 'input';

        if (!is_numeric($f_idx))
            show_404();

        $model_name = ($type == 'output') ? 'MApiOutput' : 'MApiInput';

        $apidata = $this->MApiList->as_array()->get($f_idx);

        // 프라이머리키에 숫자형이 입력되지 않으면 에러처리
        if (!is_numeric($pid)) {
            show_404();
        }

        $primary_key = $this->{$model_name}->primary_key;

        // 수정 페이지일 경우 기존 데이터를 블러 온다.
        $f_data = array();
        if ($pid) {
            $f_data = $this->{$model_name}->as_array()->get($pid);
        } else {
            $max_sort = $this->{$model_name}->select_max_sort($f_idx);
            $f_data['f_sort'] = $max_sort + 1;
        }

        $this->load->library('form_validation');

        $config = array(
            array(
                'field' => 'f_api',
                'label' => 'API IDX',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'f_name',
                'label' => '이름',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'f_type',
                'label' => '타입',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'f_ness',
                'label' => '종류',
                'rules' => 'trim',
            ),
            array(
                'field' => 'f_exp',
                'label' => '설명',
                'rules' => 'trim',
            ),
            array(
                'field' => 'f_sort',
                'label' => '순서',
                'rules' => 'trim|numeric',
            ),
        );
        $this->form_validation->set_rules($config);

        $param = &$this->querystring;

        if ($this->form_validation->run() === false) {
            // 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우,
            // 즉 글쓰기나 수정 페이지를 보고 있는 경우
            $this->render('apis/manage/argumentwrite', $this->_page_privilege, [
                'type' => $type,
                'apidata' => $apidata,
                'data' => $f_data,
                'primary_key' => $primary_key,
                'write_url' => base_url('apimng/argumentwrite/' . $type . '/' . $f_idx . '?' . $param->output()),
                'input_list_url' => base_url('apimng/param_list/input/' . $f_idx),
                'output_list_url' => base_url('apimng/param_list/output/' . $f_idx)
            ]);
        } else {
            // 유효성 검사를 통과한 경우,
            // 즉 데이터의 insert 나 update 의 처리가 필요한 상황

            $updatedata = array(
                'f_api' => $this->input->post('f_api'),
                'f_name' => $this->input->post('f_name'),
                'f_type' => $this->input->post('f_type'),
                'f_ness' => $this->input->post('f_ness'),
                'f_exp' => $this->input->post('f_exp'),
                'f_sort' => $this->input->post('f_sort'),
            );

            if ($this->input->post($primary_key)) {
                // 게시물을 수정하는 경우입니다

                $this->{$model_name}->update($updatedata, $this->input->post($primary_key));
                $this->session->set_flashdata(
                    'message', '정상적으로 수정되었습니다'
                );
            } else {
                // 게시물을 새로 입력하는 경우입니다
                $this->{$model_name}->update_sort($this->input->post('f_idx', null, ''), $this->input->post('f_sort', null, ''));
                $this->{$model_name}->insert($updatedata);
                $this->session->set_flashdata(
                    'message', $this->input->post('f_name') . '변수명이 등록되었습니다'
                );
            }

            if ($this->input->post('reinput')) {
                $redirecturl = base_url('apimng/argumentwrite/' . $type . '/' . $f_idx . '?' . $param->output());
            } else {
                $redirecturl = base_url('apimng/param_list/' . $type . '/' . $f_idx . '?' . $param->output());
            }

            redirect($redirecturl);
        }
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     *
     * @param string $type input/output 문짜
     * @param string $f_idx 파라미터 아이디
     */
    public function argumentdelete($type = '', $f_idx = '')
    {
        if ($type != 'output')
            $type = 'input';

        if (!is_numeric($f_idx))
            show_404();

        $model_name = $type == 'output' ? 'MApiOutput' : 'MApiInput';

        $arr_chk = $this->input->post('chk');
        if ($arr_chk && is_array($arr_chk)) {
            foreach ($arr_chk as $val) {
                $this->{$model_name}->delete($val);
            }
        }

        $this->session->set_flashdata(
            'message', '정상적으로 삭제되었습니다'
        );

        $param = &$this->querystring;

        redirect(base_url('apimng/param_list/' . $type . '/' . $f_idx . '?' . $param->output()));
    }

}

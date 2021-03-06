<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_peg extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('login')) {
          redirect('auth');
        }
        else if($this->session->userdata('level') != 'baak'){
            redirect('auth/logout');
        }
        else {
          $this->load->model('Login_peg_model');
          $this->load->library('form_validation');
        }
    }

    public function index()
    {
        $login_peg = $this->Login_peg_model->get_all();

        $data = array(
            'login_peg_data' => $login_peg
        );
        $data['site_title'] = 'SIMALA';
        $data['title_page'] = 'Olah Data Pengguna Basis Pegawai';
        $data['assign_js'] = 'login_peg/js/index.js';
        load_view('login_peg/login_peg_list', $data);
    }

    public function read($id)
    {
        $row = $this->Login_peg_model->get_by_id($id);
        if ($row) {
            $data = array(
          		'uid' => $row->uid,
          		'username' => $row->username,
          		'password' => $row->password,
          		'level' => $row->level,
        	  );
            $data['site_title'] = 'SIMALA';
            $data['title_page'] = 'Olah Data Pengguna Basis Pegawai';
            $data['assign_js'] = 'login_peg/js/index.js';
            load_view('login_peg/login_peg_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('login_peg'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('login_peg/create_action'),
      	    'uid' => set_value('uid'),
      	    'username' => set_value('username'),
      	    'password' => set_value('password'),
      	    'level' => set_value('level'),
      	);
        $data['site_title'] = 'SIMALA';
        $data['title_page'] = 'Olah Data Pengguna Basis Pegawai';
        $data['assign_js'] = 'login_peg/js/index.js';
        load_view('login_peg/login_peg_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
          		'username' => $this->input->post('username',TRUE),
          		'password' => $this->input->post('password',TRUE),
          		'level' => $this->input->post('level',TRUE),
          	);
            $this->Login_peg_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('login_peg'));
        }
    }

    public function update($id)
    {
        $row = $this->Login_peg_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('login_peg/update_action'),
            		'uid' => set_value('uid', $row->uid),
            		'username' => set_value('username', $row->username),
            		'password' => set_value('password', $row->password),
            		'level' => set_value('level', $row->level),
            );
            $data['site_title'] = 'SIMALA';
            $data['title_page'] = 'Olah Data Pengguna Basis Pegawai';
            $data['assign_js'] = 'login_peg/js/index.js';
            load_view('login_peg/login_peg_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('login_peg'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('uid', TRUE));
        } else {
            $data = array(
          		'username' => $this->input->post('username',TRUE),
          		'password' => $this->input->post('password',TRUE),
          		'level' => $this->input->post('level',TRUE),
          	);

            $this->Login_peg_model->update($this->input->post('uid', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('login_peg'));
        }
    }

    public function delete($id)
    {
        $row = $this->Login_peg_model->get_by_id($id);

        if ($row) {
            $this->Login_peg_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('login_peg'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('login_peg'));
        }
    }

    public function _rules()
    {
    	$this->form_validation->set_rules('username', 'username', 'trim|required');
    	$this->form_validation->set_rules('password', 'password', 'trim|required');
    	$this->form_validation->set_rules('level', 'level', 'trim|required');

    	$this->form_validation->set_rules('uid', 'uid', 'trim');
    	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

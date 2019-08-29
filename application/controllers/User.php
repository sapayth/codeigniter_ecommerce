<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function index() {
		$this->load->view('header');
		$this->load->view('pages/admin-ui/dashboard');
		$this->load->view('footer');
	}

	public function view_user() {
		$this->load->view('header');
		$this->load->view('template-parts/header-admin');
		$this->load->view('template-parts/sidebar-left-admin');
		$this->load->view('pages/admin-ui/user-management/view-user');
		$this->load->view('footer-copyright');
		$this->load->view('template-parts/sidebar-control-admin');
		$this->load->view('footer');
	}

	public function add_user() {
		$this->load->helper('form');

		// get roles from database
		$role_rs = $this->db->query("SELECT id, name FROM user_role;");
		$data['role_arr'] = $role_rs->result_array();

		// $data['title'] = 'News archive';
		
		$this->load->view('header');
		$this->load->view('template-parts/header-admin');
		$this->load->view('template-parts/sidebar-left-admin');
		$this->load->view('pages/admin-ui/user-management/add-user', $data);
		$this->load->view('footer-copyright');
		$this->load->view('template-parts/sidebar-control-admin');
		$this->load->view('footer');
	}

	public function save_user() {
		$this->load->helper('form');
		$name = $this->input->post('txtName');
		$email = $this->input->post('txtEmail');
		$role_id = $this->input->post('cmbRole');
		$pass = $this->input->post('pwdPass');
		$avatar = $name . '_' . time();

		// echo $role_id;

		$config['upload_path']          = './assets/img/avatars';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']			= $avatar;
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('fileUserAvatar')) {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('header');
			$this->load->view('template-parts/header-admin');
			$this->load->view('template-parts/sidebar-left-admin');
			$this->load->view('pages/admin-ui/user-management/view-user', $error);
			$this->load->view('footer-copyright');
			$this->load->view('template-parts/sidebar-control-admin');
			$this->load->view('footer');
        } else {
            $data = array('upload_data' => $this->upload->data());
            $user = array(
				'name' => $name,
				'email' => $email,
				'role_id' => $role_id,
				'password' => $pass,
				'avatar_name' => $avatar
			);
			$this->db->insert('users', $user);

			redirect('user/view_user');
    	}
		
		
		// $this->load->view('header');
		// $this->load->view('template-parts/header-admin');
		// $this->load->view('template-parts/sidebar-left-admin');
		// $this->load->view('pages/admin-ui/user-management/view-user');
		// $this->load->view('footer-copyright');
		// $this->load->view('template-parts/sidebar-control-admin');
		// $this->load->view('footer');
	}

	public function edit_user() {
		$this->load->helper('form');

		$this->load->view('header');
		$this->load->view('template-parts/header-admin');
		$this->load->view('template-parts/sidebar-left-admin');
		$this->load->view('pages/admin-ui/user-management/edit-user');
		$this->load->view('footer-copyright');
		$this->load->view('template-parts/sidebar-control-admin');
		$this->load->view('footer');
	}

	public function manage_user() {
		if(isset($_POST['btnEdit'])) {
			redirect("user/edit_single_user");
		}

		if(isset($_POST['btnDelete'])) {
			$id = $this->input->post('hdnId');
			$this->db->delete('users', array('id' => $id));

			redirect("user/edit_user");
		}
	}

	public function edit_single_user() {
		$this->load->helper('form');

		$this->load->view('header');
		$this->load->view('template-parts/header-admin');
		$this->load->view('template-parts/sidebar-left-admin');
		$this->load->view('pages/admin-ui/user-management/edit-single-user');
		$this->load->view('footer-copyright');
		$this->load->view('template-parts/sidebar-control-admin');
		$this->load->view('footer');
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ProfileUpdate extends CI_Controller {

	public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('authentication', 'personality', 'form_validation'));
        $this->load->helper('html');
		$this->load->helper('form');
		
		$this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
		
        $data = $this->buildUser($this->authentication->currentUserId());
        $data['profileType'] = 'form';
		
        $config = array(
        		array(
        			'field' => '',
        			'label' => '',
        			'rules' => array()
				),
				array(
        			'field' => '',
        			'label' => '',
        			'rules' => array()
				),
				array(
        			'field' => '',
        			'label' => '',
        			'rules' => array()
				)
        );
        $this->form_validation->set_rules($config);
        
        if ($this->form_validation->run() == false) {
			$this->load->view('content/profile', $data);
		}
		else {
			$data = array('user' => array('text' => 'Smoked yah!'));
			$this->load->view('content/testView', $data);
		}
        
        $this->load->view('footer');
	}
	
	private function buildUser($userId) {
		$user = $this->user->load($userId);
		$user['personality'] = $this->personality->dominantPersonalityComponents($user);
		$user['preference'] = $this->personality->dominantPersonalityComponents($user, true);
		return $user;
	}
}	
?>
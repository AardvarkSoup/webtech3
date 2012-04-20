<?php

class ViewProfile extends CI_Controller
{
	public function index($msg = null)
	{
		if($this->authentication->userLoggedIn()) {
			$this->showProfile($this->authentication->currentUserId(), 'form');
		}
		else {
			$this->load->view('header',array("pagename" => "Profile"));
	        $this->load->view('loginbox');
			$this->load->view('nav');
			$this->load->view('error',array('error' => "You need to be logged in to edit your profile."));
			$this->load->view('footer');
		}
	}
	
	public function showProfile($userId, $profileType = 'big')
	{
		$this->load->model('user','',true);
		
		$this->load->view('header',array("pagename" => "Profile"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		$data = array(	'profileType' => $profileType,
						'profiles' => array($this->user->getUserProfile($userId)));
		$this->load->view('content/profile',$data);
		$this->load->view('footer');
	}
	
	public function like()
	{
		$otherUser = $this->input->post('otherId');
        
        if($otherUser != null)
        {
            $this->model->load('User', 'user');
            $this->user->like($otherUser);
        }
	}
}
?>
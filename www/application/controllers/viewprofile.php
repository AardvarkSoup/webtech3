<?php

include_once('loginsystem.php');
class ViewProfile extends LoginSystem
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
}
?>
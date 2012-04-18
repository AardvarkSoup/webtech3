<?php

include_once('loginsystem.php');
class ViewProfile extends LoginSystem
{
	public function index($msg = null)
	{
		$this->load->view('header',array("pagename" => "Profile"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		$this->load->view('error',array('error' => "Woops, we don\'t know that user."));
		$this->load->view('footer');
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
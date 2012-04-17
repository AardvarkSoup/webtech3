<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Datingsite extends CI_Controller 
{

    public function index($msg = null)
	{
        $this->load->model('user','',true);
        $this->load->library('personality');
        $this->load->helper('html');
		$this->load->helper('form');
		
		$this->load->view('header');
        $this->load->view('loginbox');
		$this->load->view('nav');
        
        
        $this->load->view('content/showcase');
        $data = array ( 'profileType'	=> 'small',
        				'profiles'		=> $this->_makeProfiles());
        $this->load->view('content/profile', $data);
        $this->load->view('footer');
	}
	
	public function updateUser()
	{
		$this->load->library('form_validation');
		
		if ($this->form_validation->run() == false) {
			$this->load->view('myform');
		}
		else {
			$this->load->view('formsuccess');
		}
		
		$this->load->helper('html');
		$data = array('user' => array('text' => 'Smoked yah!'));
		$this->load->view('content/testView', $data);
	}
	
	private function _buildUser($userId) {
		$user = $this->user->load($userId);
		$user['personality'] = $this->personality->dominantPersonalityComponents($user);
		$user['preference'] = $this->personality->dominantPersonalityComponents($user, true);
		return $user;
	}
	
    /*  Makes the small profiles for the homepage
    */
    private function _makeProfiles()
    {
        //$this->load->library('parser');
        //$data = array(
        $profiles = array(1 => $this->user->getUserProfile(1),
                          2 => $this->user->getUserProfile(2), 
                          3 => $this->user->getUserProfile(3), 
                          4 => $this->user->getUserProfile(4), 
                          5 => $this->user->getUserProfile(5),
                          6 => $this->user->getUserProfile(6));
        //$this->parser->parse('content/profiles-small', $data);
        return $profiles;
    }
}    

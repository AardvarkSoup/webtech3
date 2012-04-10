<?php
class Datingsite extends CI_Controller {

    public function index()
	{
        $this->load->model('user','',true);
        $this->load->library('personality');
        $this->load->helper('html');
		$this->load->helper('form');
        
		$this->user->updateSelf( array('description' => 'I like apples and kiwi\'s and strawberries and melon. I also enjoy watching the birds building their nest in the back of the garden. Too bad those eksters keep attacking them...' ));
		
		$this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
        $this->load->view('content/showcase');
        /*$data = array ( 'profileType'	=> 'big',
        				'user'			=> $this->buildUser(1));*/
        $data = $this->buildUser(1);
        $data['profileType'] = 'small';
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
	
	private function buildUser($userId) {
		$user = $this->user->load($userId);
		$user['personality'] = $this->personality->dominantPersonalityComponents($user);
		$user['preference'] = $this->personality->dominantPersonalityComponents($user, true);
		return $user;
	}
	
    /*  Makes the small profiles for the homepage
    */
    private function makeProfiles()
    {
        $this->load->library('parser');
        $data = array('profiles' => array(  array('number' => '1'),
                                            array('number' => '2'), 
                                            array('number' => '3'), 
                                            array('number' => '4'), 
                                            array('number' => '5'),
                                            array('number' => '6')));
        $this->parser->parse('content/profiles-small', $data);
    }
}    

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller 
{

    public function index($msg = null)
	{
        $this->load->model('user','',true);
        $this->load->library('personality');
        $this->load->helper('html');
		$this->load->helper('form');
		
		$this->load->view('header',array("pagename" => "Home"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		// Show message if specified.
		if($msg !== null)
		{
		    $this->parser->parse('msgbox', array('message' => $msg));
		}
        
        $this->load->view('content/showcase');
        $data = array ( 'profileType'	=> 'big',
        				'user'			=> $this->_buildUser(1));
        $data = $this->_buildUser(1);
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
        $this->load->library('parser');
        $data = array('profiles' => array(  array('number' => '1'),
                                            array('number' => '2'), 
                                            array('number' => '3'), 
                                            array('number' => '4'), 
                                            array('number' => '5'),
                                            array('number' => '6')));
        $this->parser->parse('content/profiles-small', $data);
    }
    
    public function login()
    {        
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if($email === false || $password === false)
        {
            // Show regular page if nothing in POST.
            $this->index();
            return;
        }
        
        $id = $this->authentication->login($email, $password);
        
        $error = null;
        if($id === null)
        {
            $error = 'Login error: this e-mail/password combination does not exist.';
        }
        
        $this->index($error);
    }
    
    public function logout()
    {
        // Log out the current user and show homepage.
        $this->authentication->logout();
        
        $this->index();
    }
    
    public function delete()
    {
        $sure = $this->input->post('sure');
        
        if($sure == 'yes')
        {
            $this->model->load('User', 'user');
            
            $this->user->deleteSelf();
            $this->authentication->logout();
        }
    }
}    

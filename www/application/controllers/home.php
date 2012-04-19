<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once('profilebrowser.php');
class Home extends ProfileBrowser
{

    public function index($msg = null)
	{
        $this->load->model('user','',true);
		
		$this->load->view('header',array("pagename" => "Home"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		
		// Show message if specified.
		if($msg !== null)
		{
		    $this->parser->parse('msgbox', array('message' => $msg));
		}
        
        $this->load->view('content/showcase');
        // Get a list of 600 userid's in random order and put them in the profile browser
    	$this->userBrowser($this->user->getRandomUsers(600));
        
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
    
    /*
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
   */ 
}    

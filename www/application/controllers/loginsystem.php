<?php

class LoginSystem extends CI_Controller
{
	
	function __construct() {
        parent::__construct();
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
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller 
{

    public function index($msg = null)
	{
        $this->load->model('user','',true);
        $this->load->library('personality');
		
		$this->load->view('header',array("pagename" => "Home"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		// Show message if specified.
		if($msg !== null)
		{
		    $this->parser->parse('msgbox', array('message' => $msg));
		}
        
        $this->load->view('content/showcase');
        /*$data = array ( 'profileType'	=> 'big',
        				'user'			=> $this->_buildUser(1));
        $data = $this->_buildUser(1);
        $data['profileType'] = 'small';
        $this->load->view('content/profile', $data);*/
        $this->shuffledProfiles();
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
    
	/**
	 * Takes a list of six userid's and parses their profiles to html
	 * 
	 * @throws Exception	If the list of id's exeeds six. 
	 */
    public function displayProfiles(/* ... */)
    {
        $userIds = func_get_args();
        
        if(count($userIds) == 1 && is_array($userIds[0]))
        {
            $userIds = $userIds[0];
        }
        
        // There shouldn't be more than six profiles to display.
        if(count($userIds) > 6)
        {
            throw new Exception("Can't display more than six profiles");
        }
        
        // Output nothing if there are no id's.
        if(count($userIds) == 0)
        {
            return;
        }
        
        // Load the user model.
        $this->load->model('User', 'user');
        
        // Load profiles.
        $profiles = array();
        foreach($userIds as $id)
        {
            $profiles[] = $this->user->getUserProfile($id);
        }
        // The profiles are placed in the data array and the profile tpe is set.
        $data['profiles'] = $profiles;
        $data['profileType'] = 'small';
        // Display profile overviews.
        $this->parser->parse('content/profile', $data);
    }
    
    /**
     * Prints a browser with shuffeled user profiles. A maximum of 600 randomly selected profiles
     * are accesible by dynamicly altering the page. 
     * 
     * @param array(int) $ids The id's of the users that should be browsable.
     */
    public function shuffledProfiles()
    {
    	// Get a list of 600 userid's in random order
    	$ids = $this->user->getRandomUsers(600);
    	
        // Display the first six results (or less, if there aren't as much).
        $toDisplay = array_splice($idscopy = $ids, 0, min(6, count($ids)));
        $this->displayProfiles($toDisplay);
            
        // Add the search browser and give it all the found id's.
        // We restrict the number of id's send to 600 to prevent generating a gigantic javascript
        // file when there are a lot of matches. 600 is more than enough since I doubt many people
        // will click 'next' more than a hundred times.
        $data = array('ids' => array());
        for($i = 0; $i < min(600, count($ids)); ++$i)
        {
            $data['ids'][] = array('id' => $ids[$i]);
        }
            
        $this->parser->parse('searchbrowser', $data);
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

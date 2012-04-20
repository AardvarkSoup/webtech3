<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ProfileUpdate extends CI_Controller {

// After succesfully validating a filled in form, use the POST data to add a new user to the
	// database.
    private function _updateUser()
	{
	    // Load libraries and models.
	    $this->load->library('picture');
	    $this->load->model('user');
	    
	    // Fetch input.
	    $input = $this->input->post();
	    
	    // This will be contain the parsed data as added to the user model.
	    $data = array();
	    
	    // Values that can be directly put to the data array (after a rename of their keys).
	    $rename = array(
	                'username' => 'username',
	                'firstName' => 'firstName',
	                'lastName' => 'lastName',
	                'email' => 'email',
	                'gender' => 'gender',
	                'description' => 'description',
	                'ageprefmin' => 'minAgePref',
	                'ageprefmax' => 'maxAgePref',
	                'birthdate' => 'birthdate'
	              );
	    foreach($rename as $key => $newkey)
	    {
	        if(isset($input[$key]))	
	    		$data[$newkey] = $input[$key];
	    }
	    
	    // Parse gender preference.
	    $data['genderPref'] = $input['genderPref'] == 2 ? null : (int) $input['genderPref'];
	    
	    // Parse brand preferences.
	    if(isset($input['brandpref']))
	    	$brands = array_keys(array_filter($input['brandpref']));
	    
		$profile = $this->user->load($this->authentication->currentUserId());
	    
	    foreach($profile as $key => $val) {
	    	if(isset($data[$key]))
	    		$profile[$key] = $data[$key]; 
	    }
	    
	    // Process uploaded image. 'picture' will be set to null if none are specified.
	    $data['picture'] = $this->picture->uploadAndProcess();
	    if(!$data['picture'] == null)
	    {
	        $profile['picture'] = $data['picture']; 
	    }
	    
	    // Actually create the user.
	    $this->user->updateSelf($profile);
	}
	
	public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('personality', 'form_validation', 'upload'));
        $this->load->helper(array('html', 'form'));
		
		$this->load->view('header',array("pagename" => "Edit Profile"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		$config = array(
				array(
        			'field' => 'firstName',
        			'label' => 'Firstname',
        			'rules' => 'required'
				),
				array(
        			'field' => 'lastName',
        			'label' => 'Lastname',
        			'rules' => 'required'
				),
				array(
        			'field' => 'email',
        			'label' => 'Email',
        			'rules' => 'required|valid_email'
				),
				array(
        			'field' => 'gender',
        			'label' => 'Gender',
        			'rules' => 'required'
				),
				array(
        			'field' => 'birthdate',
        			'label' => 'Birthdate',
        			'rules' => 'required|alpha-dash|callback_validateDate'
				),
				array(
        			'field' => 'description',
        			'label' => 'About you',
        			'rules' => ''
				),
				array(
        			'field' => 'genderPref',
        			'label' => 'Gender preference',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmin',
        			'label' => 'Minimum age',
        			'rules' => 'required|numeric|greater_than[17]|callback_validAgePref[ageprefmax]'
				),
				array(
        			'field' => 'ageprefmax',
        			'label' => 'Maximum age',
        			'rules' => 'required|numeric|less_than[123]'
				),
				array(
                    'field' => 'picture',
                    'label' => 'Upload picture',
                    'rules' => ''				
				)
        );
		
        // The list of brands is loaded from the database
        $this->load->model('brand');
        $brands = $this->brand->getBrands();
        
		foreach($brands as $brand)
	    {
			$config[] = array(
	        				'field' => $brand,
	        				'label' => $brand,
	        				'rules' => '');
	    }
	    
        // And all the rules are added to the form_validation
        $this->form_validation->set_rules($config);
        // All errors are placed in a div with the error class
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        // The profile is loaded
        $profile = $this->user->load($this->authentication->currentUserId());
        
        // Check if there is a post result. If there is, the brandpref list is handed
        // to the brandCheckBoxes function for re-populating.
        if(array_key_exists('brandpref',$_POST)) {
        	$brandpref = $_POST['brandpref'];
        }
        else {
        	// If there is a profile, its brandspreferences are used as the re-populate list.
        	$brandpref = $profile['brands'];
        }
        
        $data = array('profile'			 => $profile,
        			  'brands'           => $brands,
                      'brandPreferences' => $brandpref);
        
        // Display the form while it is invalid.
        if ($this->form_validation->run() === false) 
        {
            $this->load->view('content/registerView', $data);
		}
		else 
		{
    	    // The form input is validated, the user is registeredand the succesmessage is displayed.
    		$this->_updateUser();
    		$data['succesmessage'] =  "You have succesfully updated your profile";
    		$this->load->view('content/registerView', $data);
		}
        
        $this->load->view('footer');
	}
	
	/**
	 * 
	 * Our own date-validation rule.
	 * @param string $date	The date that will be checked
	 * @return boolean		False if failed
	 */
	public function validateDate($date)
	{
		$dummy = array();
	    $validDate = !!preg_match_all('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $date, $dummy);
	    $year; $month; $day;
	    sscanf($date, '%D-%D-%D', $year, $month, $day);
	    $thisYear = date('o');
	    $validDate &= $year > $thisYear - 122 && $year <= $thisYear - 18
	               && $month >=  1 && $month <= 12
	               && $day >= 1 && $day <= 31;
	    
	    if(!$validDate) {
	    	if(is_numeric($year) && $year < $thisYear - 122)
	    		$this->form_validation->set_message('validateDate', 
							'If you are older than 122, please contact the Guiness Book of World Records.');
	    	else 
	    		$this->form_validation->set_message('validateDate', 
							'Invalid date');
			return false;
	    }
	    else {
	    	return true;
	    }
	}
	
	/**
	 * 
	 * Checks if ageprefmin is smaller or equal to ageprefmax.
	 * @param int $ageprefmin		The minimum age preference
	 * @param string $ageprefmax	The name for the maximum age preference in the post
	 * @return boolean 				false if fails
	 */
	public function validAgePref($ageprefmin, $ageprefmax)
	{
		if($ageprefmin > $this->input->post($ageprefmax)) {
			$this->form_validation->set_message('validAgePref', 
							'The %s field can not be greater than the Maximum age');
			return false;
		}
		else {
			return true;
		}
	}
	
	public function check_password($password)
	{
		$this->load->model('user','',true);
		$profile = $this->user->load($this->authentication->currentUserID(), array('email'));
		
		if($this->user->lookup($profile['email'], $password) == null) {
			$this->form_validation->set_message('check_password', 
							'Incorrect password');
			return false;
		}
		else return true;
	}
}	
?>
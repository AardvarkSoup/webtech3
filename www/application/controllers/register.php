<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends CI_Controller 
{

	// After succesfully validating a filled in form, use the POST data to add a new user to the
	// database.
    private function _registerUser()
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
	        $data[$newkey] = $input[$key];
	    }
	    
	    // Get password (will be hashed by User::createUser).
	    $password = $input['password'];
	    
	    // Parse gender preference.
	    $data['genderPref'] = $input['genderPref'] == 2 ? null : (int) $input['genderPref'];
	    
	    // Parse brand preferences.
	    $brands = $input['brandpref'];
	    
	    // Process uploaded image. 'picture' will be set to null if none are specified.
	    $data['picture'] = $this->picture->uploadAndProcess();
	    
	    // Determine personality from questionnaire.
	    // Also set initial personality preference to the opposite of that.
	    {
	        // Parse answers.
	        $answers = array(); 
	        for($q = 1; $q <= 19; ++$q)
	        {
	            $answers[$q] = $input["question$q"];
	        }
	        
	        // E versus I.
	        $e = 50;
            for($i = 1; $i <= 5; ++$i)
            {
                if($answers[$i] == 'A')
                {
                    $e += 10;
                }
                else if($answers[$i] == 'B')
                {
                    $e -= 10;
                }
            }
            $data['personalityI'] = 100 - $e;
            $data['preferenceI'] = $e;
            
            // N versus S.
            $n = 50;
            for($i = 1; $i <= 5; ++$i)
            {
                if($answers[$i] == 'A')
                {
                    $n += 12.5;
                }
                else if($answers[$i] == 'B')
                {
                    $n -= 12.5;
                }
            }
            $data['personalityN'] = (int) $n;
            $data['preferenceN'] = 100 - (int) $n;
            
            // T versus F.
            $t = 50;
            for($i = 1; $i <= 5; ++$i)
            {
                if($answers[$i] == 'A')
                {
                    $t += 12.5;
                }
                else if($answers[$i] == 'B')
                {
                    $t -= 12.5;
                }
            }
            $data['personalityT'] = (int) $t;
            $data['preferenceT'] = 100 - (int) $t;
            
            // J versus P.
            $j = 50;
            for($i = 1; $i <= 5; ++$i)
            {
                if($answers[$i] == 'A')
                {
                    $j += 8.3333;
                }
                else if($answers[$i] == 'B')
                {
                    $j -= 8.3333;
                }
            }
            $data['personalityJ'] = (int) $j;
            $data['preferenceJ'] = 100 - (int) $j;
	    }
	    
	    // Actually create the user.
	    $this->user->createUser($data, $password, $brands);
	}
	
	public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('personality', 'form_validation'));
        $this->load->helper('html');
		$this->load->helper('form');
				
		$this->load->view('header',array("pagename" => "Register"));
        $this->load->view('loginbox');
		$this->load->view('nav');
		
		$config = array(
        		array(
        			'field' => 'username',
        			'label' => 'Username',
        			'rules' => 'required|min_length[4]|max_length[20]' 
        			//TODO controleer dat de naam uniek is. Zie is_unique[table.field]bij form_validation
				),
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
        			'field' => 'password',
        			'label' => 'Password',
        			'rules' => 'required|min_length[8]|max_length[20]'
				),
				array(
        			'field' => 'passconf',
        			'label' => 'Repeat password',
        			'rules' => 'required|matches[password]|min_length[8]|max_length[20]'
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
        
		// For each of the 20 personality question, the rule required is set
        for($q = 1; $q <= 19; ++$q)
	    {
	        $config[] =	array(
        					'field' => "question$q",
        					'label' => "Personality question $q",
        					'rules' => 'required'
						);
	    }
	    
	    //TODO Hier gaat het fout.
        //$config = $this->rules_storage->getRules();
        
        // And all the rules are added to the form_validation
        $this->form_validation->set_rules($config);
        // All errors are placed in a div with the error class
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        // Check if there is a post result. If there is, the brandpref list is handed
        // to the brandCheckBoxes function for re-populating.
        if(array_key_exists('brandpref',$_POST)) {
        	$brandpref = $_POST['brandpref'];
        }
        else {
        	// If there is a profile, its brandspreferences are used as the re-populate list.
        	$brandpref = null;
        }
        
        $data = array('brands'           => $brands,
                      'brandPreferences' => $brandpref);
        
        // Display the form while it is invalid.
        if ($this->form_validation->run() === false) 
        {
            $this->load->view('content/registerView', $data);
		}
		else 
		{
    	    // The form input is validated, the user is registeredand the succesmessage is displayed.
    		$this->_registerUser();
    		$this->load->view('content/register_succes', $_POST);
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
	    	if($year < $thisYear - 122)
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
	
}	

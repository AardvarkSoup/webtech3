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
	                'firstname' => 'firstName',
	                'lastname' => 'lastName',
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
	    $data['genderPref'] = $input['genderpref'] == 2 ? null : (int) $input['genderpref'];
	    
	    // Parse brand preferences.
	    $brands = array_keys(array_filter($input['brandpref']));
	    
	    // Process uploaded image.
	    if(isset($input['picture']))
	    {
	        $data['picture'] = $this->picture->process($input['picture']);
	    }
	    
	    // Determine personality from questionnaire.
	    // Also set initial personality preference to the opposite of that.
	    {
	        // Parse answers.
	        $answers = array(); 
	        for($q = 1; $q <= 19; ++$q)
	        {
	            $answers[$q] = $input["Question$q"];
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
		
		// TODO: upload configuration.
		$this->load->library('upload');
		
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
        			'field' => 'firstname',
        			'label' => 'Firstname',
        			'rules' => 'required'
				),
				array(
        			'field' => 'lastname',
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
        			'rules' => 'required|valid_email' //TODO email uniek checken
				),
				array(
        			'field' => 'gender',
        			'label' => 'Gender',
        			'rules' => 'required'
				),
				array(
        			// TODO: validate and indicate or enforce date formatting
        			'field' => 'birthdate',
        			'label' => 'Birthdate',
        			'rules' => 'required'
				),
				array(
        			'field' => 'description',
        			'label' => 'About you',
        			'rules' => ''
				),
				array(
        			'field' => 'genderpref',
        			'label' => 'Gender preference',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmin',
        			'label' => 'Minimum age',
        			'rules' => 'required|greater_than[18]'
				),
				array(
        			'field' => 'ageprefmax',
        			'label' => 'Maximum age',
        			'rules' => 'required|less_than[122]'
				)
        );
		
        // Some inputs are selects from a list of possibilities.
        // The list of gender options is prepared
        $genders = array( '0' => 'Man',
        				  '1' => 'Woman');
        // The list of possible gender preferences
        $genderprefs = array( '0' => 'Men',
        				  	  '1' => 'Women',
        					  '2' => 'Men/Women');
        
        // The list of brands is loaded from the database and turned into html
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
        $this->form_validation->set_rules($config);
        // All errors are placed in a div with the error class
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        // Check if there is a post result. If there is, the brandpref list is handed
        // to the brandCheckBoxes function for re-populating.
        if(array_key_exists('brandpref',$_POST)) {
        	$brandpref = $_POST['brandpref'];
        }
        else {
        	$brandpref = null;
        }
        
        $data = array('genders' => $genders,
        			  'genderprefs' => $genderprefs,
        			  'brandPreferences' => $this->brandCheckBoxes($brands,$brandpref)
                );
        
        // Display the form while it is invalid.
        if ($this->form_validation->run() == false) {
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
	 * The brandprefs are returned in an array. CodeIgniter can not handle an array in its value
	 * set functions, so we made our own. :-)
	 * 
	 * brandCheckBoxes turns an array of brands into html which displays a list of checkboxes.
	 * If the form was posted, the checkboxes are re-populated.
	 * @param array $brands			List of brands
	 * @param array $brandprefs		List of checked brands
	 * @return string				html for a list of checkboxes
	 */
	private function brandCheckBoxes(array $brands, array $brandprefs = null)
	{
		$html = form_fieldset("Brand-preferences", array('class' => 'brands')). 
				form_error('brandpref[]') ."<ul>";
		foreach($brands as $brand) {
			$html .= "<li>". form_checkbox('brandpref[]',$brand, 
						$brandprefs != null && in_array($brand,$brandprefs)). $brand. "</li>";
		}
		return $html. "</ul></fieldset>";
	}
	
}	

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once('profilebrowser.php');
class Search extends ProfileBrowser
{
    // Returns null when input is valid, or an error message otherwise.
    private function _validateInput($input)
    {
        // Generic error message.
        $genericError = 'Something went wrong while processing your search query.';
        
        // Check whether keys are correct.
        $required = array('ownGender', 'genderPref', 'ownAge', 'minAge', 'maxAge', 'attitude',
                          'perceiving', 'judging', 'lifestyle');
        if(sort(array_keys($input)) != sort($required))
        {
            return $genericError;
        }
        
        if(!in_array($input['ownGender'], array('male', 'female')))
        {
            return $genericError;
        }
        
        if(!in_array($input['genderPref'], array('male', 'female', 'either')))
        {
            return $genericError;
        }
        
        foreach (array($input['ownAge'], $input['minAge'], $input['maxAge']) as $age)
        {
            if($age < 18 || $age > 122)
            {
                return "Please enter a valid age: a number between 18 and 122.";
            }
        }
        
        if($input['minAge'] > $input['maxAge'])
        {
            return "Maximal age should not be lower than minimal age.";
        }
        
        return null;
    }
    
    // Displays the matching users. Or nothing, if no user is logged in.
    public function matching()
    {
    	// Confirm whether the user is logged in.
		if(!$this->authentication->userLoggedIn())
		{
			throw new Exception('User is not logged in.');
		}
    	
    	$this->load->model('Matching', 'matching');
    	
    	// Header and navigation bar.
        $this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
        
        $current = $this->authentication->currentUserId();
        if($current !== null)
        {
        	$list = $this->matching->matchingList($current);
        	$this->userBrowser($list);
        }
        
        // Footer.
        $this->load->view('footer');
    }
    
    public function index()
    {        
        // Header and navigation bar.
        $this->load->view('header', array("pagename" => "Search"));
        $this->load->view('nav');
        $this->load->view('loginbox');
        
        // Fetch search query data, if present.
        $input = $this->input->post();
        
        $error = null;
        $doSearch = false;
        $data;
        
        // If something in POST, it means a search query has been done.
        if($input && count($input) > 0)
        {
            // Validate input.
            $error = $this->_validateInput($input);
            
            if($error === null)
            {
                $doSearch = true;
            }
            
            // Specify error and data that should be left filled in form from last query.
            $data = array(
                      'error'   => $error,
                      'sFemale' => $input['ownGender'] == 'female' ? 'selected' : '',
                      
                      'malePref' => $input['genderPref'] == 'male' ? 'selected' : '',
                      'femalePref' => $input['genderPref'] == 'female' ? 'selected' : '',
                      
                      'ownAge' => $input['ownAge'],
                      'minAge' => $input['minAge'],
                      'maxAge' => $input['maxAge'],
                      
                      'sI' => $input['attitude'] == 'I' ? 'selected' : '',
                      'sN' => $input['perceiving'] == 'N' ? 'selected' : '',
                      'sF' => $input['judging'] == 'F' ? 'selected' : '',
                      'sP' => $input['lifestyle'] == 'P' ? 'selected' : '',
                      
                      'brands' => $input['brands']
            ); 
        }
        else
        {
            /*$data = array_fill_keys(
                array('error', 'sFemale', 'malePref', 'femalePref', 'ownAge', 'minAge', 'maxAge',
                      'sI', 'sN', 'sF', 'sP', 'brands'), null);*/
            
            $keys = array('error', 'sFemale', 'malePref', 'femalePref', 'ownAge', 'minAge', 
                          'maxAge', 'sI', 'sN', 'sF', 'sP', 'brands');
            foreach($keys as $key)
            {
                $data[$key] = null;
            }
        }

        
        // Display the search form with filled in values and possible error.
        $this->parser->parse('searchform', $data);
        
        // If a search is to be done. Do so and display first six profiles.
        if($doSearch)
        {
            // Load search model.
            $this->load->model('SearchModel', 'search');
            
            // Perform the search operation.
            $ids = $this->search->search($input);
                        
            $this->userBrowser($ids);
        }
        
        // Footer.
        $this->load->view('footer');
    }
}

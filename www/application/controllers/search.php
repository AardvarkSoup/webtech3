<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller
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
    
    /**
     * TODO
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
        //$this->parser->parse('content/searchresults', array('profiles' => $profiles));
    }
    
    /**
     * Prints a browser for user profiles.
     * 
     * @param array(int) $ids The id's of the users that should be browsable.
     */
    public function userBrowser($ids)
    {
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

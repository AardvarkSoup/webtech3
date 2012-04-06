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
            throw new Exception("Can't display more than one profile");
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
            $profiles[] = $this->user->load($id);
        }
        
        // Display profile overviews.
        $this->parser->parse('searchresults', $profiles);
    }
    
    /**
     * TODO
     */
    public function index()
    {        
        // Header.
        $this->load->view('header');
        
        // Fetch search query data, if present.
        $input = $this->input->post;
        
        $error = null;
        $doSearch = false;
        
        // If something in POST, it means a search query has been done.
        if(count($input) > 0)
        {
            // Validate input.
            $error = _validateInput($input);
            
            if($error === null)
            {
                $doSearch = true;
            }
        }

        // Display the search form. With error message if neccessary.
        $data = array();
        if($error !== null)
        {
            $data['error'] = $error;
        }
        $this->parser->parse('searchform', $data);
        
        // If a search is to be done. Do so and display first six profiles.
        if($doSearch)
        {
            // Load search model.
            $this->load->model('Search', 'search');
            
            // Perform the search operation.
            $ids = $this->search->search($input);
            
            // Display the first six results (or less, if there aren't as much).
            $toDisplay = array_splice($ids, 0, min(6, count($ids)));
            $this->displayProfiles($toDisplay);
            
            // Add the search browser and give it all the found id's.
            $data = array();
            foreach($ids as $id)
            {
                $data[] = array('id' => $id);
            }
            $this->parser->parse('searchbrowser', $data);
        }
        
        // Footer.
        $this->load->view('footer');
    }
}
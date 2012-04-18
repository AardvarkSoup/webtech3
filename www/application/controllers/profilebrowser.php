<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once('loginsystem.php');
class ProfileBrowser extends LoginSystem
{
	
	function __construct() {
        parent::__construct();
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
}
?>
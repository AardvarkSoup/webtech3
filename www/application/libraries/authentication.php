<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Authentication
{
    /**
     * @return bool Whether somebody is logged in.
     */
    public function userLoggedIn()
    {
        return $this->currentUserId() !== null;
    }
    
    /**
     * @return int The ID of the user that is currently logged in, or null of none is.
     */
    public function currentUserId()
    {       
        // Get CodeIgniter object.
        $ci =& get_instance();
        
        // Fetch id from session data.
        // If the session ID in the user's cookie does not match a session in the database, 
        // CodeIgniter will have destroyed the session already and this will return false.
        $id = $ci->session->userdata('userId');
        
        if($id !== false)
        {
            return $id;
        }
        else
        {
            return null;
        }
    }
    
    public function userIsAdmin()
    {
    	// Fetch current user.
        $id = $this->currentUserId();
        
        if($id !== null)
        {
            // Get CodeIgniter object.
            $ci =& get_instance();
            
            // Confirm whether admin with database.
            $result = $ci->db->select('admin')->from('Users')
                             ->where('userId', $id)->get()->row();
            
            if($result->admin)
            {
                // If an admin, return true.
                return true;
            }
        }
        
        // No admin.
        return false;
    }
    
    /**
     * Throws an exception if the currently logged in user, if any, is not an administrator.
     * 
     * This should be used to protect admin only functionality at the server side against forged
     * requests. This function shouldn't be used in areas reachable by regular users.
     * 
     * @throws Exception If no administrator is logged in.
     */
    public function assertAdministrator()
    {
        if(!$this->userIsAdmin())
        {
        	throw new Exception('Access denied.');
        }
    }
    
    
    /**
    * Logs in a user.
    *
    * @param email     The e-mail adres the user has entered.
    * @param password  The password the user has entered.
    * 
    * @return int    The user ID of the user that has been succesfully logged in, if any. 
    *                If no user with the provided e-mail/password combination exists. This will
    *                return null.
    */
    public function login($email, $password)
    {
        // Get CodeIgniter object.
        $ci =& get_instance();
        
        if($this->authentication->userLoggedIn())
        {
            // You can't log in twice.
            return null;
        }
    
        // Load user model.
        $ci->load->model('user');
    
        // Look up which user belongs to this e-mail address and password.
        $id = $ci->user->lookup($email, $password);
    
    
        if($id === null)
        {
            // Login failed. Return null.
            return null;
        }
        else
        {
            // Add the user's ID to the session data.
            $ci->session->set_userdata('userId', $id);

            // Return the username.
            return $id;
        }
    }
    
    /**
     * Logs out the current user.
     */
    public function logout()
    {
        // Simply destroy the current session.
        $ci =& get_instance();
        $ci->session->sess_destroy();
    }
}

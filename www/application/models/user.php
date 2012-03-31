<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model
{
    /**
     * Indicates whether anonymous users can view the content of a certain column. Columns not in
     * this array shouldn't be visible at all. Besides these, viewing brand preferences also does
     * not require being logged in.
     */
    private $visibility = array(
        'username'      => true,
        'email'         => false,
        'firstName'     => false,
        'lastName'        => false,
        'gender'        => true,
        'birthdate'        => true,
        'description'    => true,
        'minAgePref'    => true,
        'maxAgePref'    => true,
        'genderPref'    => true,
        'personalityI'    => true,
        'personalityN'    => true,
        'personalityT'    => true,
        'personalityJ'    => true,
        'preferenceI'    => true,
        'preferenceN'    => true,
        'preferenceT'    => true,
        'preferenceJ'    => true,
        'picture'        => false
    );
    
    /**
     * Returns the columns visibile to the currently active user.
     */
    private function visibleColumns()
    {
        $login = $this->authentication->userLoggedIn();
        if($login)
        {
            return array_keys($this->visibility);
        }
        else
        {
            $result = array();
            foreach($this->visibility as $col => $visible)
            {
                if($visible)
                {
                    $result[] = $col;
                }
            }
            return $result;
        }
    }
    
    /**
     * Loads the preferred brands of a user.
     * 
     * @param int $userId The ID of the user.
     * 
     * @return array(string) The preferred brands of this user. 
     */
    private function loadUserBrands($userId)
    {
        $result = $this->db->select('UserBrands.brandName')
                           ->from('UserBrands, Brands')
                           ->where('UserBrands.brandName == Brands.brandName')
                           ->where(array('userId' => $userId))
                           ->get()->result_array();
        
        if(count($result) == 0)
        {
            //TODO
            throw new Exception('User not found or no brands associated.');
        }
        
        $brandNames = array();
        foreach($result as $row)
        {
            $brandNames[] = $row['brandName'];
        }
        return $brandNames;
    }
    
    private function removeInvisibleColumns($data)
    {
        $login = $this->authentication->userLoggedIn();
        
        $result = array();
        foreach($data as $key => $val)
        {
            if(isset($this->visibility[$key]))
            {
                if($login || $this->visibility[$key])
                {
                    $result[$key] = $val;
                }
            }
        }
    
        return $result;
    }
    
    /**
     * Loads the properties of a user visible to the current user. These properties are the values
     * of columns in the Users-table plus an entry 'brands' containing an array of strings 
     * representing brand names preferred by the user.
     * 
     * @param int           $userId     The identifier of the user to load.
     * @param array(string) $properties The properties of the user to load. If null,
     *                                  all accessible properties are loaded.
     * 
     * @return array(string => mixed) An associative array of user properties and values.
     */
    public function load($userId, $properties = null)
    {
        // Determine which columns to select.
        if($properties !== null)
        {
            // Filter by wanted properties.
            $columns = $this->removeInvisibleColumns($properties);
        }
        else
        {
            $columns = $this->visibleColumns();
        }
        
        $result = $this->db->select($columns)
                           ->from('Users')
                           ->where(array('userId' => $userId))
                           ->get()->row_array();
        
        if(!$result)
        {
            //TODO
            throw new Exception('User not found.');
        }
        
        $user = $result;
        
        // Add brands if wanted.
        if($properties === null || in_array('brands', $properties))
        {
            $user['brands'] = $this->loadUserBrands($userId);
        }
        
        return $user;
    }
    
    public function updateSelf($newprops)
    {
        // Determine which user is logged in.
        $userId = $this->authentication->currentUserId();
        
        if($userId === null)
        {
            //TODO
            throw new Exception('User is not logged in.');
        }
        
        //Start a transaction.
        $this->db->trans_start();
                
        // Values in Users table to change are all new properties that are also visible columns.
        $data = $this->removeInvisibleColumns($newprops);
        
        // Do update.
        $this->db->where(array('userId' => $userId))
                 ->update('Users', $data);
        
        // If requested, update brands.
        if(isset($newprops['brands']))
        {
            // First simply remove this user's brands.
            $this->db->delete('UserBrands', array('userId' => $userId));
            
            // Now insert new brands.
            foreach($newprops['brands'] as $brand)
            {
                $this->db->insert('UserBrands',
                                array('userId'    => $userId,
                                      'brandName' => $brand));
            }
            //$this->db->insert_batch('UserBrands', $brandList);
        }
                 
        // Complete transaction.
        $this->db->trans_complete();
    }
    
    // Creates a salted SHA-1 hash of a password.
    private function hashPassword($password)
    {
        // The encryption key we specified is also perfectly suitable to be a salt.
        // Also add a smiley face wearing a hat, which is incredibly important.
        $salt = $this->config->item('encryption_key') . '<:)';
        
        return do_hash($password . $salt);
    }
    
    /**
     * Create a new user.
     * 
     * @param array(string => mixed) $data     Values to be inserted into the Users table, except for
     *                                         the password hash. Data should be already validated!
     * @param string                 $password The user's password. Will be hashed by this function.
     * @param array(string)          $brands   List of preferred brands by this user. There should
     *                                         be at least one.
     *                                         
     * @return int The ID of the newly created user.
     */
    public function createUser($data, $password, $brands)
    {        
        // Hash password.
        $data['passwordHash'] = $this->hashPassword($password);
        
        //Start a transaction.
        $this->db->trans_start();
        
        // Insert new user into table.
        $this->db->insert('Users', $data);
        
        // Add preferred brands.
        $userId = $this->db->insert_id();
        $brandList = array();
        foreach($brands as $brand)
        {
            $this->db->insert('UserBrands',
                                array('userId'    => $userId,
                                      'brandName' => $brand));
        }
        
        // Complete transaction.
        $this->db->trans_complete();
        
        return $userId;
    }
    
    /**
     * Delete the currently active user.
     */
    public function deleteSelf()
    {
        // Determine which user is logged in, if any.
        $userId = $this->authentication->currentUserId();
        
        if($userId === null)
        {
            //TODO
            throw new Exception('User is not logged in.');
        }
        
        // Delete this user.
        $this->db->delete('Users', array('userId' => $userId));
    }
    
    /**
     * Makes the current user 'like' another user.
     * 
     * @param int $likedUser ID of the user to like.
     */
    public function like($likedUser)
    {
        // Determine which user is logged in, if any.
        $userId = $this->authentication->currentUserId();
        
        if($userId === null)
        {
            throw new Exception('Not logged in.');
        }
        if($userId == $likedUser)
        {
            throw new Exception("Can't like yourself.");
        }
        
        // For learning, retrieve the current alpha setting.
        $alpha = $this->db->select('alpha')->from('Configuration')
                          ->get()->row()->alpha;
        $beta = 1 - $alpha;
        
        // Start a transaction.
        $this->db->trans_start();
        
        // Determine the new personality preference of this user.
        $ownPref = $this->db->select(array('preferenceI', 'preferenceN', 
                                           'preferenceT', 'preferenceJ'))
                            ->from('Users')
                            ->where('userId', $userId)
                            ->get()->row_array();
        $likedPers = $this->db->select(array('personalityI', 'personalityN', 
                                             'personalityT', 'personalityJ'))
                              ->from('Users')
                              ->where('userId', $likedUser)
                              ->get()->row_array();
        $newPref = array();
        foreach(array('I', 'N', 'T', 'J') as $d)
        {
            $newPref["preference$d"] = $alpha * $ownPref["preference$d"]
                                     + $beta * $likedPers["personality$d"];
        }
        
        // Now update it.
        $this->db->where('userId', $userId)
                 ->update('Users', $newPref);
        
        // Add to Likes table (unique and foreign key constraints enforce $likedUser is an
        // existing user id and that there are no duplicates).
        $this->db->insert('Likes', array(
                                      'userLiking' => $userId,
                                      'userLiked'  => $likedUser
                                    ));
        
        // Complete transaction.
        $this->db->trans_complete();
    }
    
    /**
     * Indicates the 'like-status' between the current user and another one. 
     * @param int $otherUser The other user.
     * 
     * @return array(bool) An array, containing two booleans A and B, where A is true iff the 
     *                     current user has liked the other one and B is true iff the other user 
     *                     likes this one.
     */
    public function getLikeStatus($otherUser)
    {
        $userA = $this->authentication->currentUserId();
        $userB = $otherUser;
        
        if($userA === null)
        {
            //TODO
            throw new Exception('User is not logged in.');
        }
        
        // Query whether this user likes the other one.
        $likeAB = (bool) $this->db->from('Likes')
                              ->where('userLiking', $userA)
                              ->where('userLiked', $userB)
                              ->get()->row();
                           
        // Query whether the other user likes this one.
        $likeBA = (bool) $this->db->from('Likes')
                                     ->where('userLiking', $userB)
                                  ->where('userLiked', $userA)
                                  ->get()->row();
                           
        
        return array($likeAB, $likeBA);
    }
}
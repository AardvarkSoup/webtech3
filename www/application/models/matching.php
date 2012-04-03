<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Matching extends CI_Model
{
    // Determine the length on the union of two arrays in O(n). $arrayA should've been flipped.
    private function _unionLength($arrayA, $arrayB)
    {
        $result = count($arrayA);
        foreach($arrayB as $val)
        {
            if(!isset($arrayA[$val]))
            {
                ++$result[];
            }
        }
        
        return $result;
    }
    
    // Determine the length on the intersection of two arrays in O(n). $arrayA should've been 
    // flipped.
    private function _intersectLength($arrayA, $arrayB)
    {
        $result = 0;
        foreach($arrayB as $val)
        {
            if(isset($arrayA[$val]))
            {
                ++$result;
            }
        }
        
        return $result;
    }
    
    private function _distance($similarityMeasure, $xFactor, $userA, $userB)
    {
        // First determine distance between personality types and preferences.
        $pd1 = ($userA->personalityI - $userB->preferenceI)
             - ($userA->personalityN - $userB->preferenceN)
             - ($userA->personalityT - $userB->preferenceT)
             - ($userA->personalityJ - $userB->preferenceJ);
        $pd2 = ($userB->personalityI - $userA->preferenceI)
             - ($userB->personalityN - $userA->preferenceN)
             - ($userB->personalityT - $userA->preferenceT)
             - ($userB->personalityJ - $userA->preferenceJ);
        
        // Take maximum of absolute distances and divide by four.
        $personalityDistance = max(abs($pd1), abs($pd2)) / 4;
        
        // Make the brand names of userA keys instead of values, effectively turning it into a hash
        // table with O(1) lookup time. 
        $brandKeys = array_flip($userA->brands);
        
        // Calculate distance between brand preferences by using the currently condifured 
        // similarity measure.
        $alen = count($userA);
        $blen = count($userB);
        $i = $this->_intersectLength($userA, $userB);
        $similarity;
        switch($similarityMeasure)
        {
            case Configuration::SMC_DICE:
                $similarity = 2 * $i / ($alen + $blen);
                break;
            case Configuration::SMC_JACCARD:
                $similarity = $i / $this->_unionLength($userA, $userB); 
                break;
            case Configuration::SMC_COSINE:
                $similarity = $i / (sqrt($alen) * sqrt($blen));
                break;
            case Configuration::SMC_OVERLAP:
                $similarity = $i / min($alen, $blen);
                break;
        }
        
        // Brand distance is the opposite of the brand's similarity.
        $brandDistance = 1 - $similarity;
        
        // Now return the total distance based on the current x-factor.
        return $xFactor * $personalityDistance 
             + (1 - $xFactor) * $brandDistance;
    }
    
    public function matchingList($userId)
    {
        // Query expression to determine the age of a user.
        $age = "(strftime('%Y', 'now') - strftime('%Y', birthdate)
              - (strftime('%j', 'now') < strftime('%j', birthdate)))";
        
        // Select neccessary data from user to match with.
        $user = $this->db->select(array('gender', 'genderPref', 'birthdate', 'minAgePref', 
        						        "$age AS age", 'maxAgePref'))
                         ->from('Users')
                         ->where('userId', $userId)
                         ->get()->row();
        
        if(!$user)
        {
            throw new Exception('User not found.');
        }
        
        // Start building a query on the users table.
        $query = $this->db->select()->from('Users');
        
        
        // First disallow matching the user with itself. Because recommending people to date 
        // themselves would be pretty weird. 
        $query->where('userId <>', $userId);
        
        // Constrain on gender and mutual preference.
        if($user->genderPref !== null)
        {
            $query->where('gender', $user->genderPref);
        }
        
        // Note: We only append a zero or one to the query, therefore this is not unsafe.
        $query->where('(genderPref IS NULL OR genderPref = ' . ($user->gender ? '1' : '0') . ')');
        
        
        // Now constrain on mutual age preference.
        $query->where("$age >=", $user->minAgePref);
        $query->where("$age <=", $user->maxAgePref);
        $query->where('minAgePref <=', $user->age);
        $query->where('maxAgePref >=', $user->age);
        
        
        // Do query.
        $matches = $query->get()->result();

        // Determine brand preferences of current user.
        $brands = $this->db->select('UserBrands.brandName')
                           ->from('UserBrands, Brands')
                           ->where('UserBrands.brandName == Brands.brandName')
                           ->where(array('userId' => $userId))
                           ->get()->result();
        $user->brands = array();
        foreach($brands as $brand)
        {
            $user->brands[] = $brand->brandName;
        }
        
        // Determine current similarity measure and x-factor.
        $configs = $this->db->select(array('similarityMeasure', 'xFactor'))
                            ->from('Configuration')
                            ->get()->row();;
        
        // Now compute personality distances between this users and possible matches.
        $distances = array();
        foreach($matches as &$match)
        {
            // First determine brand preferences for this user.
            $match->brands = $this->db->select('UserBrands.brandName')
                  ->from('UserBrands, Brands')
                  ->where('UserBrands.brandName == Brands.brandName')
                  ->where(array('userId' => $match->userId))
                  ->get()->result_array();
            
            // Now calculate the distance to this user.
            $distances[] = $this->_distance($configs->similarityMeasure, $configs->xFactor, $match);
        }
        
        
        // Sort the matches on these distances.
        array_multisort($distances, SORT_NUMERIC, $matches);
        
        // Return the ordered matches.
        return $matches;
    }
}
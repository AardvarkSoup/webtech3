<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Personality
{
    
    /**
     * This function takes an associative array containing personality values (e.g. the result of
     * User::load); these values have keys 'personalityI', 'personalityN', 'personalityT' or
     * 'personalityJ' and a floating point number between 0 and 1 as value. Each of these present
     * in the input array will either be returned directly or, if their value is lower than 0.5, the
     * opposite letter of the same dicotomy will be given instead. 
     * 
     * @param array(string => mixed) $arr        The input associative array. Will not be modified.
     * @param bool                   $preference If true, this will work on preferences rather than 
     *                                           own personalities.
     * 
     * @return array(string => float) The resulting dominant personality values, e.g. 
     *                                array(I => 0.8, N => 0.7,  T => 0.51, J => 0.65).      
     */
    public function dominantPersonalityComponents($arr, $preference = false)
    {
        // Determine whether to look at personality or preference.
        $subject;
        if($preference)
        {
            $subject = 'preference';
        }
        else
        {
            $subject = 'personality';
        }
        $slen = strlen($subject);
        
        // Mapping of components to their opposites. 
        $opposites = array('I' => 'E', 'N' => 'S', 'T' => 'F', 'J' => 'P');
        
        // Filter personality/preference values from array.        
        $result = array();
        foreach($arr as $key => $val)
        {
            // Check whether key starts with 'personality'
            if(substr_compare($subject, $key, 0, $slen) === 0)
            {
                // Component is I, N, T or J.
                $component = $key[$slen];
                if($val < 0.5)
                {
                    // Component is not dominant, give opposite one instead.
                    $component = $opposites[$component];
                    
                    // Invert value.
                    $val = 1 - $val;
                }
                
                // Add to result.
                $result[$component] = $val;
            }
        }
        
        return $result;
    }
}
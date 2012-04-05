<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Model
{
	
	public function search($input)
	{		
		// Build query.
		$query = $this->db->select('u.userId')->from('Users u');

		if(isset($input['gender']))
		{
			$query->where('gender', $input['gender']);
		}
		
		$gpref_bool = $input['genderPref'] ? '1' : '0';
		
		$query->where("(genderPref IS NULL OR genderPref = $gpref_bool)")
			  ->join('UserBrands b', 'b.userId = u.userId', 'left')
			  ->where_in('brandName', $input['brands']);
			  
		// Personality preference is string like 'ENTP'.
	    $personPref = $input['personalityPreference'];
		
	    $dicts1 = 'INTJ';
	    $dicts2 = 'ESFP';
	    for($i = 0; $i < 4; ++$i)
	    {
	    	$col = 'preference' . $dicts1[$i];
	    	if($personPref[$i] == $dicts1[$i])
	    	{
	    		$query->where("$col > 0.5");
	    	}
	    	else
	    	{
	    		$query->where("$col < 0.5");
	    	}
	    }
	    
	    // Execute query.
	    $result = $query->get()->result_array();
	    
	    // Return userId's.
	    return array_merge($result);
	}
}
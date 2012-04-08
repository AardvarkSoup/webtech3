<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchModel extends CI_Model
{
    
    public function search($input)
    {        
        // Query expression to determine the age of a user.
        $age = "(strftime('%Y', 'now') - strftime('%Y', birthdate)
              - (strftime('%j', 'now') < strftime('%j', birthdate)))";
        
        // Build query.
        $query = $this->db->select('u.userId')->from('Users u');

        // Restrict users in result by wanted gender.
        if($input['genderPref'] != 'either')
        {
            $query->where('gender', $input['genderPref'] == 'female');
        }
        
        // Restrict on age.
        $query->where('minAgePref <=', (int) $input['ownAge']);
        $query->where('maxAgePref >=', (int) $input['ownAge']);
        $query->where("$age <=", (int) $input['maxAge']);
        $query->where("$age >=", (int) $input['minAge']);
        
        // 0: male, 1: female.
        $gpref_bool = $input['ownGender'] == 'female' ? '1' : '0';
        
        // Add gender preference to query. 
        $query->where("(genderPref IS NULL OR genderPref = $gpref_bool)");
        
        // Parse brand preference list.
        $brands = array_filter(array_map('trim', explode(',', $input['brands'])));
        
        // If at least one brand is specified, add them to the query.
        if(count($brands) > 0)
        {
            $query->join('UserBrands b', 'b.userId = u.userId', 'left')
                  ->where_in('brandName', $brands);
        }
        
              
        // Retrieve personality preference.
        $personPref = array($input['attitude'],
                            $input['perceiving'],
                            $input['judging'],
                            $input['lifestyle']
                       );
        
        $dicts1 = 'INTJ';
        $dicts2 = 'ESFP';
        for($i = 0; $i < 4; ++$i)
        {
            $col = 'preference' . $dicts1[$i];
            if($personPref[$i] == $dicts1[$i])
            {
                $query->where("$col >= 0.5");
            }
            else
            {
                $query->where("$col < 0.5");
            }
        }
        
        // Execute query.
        $result = $query->get()->result_array();
        
        // Return userId's.
        foreach($result as &$row)
        {
            $row = $row['userId'];
        }
        return array_merge($result);
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends CI_Model
{
    /**
     * Constants representing similarity measure coefficients.
     */
    const SMC_DICE    = 0,
          SMC_JACCARD = 1,
          SMC_COSINE  = 2,
          SMC_OVERLAP = 3;
    
    /**
     * Load the current settings.
     * 
     * @return array(string => int/float) The settings and their current values.
     */
    public function load()
    {
        $settings = array(
                      'similarityMeasure',
                      'xFactor',
                      'alpha'
                    );
        
        $result = $this->db->select($settings)
                       ->from('Configuration')
                       ->get()->row_array();
        
        return $result;
    }
    
    /**
     * Save changed settings.
     *
     * @param array(string => int/float) $data The changed settings. Should already be validated!
     */
    public function save($data)
    {       
        // Make sure an admin is doing this.
        $this->authentication->assertAdministrator();
        
        $this->db->update('Configuration', $data);
    }
}
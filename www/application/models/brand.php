<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model for queries on brands.
 */
class Brand extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Returns the names of all available brands.
     * 
     * @return array(string)
     */
    public function getBrands()
    {
        $result = $this->db->select('brandName')->from('Brands')->get()->result();
        
        // Only return brand names.
        foreach($result as &$brand)
        {
            $brand = $brand->brandName;
        }
        
        return $result;
    }
}
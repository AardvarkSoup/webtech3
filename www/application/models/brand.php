<?php

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
        $query = $this->db->select('brandName')->from('Brands')->get();
        return $query->result_array();
    }
}
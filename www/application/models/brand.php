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
        $query = $this->db->query('select brandname from brands;');
        $names = array();
        
        foreach($query->result() as $row)
        {
            $names[] = $row->brandName;
        }
        
        return $names;
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{

	public function index()
	{
	    $this->load->library('parser');
	    $this->load->model('Brand', 'brand');
	    
	    $data = array('brands' => $this->brand->getBrands());
	    
	    $this->load->view('test', $data);
	}
}


<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{

	public function index()
	{
	    $this->load->helper('url');
	    /*$this->load->model('Brand', 'brand');
	    
	    $data = array('brands' => $this->brand->getBrands());
	    $this->parser->parse('test', $data);*/
		
		$this->load->library('parser');
		$this->load->view('header');
		$this->load->view('nav');
		$this->load->view('content/showcase');
		$this->load->view('footer');
	}
}


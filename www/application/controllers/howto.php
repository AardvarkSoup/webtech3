<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HowTo extends CI_Controller 
{

    public function index($msg = null)
	{
		$this->load->view('header',array("pagename" => "How To"));
        $this->load->view('loginbox');
		$this->load->view('nav');
        $this->load->view('footer');
	}
	
}
?>
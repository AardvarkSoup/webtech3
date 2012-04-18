<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('loginsystem.php');
class About extends LoginSystem 
{

    public function index($msg = null)
	{
		$this->load->view('header',array("pagename" => "About"));
        $this->load->view('loginbox');
		$this->load->view('nav');
        $this->load->view('footer');
	}
	
}
?>
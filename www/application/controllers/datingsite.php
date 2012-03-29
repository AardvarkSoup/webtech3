<?php
class Datingsite extends CI_Controller {

    public function index()
	{
        $this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
        $this->load->view('content/showcase');
        $this->load->view('footer');
	}
}    
?>
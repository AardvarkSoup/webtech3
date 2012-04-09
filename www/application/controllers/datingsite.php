<?php
class Datingsite extends CI_Controller {

    public function index()
	{
        $this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
        $this->load->view('content/showcase');
        $this->makeProfiles();
        $this->load->view('footer');
	}
    
    /*  Makes the small profiles for the homepage
    */
    private function makeProfiles()
    {
        $this->load->library('parser');
        $data = array('profiles' => array(  array('number' => '1'),
                                            array('number' => '2'), 
                                            array('number' => '3'), 
                                            array('number' => '4'), 
                                            array('number' => '5'),
                                            array('number' => '6')));
        $this->parser->parse('content/profiles-small', $data);
    }
}    

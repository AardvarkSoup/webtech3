<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{

	public function index()
	{
	    $this->load->helper('url');
	    $this->load->helper('security');
	    $this->load->model('User', 'user');
	    $this->load->library('Personality', 'personality');
	    $this->load->model('Matching', 'matching');
	    
	    $brands = array('brandA', 'brandC');
	    $data = array(
	    		'username' => 'test2',
	    		'email' => 'a@d.c',
	    		'firstName' => 'Test',
	    		'lastName' => 'Test',
	    		'gender' => false,
	    		'birthdate' => '1988-01-02',
	    		'description' => 'blah',
	    		'minAgePref' => 20,
	    		'maxAgePref' => 30,
	    		'genderPref' => null,
	    		'personalityI' => 0.30,
	    		'personalityN' => 0.31,
	    		'personalityT' => 0.50,
	    		'personalityJ' => 0.65,
	    		'preferenceI' => 0.93,
	    		'preferenceN' => 0.32,
	    		'preferenceT' => 0.87,
	    		'preferenceJ' => 0.85	
	    );
	    
	    /*$this->user->deleteSelf();
	    $id = $this->user->createUser($data, 'hoi', $brands);
	    $user = $this->user->load(1);
	    print_r($user['genderPref'] === '0');*/
	    //print_r($this->matching->matchingList(1));
	    
	    //$this->user->like(2);
	    print_r($this->user->getLikeStatus(2));
	}
}


<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{

	public function index()
	{
	    $this->load->helper('url');
	    $this->load->helper('security');
	    $this->load->model('User', 'user');
	    
	    $brands = array('brandA');
	    $data = array(
	    		'username' => 'test',
	    		'email' => 'a@b.c',
	    		'firstName' => 'Test',
	    		'lastName' => 'Test',
	    		'gender' => true,
	    		'birthdate' => '01-01-88',
	    		'description' => 'bla',
	    		'minAgePref' => 20,
	    		'maxAgePref' => 30,
	    		'genderPref' => false,
	    		'personalityI' => 20,
	    		'personalityN' => 20,
	    		'personalityT' => 20,
	    		'personalityJ' => 20,
	    		'preferenceI' => 20,
	    		'preferenceN' => 20,
	    		'preferenceT' => 20,
	    		'preferenceJ' => 20
	    );
	    
	    $id = $this->user->createUser($data, 'hoi', $brands);
	    
	    print_r($this->user->load($id));
	}
}


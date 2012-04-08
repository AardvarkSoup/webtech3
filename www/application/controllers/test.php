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
        
        if(false)
        for($i = 101; $i < 200; ++$i)
        {
            $brands = array('brandA', 'brandC');
            $data = array(
                    'username' => "Testuser$i",
                    'email' => "user$i@users.com",
                    'firstName' => 'Test User',
                    'lastName' => 'The ' . $i . ' th',
                    'gender' => $i % 2 == 0,
                    'birthdate' => '198' . $i % 10 . '-01-01',
                    'description' => 'blahblablaat',
                    'minAgePref' => 20,
                    'maxAgePref' => 30,
                    'genderPref' => $i % 3 == 0 ? null : $i % 3 == 1,
                    'personalityI' => 0.35,
                    'personalityN' => 0.31,
                    'personalityT' => 0.50,
                    'personalityJ' => 0.95,
                    'preferenceI' => 0.93,
                    'preferenceN' => 0.32,
                    'preferenceT' => 0.61,
                    'preferenceJ' => 0.85    
            );
            $this->user->createUser($data, 'hoi', $brands);
        }
                
        //$id = $this->user->createUser($data, 'hoi', $brands);
        /*$this->user->deleteSelf();
        $id = $this->user->createUser($data, 'hoi', $brands);
        $user = $this->user->load(1);
        print_r($user['genderPref'] === '0');*/
        
        //print_r($this->user->load(1));
        //print_r($this->matching->matchingList(1));
        
        //$this->user->like(2);
        //print_r($this->user->getLikeStatus(2));
        
        //$this->parser->parse('searchbrowser', array('bla' => array('Blah!', 'Blablah!')));
    }
}


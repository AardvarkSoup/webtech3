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
        $this->load->model('Brand', 'brand');
        
        // User generator.
        $allBrands = $this->brand->getBrands();
        for($i = 1; $i < 50; ++$i)
        {
            $brands = array_rand(array_flip($allBrands), rand(2, 50));
            $data = array(
                    'username' => "ExtraTestuser$i",
                    'email' => "euser$i@users.com",
                    'firstName' => 'Test User',
                    'lastName' => 'The ' . $i . ' th',
                    'gender' => $i % 2 == 0,
                    'birthdate' => '198' . $i % 10 . '-01-01',
                    'description' => 'Ik ben een testgebruiker om dit geheel een beetje op te vullen. Alleen dan wat beter gebalanceerd.',
                    'minAgePref' => 18,
                    'maxAgePref' => 100,
                    'genderPref' => $i % 3 == 0 ? null : $i % 3 == 1,
                    'personalityI' => rand(0, 100) / 100,
                    'personalityN' => rand(0, 100) / 100,
                    'personalityT' => rand(0, 100) / 100,
                    'personalityJ' => rand(0, 100) / 100,
                    'preferenceI' => rand(0, 100) / 100,
                    'preferenceN' => rand(0, 100) / 100,
                    'preferenceT' => rand(0, 100) / 100,
                    'preferenceJ' => rand(0, 100) / 100    
            );
            $this->user->createUser($data, 'test12345', $brands);
            /*print_r($brands);
            echo "<br/>";
            echo "<br/>";
            echo "<br/>";*/
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
        
        /*if(count($_FILES) > 0)
        {
            $this->load->library('picture');
            print_r($this->picture->uploadAndProcess());
        }
        else
        {
            echo form_open_multipart() . form_upload('picture') . form_submit('submit', 'go') . form_close();
        }*/
    }
}


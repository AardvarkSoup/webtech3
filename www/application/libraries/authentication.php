<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

//TODO
class Authentication
{
    public function userLoggedIn()
    {
        //TODO
        return $this->currentUserId() !== null;
    }
    
    public function currentUserId()
    {
        //TODO
        return 1;
    }
    
    public function assertAdminstrator()
    {
        //TODO
    }
}
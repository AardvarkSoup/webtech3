<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

//TODO
class Authentication
{
    public function userLoggedIn()
    {
        //TODO
        return currentUserId() !== null;
    }
    
    public function currentUserId()
    {
        //TODO
        return null;
    }
    
    public function assertAdminstrator()
    {
        //TODO
    }
}
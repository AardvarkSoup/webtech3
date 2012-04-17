<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminPanel extends CI_Controller
{
    // A simple validator that checks whether the input has the right format.
    // Returns null if it validates, or a string containing an error if it doesn't.
    // This function also removes excess keys from the input array.
    private function _validateInput(&$input)
    {
        $s = $input['similarityMeasure'];
        $x = $input['xFactor'];
        $a = $input['alpha'];
        
        if($s === null || $s < 0 || $s > 3)
        {
            return 'Invalid similarity measure.';
        }
        if($x === null || $x < 0 || $x > 1)
        {
            return 'Invalid X-factor.';
        }
        if($a === null || $a < 0 || $a > 1)
        {
            return 'Invalid alpha factor.';
        }
        
        // Rebuild array to remove unnessecary input.
        $input = array('similarityMeasure' => $s,
                       'xFactor'           => $x,
                       'alpha'             => $a);
        
        // Validation passed.
        return null;
    }
    
    public function index()
    {
        // Only admins should have access to this page.
        $this->authentication->assertAdministrator();
        
        // Load configuration model.
        $this->load->model('Configuration', 'conf');
        
        // Load form helper.
        $this->load->helper('form');
        
        $error = null;
        
        // Check whether configurations have been changed.
        $input = $this->input->post();
        if($input !== false)
        {
            // Validate input.
            $error = $this->_validateInput($input);

            if($error === null)
            {
                // Update configuration.
                $this->conf->save($input);
            }
        }
        
        // Load current configuration.
        $data = $this->conf->load();
        
        // Add error.
        $data['error'] = $error;
        
        // Header.
        $this->load->view('header', array('pagename' => 'Configuration'));
        $this->load->view('nav');
        $this->load->view('loginbox');
        
        // Show admin panel view with data.
        $this->load->view('adminpanel', $data);
        
        // Footer.
        $this->load->view('footer');
    }
}

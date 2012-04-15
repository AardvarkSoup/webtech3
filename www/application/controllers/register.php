<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Register extends CI_Controller 
{
    const THUMBNAIL_WIDTH =  250,
          THUMBNAIL_HEIGHT = 300;
    

	// After succesfully validating a filled in form, use the POST data to add a new user to the
	// database.
    private function _registerUser()
	{
	    // Fetch input.
	    $input = $this->input->post();
	    
	    // This will be contain the parsed data as added to the user model.
	    $data = array();
	    
	    // Values that can be directly put to the data array (after a rename of their keys).
	    $rename = array(
	                'username' => 'username',
	                'firstname' => 'firstName',
	                'lastname' => 'lastName',
	                'email' => 'email',
	                'gender' => 'gender',
	                'description' => 'description',
	                'ageprefmin' => 'minAgePref',
	                'ageprefmax' => 'maxAgePref',
	                'birthdate' => 'birthdate'
	              );
	    foreach($rename as $key => $newkey)
	    {
	        $data[$newKey] = $input[$key];
	    }
	    
	    // Get password (will be hashed by User::createUser).
	    $password = $input['password'];
	    
	    // Parse gender preference.
	    $data['genderPref'] = $input['genderpref'] == 2 ? null : (int) $input['genderpref'];
	    
	    // Parse brand preferences.
	    $brands = array_keys(array_filter($input['brandpref']));
	    
	    // Process uploaded image.
	    $picFile;
	    if(isset($input['picture']))
	    {
    	    $picFile = $input['picture'];
    	    
    	    // Copy 250x300 version of uploaded picture to img-folder, using GD.
    	    $source = imagecreatefromjpeg($picFile);
    	    list($sourceWidth, $sourceHeight) = getimagesize($picFile);
    	    $dest = imagecreatetruecolor(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
    	    
    	    $success = imagecopyresampled($dest, $source, 0, 0, 0, 0, 
    	                  self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT, $sourceWidth, $sourceHeight);
    	    
    	    if($success)
    	    {
    	        // Generate picture filename (16 random lowercase characters + .jpg).
    	        $picture = '';
    	        for($i = 0; $i < 16; ++$i)
    	        {
    	            $picture .= chr(rand(ord('a'), ord('z')));
    	        }
    	        $picture .= '.jpg';
    	        
    	        // Save image.
    	        $success = imagejpeg($dest, $picture);
    	    }
    	    
    	    if($success)
    	    {
    	        // Add Image filename to data.
    	        $data['picture'] = $picture;
    	    }
    	    else
    	    {
    	        // TODO: Give error message.
    	    }
	    }
	    
	    
	    // TODO: personality (preference)
	    
	    if(isset($data['picture']))
	    {
	        // Delete image upload.
	        unlink($picFile);
	    }
	}
    
    public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('personality', 'form_validation'));
        $this->load->helper('html');
		$this->load->helper('form');
		
		// TODO: upload configuration.
		$this->load->library('upload');
		
		$this->load->view('header');
        $this->load->view('nav');
        $this->load->view('loginbox');
		
        $config = array(
        		array(
        			'field' => 'username',
        			'label' => 'Gebruikersnaam',
        			'rules' => 'required|min_length[4]|max_length[20]' 
        			//TODO controleer dat de naam uniek is. Zie is_unique[table.field]bij form_validation
				),
				array(
        			'field' => 'firstname',
        			'label' => 'Voornaam',
        			'rules' => 'required'
				),
				array(
        			'field' => 'lastname',
        			'label' => 'Achternaam',
        			'rules' => 'required'
				),
				array(
        			'field' => 'password',
        			'label' => 'Wachtwoord',
        			'rules' => 'required|min_length[8]|max_length[20]'
				),
				array(
        			'field' => 'passconf',
        			'label' => 'Herhaal wachtwoord',
        			'rules' => 'required|matches[password]|min_length[8]|max_length[20]'
				),
				array(
        			'field' => 'email',
        			'label' => 'Email',
        			'rules' => 'required|valid_email' //TODO email uniek checken
				),
				array(
        			'field' => 'gender',
        			'label' => 'Geslacht',
        			'rules' => 'required'
				),
				array(
        			// TODO: validate and indicate or enforce date formatting
        			'field' => 'birthdate',
        			'label' => 'Geboortedatum',
        			'rules' => 'required'
				),
				array(
        			'field' => 'description',
        			'label' => 'Beschrijving',
        			'rules' => ''
				),
				array(
        			'field' => 'genderpref',
        			'label' => 'Geslachtsvoorkeur',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmin',
        			'label' => 'Minimumleeftijd',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmax',
        			'label' => 'Maximumleeftijd',
        			'rules' => 'required'
				),
				array(
        			'field' => 'brandpref',
        			'label' => 'Merkvoorkeuren',
        			'rules' => ''
				)
        );
        
        $this->form_validation->set_rules($config);
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_message('required', 'Dit is een verplicht veld');
        
        // Hier is een testlijst van brands.
        $this->load->model('brand');
        $brands = $this->brand->getBrands();
        
        
        $data = array('brandPreferences' => $this->brandCheckBoxes($brands));
        
        if ($this->form_validation->run() == false) {
			$this->load->view('content/registerView', $data);
		}
		else 
		{
		    $this->_registerUser();
			$this->load->view('content/register_succes', $_POST);
		}
        
        $this->load->view('footer');
	}
	
	/* 
	 * brandCheckBoxes neemt een lijst van brands en maakt voor elk een checkbox in html aan
	 */
	private function brandCheckBoxes($brands)
	{
		$html = heading("Merkvoorkeuren", 4). form_error('brandpref');
		foreach($brands as $brand) { 
			$html .= "<input type=\"checkbox\" name=\"brandpref[]\" value=\"$brand\" ".
						set_checkbox('brandpref[]', $brand). " /> ". $brand. " <br />";
		}
		return $html;
	}
	
}	

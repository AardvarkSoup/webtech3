<?php
class Register extends CI_Controller 
{

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
	    
	    // TODO: personality (preference), picture 
	}
    
    public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('personality', 'form_validation'));
        $this->load->helper('html');
		$this->load->helper('form');
		$this->load->
		
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
        $brands = array('My Little Pony', 
        				'Transformers', 
        				'X-Men', 
        				'Pokémon', 
        				'Digimon', 
        				'Vicky de Viking',
        				'Smurfen',
        				'Dexter\'s Laboratory');
        
        
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

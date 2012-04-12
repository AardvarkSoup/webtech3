<?php
class Register extends CI_Controller {

	public function index()
	{
        $this->load->model('user','',true);
        $this->load->library(array('personality', 'form_validation'));
        $this->load->helper('html');
		$this->load->helper('form');
		
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
        			'label' => 'Wachtwoord confirmatie',
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
        			'label' => 'Geslachts voorkeur',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmin',
        			'label' => 'Minimum leeftijd',
        			'rules' => 'required'
				),
				array(
        			'field' => 'ageprefmax',
        			'label' => 'Maximum leeftijd',
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
			$this->load->view('content/register', $data);
		}
		else {
			$this->load->view('content/register_succes', $_POST);
		}
        
        $this->load->view('footer');
	}
	
	/* 
	 * brandCheckBoxes neemt een lijst van brands en maakt voor elk een checkbox in html aan
	 */
	private function brandCheckBoxes($brands)
	{
		$html = heading("Merk voorkeuren", 4). form_error('brandpref');
		foreach($brands as $brand) { 
			$html .= "<input type=\"checkbox\" name=\"brandpref[]\" value=\"$brand\" ".
						set_checkbox('brandpref[]', $brand). " /> ". $brand. " <br />";
		}
		return $html;
	}
	
}	
?>
<section id='registerblock'>
<?php
	
	/*
	 * First some variables needed for creating and populating the fields are defined
	 */
	
	// If no profile is submitted, profileEdit is set to false and the profile to null.
	$profileEdit = isset($profile);
	if(!$profileEdit) $profile = null;
	
    // Some inputs are selects from a list of possibilities.
    // The list of gender options is prepared
    $genders = array( '0' => 'Male',
        			  '1' => 'Female');
    // The list of possible gender preferences
    $genderprefs = array( '0' => 'Male',
        			  	  '1' => 'Female',
        				  '2' => 'Either');
	
    /*
     * Then some functions for building the html for the form fields
     */
    
	/**
	 * The brandprefs are returned in an array. CodeIgniter can not handle an array in its value
	 * set functions, so we made our own. :-)
	 * 
	 * brandCheckBoxes turns an array of brands into html which displays a list of checkboxes.
	 * If the form was posted, the checkboxes are re-populated.
	 * @param array $brands			List of brands
	 * @param array $brandprefs		List of checked brands
	 * @return string				html for a list of checkboxes
	 */
	function brandCheckBoxes(array $brands, array $brandprefs = null)
	{
		$html = form_fieldset("Brand-preferences", array('class' => 'brands')). 
				form_error('brandpref[]') ."<ul>";
		foreach($brands as $brand) {
			$html .= "<li>". form_checkbox('brandpref[]',$brand, 
						$brandprefs != null && in_array($brand,$brandprefs)). $brand. "</li>";
		}
		return $html. "</ul></fieldset>";
	}
	
	function buildField($heading, $name, $size = 50, $profileEdit = false, 
							array $profile = null, $password = false)
	{
		$output = heading($heading, 4). form_error($name);
		
		// If the field is a passwordfiel, it should not be re-populated
		if($password) $value = '';
		else if($profileEdit) $value = set_value($name, $profile[$name]);
		else $value = set_value($name);
		
		// The input parameters are set. 
		// If the field is a password, the type is set to password, else text.
		$data = array('name'  => $name,
					  'value' => $value,
					  'size'  => $size,
					  'type'  => $password ? 'password' : 'text'
		              );
		$output .= form_input($data). br(2);
		echo $output;
	}
	
	function buildDropdown($heading, $name, $filling, $profileEdit = false, array $profile = null)
	{
		$output = heading($heading, 4). form_error($name);
		$output .= form_dropdown($name, $filling, set_value($name)). br(2);
		echo $output;
	}
	
	
	/*
	 * Start of the form
	 */
	
	echo form_open_multipart('register');
	
	// If the profile is being edited, display a welcome message with the username
	if($profileEdit) echo heading('Hi '. $profile['username']. '!', 2);
	// Else, offer a field to register a username
	else buildField('Username', 'username');
	
	buildField('First name', 'firstName', 50, $profileEdit, $profile);
	buildField('Last name', 'lastName', 50, $profileEdit, $profile);
	
	// If the profile is being edited, one field for the old password and two for the new one are displayed 
	if($profileEdit) {
		buildField('Old password', 'oldpassword', 50, false, null, true);
		buildField('New password', 'password', 50, false, null, true);
		buildField('Repeat new password', 'passconf', 50, false, null, true);
	}
	else {  // Else, only two password fields are shown to register a password
		buildField('Password', 'password', 50, false, null, 'password');
		buildField('Repeat password', 'passconf', 50, false, null, 'password');
	}
	buildField('Email', 'email', 50, $profileEdit, $profile);
	buildDropdown('Gender', 'gender', $genders);
	buildField('Email', 'email', 50, $profileEdit, $profile);
	buildField('Birthdate (yyyy-mm-dd)', 'birthdate', 50, $profileEdit, $profile);  
?>

	<h4>Description</h4>
	<?php echo form_error('description'); ?>
	<textarea name="description" rows="5" cols="37"><?php echo set_value('description'); ?></textarea>
	<br /><br />
	
	<?php buildDropdown('Gender preference', 'genderpref', $genderprefs); ?>
	
	<h4>Age preference</h4>
	<?php echo form_error('ageprefmax'); ?>
		Minimum: 
		<input type="text" name="ageprefmin" value="<?php echo set_value('ageprefmin', 18); ?>"/>
		Maximum: 
		<input type="text" name="ageprefmax" value="<?php echo set_value('ageprefmax', 122); ?>"/>
		<br /><br />
		
	<h4>Upload picture (JPEG format required, should be smaller than 1MB) </h4>
	<?php echo form_error('picture'); 
	      echo form_upload('picture'); ?>
          <br /><br />
	
	<?php echo brandCheckBoxes($brands, $brandPreferences). br(); ?>
	
	<?php
	
	if(!$profileEdit) {
	
	$questions = array(1  => array('A' => "Ik geef de voorkeur aan grote groepen mensen, met een grote diversiteit.",
								   'B' => "Ik geef de voorkeur aan intieme bijeenkomsten met uitsluitend goede vrienden."),
					   2  => array('A' => "Ik doe eerst, en dan denk ik.",
								   'B' => "Ik denk eerst, en dan doe ik."),
					   3  => array('A' => "Ik ben makkelijk afgeleid, met minder aandacht voor een enkele specifieke taak.",
								   'B' => "Ik kan me goed focussen, met minder aandacht voor het grote geheel."),
					   4  => array('A' => "Ik ben een makkelijke prater en ga graag uit.",
								   'B' => "Ik ben een goede luisteraar en meer een privé-persoon."),
					   5  => array('A' => "Als gastheer/-vrouw ben ik altijd het centrum van de belangstelling.",
								   'B' => "Als gastheer/-vrouw ben altijd achter de schermen bezig om te zorgen dat alles soepeltjes verloopt."),
					   6  => array('A' => "Ik geef de voorkeur aan concepten en het leren op basis van associaties.",
								   'B' => "Ik geef de voorkeur aan observaties en het leren op basis van feiten."),
					   7  => array('A' => "Ik heb een groot inbeeldingsvermogen en heb een globaal overzicht van een project.",
								   'B' => "Ik ben pragmatisch ingesteld en heb een gedetailleerd beeld van elke stap."),
					   8  => array('A' => "Ik kijk naar de toekomst.",
								   'B' => "Ik houd mijn blik op het heden gericht."),
					   9  => array('A' => "Ik houd van de veranderlijkheid in relaties en taken.",
								   'B' => "Ik houd van de voorspelbaarheid in relaties en taken."),
					   10 => array('A' => "Ik overdenk een beslissing helemaal.",
								   'B' => "Ik beslis met mijn gevoel."),
					   11 => array('A' => "Ik werk het beste met een lijst plussen en minnen.",
								   'B' => "Ik beslis op basis van de gevolgen voor mensen."),
					   12 => array('A' => "Ik ben van nature kritisch.",
								   'B' => "Ik maak het mensen graag naar de zin."),
					   13 => array('A' => "Ik ben eerder eerlijk dan tactisch.",
								   'B' => "Ik ben eerder tactisch dan eerlijk."),
					   14 => array('A' => "Ik houd van vertrouwde situaties.",
								   'B' => "Ik houd van flexibele situaties."),
					   15 => array('A' => "Ik plan alles, met een to-do lijstje in mijn hand.",
								   'B' => "Ik wacht tot er meerdere ideeën opborrelen en kies dan on-the-fly wat te doen.",),
					   16 => array('A' => "Ik houd van het afronden van projecten.",
								   'B' => "Ik houd van het opstarten van projecten.",),
					   17 => array('A' => "Ik ervaar stress door een gebrek aan planning en abrupte wijzigingen.",
								   'B' => "Ik ervaar gedetailleerde plannen als benauwend en kijk uit naar veranderingen."),
					   18 => array('A' => "Het is waarschijnlijker dat ik een doel bereik.",
								   'B' => "Het is waarschijnlijker dat ik een kans zie."),
					   19 => array('A' => "\"All play and no work maakt dat het project niet afkomt.\"",
								   'B' => "\"All work and no play maakt je maar een saaie pief.\""),
				 );
	
	// The questions order is randomized
	shuffle($questions);
	
	// The C-answere, which is always the same, is added to the questions. Of course, after the shuffle.
	$questions['C'] = "Ik zit er eigenlijk tussenin.";
				 
	// The questions array is turned into valid html with re-population
	$questionsHtml = array();
	for($q = 1; $q < count($questions); $q++) {
		echo heading("Question $q:",4). form_error("question$q"). br().
				form_radio("question$q",'A',set_value("question$q") == 'A'). utf8_encode($questions[$q-1]['A']). br().
				form_radio("question$q",'B',set_value("question$q") == 'B'). utf8_encode($questions[$q-1]['B']). br().
				form_radio("question$q",'C',set_value("question$q") == 'C'). utf8_encode($questions['C']). br(2);
	
	}
	}
	?>
	
	<br />
	<div><input type="submit" value="Submit" /></div>

	</form>
</section>
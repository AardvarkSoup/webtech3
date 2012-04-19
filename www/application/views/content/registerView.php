<section id='registerblock'>
<?php
	echo form_open_multipart('register');
?>

	<h4>Username</h4>
	<?php echo form_error('username'); ?>
	<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" />
	<br /><br />
	
	<h4>First name</h4>
	<?php echo form_error('firstname'); ?>
	<input type="text" name="firstname" value="<?php echo set_value('firstname'); ?>" size="50" />
	<br /><br />
	
	<h4>Last name</h4>
	<?php echo form_error('lastname'); ?>
	<input type="text" name="lastname" value="<?php echo set_value('lastname'); ?>" size="50" />
	<br /><br />
	
	<h4>Password</h4>
	<?php echo form_error('password'); ?>
	<input type="password" name="password" value="" size="50" />
	<br /><br />
	
	<h4>Repeat password</h4>
	<?php echo form_error('passconf'); ?>
	<input type="password" name="passconf" value="" size="50" />
	<br /><br />
	
	<h4>E-mail</h4>
	<?php echo form_error('email'); ?>
	<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50" />
	<br /><br />
	
	<h4>Gender</h4>
	<?php echo form_dropdown('gender',$genders,set_value('gender')); ?>
	<br /><br />
	
	<h4>Birthdate (yyyy-mm-dd)</h4>
	<?php echo form_error('birthdate');
	      if(isset($date_error)) echo "<div class='error'>$date_error</div>"; ?>
	<input type="text" name="birthdate" value="<?php echo set_value('birthdate'); ?>" size="50" />
	<br /><br />
	
	<h4>Description</h4>
	<?php echo form_error('description'); ?>
	<textarea name="description" rows="5" cols="37"><?php echo set_value('description'); ?></textarea>
	<br /><br />
	
	<h4>Gender preference</h4>
	<?php echo form_dropdown('genderpref',$genderprefs,set_value('genderpref'))?>
	<br /><br />
	
	<h4>Age preference</h4>
	<?php echo form_error('ageprefmin'); 
	      if(isset($age_error)) echo "<div class='error'>$age_error</div>"; ?>
	<?php echo form_error('ageprefmax'); ?>
		Minimum: 
		<input type="text" name="ageprefmin" value="<?php echo set_value('ageprefmin', 18); ?>"/>
		Maximum: 
		<input type="text" name="ageprefmax" value="<?php echo set_value('ageprefmax', 122); ?>"/>
		<br /><br />
		
	<h4>Upload picture (JPEG format required) </h4>
	<?php echo form_error('picture'); 
	      echo form_upload('picture'); ?>
          <br /><br />
	
	<?php echo $brandPreferences. br(); ?>
	
	<?php
	
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
	?>
	
	<br />
	<div><input type="submit" value="Submit" /></div>

	</form>
</section>
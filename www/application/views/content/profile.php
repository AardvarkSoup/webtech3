<div id='profileArea'>
<?php
	// boldShow($type, $content) returns escaped html with $type in bold followed by
	//							 $content in normal style.
	//							 If the profile is a form, boldShow will turn it into
	//							 an input field.
	function boldShow($type, $content, $profileType = "", $textblock = false)
	{	
		if($profileType == "form") {
			if($textblock) {
				$content = form_textarea($type, $content, "id=\"$type\" rows=\"6\" cols=\"80\""); 
			}
			else {
				$content = form_input($type, $content, "id=\"$type\"");
			}
		}
		else {
			$type = htmlentities($type, ENT_QUOTES, "UTF-8");
			$content = htmlentities($content, ENT_QUOTES, "UTF-8");
		}
		return "<p><b>$type:</b> $content</p>\n";
	}
	
	// Calculates the current age based on the birthdate
	function getAge($birthdate) {
		list($year, $month, $day) = explode("-", $birthdate);
		$year_diff  = date("Y") - $year;
    	$month_diff = date("m") - $month;
    	$day_diff   = date("d") - $day;
    	
    	// If the month-difference is smaller then 0, or is 0 with a smaller day-difference,
    	// the user hasn't had his/her birthday this year yet.
    	if($month_diff < 0) {
    		$year_diff--;
    	}
    	else if($month_diff == 0 && $day_diff < 0) {
    		$year_diff--;
    	}
    	return $year_diff;
	}

	foreach($profiles as $profile) {
		if($profileType == "form") {
			echo validation_errors();
			echo heading(htmlentities($username, ENT_QUOTES, "UTF-8"), 3);
			echo form_open('profileUpdate');
			echo boldShow("Voornaam", $profile['firstName'], $profileType);
			echo boldShow("Achternaam", $profile['lastName'], $profileType);
			echo boldShow("Email-adres", $profile['email'], $profileType);
			echo boldShow("Geslacht", $profile['gender'], $profileType);
		}
		else {
			// A profilebox is opened and the username and gender for the public profile are echoed.
			echo "<div class='profilebox'>\n". heading(htmlentities($profile['username']. " (".
					 $profile['gender']. ")", ENT_QUOTES, "UTF-8"), 3);
		}
		
		if($profileType != "form") {
			// The age of the user is displayed
			echo boldShow("Leeftijd", getAge($profile['birthdate']), $profileType);
		}
		
		// If the profileType is small, the discription should show only the first line.
		if($profileType == "small") {
			$point = strpos($profile['description'], '.');		// Lines usually end with a point.
			
			// However, if the line is longer then 100 characters, it is cut off.
			if($point === false or $point > 100) {
				$disc = substr($profile['description'], 0, 100). "...";
			}
			else {
				$disc = substr($profile['description'], 0, $point+1);
			}
		}
		else {
			$disc = $profile['description'];
		}
		
		// And the discription, in correct length, is displayed.
		// The true turns it into an textarea if the profile is a form.
		echo boldShow("About me", $disc, $profileType, true);
		
		// The usertype and preference can not be edited in the profile editing form.
		if($profileType != "form") {
			echo boldShow("Personality", $profile['personality']);
			echo boldShow("Preference", $profile['preference']);
		}
		
		//  If the profile is a form, the submit button is placed and the form is closed
		if($profileType == "form") {
			echo form_submit('profileSubmit', 'Update profiel!');
			echo form_close();
		}
		else {	// else, the profilebox div is closed
			echo "</div>";
		}
	}
?>
</div>
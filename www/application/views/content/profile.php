<div class='profilebox'>
<?php
	if($profileType == "form") {
		// TODO formhelper and stuff
		echo form_open('email/send');
	}
	else {
		// The username and gender for the public profile are echoed.
		echo heading(htmlentities($username. " (". $gender. ")",ENT_QUOTES,"UTF-8"), 3);
	}
		
	// boldShow($type, $content) returns escaped html with $type in bold followed by
	//							 $content in normal style.
	//							 If the profile is a form, boldShow will turn it into
	//							 an input field.
	function boldShow($type, $content, $textblock = false)
	{
		if($profileType == "form") {
			if($textblock) {
				$content = "<textarea name = ". $type. "rows=\"6\" cols=\"80\
							value=". $content. " />"; 
			}
			else {
				$content = form_input($type, $content);
			}
		}
		return htmlentities("<p><b>". $type. ":</b> ". $content. "</p>",ENT_QUOTES,"UTF-8");
	}
	
	// The age of the user is displayed
	echo boldShow("Leeftijd", $age);
	// If the profileType is small, the discription should show only the first line.
	if($profileType == "small") {
		$point = strpos($discription, '.');		// Lines usually end with a point.
		
		// However, if the line is longer then 100 characters, it is cut off.
		if($point === false or $point > 100) {
			$disc = substr($discription, 0, 100). "...";
		}
		else {
			$disc = substr($discription, 0, $point+1);
		}
	}
	else {
		$disc = $discription;
	}
	
	// And the discription, in correct length, is displayed.
	// The true turns it into an textarea if the profile is a form.
	echo boldShow("Omschrijving", $disc, true);
	
	// The usertype and preference can not be edited in the profile editing form.
	if(!$profileType == "form") {
		// For each 
		foreach($personality as $key => $value) {
			$pers .= $key;
		}
		echo boldShow("Type", $pers);
		foreach($preference as $key => $value) {
			$pref .= $key;
		}
		echo boldShow("Preferentie", $pref);
	}
?>
</div>
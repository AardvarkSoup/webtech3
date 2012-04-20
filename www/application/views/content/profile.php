<div id='profileArea'>
<?php
	// boldShow($type, $content) returns escaped html with $type in bold followed by
	//							 $content in normal style.
	//							 If the profile is a form, boldShow will turn it into
	//							 an input field.
	function boldShow($type, $content, $profileType = "", $textblock = false)
	{	
		
		$type = htmlentities($type, ENT_QUOTES, "UTF-8");
		$content = htmlentities($content, ENT_QUOTES, "UTF-8");
		return "<p class='profiledata'><b>$type:</b> $content</p>\n";
	}
	
	// Calculates the current age based on the birthdate
	function getAge($birthdate)
	{
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
	
	function getLikePicture(array $likestatus)
	{
		$status = array();
		foreach($likestatus as $liked) {
			if($liked) {
				$status[] = "liked";
			}
			else {
				$status[] = "pending";
			}
		}
		return "$status[0]-$status[1].png";
	}

	foreach($profiles as $profile) {
		
		
		// A profilebox is opened and the username and gender for the public profile are echoed.
		// If the profile is big, the profilebox is a section.
		if($profileType == "big") {
			$boxType = "<div id='bigprofile' ";
		}
		// If the profile is small, it is a link
		else {
			$boxType = "<a href=\"". base_url(). 
				"/index.php/viewprofile/showprofile/". $profile['userId']. "\" ";
		}
		
		// The gender is converted to readable matter
		if($profile['gender'] == '0') {
			$gender = "M";
		}
		else {
			$gender = "F";
		}
		// The username and gender are displayed extra big
		echo $boxType. "class='profilebox'>\n". heading(htmlentities($profile['username']. " (".
				 $gender. ")", ENT_QUOTES, "UTF-8"), 3);
		
		// The thumbnail is shown. If the user is logged in, the real picture, else, a silhouette.
		if($this->authentication->userLoggedIn()) {
			echo "Show real picture";
			// If there is a mutual like, show the users name
			if(isset($profile['likestatus']) && $profile['likestatus'][0] && $profile['likestatus'][1]) {
				echo boldShow("Name", $profile['firstName']. " ". $profile['lastName']);
				echo boldShow("Email", $profile['email']);
			}
		}
		else {
			echo "Show silhouette";
		} 
		
		// The age is displayed
		echo boldShow("Age", getAge($profile['birthdate']), $profileType);
		
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
		// The true turns it into a textarea if the profile is a form.
		echo boldShow("About me", $disc, $profileType, true);
		
		// The usertype and preference are displayed
		echo boldShow("Personality", $profile['personality']);
		echo boldShow("Preference", $profile['preference']);
		
		// If the profile is small, only a maximum of 4 random brands are shown
		if($profileType == 'small') {
			$maxBrands = min(array(3, count($profile['brands']) - 1));
			shuffle($profile['brands']);
		}
		// else, if the profile is big, all brands are shown
		else $maxBrands = count($profile['brands']) - 1;
		
		$brands = "";
		for($b = 0; $b < $maxBrands; $b++) {
			$brands .= $profile['brands'][$b]. ", ";
		}
		// To avoid a comma at the end, the last brand is added after the loop.
		$brands .= $profile['brands'][$b];
		boldShow("Favorite brands", $brands);
		
		// The like-status should be displayed.
		if($this->authentication->userLoggedIn()) {
			if($profileType == 'big' && !$profile['likestatus'][0]) {
				
				echo "<div id='likebutton'>Click ". getLikePicture($profile['likestatus']). " to like user</div>";
			}
			else {
				echo getLikePicture($profile['likestatus']);
			}
		}
		
		// If the profilebox is big, the div is closed
		if($profileType == "big") {
			echo "</div>";
		}
		else {	// and if it is small, it's link is closed.
			echo "</a>";
		}
	}
?>
</div>

<script type="text/javascript">
// Add mouse listener to the likebutton. 

$('#likebutton').click(function()
{
	var ok = confirm("<?php echo 'Do you want to like '. $profile['username']. '?'; ?>");

	if(ok)
	{
		// Send like request. 
		$.post('<?php echo base_url(). '/index.php/viewprofile/like'; ?>',
			   {otherId: <?php echo $profile['userId'] ?>},
			   function()
			   {
					// Refresh page when done. 
					window.location.reload();
			   });
	}
});
</script>
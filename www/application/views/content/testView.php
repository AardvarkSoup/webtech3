<?php
	
	function boldShow($type, $content, $textblock = false)
	{
		global $profileType;
		
		if($profileType == "form") {
			if($textblock) {
				$content = form_textarea($type, $content, "rows=\"6\" cols=\"80\""); 
			}
			else {
				$content = form_input($type, $content);
			}
		}
		return "<p><b>". htmlentities($type, ENT_QUOTES, "UTF-8"). ":</b> ".
				 htmlentities($content, ENT_QUOTES, "UTF-8"). "</p>";
	}
	
	foreach($user as $key => $val) {
		echo boldShow($key, $val). br(1);
	}
	
?>
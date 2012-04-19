<h3>Your form was successfully submitted!</h3>

<p><?php echo anchor('registertest', 'Try it again!'); ?></p>

<p><?php
	echo $username. "<br />";
	echo $firstname. "<br />";
	echo $lastname. "<br />";
	if($gender == '0') {
		echo "You are a man<br />";
	}
	else {
		echo "You are a woman<br />";
	}
	echo "brands:". "<br />";
	foreach($brandpref as $brand) {
		echo $brand. "<br />";
	}
	
?></p>
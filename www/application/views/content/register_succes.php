<h3>Your form was successfully submitted!</h3>

<p><?php echo anchor('register', 'Try it again!'); ?></p>

<p><?php
	echo $username;
	echo $firstname;
	echo $lastname;
	if($gender) {
		echo "Yousa ish a man.";
	}
	else {
		echo "You awe a giwl :o";
	}
	echo $brandpref;
	foreach($brandpref as $brand) {
		echo $brand;
	}
	
?></p>
<?php 
$base = base_url() . 'index.php';
$ci =& get_instance();
$admin = $ci->authentication->userIsAdmin();
?>


	<nav>
    	<ul>
        	<li><a href="<?php echo "$base/home"?>">Home</a></li>
        	<li><a href="<?php echo "$base/search"?>">Search</a></li>
	        <?php 
	        if($this->authentication->userLoggedIn()) {
	        	echo "<li><a href=\"$base/search/matching\">Match!</a></li>";
	        	echo "<li><a href=\"$base/viewprofile\">Edit Profile</a></li>";
	        	echo "<li><a href=\"$base/search/likes\">Likes</a></li>";
	        }
	        if($admin) {
	        	echo "<li><a href=\"$base/adminpanel\">Configuration</a></li>";
	        }
	        ?>
    	</ul>
	</nav>
	<section id='main'>
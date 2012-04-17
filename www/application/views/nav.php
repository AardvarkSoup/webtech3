<?php 
$base = base_url() . 'index.php';
$ci =& get_instance();
$admin = $ci->authentication->userIsAdmin();
?>


	<nav>
    	<ul>
        	<li><a href="<?php echo "$base/home"?>">Home</a></li>
        	<li><a href="<?php echo "$base/search"?>">Search</a></li>
	        <li><a href="<?php echo "$base/search/matching"?>">Match!</a></li>
	        <?php 
	        if($admin)
	        {
	        	?>
	        	<li><a href="<?php echo "$base/adminpanel"?>">Configuration</a></li>
	        	<?php
	        }
	        ?>
    	</ul>
	</nav>
	<section id='main'>
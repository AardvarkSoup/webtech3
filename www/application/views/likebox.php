<?php 
$base = base_url() . 'index.php/search/likes';
?>

<div id="likebox">
<h1>What do you want to display?</h1>
	<a href="<?php echo "$base/mutual" ?>">People you like, that like you</a><br/>
	<a href="<?php echo "$base/liking" ?>">People liking you</a><br/>
	<a href="<?php echo "$base/liked" ?>">People you like</a><br/>
</div>
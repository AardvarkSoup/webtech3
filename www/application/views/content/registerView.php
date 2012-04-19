<section id='registerblock'>
<?php
	echo form_open('register');
?>

	<h4>Username</h4>
	<?php echo form_error('username'); ?>
	<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" />
	
	<h4>First name</h4>
	<?php echo form_error('firstname'); ?>
	<input type="text" name="firstname" value="<?php echo set_value('firstname'); ?>" size="50" />
	
	<h4>Last name</h4>
	<?php echo form_error('lastname'); ?>
	<input type="text" name="lastname" value="<?php echo set_value('lastname'); ?>" size="50" />
	
	<h4>Password</h4>
	<?php echo form_error('password'); ?>
	<input type="password" name="password" value="" size="50" />
	
	<h4>Repeat password</h4>
	<?php echo form_error('passconf'); ?>
	<input type="password" name="passconf" value="" size="50" />
	
	<h4>E-mail</h4>
	<?php echo form_error('email'); ?>
	<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50" />
	
	<h4>Gender</h4>
	<?php echo form_error('gender'); ?>
	<select name="gender">
		<option value='0' <?php echo set_select('gender', '0', set_value('gender')=='0'); ?> >M</option>
		<option value='1' <?php echo set_select('gender', '1', set_value('gender')=='1'); ?> >V</option>
	</select>
	
	<h4>Birthdate</h4>
	<?php echo form_error('birthdate'); ?>
	<input type="text" name="birthdate" value="<?php echo set_value('birthdate'); ?>" size="50" />
	
	<h4>Description</h4>
	<?php echo form_error('description'); ?>
	<textarea name="description" rows="5" cols="37"><?php echo set_value('description'); ?></textarea>
	
	<h4>Gender preference</h4>
	<?php echo form_error('genderpref'); ?>
	<select name="genderpref">
		<option value='0' <?php echo set_select('genderpref', '0', set_value('gender')=='0'); ?> >M</option>
		<option value='1' <?php echo set_select('genderpref', '1', set_value('gender')=='1'); ?> >V</option>
		<option value='2' <?php echo set_select('genderpref', '2', set_value('gender')=='2'); ?> >M / V</option>
	</select>
	
	<h4>Age preference</h4>
	<?php echo form_error('ageprefmin'); ?>
	<?php echo form_error('ageprefmax'); ?>
		Minimum: 
		<input type="text" name="ageprefmin" value="<?php echo set_value('ageprefmin', 18); ?>" size="2" />
		Maximum: 
		<input type="text" name="ageprefmax" value="<?php echo set_value('ageprefmax', 122); ?>" size="2" />
		
	<h4>Upload picture (JPEG format required) </h4>
	<?php echo form_error('picture'); 
	      echo form_upload('picture'); ?>
	
	
	<?php echo $brandPreferences; ?>
	
	<br />
	<div><input type="submit" value="Submit" /></div>

	</form>
</section>
<div id="loginbox">

<?php 
$home = base_url() . 'index.php';

// Get CodeIgniter object.
$ci =& get_instance();

// Check which user is logged in.
$uname = $ci->authentication->currentUserName();

if($uname !== null)
{
    // User is logged in, show a welcome message with logout option.
    echo '<p><strong>Welcome, ' . form_prep($uname) . '!</strong>'
      .  ' (<a href="' . "$home/logout" . '">logout</a>)';
    
    // Show option for deleting the account.
    echo '<p><strong id="deleter">Delete account</strong></p>';
}
else
{
	// If nobody is logged in, show login form.
    echo '<h3>Login or <a href="' . "$home/register" . '">Register</a> </h3>';
 
    echo form_open('home/login')
      .  form_label('E-mail: &nbsp&nbsp&nbsp&nbsp&nbsp', 'email')
      .  form_input('email')
      .  '<br/>'
      .  form_label('Password: ', 'password')
      .  form_password('password')
      .  form_submit('submit', 'Login')
      .  form_close();
}

?>    	
</div>

<script type="text/javascript">
// Add mouse listener to delete link. 

$('#deleter').click(function()
{
	var ok = confirm('You are about to delete your account. Are you absolutely sure?');

	if(ok)
	{
		// Send delete request. 
		$.post('<?php echo "$home/delete"; ?>',
			   {sure: 'yes'},
			   function()
			   {
					// Refresh page when done. 
					window.location.reload();
			   });
	}
});
</script>
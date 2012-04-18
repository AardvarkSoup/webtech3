<!DOCTYPE html>
<html>
<head>
    <title>Risa - <?php
                      if(isset($pagename))
                      { 
                    	echo $pagename;
                      }
                  ?>
	</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . '/css/style.css'; ?>" />
    <script type="text/javascript" src="<?php echo base_url() . '/js/jquery.js'; ?>"> </script>
</head>
<body>
	<div id='container'>
    <header>
        <h1>
            Risa, matchmaking since 2012
        </h1>
    </header>
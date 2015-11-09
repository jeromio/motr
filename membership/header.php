<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">

<head profile="http://gmpg.org/xfn/11">
	<title>Contacts v0.1</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" media="screen, projection" href="css/screen.css" />
	<script type="text/javascript" src="formFocus.js"></script>

</head>

<body onLoad="getFocus()">
	<div id="container">
		<div id="header">
			<h1 id="logo"><a href="index.php">Contacts</a></h1>
			<div id="search">
				<form action="index.php" method="get">
					<fieldset>
						<legend>Search</legend>
						<div class="input textfield"><label for="s">Search</label><input type="text" name="s" id="s" size="20" value="<?php if(isset($_GET['s'])) { echo $_GET['s']; } ?>" /></div>
						<div class="buttons">
							<button type="submit" id="submit">
								Submit
							</button>
							<a href="index.php" id="reset">Clear</a>
						</div> <!-- /.buttons -->
					</fieldset>
				</form>
			</div> <!-- /#search -->
		</div> <!-- /#header -->

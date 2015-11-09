<?php 
require "config.php";
require "functions.php";

	if (isset($_GET['uid'])) {
		$uid = $_GET['uid'];
		if (isset($_GET['confirm'])) {
			if($_GET['confirm']=="y") {
				deleteContact($uid);
				header( 'Location: index.php' );
			}
		}
		$name = getContactName($uid);
	} else {
		$uid = 0;
	}

include "header.php"; ?>
		<div class="content alt confirm" id="contact-del">
			<h2>Delete contact?</h2>
			<p>Are you sure you would like to delete contact information for: <strong><?php echo $name; ?></strong>?</p>
			<ul id="del-confirm">
				<li id="yes"><a href="del-contact.php?uid=<?php echo $uid; ?>&amp;confirm=y">Yes, delete</a></li>
				<li id="no"><a href="index.php">No</a></li>
			</ul>
		</div> <!-- /.content#contact-del -->
<?php include "footer.php" ?>

<?php 
require "config.php";
require "functions.php";
include "header.php";

if (isset($_GET['uid'])) {
	$uid = $_GET['uid'];
	$card = getCard($uid);
} else {
	$uid = 0;
} ?>
		<div class="content alt vcard" id="contact-view">
			<h2 id="name-title" class="fn"><?php echo $card['firstname'] . " " . $card['lastname']; ?></h2>
			<dl id="card">
				Member # <?php echo $card['memnum']; ?> <BR>
				DOB <?php echo $card['memdob']; ?> <BR>
				<?php if ($card['company']!="") { ?>
				<dt class="icon-company">Company</dt>
				<dd class="org"><?php echo $card['company']; ?></dd>
				<?php }
				if ($card['phone']!="") { ?>
				<dt class="icon-phone">Phone</dt>
				<dd class="tel"><span class="value"><?php echo formatPhone($card['phone']); ?></span> <?php if($card['extension']!="0") { echo "Ext " . $card['extension']; } ?></dd>
				<?php }
				if ($card['email']!="") { ?>
				<dt class="icon-email">Email</dt>
				<dd class="email"><?php echo $card['email']; ?></dd>
				<?php }
				if ($card['street']!="" || $card['city']!="" || $card['state']!="" || $card['zip']!="") { ?>
				<dt class="icon-address">Address</dt>
				<dd class="adr"><span class="street-address"><?php echo $card['street']; ?></span><br /><span class="locality"><?php echo $card['city']; ?></span>, <abbr class="region"><?php echo $card['state']; ?></abbr> <span class="postal-code"><?php echo $card['zip']; ?></span></dd>
				<?php } ?>
			</dl>
		</div> <!-- /.content#contact-view -->
		<div id="admin">
			<ul>
				<li id="del-contact"><a href="del-contact.php?uid=<?php echo $uid; ?>">Delete contact</a></li>
				<li id="edit-contact"><a href="add-edit-contact.php?uid=<?php echo $uid; ?>">Edit contact</a></li>
			</ul>
		</div> <!-- /#admin -->
<?php include "footer.php" ?>

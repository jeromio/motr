<?php 
require "config.php";
require "functions.php";
include("/home/hnmtf/public_html/motorcomusic.com/password_protect.php"); 
include "header.php"; 
?>
	<?php if(checkInstall()) { ?>
		<div class="content" id="listings">
			<div class="group-list" id="companies">
				<h2>Companies</h2>
				<ul>
					<?php
						if (isset($_GET['company'])) { 						// display single company
							$companyName = reverseLookup($_GET['company']);
							echo '<li>' . $companyName . '</li>';
						} elseif (isset($_GET['s'])) { 						// search companies
							$companies = searchCompanies($_GET['s']);
							if (count($companies)>0) {
								foreach($companies as $company) {
									echo '<li><a href="index.php?company=' . generateSlug($company) . '">' . $company . '</a></li>';
								}
							}
						} else { 											// display all companies
							$companies = getCompanies();
							if (count($companies)>0) {
								foreach($companies as $company) {
									if(trim($company)!="") {
										echo '<li><a href="index.php?company=' . generateSlug($company) . '">' . $company . '</a></li>';
									}
								}
							}
						}
					?>
				</ul>
			</div> <!-- /.group-list#companies -->
			<div class="group-list" id="people">
				<h2>People</h2>
				<ul>
					<?php
						if (isset($companyName)) {							// all contacts from single company
							$contacts = getCompanyNames($companyName);
							if (count($contacts)>0) {
								foreach($contacts as $contactid) {
									echo '<li><a href="view-contact.php?uid=' . $contactid . '">' . getContactName($contactid) . '</a></li>';
								}
							}
						} elseif (isset($_GET['s'])) {						// search contacts
							$contacts = searchContacts($_GET['s']);
							if (count($contacts)>0) {
								foreach($contacts as $contactid) {
									echo '<li><a href="view-contact.php?uid=' . $contactid . '">' . getContactName($contactid) . '</a></li>';
								}
							}
						} else {											// display all contacts
							$contacts = getAllNames();
							if (count($contacts)>0) {
								foreach($contacts as $contactid) {
									echo '<li><a href="view-contact.php?uid=' . $contactid . '">' . getContactName($contactid) . '</a></li>';
								}
							}
						}
					?>
				</ul>
			</div> <!-- /.group-list#people -->
		</div> <!-- /.content -->
		<div id="admin">
			<ul>
				<li id="add-contact"><a href="add-edit-contact.php">Add contact</a></li>
			</ul>
		</div> <!-- /#admin -->
	<?php } else { ?>
		<p id="install-notice">Please update <code>config.php</code> with the proper credentials and run <a href="install.php">install.php</a></p>
	<?php } ?>
<?php include "footer.php" ?>

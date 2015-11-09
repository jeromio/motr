<?php 
require "config.php";
require "functions.php";
include("/home/hnmtf/public_html/motorcomusic.com/password_protect.php"); 
if(isset($_POST['name'])) { 

// foreach($_POST as $key => $i){
//     print("KEY: ".$key."=".$j);
//     print($_POST[$key] . "<br>");
// }
	//check for barcode string
	if(isset($_POST['barcode'])) {	
		$barcode = $_POST['barcode'];
//print("> Yes barcode");
	}
	$barlen = strlen($barcode);
//print("> barlen: ".$barlen);
	if($barlen > 0) {
//print("got a barcode: [".$barcode."]");
		$tempArray = splitBarcode($barcode);
		//assign individual vars to array elements
		$memdob = $tempArray[DOB];
//print("\n>passed DOB was ".$tempArray[DOB]."\n");
		$firstName = $tempArray[FIRSTNAME];
		$lastName = $tempArray[LASTNAME];
		$address = $tempArray[ADDRESS];
//print("\n>passed address was ".$tempArray[ADDRESS]."\n");
		$city = $tempArray[CITY];
		$zip = $tempArray[ZIP];
		$dlnumber = $tempArray[DLNUMBER];
		//Check DB for existing membership!		
		if(isset($_POST['email'])) { $email = $_POST['email']; } else { $email = ""; }
		if (checkMemberExists($dlnumber)) {
			$memberExists = TRUE;
		} else {
			$memberExists = FALSE;	
		}	 
		//	
	} else {
//print("> memnum: ".$memnum);	
		if(isset($_POST['memnum'])) { 
			$memnum = $_POST['memnum'];
		 } else { 
			$memnum = ""; 
		}
		if(isset($_POST['memdob'])) { $memdob = $_POST['memdob']; } else { $memdob = ""; }
		if(isset($_POST['name'])) { $name = $_POST['name']; } else { $name = ""; }
		$name = explode(" ",$name);
		if(count($name)==2) { // first and last name
			$firstName = $name[0];
			$lastName = $name[1];
		} elseif(count($name)>2) { // more than first and last name; make sure last name is by itself
			$firstName = $name[0];
			for($i=1;$i<(count($name)-1);$i++){
				$firstName .= " " . $name[$i];
			}
			$i = count($name)-1;
			$lastName = $name[$i];
		} else { // only first name
			$firstName = $name[0];
			$lastName = "";
		}
		if(isset($_POST['email'])) { $email = $_POST['email']; } else { $email = ""; }
		if(isset($_POST['address'])) { $address = $_POST['address']; } else { $address = ""; }
		if(isset($_POST['city'])) { $city = $_POST['city']; } else { $city = ""; }
		if(isset($_POST['zip'])) { $zip = $_POST['zip']; } else { $zip = ""; }
		if(isset($_POST['dlnumber'])) { $dlnumber = $_POST['dlnumber']; } else { $dlnumber = ""; }	
		if(isset($_POST['company'])) { $company = $_POST['company']; } else { $company = ""; }
		if(isset($_POST['website'])) { $website = $_POST['website']; } else { $website = ""; }
		if(isset($_POST['phone'])) { $phone = ereg_replace('[^0-9]+','',$_POST['phone']); } else { $phone = ""; }
	}
	if(isset($_POST['uid'])) {
		editCard($_POST['uid'],$memnum,$memdob,$firstName,$lastName,$address,$email,$city,$zip,$company,$website,$phone, $dlnumber);
		header( 'Location: view-contact.php?uid=' . $_POST['uid'] );
	} else {
		$test = addCard($memnum,$memdob,$firstName,$lastName,$address,$city,$zip,$email,$company,$website,$phone, $dlnumber);
		header( 'Location: add-edit-contact.php' );
		//print("Card added</br>".$test);
	}

}

if(isset($_GET['uid'])) {

 foreach($_GET as $key => $i){
//     print("Correct data? " . $_GET[$key] . "<br>");
  //   print("$key=$j<br>");
 }

	if (is_numeric($_GET['uid']) && $_GET['uid']!="") {
		$card = getCard($_GET['uid']);
		$memdob = $card['memdob'];
		$memnum = $card['memnum'];
		$name = $card['firstname'] . " " . $card['lastname'];
		$email = $card['email'];
		$address = $card['street'];
		$city = $card['city'];
		$zip = $card['zip'];
		$dlnumber = $card['dlnumber'];
		$company = $card['company'];
		$phone = formatPhone($card['phone']);
		$website = $card['website'];

	}
} else {
	$memnum = "";
	$memdob = "";
	$name = "";
	$company = "";
	$phone = "";
	$email = "";
	$address = "";
	$city = "";
	$zip = "";
	$dlnumber = "";
	$website = "";
}

include "header.php"; 

 foreach($_GET as $key => $i){
     print("Correct data? " . $_GET[$key] . "<br>");
     print("$key=$j<br>");
 }
//print("member exists: ".$memberExists);
?>

		<div class="content alt" id="contact-add">
			<form action="add-edit-contact.php" method="post">
				<fieldset>
					<legend>Option 1: Scan Driver's License</legened>
					<div class="input textfield"><label for="barcode" class="icon-name">Barcode</label><input type="text" name="barcode" id="barcode" size="700" value="" /></div>			
					<div class="input textfield"><label for="email" class="icon-email">Email</label><input type="text" name="email" id="email" size="20" value="<?php echo $email; ?>" /></div>
					<legend>Option 2: Add manually</legend>
					<div class="input textfield"><label for="memnum" class="icon-name">Member Number</label><input type="text" name="memnum" id="memnum" size="5" value="<?php echo $memnum; ?>" /></div>
					<div class="input textfield"><label for="memdob" class="icon-name">Date of Birth</label><input type="text" name="memdob" id="memdob" size="20" value="<?php echo $memdob; ?>" /></div>
					<div class="input textfield"><label for="name" class="icon-name">Name</label><input type="text" name="name" id="name" size="20" value="<?php echo $name; ?>" /></div>
					<div class="input textfield" id="address-street"><label for="address" class="icon-address">Address</label><input type="text" name="address" id="address" size="20" value="<?php echo $address; ?>" /></div>
					<div id="data-address">
						<div class="input textfield" id="address-city"><label for="city" class="icon-city">City</label><input type="text" name="city" id="city" size="20" value="<?php echo $city; ?>" /></div>
						<div class="input textfield" id="address-zip"><label for="zip" class="icon-zip">Zip</label><input type="text" name="zip" id="zip" size="20"  maxlength="5" value="<?php echo $zip; ?>" /></div>
						<?php if(isset($_GET['uid'])) { echo '<input type="hidden" class="hidden" name="uid" value="' . $_GET['uid'] . '" />'; } ?>
					</div>
					<div class="input textfield"><label for="dlnumber" class="icon-dlnumber">DL Number</label><input type="text" name="dlnumber" id="dlnumber" size="10" value="<?php echo $dlnumber; ?>" /></div>
					<div class="input textfield"><label for="email" class="icon-email">Email</label><input type="text" name="email" id="email" size="20" value="<?php echo $email; ?>" /></div>
					<div class="input textfield"><label for="company" class="icon-company">Company</label><input type="text" name="company" id="company" size="20" value="<?php echo $company; ?>" /></div>
					<div class="input textfield"><label for="website" class="icon-website">Website</label><input type="text" name="website" id="website" size="50" value="<?php echo $website; ?>" /></div>
					<div id="data-phone">
						<div class="input textfield" id="phone-number"><label for="phone" class="icon-phone">Phone</label><input type="text" name="phone" id="phone" size="20" value="<?php echo $phone; ?>" /></div>
					</div>

					<div class="buttons">
						<button type="submit" id="add-submit">
							Submit
						</button>
					</div>
				</fieldset>
			</form>
		</div> <!-- /.content#add-contact -->
<?php include "footer.php" ?>

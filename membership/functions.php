<?php
	require('config.php');

        defineConstant("DLNUMBER", 1);
        defineConstant("LASTNAME", 2);
        defineConstant("FIRSTNAME", 3);
        defineConstant("MIDDLENAME", 4);
        defineConstant("ADDRESS", 6);
        defineConstant("CITY", 8);
        defineConstant("STATE", 9);
        defineConstant("ZIP", 10);
        defineConstant("MYSTERY", 5);
        defineConstant("DLCLASS", 12);
        defineConstant("ENDORSEMENT", 13);
        defineConstant("HEIGHT", 14);
        defineConstant("EYECOLOR", 15);
        defineConstant("HAIRCOLOR", 16);
        defineConstant("EXPIRATION", 17);
        defineConstant("DOB", 18);
	defineConstant("GENDER", 19);
        defineConstant("ISSUED", 20);
	//-----------------------------------
	// 	defineConstant
	//		Checks for definition first
	//-----------------------------------
        function defineConstant($constName, $constValue) {
                if (!defined($constName)) {
                        define($constName, $constValue);
                } else {
                        return "ERROR";
                }
        }



	//-----------------------------------
	//	checkInstall
	//		Checks to see if installed
	//-----------------------------------
	
	function checkInstall() { 
		if (!$link = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
			$installed = false;
		} else {
			$installed = false;
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
			$db = mysql_select_db(DB_NAME, $link); 
			$query = "show table status like 'cards'";
			$result = mysql_query($query, $link);
			if(mysql_num_rows($result)) { $installed = true; }
			return $installed;
		}
		return $installed;
	}

	//-----------------------------------
	//	setupTable
	//		Set up database table
	//-----------------------------------
	
	function setupTable() {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
		$query = "CREATE TABLE IF NOT EXISTS `cards` (`uid` int(255) NOT NULL auto_increment, `memnum` int(5), `memdob` date, 
`firstname` varchar(255) NOT NULL,`lastname`varchar(255) NOT NULL,`dlnumber` varchar(12), `street` varchar(255) NOT NULL,`city` varchar(255) NOT 
NULL,`zip` int(5) NOT NULL,`email` varchar(255) NOT NULL,`company` varchar(255) NOT NULL,`website` varchar(255) NOT NULL, `phone` varchar(11) NOT NULL,PRIMARY KEY  (`uid`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		$result = mysql_query($query, $link);
	}

	//-----------------------------------
	//	generateSlug
	//		Generates a slug
	//-----------------------------------
	
	function generateSlug($string) {
		$slug = preg_replace("/[^a-zA-Z0-9 ]/", "", $string);
		$slug = strtolower(str_replace(" ", "-", $slug));
		return $slug;
	}

	//-----------------------------------
	//	reverseLookup
	//		Looks up original company name based on slug
	//-----------------------------------
	
	function reverseLookup($slug) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "SELECT company FROM cards GROUP BY company";
		$result = mysql_query($query, $link);
		while ($row = mysql_fetch_assoc($result)) {
			if(generateSlug($row['company'])==$slug) { $company = $row['company']; }
		}
		return $company;
	}
	
	//-----------------------------------
	//	formatPhone
	//		Applies dashes in phone numbers
	//		Credit: http://www.justin-cook.com/wp/2006/11/17/parse-and-format-phone-numbers-with-php/
	//-----------------------------------
	
	function formatPhone($phone) {
		if (empty($phone)) return "";
		if (strlen($phone) == 7) 
			sscanf($phone, "%3s%4s", $prefix, $exchange);
		else if (strlen($phone) == 10)
			sscanf($phone, "%3s%3s%4s", $area, $prefix, $exchange);
		else if (strlen($phone) > 10)
			if(substr($phone,0,1)=='1') {
				sscanf($phone, "%1s%3s%3s%4s", $country, $area, $prefix, $exchange);
			} else {
				sscanf($phone, "%3s%3s%4s%s", $area, $prefix, $exchange, $extension);
			}
		else { return "unknown phone format: $phone"; }
		$out = "";
		$out .= isset($country) ? $country.' ' : '';
		$out .= isset($area) ? '(' . $area . ') ' : '';
		$out .= $prefix . '-' . $exchange;
		$out .= isset($extension) ? ' x' . $extension : '';
		return $out;
	}	

	//-----------------------------------
	//	addCard
	//		Adds a new card
	//-----------------------------------
	
	function addCard($memnum,$memdob,$firstName,$lastName,$address,$city,$zip,$email,$company,$website,$phone, $dlnumber) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
//print("> MemNum1=".$memnum." > ");
$temp = (int)$memnum;
//print(">cast memnum int: ".$temp);
		if ((int)$memnum == 0) {
//print("> why is memnum 0");
			$query = "SELECT MAX(memnum) FROM cards as memnum";
			$tempres = mysql_query($query, $link);
			$tempnum = mysql_fetch_assoc($tempres);
//print("\n>tempnum is ".$tempnum."\n");
//print_r($tempnum);
			$memnum = (int)$tempnum['MAX(memnum)'] + 1;
		}
//print("> MemNum2=".$memnum." > ");
//print("DB=".$db);
		//fix the date field
//print("\n>mem DOB is ".$memdob."\n");
		//replace for dash with slash
		$datefield = str_replace("-", "/", $memdob);
		//assuming american mm/dd/yy format
		$tmpdate = explode("/", $datefield);
//print_r($tmpdate);
		$yearfield = $tmpdate[2];		
		if (strlen($yearfield) == 2) {
			$yearfield = "19".$yearfield;
		}
//print("  year field: $yearfield  ");
		$datefield = $yearfield."/".$tmpdate[0]."/".$tmpdate[1];
//print("datefield: $datefield  ");
//		$unixdate = safestrtotime($datefield);
//print("unix date: $unixdate");
//		$datefield = date('y-m-d', $unixdate);
		$query = "INSERT INTO cards (`uid` ,`memnum`,`memdob`,`firstname` ,`lastname` ,`street` ,`city` ,`zip`,`email`,`company`,`website` ,`phone`, `dlnumber`) 
			VALUES (NULL , '".$memnum."', '".$datefield."', '".$firstName."', '".$lastName."', '".$address."', '".$city."', '".$zip."', '".$email."', 
			'".$company."', '".$website."', '".$phone."', '".$dlnumber."')";
//print("Query=".$query);
		$result = mysql_query($query, $link);
	}

	//-----------------------------------
	//	editCard
	//		Edits an existing card
	//-----------------------------------
	
	function editCard($uid,$memnum,$memdob,$firstName,$lastName,$address,$city,$zip,$email,$company,$website,$phone, $dlnumber) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
		//fix the date field
                $unixdate = strtotime($memdob);
                $datefield = date('y-m-d', $unixdate);

		$query = "UPDATE cards SET `memnum` = '".$memnum."',`memdob` = '".$datefield."',`firstname` = '".$firstName."',`lastname` = '".$lastName.",`street` = '".$address."',`city` = '".$city."',`zip` = '".$zip."',`email` = '".$email."'',`company` = '".$company."',`website` = '".$website."',`phone` = '".$phone."' WHERE `cards`.`uid`=".$uid." LIMIT 1";
		$result = mysql_query($query, $link);
	}
	
	//-----------------------------------
	//	getCard
	//		Returns data for one uid
	//-----------------------------------
	
	function getCard($uid) {
		if(is_numeric($uid)) {
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
			$db = mysql_select_db(DB_NAME, $link); 
			$query = "SELECT * FROM cards WHERE `cards`.`uid` =".$uid;
			$result = mysql_query($query, $link);
			$row = mysql_fetch_assoc($result);
//print("Row! ");
//print_r($row);
			return $row;
		}
	}

	//-----------------------------------
	//	splitBarcode
	//		returns an array of elements from PDF417 string
	//-----------------------------------

	function splitBarcode($barcode) {
		//run thru each delim and split the big string
		//save the left side in a growing array of tokens
		//continue to process the right side with the next delim
//print("Split Barcode: ".$barcode);
		$i = 0;
		$results = array($i => ""); //init the array
		$work = $barcode;
		$delims = array('AAMVA36004001DL', 'DLDAB', 'DAC', 'DAD', 'DAE', 'DAF', 'DAG', 'DAH', 'DAI', 'DAJ', 'DAK', 'DAL', 'DAM',
				'DAN', 'DAO', 'DAP', 'DAQ', 'DAQ', 'DAR', 'DAT', 'DAV', 'DAY', 'DAZ', 'DBA', 'DBB', 'DBC', 'DBD', 'DBH');
		foreach ($delims as $delim) {
//print(">delimeter: ".$delim);		
			$pairs = explode($delim, $work);
//print(">pairs 0 is ".$pairs[0]."\n");
//print(">pairs 1 is ".$pairs[1]."\n");
		$testleft = strlen($pairs[0]);
//print(">leftside is ".$testleft."\n");
		if($pairs[0] != $work) { //if delim not present, don't assign
			if($testleft > 0) {$results[$i] = $pairs[0];} //if leftside is empty, don't assign
			$work = $pairs[1];  
			$i++;
		}
$stupidphpsucksballs = $results;
//print("\n>results ".$i." is ".$stupidphpsucksballs."\n");
//print("> i: ".$i." > left pair: ".$pairs[0]." > work: ".$work."\n");
		}
//print_r($results);
		return $results;
	}
	
	//-----------------------------------
	//	getCompanies
	//		Returns array of grouped companies
	//-----------------------------------
	
	function getCompanies() {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "SELECT company FROM cards GROUP BY company ASC";
		$result = mysql_query($query, $link);
		$i = 0;
		// loop through results and generate array
		while ($row = mysql_fetch_assoc($result)) {
			$companies[$i] = $row['company'];
			$i++;
		}
		return $companies;
	}

	//-----------------------------------
	//	getAllNames
	//		Returns array of all contacts
	//-----------------------------------
	
	function getAllNames() {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "SELECT uid FROM cards ORDER BY lastname ASC";
		$result = mysql_query($query, $link);
		$i = 0;
		// loop through results and generate array
		while ($row = mysql_fetch_assoc($result)) {
			$contacts[$i] = $row['uid'];
			$i++;
		}
		return $contacts;
	}

	//-----------------------------------
	//	getCompanyNames
	//		Returns array of all contacts
	//-----------------------------------
	
	function getCompanyNames($company) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "SELECT uid, company, lastname FROM cards WHERE company='" . $company . "' ORDER BY lastname ASC";
		$result = mysql_query($query, $link);
		$i = 0;
		// loop through results and generate array
		while ($row = mysql_fetch_assoc($result)) {
			$contacts[$i] = $row['uid'];
			$i++;
		}
		return $contacts;
	}

	//-----------------------------------
	//	getContactName
	//		Returns name of passed uid
	//-----------------------------------
	
	function getContactName($uid) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "SELECT memnum, firstname, lastname FROM cards WHERE `cards`.`uid` =".$uid;
		$result = mysql_query($query, $link);
		$row = mysql_fetch_assoc($result);
		$name = $row['memnum'] . " - " . $row['firstname'] . " " . $row['lastname'];
		return $name;
	}


	//-----------------------------------
	//	searchCompanies
	//		Searches all companies
	//-----------------------------------
	
	function searchCompanies($string) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
		$string = trim($string);
		$query = "SELECT company FROM cards WHERE company like \"%$string%\" GROUP BY company ORDER BY company";
		$result = mysql_query($query, $link);
		$i = 0;
		// loop through results and generate array
		while ($row = mysql_fetch_assoc($result)) {
			$companies[$i] = $row['company'];
			$i++;
		}
		return $companies;
	}

	

	//-----------------------------------
	//	checkMemberExists
	//		See if member is already in the system
	//-----------------------------------

	function checkMemberExists($searchString) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
		$searchString = trim($searchString);
		$query = "SELECT * FROM cards WHERE dlnumber like \"%$searchString%\" OR lastname like \"%$searchString%\""; 
		$result = mysql_query($query, $link);
	}
		
	//-----------------------------------
	//	searchContacts
	//		Searches all contacts
	//-----------------------------------
	
	function searchContacts($string) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link);
		$string = trim($string);
		$query = "SELECT * FROM cards WHERE firstname like \"%$string%\" OR lastname like \"%$string%\" OR company like \"%$string%\" OR phone like \"%$string%\" OR email like \"%$string%\" OR street like \"%$string%\" OR city like \"%$string%\" OR memnum like \"%$string%\" OR zip like \"%$string%\" ORDER BY lastname";
		$result = mysql_query($query, $link);
		$i = 0;
		// loop through results and generate array
		while ($row = mysql_fetch_assoc($result)) {
			$contacts[$i] = $row['uid'];
			$i++;
		}
		return $contacts;
	}

	//-----------------------------------
	//	deleteContact
	//		Deletes a contact
	//-----------------------------------
	
	function deleteContact($uid) {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('I cannot connect to the database because: ' . mysql_error());
		$db = mysql_select_db(DB_NAME, $link); 
		$query = "DELETE FROM cards WHERE uid = $uid LIMIT 1";
		$result = mysql_query($query, $link);
	}

function safestrtotime($strInput) { 
        $iVal = -1; 
        for ($i=1900; $i<=1969; $i++) { 
            # Check for this year string in date 
            $strYear = (string)$i; 
            if (!(strpos($strInput, $strYear)===false)) { 
                $replYear = $strYear; 
                $yearSkew = 1970 - $i; 
                $strInput = str_replace($strYear, "1970", $strInput); 
            }; 
        }; 
        $iVal = strtotime($strInput); 
        if ($yearSkew > 0) { 
            $numSecs = (60 * 60 * 24 * 365 * $yearSkew); 
            $iVal = $iVal - $numSecs; 
            $numLeapYears = 0;        # Work out number of leap years in period 
            for ($j=$replYear; $j<=1969; $j++) { 
                $thisYear = $j; 
                $isLeapYear = false; 
                # Is div by 4? 
                if (($thisYear % 4) == 0) { 
                    $isLeapYear = true; 
                }; 
                # Is div by 100? 
                if (($thisYear % 100) == 0) { 
                    $isLeapYear = false; 
                }; 
                # Is div by 1000? 
                if (($thisYear % 1000) == 0) { 
                    $isLeapYear = true; 
                }; 
                if ($isLeapYear == true) { 
                    $numLeapYears++; 
                }; 
            }; 
            $iVal = $iVal - (60 * 60 * 24 * $numLeapYears); 
        }; 
        return($iVal); 
    }; 

?>

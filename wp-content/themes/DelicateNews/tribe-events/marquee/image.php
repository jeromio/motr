<?php

//$url='./wp-content/uploads/2012/10/P1080441.jpg';
//$result = marquee("Garage: Some Event", "25 Nov 2012", "$5/$8", $url);
$fileType = null;
function createImageHandle( $fileUrl ) {
	$fileInfo = new SplFileInfo($fileUrl);
	$fileType = $fileInfo->getExtension();
    $fileHandle = null;
	echo "file URL is".$fileUrl."\n";
    switch($fileType) {
        case('gif'):
            $fileHandle = imagecreatefromgif($fileUrl);
            break;
            
        case('png'):
            $fileHandle = imagecreatefrompng($fileUrl);
            break;
            
        default:
			//TODO: Add error traps for other image formats
            $fileHandle = imagecreatefromjpeg($fileUrl);
    }

    
    imageAlphaBlending($fileHandle, true);
    imageSaveAlpha($fileHandle, true); 
    return $fileHandle;
}

function marquee($title, $date, $details, $thumbnail_url, $filename) {
    #
    # $title = text
    # $thumbnail_url = Filename of the 24-bit PNG thumbnail file.
    # $fileName = name for the file.
    #
	// Set the enviroment variable for GD
	putenv('GDFONTPATH=' . realpath('.'));
	// Name the font to be used (note the lack of the .ttf extension)
	//$font = 'PlacardCondensed';//hard coded for now
	$font = dirname(__FILE__) . '/PlacardCondensed.ttf';

	$width = 1920;
	$height = 1080;
	$fontsize = 120;
	$textYpos = 580;
	$textfile_id = imagecreatetruecolor( $width, $height );
	$background = imagecolorallocate( $textfile_id, 0,0,0 );
	$text_colour = imagecolorallocate( $textfile_id, 125, 125, 80 );
	$line_colour = imagecolorallocate( $textfile_id, 228, 228, 228 );
	//clean up html code crap
	$title = str_replace( "&amp", "&", $title);
	$details = str_replace( "&amp", "&", $details);
	$date = str_replace( "&amp", "&", $date);
	//position text in the center, based on length & spacing
    $MAGIC_CHAR_WIDTH = 38;
    $TitleLen = strlen($title);
    if ($TitleLen > $MAGIC_CHAR_WIDTH ) { //shrink long titles to fit
	$fontsize = $fontsize * $MAGIC_CHAR_WIDTH  / $TitleLen;
    }	
    $spacing = 0;
    $line = array("linespacing" => $spacing);
    $coords = imageftbbox($fontsize,0,$font,$title,$line);
    $textXpos = ($width-10-$coords[4])/2;
    $newYpos =  $textYpos + 14 - ( $coords[5] ); //* $MAGIC_CHAR_WIDTH  / $TitleLen );
    imagettftext( $textfile_id, $fontsize, 0, $textXpos, $newYpos,$text_colour, $font, $title );
    $newYpos = $newYpos + 14;
	imagesetthickness ( $textfile_id, 4 );
	imageline( $textfile_id, 40, $newYpos, 1900, $newYpos, $line_colour );
	$newYpos = $newYpos + 14;
    $coords = imageftbbox($fontsize,0,$font,$date,$line);
    $textXpos = ($width-10-$coords[4])/2;

    $newYpos = $newYpos - $coords[5] + 14;
    imagettftext( $textfile_id, $fontsize, 0, $textXpos, $newYpos, $text_colour, $font, $date );
    $newYpos = $newYpos - $coords[5] + 14;

	$fontsize = 90;
    $coords = imageftbbox($fontsize,0,$font,$details,$line);
    $textXpos = ($width-10-$coords[4])/2;
   
    imagettftext( $textfile_id, $fontsize, 0, $textXpos, $newYpos, $text_colour, $font, $details );
    $newYpos = $newYpos  + 10;

	$picHeight = $textYpos;//$height - $newYpos;
	if ( !empty( $thumbnail_url ) ) {
		$thumbnail_id = createImageHandle( $thumbnail_url );
	} else {
		$thumbnail_id = createImageHandle( "http://motorcomusic.com/images/flyingMsc.jpg" );
	}
	//Get the sizes of both pix   
	$textfile_width=imagesx($textfile_id);
	$textfile_height=imagesy($textfile_id);
	$thumbnail_width=imagesx($thumbnail_id) + 1;
	$thumbnail_height=imagesy($thumbnail_id) + 1;

// here dest_x is calculated based on actual dimensions
//    $dest_x = ( $textfile_width / 2 ) - ( $thumbnail_width / 2 );
// here it is hard coded based on overall text height
	$picWidth = ($thumbnail_width * $picHeight / $thumbnail_height);
	$dest_x = ( $textfile_width - $picWidth ) / 2;
    $dest_y = 10;//( $textfile_height / 2 ) - ( $thumbnail_height / 2 );
        $fileType = strtolower(substr($thumbnail_url, strlen($thumbnail_url)-3));

    // if a gif, we have to upsample it to a truecolor image
    if($fileType == 'gif') {
        // create an empty truecolor container
        $tempimage = imagecreatetruecolor($textfile_width, $textfile_height);
        
        // copy the 8-bit gif into the truecolor image
        imagecopy($tempimage, $textfile_id, 0, 0, 0, 0, $textfile_width, $textfile_height);
        
        // copy the source_id int
        $textfile_id = $tempimage;
    }

//    imagecopy($textfile_id, $thumbnail_id, $dest_x, $dest_y, 0, 0, $thumbnail_width, $thumbnail_height);
    imagecopyresampled($textfile_id, $thumbnail_id, $dest_x, $dest_y, 0, 0, $picWidth, $picHeight, $thumbnail_width, $thumbnail_height);

    $filename = "marquee/mq"."_". str_replace(' ','_',$filename).".".$fileType;
	echo "Filename is: ".$filename;
    //Create a jpeg out of the modified picture
    switch($fileType) {
    
        // remember we don't need gif any more, so we use only png or jpeg.
        // See the upsample code immediately above to see how we handle gifs
        case('png'):
            //header("Content-type: image/png");
	
            imagepng ($textfile_id, $filename);
            //imagepng ($textfile_id);
            break;
            
        default:
            //header("Content-type: image/jpg");
            imagejpeg ($textfile_id, $filename);
    }           
  
    imagedestroy($textfile_id);
    imagedestroy($thumbnail_id);
    
}
?>

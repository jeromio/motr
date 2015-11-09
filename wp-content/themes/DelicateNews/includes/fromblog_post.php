<?php $thumb = '';
	$width = 59;
	$height = 59;
	$classtext = '';
	$titletext = get_the_title();

	$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
	$thumb = $thumbnail["thumb"]; ?>
<li>
	<a href="<?php the_permalink(); ?>" class="clearfix"><?php if ($thumb <> '') { ?><span class="box"><span class="overlay"></span><?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext , $width, $height, $classtext); ?></span><?php }; ?><span class="title"><?php the_title();?></span>
	</a>
</li>
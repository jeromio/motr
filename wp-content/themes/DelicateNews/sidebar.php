<div id="sidebar">

	<div id="one-col">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar One Column') ) : ?>
		<?php endif; ?>
	</div> <!-- end #first-col -->

	<div id="first-col">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Right Column') ) : ?>
		<?php endif; ?>
	</div> <!-- end #first-col -->

	<div id="last-col">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Left Column') ) : ?>
		<?php endif; ?>
	</div> <!-- end #last-col -->

</div> <!-- end #sidebar -->
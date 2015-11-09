<?php 
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */
?>

<style>
	h1 { margin-left: 40px; }
	h3 span { text-transform: lowercase; }
</style>

<!-- LOOP -->
<div class="tribe-events-loop hfeed vcalendar">

<?php while ( have_posts() ) : the_post(); ?>

<!-- Each Event  -->
<div id="post-<?php the_ID() ?>">
<!-- Event Date/Cost -->
<h2>
    <?php
        echo tribe_events_event_schedule_details();
        echo ", ";
        echo tribe_get_cost(); ?>
</h2>

<!-- Event Title -->
<h1>
<a href="<?php echo tribe_get_event_link() ?>"><?php the_title() ?></a>
</h1>

<!-- Event Opener -->
<h3>
    <?php $opener = get_post_meta( get_the_ID(), "_ecp_custom_1", true);
        if( strlen($opener) > 0 )
        {
            echo "<span>w/ </span>";
            echo $opener;
        }
    ?>
</h3>

<hr />
</div>
<!-- // Each Event  -->

<?php endwhile; ?>
</div>
<!-- // LOOP -->
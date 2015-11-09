

<!-- Event Date/Cost -->
<h2>
    <?php
        echo tribe_events_event_schedule_details();
        echo ", ";
        echo tribe_get_cost(); ?>
    <?php //echo tribe_events_recurrence_tooltip() ?>
</h2>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h1>
<a class="url" href="<?php echo tribe_get_event_link() ?>"><?php the_title() ?></a>
</h1>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

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




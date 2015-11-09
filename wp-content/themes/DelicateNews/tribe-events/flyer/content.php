<?php
/**
 * List View Content Template
 * The content template for the list view. This template is also used for
 * the response that is returned on list view ajax requests.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/content.php
 * 
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */
 ?>



<!-- Events Loop -->
<?php if ( have_posts() ) : ?>

<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video
{
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}

/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section { display: block; }
body { line-height: 1; }
ol, ul { list-style: none; }
blockquote, q { quotes: none; }
blockquote:before, blockquote:after,
q:before, q:after { content: none; }

table
{
	border-collapse: collapse;
	border-spacing: 0;
}

h1, h2, h3 { text-transform: uppercase; }
h1 { margin-left: 40px; }
h3 span { text-transform: lowercase; }
</style>

    <!-- LOOP -->
    <div class="tribe-events-loop hfeed vcalendar">
        <?php while ( have_posts() ) : the_post(); ?>

        <!-- Each Event  -->
        <div id="post-<?php the_ID() ?>">
            <!-- Event Date/Cost -->
            <h2><?php
                    echo tribe_events_event_schedule_details();
                    echo ", ";
                    echo tribe_get_cost(); ?></h2>

            <!-- Event Title -->
            <h1><a href="<?php echo tribe_get_event_link() ?>"><?php the_title() ?></a></h1>

            <!-- Event Opener -->
            <h3><?php $opener = get_post_meta( get_the_ID(), "_ecp_custom_1", true);
                    if( strlen($opener) > 0 )
                    {
                        echo "<span>w/ </span>";
                        echo $opener;
                    }
                ?></h3>

            <hr />
        </div>
        <!-- // Each Event  -->

        <?php endwhile; ?>
    </div>
    <!-- // LOOP -->

<?php endif; ?>
<!-- // Events Loop -->

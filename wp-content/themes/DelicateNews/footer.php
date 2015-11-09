			</div> <!-- end #content -->

		</div> <!-- end .container -->
	</div>	<!-- end #bg2 -->
</div> 	<!-- end #bg -->


<div id="footer">
	<div class="container clearfix">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
		<?php endif; ?>
				
		<p id="copyright">		723 Rigsbee Ave, Durham 27701
&copy; <?php echo date('Y');?> | <a href="http://motorcomusic.com">motorcomusic.com</a></p>

	</div> <!--end .container -->
</div> <!-- end #footer -->

	<?php get_template_part('includes/scripts'); ?>

	<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12494531-4', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>

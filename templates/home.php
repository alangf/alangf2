<div class="container">
	<?php if ( have_posts() ) : 
		while ( have_posts() ) : the_post();
			get_part('card');
		endwhile; 
	else : ?>
	<?php endif; ?>	
</div>

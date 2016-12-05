<header class="site-header" id="header">
	<div class="container">
		<div class="half logo-wrap">
			<a href="<?php echo home_url() ?>" title="<?php bloginfo('blogname') ?>">
				<h1>
					Alan
				</h1>				
			</a>
		</div>
		<div class="half menu-wrap">
			<?php 
			wp_nav_menu(array(
				'theme_location' => 'header',
				'menu' => 'header',
				'container' => '',
				'container_class' => 'menu-{menu-slug}-container',
				'items_wrap' => '<ul class = "%2$s">%3$s</ul>',
			));			
			?>
		</div>
	</div>
</header>
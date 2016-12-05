<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head() ?>	
	</head>
	<body <?php echo body_class() ?>>
		<main class="wrap container" id="app">
			<div class="sidebar">
				<router-link to="/" class="logo">
					<h1>Alan Gomes</h1>
				</router-link>
				<span><?php _l('webdev') ?></span>
				<br>
				<p>
					<ul>
						<li><router-link to="/"><?php _l('projects') ?></router-link></li>
						<li><router-link to="/contact"><?php _l('contact') ?></router-link></li>
					</ul>
				</p>
			</div> 	
			<div class="content">
				<router-view keep-alive></router-view>
			</div>
		</main>
		<?php wp_footer() ?>
	</body>
</html>
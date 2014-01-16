<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php print $head ?>
  <title><?php print $head_title ?></title>
  <?php print $styles ?>
  <?php print $scripts ?>
</head>

	<body class="twoColFixLtHdr">
		<div id="container">
			<div id="header">
				<div class="floatleft lefttext"><img src="<?php print $logo ?>" alt="" /></div>
				<div class="tagline"><?php print $site_slogan?></div>
			</div>
			<div id="sidebar">
				<?php 
					if (isset($primary_links)) print primary_links_add_icons(); 
					if (isset($secondary_links)) print theme('links', $secondary_links, array('class' => 'links secondary-links'));
					print $left 
				?>
				<div class="corner bl"></div>
					<div class="corner br"></div>
						<div class="corner tl"></div>
							<div class="corner tr"></div>			
			</div>
			<div id="mainContent">
				<h1><?php print $title ?></h1>
				<div><?php print $tabs ?></div>
        <?php 
        	if ($show_messages) { print $messages; } 
        	print $help;
        	print $content; 
        ?>
        <? #php print $feed_icons; ?>
			</div>			
			<div style="clear:both"></div>
			<div id="footer">
				  <?php print $footer.$footer_message ?>
			</div>
		</div>		
		<?php print $closure ?>
	</body>
</html>

<html>
	<head>
		<?= $head ?>
		<title><?= $head_title ?></title>
		<?= $styles ?>
		<?= $scripts ?>
	</head>
	<body>
		<div class="header-portion">
			<div class="content-part">
				<div class="align-vertical-center absolute-right header-size">
					<ul>
						<li>
							<?= top_login_bar(); ?>
						</li>
						<li>
							<?= $language_region; ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="body-portion">
			<div class="content-part">
				<div class="menu-portion">
					<div class="menu-content">
						<?= $main_menu_region; ?>
					</div>
				</div>
				<div class="content-logo">
					<img src="/sites/all/themes/enblic/images/theme_logo.jpg" />
				</div>
				<div class="content-portion">
					<div class="content-left content-full-width content-right-border">
						<div class="content-block">
							<div class="title">
								<div class="text"><?= $title ?></div>
								<div class="title-tabs"><?= $tabs ?></div>
							</div>
							<div class="body">
								<div>
									<?php
										if ($show_messages) print "<p>$messages</p>";
										print "<p>$help</p><p>$content</p>";
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-block">
					<?= $footer_region . $footer . $footer_message ?>
				</div>
			</div>
		</div>
		<?= $closure ?>
	</body>
</html>
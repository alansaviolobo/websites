<?php
function top_login_bar() {
	global $user;
	$output = '';

	if (! $user->uid) {
		$output .= drupal_get_form ( 'user_login_block' );
		$output .= l(t('REGISTER'),'user/register',array('class'=> 'item-list'));
	} else {
		$output .= t ( '<p class="user-info">Hi !user, welcome back.</p>', array ('!user' => theme ( 'username', $user ) ) );
		$output .= theme ( 'item_list', array (l ( t ( 'MY ACCOUNT' ), 'user_profile', array () ), l ( t ( 'Sign out' ), 'logout' ) ) );
	}
	return "<div id='user-bar'>$output</div>";
}
?>
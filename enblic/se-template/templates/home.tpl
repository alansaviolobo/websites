				{include file="header.tpl"}
					{* SHOW BELOW-MENU ADVERTISEMENT BANNER *}
					{if $ads->ad_belowmenu != ""}<div style='display: block; visibility: visible;'>{$ads->ad_belowmenu}</div>{/if}
					<div class="content-left">						
						<div class="content-block">
							<div class="outer-title-border">
								<div class="inner-title-border">
									{lang_print id=850009}
								</div>
							</div>
							<div class="outer-content-border">
								<div class="inner-content-border">
									{lang_print id=657}
								</div>
							</div>
						</div>
						
						{if $news|@count > 0}
						<div class="content-block">
							<div class="outer-title-border">
								<div class="inner-title-border">
									{lang_print id=664}
								</div>
							</div>
							<div class="outer-content-border">
								<div class="inner-content-border">
									{section name=news_loop loop=$news max=3}
										<div style='margin-top: 3px;'><img src='./images/icons/news16.gif' border='0'><b>{$news[news_loop].announcement_subject}</b> - {$news[news_loop].announcement_date}</div>
										<div style='margin-top: 3px;'>{$news[news_loop].announcement_body}</div>
      						{/section}
								</div>
							</div>
						</div>
						{/if}
						
						{if $actions|@count > 0}
						<div class="content-block">
							<div class="outer-title-border">
								<div class="inner-title-border">
									{lang_print id=737}
								</div>
							</div>
							<div class="outer-content-border">
								<div class="inner-content-border">
									{if $ads->ad_feed != ""}
										<div  style='display: block; visibility: visible; padding-bottom: 10px;'>{$ads->ad_feed}</div>
									{/if}

									{* SHOW ACTIONS *}
									{section name=actions_loop loop=$actions max=10}
									<div id='action_{$actions[actions_loop].action_id}' class='portal_action{if $smarty.section.actions_loop.first}_top{/if}'>
										<table cellpadding='0' cellspacing='0'>
											<tr>
												<td valign='top'><img src='./images/icons/{$actions[actions_loop].action_icon}' border='0'></td>
												<td valign='top' width='100%'>
													{assign var='action_date' value=$datetime->time_since($actions[actions_loop].action_date)}
													<div>{lang_sprintf id=$action_date[0] 1=$action_date[1]}</div>
													{assign var='action_media' value=''}
													{if $actions[actions_loop].action_media !== FALSE}{capture assign='action_media'}{section name=action_media_loop loop=$actions[actions_loop].action_media}<a href='{$actions[actions_loop].action_media[action_media_loop].actionmedia_link}'><img src='{$actions[actions_loop].action_media[action_media_loop].actionmedia_path}' border='0' width='{$actions[actions_loop].action_media[action_media_loop].actionmedia_width}'></a>{/section}{/capture}{/if}
													{lang_sprintf assign=action_text id=$actions[actions_loop].action_text args=$actions[actions_loop].action_vars}
													{$action_text|replace:"[media]":$action_media|choptext:50:"<br>"}
											  </td>
											</tr>
										</table>
								</div>
      					{/section}
								</div>
							</div>
						</div>
						{/if}
						
				</div>
						
						<div class="content-right">
							{if !$user->user_exists}
								<div class="content-block">
									<div class="outer-title-border">
										<div class="inner-title-border">
											{lang_print id=659}
										</div>
									</div>
									<div class="outer-content-border">
										<div class="inner-content-border">						
										<form action='login.php' method='post'>
											<table cellpadding='0' cellspacing='0' align='center'>
											<tr>
												<td>
													{lang_print id=89}:<br />
													<input type='text' name='email' size='25' maxlength='100' value='{$prev_email}' />
												</td>
											</tr>
											<tr>
												<td style='padding-top: 6px;'>
													{lang_print id=29}:<br />
													<input type='password' name='password' size='25' maxlength='100' />
												</td>
											</tr>
											{if !empty($setting.setting_login_code)}
											<tr>
												<td style='padding-top: 6px;'>
													<table cellpadding='0' cellspacing='0'>
														<tr>
															<td><input type='text' name='login_secure' size='6' maxlength='10' />&nbsp;</td>
															<td>
																<table cellpadding='0' cellspacing='0'>
																  <tr>
																	<td align='center'>
																	  <img src='./images/secure.php' id='secure_image' border='0' height='20' width='67' alt='' /><br />
																	  <a href="javascript:void(0);" onClick="$('secure_image').src = './images/secure.php?' + (new Date()).getTime();">{lang_print id=975}</a>
																	</td>
																	<td>{capture assign=tip}{lang_print id=691}{/capture}<img src='./images/icons/tip.gif' border='0' title='{$tip|escape:quotes}' alt='' /></td>
																  </tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											{/if}
											<tr>
												<td style='padding-top: 10px;'>
													<input type='submit' value='{lang_print id=30}' />&nbsp;
													<input type='checkbox' name='persistent' value='1' id='rememberme' />
													<label for='rememberme'>{lang_print id=660}</label>
												</td>
											</tr>
											</table>						
										<noscript><input type='hidden' name='javascript_disabled' value='1' /></noscript>
										<input type='hidden' name='task' value='dologin' />
										<input type='hidden' name='ip' value='{$ip}' />
								</form>
							</div>
						</div>
					</div>
							{else}
							<div class="banner_300_by_250">
								<div>
									<div>{lang_sprintf id=510 1=$user->user_displayname_short}</div>
									<a href='{$url->url_create('profile',$user->user_info.user_username)}'>
									<img src='{$user->user_photo("./images/nophoto.gif")}' width='{$misc->photo_size($user->user_photo("./images/nophoto.gif"),'90','90','w')}' border='0' alt="{lang_sprintf id=509 1=$user->user_info.user_username}">
									</a>
		    				</div>
      					<div>[ <a href='user_logout.php'>{lang_print id=26}</a> ]</div>
    					</div>
 							{/if}
							
							{if $user->user_exists == 0}
							<div class="banner_300_by_250">
								<a href='signup.php'>{lang_print id=1115}</a>
							</div>
							{/if}
							
							<div class="banner_300_by_600">
								<div>{lang_print id=511}</div>
								<div>
									{foreach from=$site_statistics key=stat_name item=stat_array}
									&#149; {lang_sprintf id=$stat_array.title 1=$stat_array.stat}<br />
									{/foreach}
								</div>
							</div>
							
						  {math assign='total_online_users' equation="x+y" x=$online_users[0]|@count y=$online_users[1]}
							{if $total_online_users > 0}							
							<div class="banner_300_by_600">
							<div>{lang_print id=665} ({$total_online_users})</div>
								{if $online_users[0]|@count == 0}
									{lang_sprintf id=977 1=$online_users[1]}
								{else}
									{capture assign='online_users_registered'}{section name=online_loop loop=$online_users[0]}{if $smarty.section.online_loop.rownum != 1}, {/if}<a href='{$url->url_create("profile", $online_users[0][online_loop]->user_info.user_username)}'>{$online_users[0][online_loop]->user_displayname}</a>{/section}{/capture}
									{lang_sprintf id=976 1=$online_users_registered 2=$online_users[1]}
								{/if}
							</div>
							{/if}
							
							<div class="banner_300_by_600">
							<div>{lang_print id=671}</div>
							{if !empty($logins)}
								<table cellpadding='0' cellspacing='0' align='center'>
									{section name=login_loop loop=$logins max=9}
									{cycle name="startrow3" values="<tr>,,"}
									<td valign="bottom" style="padding:2px;">
									{if !empty($logins[login_loop])}	
									<a href='{$url->url_create("profile",$logins[login_loop]->user_info.user_username)}' TITLE='{$logins[login_loop]->user_displayname|truncate:15:"...":true}'><img src='{$logins[login_loop]->user_photo("./images/nophoto.gif", TRUE)}' width='50' height='50' border='0'></a>
									{/if}
									</td>
									{cycle name="endrow3" values=",,</tr>"}
									{/section}
								</table>
							{else}
								{lang_print id=672}
							{/if}
							</div>
							
							<div class="banner_300_by_600">
								<div>{lang_print id=666}</div>
								{if !empty($logins)}
								<table cellpadding='0' cellspacing='0' align='center'>
									{section name=signups_loop loop=$signups max=9}
									{cycle name="startrow" values="<tr>,,"}
									<td valign="bottom" style="padding:2px;">
										{if !empty($signups[signups_loop])}
											<a href='{$url->url_create("profile",$signups[signups_loop]->user_info.user_username)}' TITLE='{$signups[signups_loop]->user_displayname|truncate:15:"...":true}'><img src='{$signups[signups_loop]->user_photo("./images/nophoto.gif", TRUE)}' width='50' height='50' border='0'></a>
										{/if}
									</td>
									{cycle name="endrow" values=",,</tr>"}
									{/section}
								</table>
								{else}
									{lang_print id=667}
								{/if}
							</div>
							
							{if $setting.setting_connection_allow != 0}
							<div class="banner_300_by_600">
								<div>{lang_print id=668}</div>
								{if !empty($logins)}
								<table cellpadding='0' cellspacing='0' align='center'>
								{section name=friends_loop loop=$friends max=9}
								{cycle name="startrow2" values="<tr>,,"}
								<td valign="bottom" style="padding:2px;">
									{if !empty($friends[friends_loop])}
									<a href='{$url->url_create("profile",$friends[friends_loop].friend->user_info.user_username)}' TITLE='{$friends[friends_loop].friend->user_displayname|truncate:15:"...":true} - {lang_sprintf id=669 1=$friends[friends_loop].total_friends}'><img src='{$friends[friends_loop].friend->user_photo("./images/nophoto.gif", TRUE)}' width='50' height='50' border='0'></a><br />
									{/if}
								</td>
								{cycle name="endrow3" values=",,</tr>"}
								{/section}
								</table>
								{else}
									{lang_print id=670}
								{/if}
							</div>
							{/if}											
					{include file="footer.tpl"}

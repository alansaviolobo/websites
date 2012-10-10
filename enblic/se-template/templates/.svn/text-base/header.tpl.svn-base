{* INCLUDE HEADER CODE *}
{include file="header_global.tpl"}
{if $smarty.const.SE_DEBUG && $admin->admin_exists}{include file="header_debug.tpl"}{/if}
<div class="header-portion">
	<div class="content-part">
		<div class="align-vertical-center absolute-right">
			<ul>
				<li><a href="#">Einloggen</a>
				</li>
				<li><a href="#">Mein Konto</a>
				</li>
				<li class="last"><a href="#">Deutsch(DE)</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="body-portion">
	<div class="content-part">
		<div class="menu-portion">
	  	{* PAGE-TOP ADVERTISEMENT BANNER *}
			{if $ads->ad_top != ""}
				<div style='display: block; visibility: visible;'>{$ads->ad_top}</div>
			{/if}
			<!--
			<div id="search-form">
				<form action='search.php' method='post'>
				{lang_print id=643}
				<input type='text' name='search_text' size='25' />
				<input type='submit' value='{lang_print id=644}' />
				<input type='hidden' name='task' value='dosearch' />
				<input type='hidden' name='t' value='0' />
				</form>
				</div>
			-->
			<div class="menu-content">	
				<div class="main-menu">
					<ul class="absolute-right">
						<li class="selected"><a href="home.php">{lang_print id=645}</a>
						</li>
						<li><a href="search_advanced.php">{lang_print id=646}</a>
						</li>
						<li><a href="invite.php">{lang_print id=647}</a>
						</li>
					  {hook_foreach name=menu_main var=menu_main_args complete=menu_main_complete max=9}
						<li>
							<a href="#">{lang_print id=$menu_main_args.title}</a>
						</li>
						{/hook_foreach}
						{if !$menu_main_complete}
							<li>
								<a href="javascript:void(0);" onclick="$('menu_main_dropdown').style.display = ( $('menu_main_dropdown').style.display=='none' ? 'inline' : 'none' ); this.blur(); return false;" class='top_menu_item'>
									{lang_print id=1316}
								</a>
							</li>
             {* SHOW ANY PLUGIN MENU ITEMS *}
   			     {hook_foreach name=menu_main var=menu_main_args start=9}
		 			     <li>
			 		       <a href='{$menu_main_args.file}' style="text-align: left;">
			 	         {lang_print id=$menu_main_args.title}
			 		       </a>
			 		     </li>
   			     {/hook_foreach}
 			     {/if}
 			     {if $user->user_exists != 0}
						<li>
		          <a href='javascript:void(0);' onClick="SocialEngine.Viewer.userNotifyPopup(); return false;">
          		{assign var="notify_total" value=$notifys.total_grouped}
          		{lang_sprintf id=1019 1="<span id='notify_total'>`$notify_total`</span>"}
         			</a>
          		&nbsp;&nbsp; 
          		<a href='javascript:void(0);' onClick="SocialEngine.Viewer.userNotifyHide(); return false;">X</a>
    		      {lang_sprintf id=649 1="<a href='user_home.php'>`$user->user_displayname_short`</a>"}
  						&nbsp;&nbsp;
							<a href='user_logout.php'>{lang_print id=26}</a>
						</li>
					{else}
						<li><a href='signup.php'>{lang_print id=650}</a></li>
						<li class="last">							
							<a href='login.php'>{lang_print id=30}</a>
						</li>
					{/if}
					</ul>
				
				</div>
				
			 {if $user->user_exists}
					{lang_javascript ids=1198,1199}
					<script type='text/javascript'>
						<!--
						var notify_update_interval;
						window.addEvent('domready', function() {ldelim}
							SocialEngine.Viewer.userNotifyGenerate({$se_javascript->generateNotifys($notifys)});
							SocialEngine.Viewer.userNotifyShow();
							notify_update_interval = (function() {ldelim}
								if( notify_update_interval ) SocialEngine.Viewer.userNotifyUpdate();
							{rdelim}).periodical(60 * 1000);
						{rdelim});
						//-->
				 </script>
			{/if}

			{literal}
			<script type='text/javascript'>
			<!--
			var open_menu;
			var current_timeout = new Array();

			function showMenu(id1)
			{
				if($(id1))
				{
					if($(id1).style.display == 'none')
					{
						if($(open_menu)) { hideMenu($(open_menu)); }
						$(id1).style.display='inline';
						startMenuTimeout($(id1));
						$(id1).addEvent('mouseover', function(e) { killMenuTimeout(this); });
						$(id1).addEvent('mouseout', function(e) { startMenuTimeout(this); });
						open_menu = id1;
					}
				}
			}

			function killMenuTimeout(divEl)
			{
				clearTimeout(current_timeout[divEl.id]);
				current_timeout[divEl.id] = '';
			}

			function startMenuTimeout(divEl)
			{
				if(current_timeout[divEl.id] == '') {
					current_timeout[divEl.id] = setTimeout(function() { hideMenu(divEl); }, 1000);
				}
			}

			function hideMenu(divEl)
			{
				divEl.style.display = 'none'; 
				current_timeout[divEl.id] = '';
				divEl.removeEvent('mouseover', function(e) { killMenuTimeout(this); });
				divEl.removeEvent('mouseout', function(e) { startMenuTimeout(this); });
			}

			function SwapOut(id1) {
				$(id1).src = Rollarrow1.src;
				return true;
			}

			function SwapBack(id1) {
				$(id1).src = Rollarrow0.src;
				return true;
			}
			//-->
			</script>
			{/literal}
			{if $user->user_exists != 0}
				<div class="sub-menu">
					<ul  class="absolute-left">
						<li class="first selected">
							<a href='user_home.php'><img src='./images/icons/menu_home.gif' border='0'>{lang_print id=1161}</a>
							<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_whatsnew');" />
								<div id='menu_dropdown_whatsnew' style='display: none;'>
									<div>
									  <div><a href='network.php'><img src='./images/icons/mynetwork16.gif' border='0'/>{lang_print id=1162}</a></div>
									</div>
								</div>
						</li>
						<li>
							<a href='{$url->url_create("profile", $user->user_info.user_username)}' class='menu_item'>
							<img src='./images/icons/profile16.gif' border='0'/>
							{lang_print id=652}
							</a>
							<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_profile');" />
							<div id='menu_dropdown_profile' style='display: none;'>
					 			 <div>
						    	<div><a href='user_editprofile.php'><img src='./images/icons/profile_edit16.gif' border='0'>{lang_print id=1163}</a></div>
						      <div><a href='user_editprofile_photo.php'><img src='./images/icons/profile_editphoto16.gif' border='0'>{lang_print id=1164}</a></div>
						      {if $user->level_info.level_profile_style != 0 || $user->level_info.level_profile_style_sample != 0}
						      <div><a href='user_editprofile_style.php'><img src='./images/icons/profile_editstyle16.gif' border='0'>{lang_print id=1165}</a></div>
						      {/if}
						    </div>
						  </div>
						</li>
						{if $global_plugins.plugin_controls.show_menu_user}
						<li>
							<a href="javascript:showMenu('menu_dropdown_apps');" onMouseUp="this.blur()">
							<img src='./images/icons/menu_apps.gif' border='0'/>
							{lang_print id=1166}
							</a>
							<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_apps');" />		
							<div class='menu_dropdown' id='menu_dropdown_apps' style='display: none;'>
				        <div>
						      {hook_foreach name=menu_user_apps var=user_apps_args}
								    <div>
								      <a href='{$user_apps_args.file}'>
								        <img src='./images/icons/{$user_apps_args.icon}' border='0'/>
								        {lang_print id=$user_apps_args.title}
								      </a>
								    </div>
						      {/hook_foreach}
				        </div>
				      </div>						
						</li>
						{/if}
						
						{if $user->level_info.level_message_allow != 0}
						<li>
							<a href='user_messages.php'>
							<img src='./images/icons/message_inbox16.gif' border='0'/>
							{lang_print id=654}{if $user_unread_pms != 0} ({$user_unread_pms}){/if}
							</a>
        			<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_messages');" />
        			<div class='menu_dropdown' id='menu_dropdown_messages' style='display: none;'>
				        <div>
				          <div><a href="javascript:TB_show('{lang_print id=784}', 'user_messages_new.php?TB_iframe=true&height=400&width=450', '', './images/trans.gif');"><img src='./images/icons/message_new16.gif' border='0'>{lang_print id=1167}</a></div>
				          <div><a href='user_messages.php'><img src='./images/icons/message_inbox16.gif' border='0'>{lang_print id=1168}</a></div>
				          <div><a href='user_messages_outbox.php'><img src='./images/icons/message_outbox16.gif' border='0'>{lang_print id=1169}</a></div>
				        </div>
				      </div>
						</li>
						{/if}
						
						{if $setting.setting_connection_allow != 0}
						<li>
							<a href='user_friends.php'>
							<img src='./images/icons/friends16.gif' border='0'/>
							{lang_print id=653}
							</a>
        			<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_friends');" />
        			<div id='menu_dropdown_friends' style='display: none;'>
				        <div>
				          <div><a href='user_friends.php'><img src='./images/icons/friends16.gif' border='0'>{lang_print id=1170}</a></div>
				          <div><a href='user_friends_requests.php'><img src='./images/icons/friends_incoming16.gif' border='0'>{lang_print id=1171}</a></div>
				          <div><a href='user_friends_requests_outgoing.php'><img src='./images/icons/friends_outgoing16.gif' border='0'>{lang_print id=1172}</a></div>
				        </div>
				      </div>
						</li>
						{/if}
						
						
						<li class="last">
							<a href='user_account.php'>
							<img src='./images/icons/settings16.gif' border='0' />
							{lang_print id=655}
							</a>
      				<img src='./images/icons/menu_arrow.gif' border='0' style='vertical-align: middle; cursor: pointer;' onMouseOver="showMenu('menu_dropdown_settings');" />
							<div id='menu_dropdown_settings' style='display: none;'>
						    <div>
						      <div><a href='user_account.php'><img src='./images/icons/settings16.gif' border='0'>{lang_print id=1173}</a></div>
						      <div><a href='user_account_privacy.php'><img src='./images/icons/settings_privacy16.gif' border='0'>{lang_print id=1174}</a></div>
						    </div>
						  </div>
						</li>
					</ul>
				</div>
				{/if}
			</div>
		</div>
		<div class="content-logo">
		</div>
		{* SHOW LEFT-SIDE ADVERTISEMENT BANNER *}
		{if $ads->ad_left != ""}
			<div style='display: block; visibility: visible;'>{$ads->ad_left}</div>
		{/if}
		<div class="content">
	{if $ads->ad_bottom != ""}
		<div style='display: block; visibility: visible;'>
			{$ads->ad_bottom}
		</div>
	{/if}
</div>
{*content right ends*}
	<div class="clear-float"></div>
	{if $ads->ad_right != ""}
	<td valign='top'><div width='1' style='display: table-cell; visibility: visible;'>{$ads->ad_right}</div></td>
	{/if}
</div>
{*content ends*}
<div class="footer-block">
					<div class="outer-title-border">
						<div class="inner-title-border">
							<ul>
								<li><a href="#">ABG</a></li>
								<li><a href="#">Datenschutz</a></li>
								<li><a href="#">Urheberrecht</a></li>
								<li><a href="#">Nutzervereinbarungen</a></li>
								<li><a href="#">Impressum</a></li>
								<li><a href="#">Jugendschutz</a></li>
								<li><a href="#">Gesundheit</a></li>
							</ul>
						</div>
					</div>
					<div class="outer-content-border">
						<div class="inner-content-border">
							<div class="footer-message">
								<div class="footer-logo"></div>
								<div class="copyrights">
									{lang_print id=1175} {$smarty.now|date_format:'%Y'}
										<a href='help.php'>{lang_print id=752}</a> &nbsp;-&nbsp;
										<a href='help_tos.php'>{lang_print id=753}</a> &nbsp;-&nbsp;
										<a href='help_contact.php'>{lang_print id=754}</a>
										{if $setting.setting_lang_anonymous == 1 && $lang_packlist|@count != 0}
											&nbsp;-&nbsp;
											{if $smarty.server.QUERY_STRING|strpos:"&lang_id=" !== FALSE}{assign var="pos" value=$smarty.server.QUERY_STRING|strstr:"&lang_id="}{assign var="query_string" value=$smarty.server.QUERY_STRING|replace:$pos:""}{else}{assign var="query_string" value=$smarty.server.QUERY_STRING}{/if}
												<select name='user_language_id' onchange="window.location.href='{$smarty.server.PHP_SELF}?{$query_string}&lang_id='+this.options[this.selectedIndex].value;">
													{section name=lang_loop loop=$lang_packlist}
														<option value='{$lang_packlist[lang_loop].language_id}'{if $lang_packlist[lang_loop].language_id == $global_language} selected='selected'{/if}>{$lang_packlist[lang_loop].language_name}</option>
													{/section}
												</select>
  									{/if}
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</body>
</html>

<?php
function primary_links_add_icons() {
  $links = menu_primary_links();
  $level_tmp = explode('-', key($links));
  $level = $level_tmp[0];
  $output = "<ul  class='MenuLink'>";
  if ($links) {
    foreach ($links as $link) {
				$link = l($link['title'], $link['href'], $link['attributes'], $link['query'], $link['fragment']);
        $cssid = str_replace(' ', '', strtolower(strip_tags($link)));
        $output .= '<p><li id="'.$cssid.'"><a href="'.$cssid.'"><img class="'.$cssid.'" src="'. path_to_theme() .'/images/img_trans.gif">&nbsp;'. strip_tags($link) . '</a></li></p>';
    };
  $output .= '</ul>';
  }
  return $output;
}
?>


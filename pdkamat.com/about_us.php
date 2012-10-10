<?php
$title = 'About Us';
$content = file_get_contents(basename(__FILE__, '.php') . '.html');
include 'template.html';
?>
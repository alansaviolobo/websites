<?php
$title = 'About Goa';
$content = file_get_contents(basename(__FILE__, '.php') . '.html');
include 'template.html';
?>
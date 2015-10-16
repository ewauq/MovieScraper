<?php
require('framework/simple_html_dom.php');
require('framework/functions.php');
require('framework/allocine.php');
require('framework/imdb.php');

// if(!isset($_GET['allocineid']) || empty($_GET['allocineid']) || !is_numeric($_GET['allocineid']))
// 	exit();

$allocine = new Allocine;
$allocine->loadPage('21189');

$json['allocine'] = json_decode($allocine->getJSON());

if(isset($_GET['html'])):
	include('html.php');
else:
	header('Content-Type: application/json');
	print $allocine->getJSON();
endif;
?>
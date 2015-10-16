<?php
require('framework/simple_html_dom.php');
require('framework/functions.php');
require('framework/allocine.php');
require('framework/imdb.php');

if(!isset($_GET['AllocineID']) || empty($_GET['AllocineID']) || !is_numeric($_GET['AllocineID']))
	exit();

$allocine = new Allocine;
$allocine->loadPage($_GET['AllocineID']);

$json['allocine'] = json_decode($allocine->getJSON());

if(isset($_GET['html'])):
	include('html.php');
else:
	header('Content-Type: application/json');
	print $allocine->getJSON();
endif;
?>
<?php

if ($_SERVER['REQUEST_URI'] === '/') {
	include 'index.php';		
	return true;
}


$a = preg_match('/^\/?painel\/?\/login\/?$/', $_SERVER["REQUEST_URI"]);
if ($a) {
	require './painel/login.php';
	die;
}
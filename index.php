<?php
$f3=require('lib/base.php');

$f3->config('config/globals.ini');
$f3->config('config/routes.ini');

$f3->set('dB',new DB\SQL(
  'mysql:host='.F3::get('db_host').';port=3306;dbname='.F3::get('db_server'),
  F3::get('db_user'),
  F3::get('db_pw')));

$f3->run();

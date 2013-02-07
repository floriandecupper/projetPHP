<?php

$f3=require('lib/base.php');

$f3->set('UI','public/');

$f3->route('GET /',
	function($f3) {
		$classes=array(
			'Base'=>
				array(
					'hash',
					'json',
					'session'
				),
			'Cache'=>
				array(
					'apc',
					'memcache',
					'wincache',
					'xcache'
				),
			'DB\SQL'=>
				array(
					'pdo',
					'pdo_dblib',
					'pdo_mssql',
					'pdo_mysql',
					'pdo_odbc',
					'pdo_pgsql',
					'pdo_sqlite',
					'pdo_sqlsrv'
				),
			'DB\Jig'=>
				array('json'),
			'DB\Mongo'=>
				array(
					'json',
					'mongo'
				),
			'Auth'=>
				array('ldap','pdo'),
			'Image'=>
				array('gd'),
			'Lexicon'=>
				array('iconv'),
			'SMTP'=>
				array('openssl'),
			'Web'=>
				array('curl','openssl','simplexml'),
			'Web\Geo'=>
				array('geoip','json'),
			'Web\OpenID'=>
				array('json','simplexml'),
			'Web\Pingback'=>
				array('dom','xmlrpc')
		);
		$f3->set('classes',$classes);
			echo View::instance()->render('header.php');
			echo View::instance()->render('accueil.php');
			echo View::instance()->render('footer.php');
		// echo View::instance()->render('accueil.htm');
	}
);

$f3->route('GET /userref',
	function($f3) { 
			echo View::instance()->render('header.php');
			echo View::instance()->render('userref.php');
			echo View::instance()->render('footer.php');
	}
);
$f3->route('GET /inscription',
	function() {
			echo View::instance()->render('header.php');
			echo View::instance()->render('inscription.php');
			echo View::instance()->render('footer.php');
	}
);
$f3->route('POST /post',
	function($f3) {
		F3::set('pseudo',$_POST['pseudo']);
		F3::set('password1',$_POST['password1']);
			echo View::instance()->render('header.php');
			echo View::instance()->render('inscription.php');
			echo View::instance()->render('footer.php');
	}
);
$f3->route('GET /connexion',
	function($f3) {
			echo View::instance()->render('header.php');
			echo View::instance()->render('connexion.php');
			echo View::instance()->render('footer.php');
	}
);

$f3->run();

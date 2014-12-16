<?php

// This is default autoload.php. It can be overwritten by Composer.

if (!is_file(__DIR__ . '/nette/nette/Nette/loader.php')) {
	echo("Nette Framework is expected in '" . __DIR__ . "/nette/nette/Nette/loader.php' but not found. Edit file '" . __FILE__ . "' or execute `composer update`.");
	exit(1);
}

require __DIR__ . '/nette/nette/Nette/loader.php';
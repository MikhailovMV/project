<?php
use M1\Env\Parser;
$env = Parser::parse(file_get_contents('../.env'));

class Config {
	public static $env;
	public static function boot() {
		self::$env = Parser::parse(file_get_contents('../.env'));

	}
}

?>

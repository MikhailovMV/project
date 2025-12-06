<?php
use M1\Env\Parser;
$env = Parser::parse(file_get_contents('../.env'));

ORM::configure([
  'connection_string' => $env['connection_string'], 
  'username' => $env['username'], 
  'password' => $env['password'] 
]);


?>
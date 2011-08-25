<?php

$zombie_root = '/var/www/zombie';
$app_root = $zombie_root . '/apps';
$web_root = '';
$domain = 'zombiephp.com';

$db_class = 'MySqlConnection';
$db_file = $zombie_root . '/brainz/sql/mysql_connection.php';
$db_host = 'localhost';
$db_user = 'mysql';
$db_pass = 'password';
$database = 'zombie';

$sess_file = $zombie_root . '/brainz/session/php_session.php';
$sess_class = 'PhpSession';

?>

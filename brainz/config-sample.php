<?php

function get_zombie_config() {
   $config = array();

   $config['config'] = array();
   $config['config']['zombie_root'] = '/var/www/zombie';
   $config['config']['web_root'] = ''; 
   $config['config']['domain'] = 'zombiephp.com';

   $config['database'] = array();
   $config['database']['type'] = 'mysql';
   $config['database']['host'] = 'localhost';
   $config['database']['user'] = 'mysql';
   $config['database']['pass'] = 'password';
   $config['database']['database'] = 'zombie';

   $config['session'] = array();
   $config['session']['type'] = 'php';
   $config['session']['timeout'] = 3600;
   $config['session']['secure'] = false;
   $config['session']['http_only'] = true;
   $config['session']['ip_sticky'] = false;

   return $config;
}

?>

<?php

function get_zombie_config() {
   static $config = array();

   if (empty($config)) {
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
      $config['session']['timeout'] = '3600';
   }

   return $config;
}

?>

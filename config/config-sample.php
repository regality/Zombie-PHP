<?php

function getZombieConfig() {
   $config = array();

   $config['env'] = 'dev';
   $config['domain'] = 'zombiephp.com';
   $config['web_root'] = '';
   $config['zombie_root'] = '/var/www/zombie';

   $config['mysql'] = array();
   $config['mysql']['host'] = 'localhost';
   $config['mysql']['user'] = 'mysql';
   $config['mysql']['pass'] = 'password';
   $config['mysql']['database'] = 'zombie';

   $config['crypt'] = array();
   $config['crypt']['type'] = 'aes128';
   $config['crypt']['pass'] = 'secret';

   $config['session'] = array();
   $config['session']['type'] = 'php';
   $config['session']['timeout'] = 3600;
   $config['session']['secure'] = false;
   $config['session']['http_only'] = true;
   $config['session']['ip_sticky'] = false;

   return $config;
}

?>

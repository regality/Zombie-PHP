<?php

abstract class Model {
   public static $purifier = false;

   public function __construct() {
   }

   public static function purify_html($html) {
      if (self::$purifier == false) {
         require_once(__DIR__ . '/../util/htmlpurifier-standalone/HTMLPurifier.standalone.php');
         $config = HTMLPurifier_Config::createDefault();
         $config->set('Cache.DefinitionImpl', null);
         self::$purifier = new HTMLPurifier($config);
      }
      $clean_html = self::$purifier->purify($html);
      return $clean_html;
   }

}

?>

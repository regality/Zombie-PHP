<?php

abstract class Model {
   public static $purifier = false;

   public function __construct() {
   }

   public static function purify_html($html) {
      if (self::$purifier == false) {
         require_once(__DIR__ . '/../util/htmlpurifier-standalone/HTMLPurifier.standalone.php');
         self::$purifier = new HTMLPurifier();
      }
      $clean_html = self::$purifier->purify($html);
      return $clean_html;
   }

}

?>

<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../../config/config.php");

abstract class ModelBase {
   public static $purifier = false;

   public function __construct() {
      $this->config = getZombieConfig();
   }

   public static function purifyHtml($html) {
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

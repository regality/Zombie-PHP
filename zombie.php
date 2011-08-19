#!/usr/bin/php
<?php

require("brainz/config.php");
require("brainz/util/util.php");

function cli_main($argv) {
   $argc = count($argv);
   if ($argc < 3) {
      die ("Usage: zombie.php <template name> <app name> <option=value> ...\n");
   }

   $template = $argv[1];
   $app = $argv[2];

   $options = array();
   for ($i = 3; $i < $argc; ++$i) {
      $opt = explode("=", $argv[$i], 2);
      if (count($opt) == 2) {
         $options[$opt[0]] = $opt[1];
      } else {
         $options[$opt[0]] = true;
      }
   }

   if (!file_exists("brainz/template/" . $template . "/template.php")) {
      die("unknown template: " . $template . "\n");
   }
   require(__DIR__ . "/brainz/template/" . $template . "/template.php");
   $template_class = underscore_to_class($template . "_template");
   $template = new $template_class($template, $app, $options);
   $template->run();
}

cli_main($argv);

?>

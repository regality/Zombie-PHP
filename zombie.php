#!/usr/bin/php
<?php
// Copyright (c) 2011, Regaltic LLC.  This file is
// licensed under the General Public License version 2.
// See the LICENSE file.

require("brainz/config.php");
require("brainz/util/util.php");

function cli_main($argv) {
   $argc = count($argv);
   if ($argc < 2) {
      die ("Usage: zombie.php <app name> <template=template_name> <option=value> ...\n");
   }

   $app = $argv[1];

   $options = array();
   for ($i = 2; $i < $argc; ++$i) {
      $opt = explode("=", $argv[$i], 2);
      if (count($opt) == 2) {
         $options[$opt[0]] = $opt[1];
      } else {
         $options[$opt[0]] = true;
      }
   }

   $template = (isset($options['template']) ? $options['template'] : 'basic');

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

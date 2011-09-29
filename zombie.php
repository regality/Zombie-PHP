<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once("zombie-core/util/util.php");
require_once("zombie-core/util/autoload.php");

function cli_main($argv) {
   $argc = count($argv);
   if ($argc < 2) {
      die("Usage: zombie.php <action> <option=value> ...\n" .
           "Availabe actions:\n" .
           "\tcreate-app\n");
   }

   $action = $argv[1];

   $options = array();
   for ($i = 2; $i < $argc; ++$i) {
      $opt = explode("=", $argv[$i], 2);
      if (count($opt) == 2) {
         $options[$opt[0]] = $opt[1];
      } else {
         $options[$opt[0]] = true;
      }
   }

   if ($action == "create-app") {
      if (!isset($options['app'])) {
         die ("Usage: zombie.php create-app app=<app name> [template=<template_name>] [option=<value>] ...\n");
      }

      $template = (isset($options['template']) ? $options['template'] : 'basic');
      if (!file_exists("zombie-core/template/" . $template . "/template.php")) {
         die("unknown template: " . $template . "\n");
      }

      $app = $options['app'];

      require(__DIR__ . "/zombie-core/template/" . $template . "/template.php");
      $template_class = underscoreToClass($template . "_template");
      $template = new $template_class($template, $app, $options);
      $template->run();
   } else if ($action == "compile") {
      require(__DIR__ . "/zombie-core/util/compile/compile.php");
      compile($options);
   } else if ($action == "kachow") {
      echo "kachow!\n";
   }
}

cli_main($argv);

?>

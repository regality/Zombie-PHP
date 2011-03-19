#!/usr/bin/php
<?php

if (count($argv) < 3) {
   die("usage: {$argv[0]} create app|form\n");
   
}

$command = $argv[1];
switch ($command) {
   case "create":
      create_app($argv[2]);
      break;
   default:
      echo "unkown command '" . $command . "'\n";
}

function create_app($app) {
   $class = ucwords($app);
   $class = str_replace(' ','', ucwords(str_replace('_', ' ', $app)));
   mkdir("apps/$app");

   $blank = file_get_contents("brainz/blank/blank.php");
   $blank = preg_replace("/blank/", $app, $blank);
   $blank = preg_replace("/Blank/", $class, $blank);
   file_put_contents("apps/$app/$app.php", $blank);

   $view = file_get_contents("brainz/blank/view.php");
   $view = preg_replace("/blank/", $app, $view);
   $view = preg_replace("/Blank/", $class, $view);
   file_put_contents("apps/$app/view.php", $view);
}


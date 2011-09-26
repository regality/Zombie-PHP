<?php

function get_dir_contents($dir, $types = array("dir", "file")) {
   $files = array();
   if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
         if (in_array(filetype($dir . $file), $types) &&
             $file != "." && $file != "..")
         {
            array_push($files, $file);
         }
      }
      closedir($dh);
   }
   return $files;
}

function compile($options) {
   $xml_config = simplexml_load_file(__DIR__ . "/../../../config/compile.xml");
   $apps_dir = __DIR__ . "/../../../apps/";
   $apps = get_dir_contents($apps_dir, array('dir'));

   foreach ($apps as $app) {
      $view_dir = __DIR__ . "/../../../apps/" . $app . "/views/";
      $css = $view_dir . $app . ".css";
      $js = $view_dir . $app . ".js";
      if (file_exists($view_dir . $app . ".css")) {
         array_push($stylesheets, $css);
      }
      if (file_exists($view_dir . $app . ".js")) {
         array_push($stylesheets, $css);
      }
   }
}

function get_compiled_js($file, $level = 'SIMPLE_OPTIMIZATIONS') {
   return `java -jar closure-compiler/compiler.jar --compilation_level=$level --js $file`;
}

?>

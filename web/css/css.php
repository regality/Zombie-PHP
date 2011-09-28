<?php

require(__DIR__ . '/../../brainz/util/compile/compile.php');
require(__DIR__ . '/../../brainz/util/compile/cssmin.php');

header("Content-type: text/css");
if (isset($_GET['type'])) {
   $app_dir = __DIR__ . '/../../apps/';
   $apps = get_dir_contents($app_dir);
   foreach ($apps as $app) {
      $css_file = __DIR__ . "/../../apps/" . $app . "/views/css/main.css";
      if ($_GET['type'] == "mobile") {
         $mobile_css_file = __DIR__ . "/../../apps/" . $app . "/views/css/mobile-main.css";
         if (file_exists($mobile_css_file)) {
            $css_file = $mobile_css_file;
         }
      }
      if (file_exists($css_file)) {
         $css_content = file_get_contents($css_file);
         if (isset($_GET['min'])) {
            echo compile_css_file($css_content);
         } else {
            $css_content = strip_css_comments($css_content);
            $css_content = substitue_css_includes($css_content);
            $css_content = substitue_css_variables($css_content);
            echo "\n/* including " . realpath($css_file) . " */\n\n";
            echo $css_content;
         }
      }
   }
}

?>

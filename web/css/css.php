<?php

require(__DIR__ . '/../../brainz/util/compile/compile.php');
require(__DIR__ . '/../../brainz/util/cssmin.php');

header("Content-type: text/css");
if (isset($_GET['type']) == 'main') {
   $app_dir = __DIR__ . '/../../apps/';
   $apps = get_dir_contents($app_dir);
   foreach ($apps as $app) {
      $css_file = __DIR__ . "/../../apps/" . $app . "/views/css/main.css";
      if (file_exists($css_file)) {
         $css_content = file_get_contents($css_file);
         $css_content = strip_css_comments($css_content);
         $css_content = substitue_css_includes($css_content);
         $css_content = substitue_css_variables($css_content);
         if (isset($_GET['min'])) {
            echo minify_css($css_content);
         } else {
            echo "\n/* including " . realpath($css_file) . " */\n\n";
            echo $css_content;
         }
      }
   }
}

?>

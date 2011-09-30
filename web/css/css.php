<?php

require_once(__DIR__ . '/../../zombie-core/util/compile/compile.php');
require_once(__DIR__ . '/../../zombie-core/util/compile/ssp.php');

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
         $c = new CssFile($css_content);
         if (isset($_GET['min'])) {
            echo $c->render(true);
         } else {
            echo "\n/* including " . realpath($css_file) . " */\n\n";
            echo $c->render();
         }
      }
   }
}

?>

<?php

header("Content-type: text/javascript");
if ((isset($_GET['js']) || isset($_GET['template'])) &&
    isset($_GET['app']))
{
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);
   if (isset($_GET['js'])) {
      $js = preg_replace('/[^0-9a-zA-Z_\/\.]/', '', $_GET['js']);
      $js_file = __DIR__ . "/../../apps/" . $app . "/views/scripts/" . $js . ".js";
      if (file_exists($js_file)) {
         echo file_get_contents($js_file);
      } else {
         header("HTTP/1.1 404 Not Found");
      }
   } else {
      $template = preg_replace('/[^0-9a-zA-Z_\/\.]/', '', $_GET['template']);
      require(__DIR__ . "/../../zombie-core/template/createTemplate.php");
      $js_code = getJSTemplate($app, $template);
      echo $js_code;
   }
} else {
   header("HTTP/1.1 404 Not Found");
}

?>
